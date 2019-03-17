<?php


namespace backend\models;


use backend\common\models\Menu;
use backend\common\components\Definition;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

class MenuForm extends BaseForm
{

    public $name;
    public $parent = 0;
    public $route;
    public $sort;
    public $icon;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'name',
                'required',
                'on' => [Definition::SCENARIOS_SAVE, Definition::SCENARIOS_UPDATE],
                'message' => '{attribute}不能为空',
            ],
            [
                'parent',
                function ($attribute) {
                    $exists = Menu::exists($this->parent);
                    if (!$exists) {
                        $this->addError($attribute, '父类不存在，请重新选择！~');
                    }
                },
                'on' => [Definition::SCENARIOS_SAVE, Definition::SCENARIOS_SEARCH, Definition::SCENARIOS_UPDATE],
            ],
            [['route', 'sort', 'icon'], 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'parent' => '父级',
            'route' => '路由',
            'sort' => '排序',
            'icon' => '图标',
        ];
    }


    /**
     * 列表
     *
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Menu::search();
        if ($this->parent) {
            $query = $query->andFilterWhere(['a.parent' => $this->parent]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30],
        ]);

        return $dataProvider;
    }


    /**
     * 保存
     *
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        if ($this->parent) {
            $maxsort = Menu::maxSort($this->parent);
        } else {
            $maxsort = Menu::maxSortOne();
            $this->parent = 0;
            $this->route = null;
        }
        $model = new Menu();
        $model->setAttributes($this->attributes, false);
        $model->sort = $maxsort + 1;
        return $model->save();
    }

    public static function findId($id, $asArray = false)
    {
        return Menu::findId($id, $asArray);
    }

    public function update($id)
    {
        if (!$this->validate()) {
            return false;
        }
        $params = [
            'name' => $this->name,
            'icon' => $this->icon
        ];

        if ($this->parent) {
            $params = ArrayHelper::merge($params, [
                'parent' => $this->parent,
                'route' => $this->route,
            ]);
        }
        $model = Menu::findId($id);
        $model->setAttributes($params, false);
        return $model->save();
    }

    public static function findParent()
    {
        $model = Menu::findParent();
        return \yii\helpers\ArrayHelper::map($model, 'zmid', 'name');
    }


    public static function del($id)
    {
        $model = Menu::isParent($id);
        if ($model) {
            return false;
        }
        return Menu::del($id);
    }

    /**
     * 排序
     *
     * @param $id
     * @param $sort
     *
     * @return bool
     */
    public static function returnSort($id, $sort)
    {
        $model = Menu::findId($id);
        $sorts = Menu::sort($sort, $model['parent']);
        $transaction = \Yii::$app->db->beginTransaction();
        if ($model->sort && $sort or $model->sort == 0) {
            try {
                if ($sorts) {
                    $update = Menu::sortUpdate($model->sort, $sorts['zmid']);
                }
                $updates = Menu::sortUpdate($sort, $id);
                if ($updates) {
                    $transaction->commit();
                    return true;
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        $transaction->rollBack();
        return false;
    }


    /**
     * Use to get assigned menu of user.
     * @param mixed $userId
     * @param integer $root
     * @param \Closure $callback use to reformat output.
     * callback should have format like
     *
     * ```
     * function ($menu) {
     *    return [
     *        'label' => $menu['name'],
     *        'url' => [$menu['route']],
     *        'options' => $data,
     *        'items' => $menu['children']
     *        ]
     *    ]
     * }
     * ```
     * @param boolean $refresh
     * @return array
     */
    public static function getAssignedMenu($userId, $root = 0, $callback = null, $refresh = false){
        $manager = \Yii::$app->authManager;
        $menus = Menu::zmid();
        $key = [__METHOD__, $userId, $manager->defaultRoles];
        $cache = \Yii::$app->cache;
        if ($refresh || $cache === null || ($assigned = $cache->get($key)) === false) {
            $routes = $filter1 = $filter2 = [];
            if ($userId !== null) {
                foreach ($manager->getPermissionsByUser($userId) as $name => $value) {
                    $routes[] = $name;
                }
            }
            $routes = array_unique($routes);
            sort($routes);
            $prefix = '\\';
            foreach ($routes as $route) {
                if (strpos($route, $prefix) !== 0) {
                    if (substr($route, -1) === '/') {
                        $prefix = $route;
                        $filter1[] = $route . '%';
                    } else {
                        $filter2[] = $route;
                    }
                }
            }
            $assigned = [];
            $query = Menu::query();
            if (count($filter2)) {
                $assigned = $query->where(['route' => $filter2])->column();
            }
            if (count($filter1)) {
                $query->where('route like :filter');
                foreach ($filter1 as $filter) {
                    $assigned = array_merge($assigned, $query->params([':filter' => $filter])->column());
                }
            }
            $assigned = static::requiredParent($assigned, $menus);
            if ($cache !== null) {
                $cache->set($key, $assigned, Menu::CACHE_DURATION, new TagDependency([
                    'tags' => Menu::CACHE_TAG
                ]));
            }
        }
        $key = [__METHOD__, $assigned, $root];
        if ($refresh || $callback !== null || $cache === null || (($result = $cache->get($key)) === false)) {
            $result = static::normalizeMenu($assigned, $menus, $callback, $root);
            if ($cache !== null && $callback === null) {
                $cache->set($key, $result, Menu::CACHE_DURATION, new TagDependency([
                    'tags' => Menu::CACHE_TAG
                ]));
            }
        }
        return $result;
    }

    /**
     * Ensure all item menu has parent.
     * @param  array $assigned
     * @param  array $menus
     * @return array
     */
    private static function requiredParent($assigned, &$menus)
    {
        $l = count($assigned);
        for ($i = 0; $i < $l; $i++) {
            $id = $assigned[$i];
            $parent_id = $menus[$id]['parent'];
            if ($parent_id != 0 && !in_array($parent_id, $assigned)) {
                $assigned[$l++] = $parent_id;
            }
        }
        return $assigned;
    }

    /**
     * Normalize menu
     * @param  array $assigned
     * @param  array $menus
     * @param   $callback
     * @param  integer $parent
     * @return array
     */
    private static function normalizeMenu(&$assigned, &$menus, $callback, $parent = 0)
    {
        $result = [];
        $sort = [];
        foreach ($assigned as $id) {
            $menu = $menus[$id];
            if ($menu['parent'] == $parent) {
                $menu['children'] = static::normalizeMenu($assigned, $menus, $callback, $id);
                if ($callback !== null) {
                    $item = call_user_func($callback, $menu);
                } else {
                    $item = [
                        'label' => $menu['name'],
                        'url' => static::parseRoute($menu['route']),
                    ];
                    if ($menu['children'] != []) {
                        $item['items'] = $menu['children'];
                    }
                }
                $result[] = $item;
                $sort[] = $menu['sort'];
            }
        }
        if ($result != []) {
            array_multisort($sort, $result);
        }
        return $result;
    }

    /**
     * Parse route
     * @param  string $route
     * @return mixed
     */
    public static function parseRoute($route)
    {
        if (!empty($route)) {
            $url = [];
            $r = explode('&', $route);
            $url[0] = $r[0];
            unset($r[0]);
            foreach ($r as $part) {
                $part = explode('=', $part);
                $url[$part[0]] = isset($part[1]) ? $part[1] : '';
            }
            return $url;
        }
        return '#';
    }




}