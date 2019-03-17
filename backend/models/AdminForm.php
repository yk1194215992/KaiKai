<?php


namespace backend\models;

use backend\common\components\Definition;
use backend\common\models\Admin;
use backend\common\components\OssUpload;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class AdminForm extends BaseForm
{
    public $username;
    public $password;
    public $realname;
    public $roleName;
    public $usernames;
    public $status;
    public $rememberMe = true;
    private $_user;
    public $iPhone;
    public $head;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'username',
                    'password',
                ],
                'required',
                'message' => '{attribute}不能为空',
                'on' => [Definition::SCENARIOS_SAVE, Definition::SCENARIOS_LOGIN],
            ],
            [
                [
                    'username',
                    'password',
                    'realname',
                    'roleName',
                ],
                'required',
                'message' => '{attribute}不能为空',
                'on' => [Definition::SCENARIOS_SAVE],
            ],
            [
                [
                    'username',
                    'realname',
                    'roleName',
                ],
                'required',
                'message' => '{attribute}不能为空',
                'on' => [Definition::SCENARIOS_UPDATE],
            ],
            [
                [
                    'iPhone',
                    'status',
                ],
                'safe',
                'on' => [Definition::SCENARIOS_UPDATE, Definition::SCENARIOS_SAVE],
            ],
            [
                [
                    'username',
                    'roleName',
                    'usernames',
                    'iPhone',
                    'head',
                ],
                'safe',
                'on' => [Definition::SCENARIOS_SEARCH, Definition::SCENARIOS_FILE],
            ],
            [
                [
                    'password',
                ],
                'required',
                'message' => '{attribute}不能为空',
                'on' => [Definition::SCENARIOS_PASSWORD],
            ],

            ['password', 'rulesPassword', 'on' => [Definition::SCENARIOS_LOGIN]],
        ];
    }


    public function rulesPassword($attribute)
    {
        $user = $this->getUser();
        if (!$user || !$user->validatePassword($this->password)) {
            \Yii::$app->session->setFlash('error', '账户密码错误！');
            $this->addError($attribute, '账户密码错误');
        }
        if ($user && $user->status == 2) {
            \Yii::$app->session->setFlash('error', '账户已被禁用！');
            $this->addError($attribute, '账户已被禁用');
        } elseif ($user && $user->status == 3) {
            \Yii::$app->session->setFlash('error', '账户已离职！');
            $this->addError($attribute, '账户已离职');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '管理员账户',
            'password' => '管理员密码',
            'realname' => '管理员姓名',
            'roleName' => '所属角色',
            'region_id' => '城市',
            'rememberMe' => '请记住我',
            'iPhone' => '手机号',
            'status' => '状态',
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            Admin::login($this->username);
            return \Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::getUser($this->username);
        }
        return $this->_user;
    }

    public function search()
    {
        if (!$this->validate()) {
            return false;
        }
        if (Yii::$app->user->identity->id != 1) {
            $query = Admin::search();
        } else {
            $query = Admin::_search();
        }
        $query = $query->andFilterWhere(['id' => $this->username])
            ->andFilterWhere(['roleName' => $this->roleName])
            ->andFilterWhere(['like', 'username', $this->usernames]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * 创建用户
     *
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $admin = new Admin();
            $admin->setAttributes($this->attributes, false);
            $admin->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $admin->status = 1;
            if ($admin->save()) {
                $uid = $admin->getPrimaryKey();
                $auth = Yii::$app->getAuthManager();
                $item = $auth->createRole($this->roleName);
                if ($auth->assign($item, $uid)) {
                    $transaction->commit();
                    return true;
                }
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

    /**
     * 更新
     *
     * @param int $uid
     *
     * @return bool
     */
    public function update($uid)
    {
        if (!$this->validate()) {
            return false;
        }
        $admin = Admin::findId($uid);
        $admin->setAttributes($this->attributes, false);
        unset($admin->password);
        if ($admin->save()) {
            $auth = Yii::$app->getAuthManager();
            $role = $auth->createRole($admin->roleName);
            $auth->revokeAll($uid);
            if ($auth->assign($role, $uid)) {
                return true;
            }
        }
        return false;
    }

    public static function status($res = '空')
    {
        $array = [
            1 => '正常',
            2 => '停用',
            3 => '离职',
        ];
        if ($res === '空') {
            return $array;
        }
        if (array_key_exists($res, $array)) {
            return $array[$res];
        }
        return $res;
    }

    /**
     * 修改封面
     */
    public function file($id)
    {
        $upload = new OssUpload();
        $surface_img = $upload->upload(new static(), 'head');
        if ($surface_img === false) {
            return false;
        }
        $params = [
            'head' => $surface_img,
        ];
        $model = Admin::findId($id);
        $model->setAttributes($params, false);
        if ($model->save()) {
            return true;
        }
        return false;
    }

    public static function user($id)
    {
        return Admin::findId($id);
    }

    public function password($id)
    {
        if (!$this->validate()) {
            return false;
        }
        $admin = Admin::findId($id);
        $admin->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        if ($admin->save()) {
            return true;
        }
        return false;
    }

    public static function count($name){
        return Admin::count($name);
    }

    /**
     * 获取列表 Key->Value
     *
     * @return array
     */
    public static function select()
    {
        $model = Admin::select();
        return ArrayHelper::map($model, 'id', 'username');
    }


}