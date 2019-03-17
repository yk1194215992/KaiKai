<?php

namespace backend\models;

use backend\common\models\Item;
use Yii;
use backend\common\components\Definition;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


class ItemForm extends BaseForm
{

    public $parentName;
    public $name;
    public $description;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['parentName', 'rulesName', 'on' => [Definition::SCENARIOS_SAVE, Definition::SCENARIOS_UPDATE],],
            [['name', 'description'], 'required', 'on' => [Definition::SCENARIOS_SAVE, Definition::SCENARIOS_UPDATE], 'message' => '{attribute}不能为空',],
            ['name', 'rulesName', 'on' => [Definition::SCENARIOS_SAVE],],
        ];
    }

    public function rulesName($attribute)
    {
        $exists = Item::name($attribute);
        if ($exists) {
            $this->addError($attribute, '属性已存在，请重新输入！~');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parentName' => '父级名称',
            'parentDescription' => '父级',
            'name' => '模块名称',
            'description' => '模块简介',
        ];
    }

    /**
     * 模块列表
     *
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Item::search();
        return new ActiveDataProvider(['query' => $query]);
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $auth = Yii::$app->authManager;
        $item = $auth->createPermission($this->name);
        $item->description = $this->description;
        if ($this->parentName) {
            $parent = $auth->createRole($this->parentName);
            return $auth->add($item) && $auth->addChild($parent, $item);
        } else {
            $item->type = Item::TYPE_DEFAULT_ROLE;
            return $auth->add($item);
        }
    }

    public function update($name)
    {
        if (!$this->validate()) {
            return false;
        }
        $auth = Yii::$app->authManager;
        $item = $auth->createPermission($this->name);
        $item->description = $this->description;
        return $auth->update($name, $item);
    }

    /**
     * 获取当前数据
     *
     * @param $name
     *
     * @return array|bool
     */
    public function findModel($name)
    {
        return Item::findModel($name);
    }

    /**
     * 获取父级列表
     *
     * @param $name
     *
     * @return array
     */
    public static function getParentSelect($name = null)
    {
        $result = Item::getParentSelect();
        $select = ArrayHelper::map($result, 'name', 'description');
        return isset($select[$name]) ? $select[$name] : $select;
    }

    public static function getItemGroup()
    {
        $parents = Item::getItemGroup();
        $itemGroup = [];
        foreach ($parents as $parent) {
            $query = Item::ItemGroup($parent['name']);
            $parent['items'] = $query;
            $itemGroup[$parent['name']] = $parent;
        }
        return $itemGroup;
    }

    public static function getList($name = null)
    {
        $result = Item::role_search()->all();
        $list = ArrayHelper::map($result, 'name', 'description');
        return (isset($list[$name]) && !empty($list[$name])) ? $list[$name] : $list;
    }


}