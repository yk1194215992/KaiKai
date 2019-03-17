<?php

namespace backend\common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use common\models\BaseMysql;

class Admin extends BaseMysql implements IdentityInterface
{
    const STATUS_NORMAL = 1; //正常
    const STATUS_FORBIDDEN = 2;//禁用
    const STATUS_DIMISSION = 3;//离职

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'attributes' => [
                    static::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    static::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
            ],
        ];
    }

    /**
     * 字段查询
     * @param $array  array 查询时不需要的字段
     * @return mixed
     */
    private static function Table_field()
    {
        return [
            'id',
            'username',
            'password',
            'iPhone',
            'realname',
            'roleName',
            'status',
            'last_time',
            'head',
            'update_time',
            'create_time',
        ];
    }

    /**
     * @param $username string 用户名称
     * @return bool
     */
    public static function login($username)
    {
        return static::updateAll(['last_time' => time()], ['username' => $username]);
    }

    /**
     * @param $username string 用户名称
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getUser($username, $asArray = false)
    {
        $select = static::Table_field();
        return static::find()->select($select)->where(['username' => $username])->asArray($asArray)->one();
    }

    public static function findId($id, $asArray = false)
    {
        $select = static::Table_field();
        return static::find()->select($select)->where(['id' => $id])->asArray($asArray)->one();
    }

    /**
     * 搜索
     */
    public static function search()
    {
        $select = static::Table_field();
        return static::find()->select($select)->where('id!="1"')->orderBy('create_time DESC');
    }

    public static function _search()
    {
        $select = static::Table_field();
        return static::find()->select($select)->orderBy('create_time DESC');
    }

    public static function count($name)
    {
        return static::find()->select(['id'])->where('roleName = :roleName', [':roleName' => $name])->count();
    }

    public static function select(){
        $select = static::Table_field();
        return static::find()->select($select)->where(['!=','id','1'])->asArray()->all();
    }

    /**
     * @param $password  string 密码
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $select = static::Table_field();
        $model = static::find()->select($select)->where(['id' => $id, 'status' => self::STATUS_NORMAL])->one();
        $model->head = empty($model->head) ? '/img/user2-160x160.jpg' : Yii::getAlias('@imgbackUrl').$model->head;
        return $model;
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return null;
//        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
//        return $this->getAuthKey() === $authKey;
    }


}