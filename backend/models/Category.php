<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property int $pid 父类id
 * @property string $tag 分类
 * @property int $type 类型 1.问答 2.咨询
 * @property int $sort 排序
 * @property int $is_use 是否使用 0.未使用 1.已使用
 * @property string $create_time 创建时间
 * @property string $update_time 修改时间
 */
class Category extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'type', 'sort', 'is_use','create_time','update_time','tag','type','tag'], 'safe'],
           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'tag' => 'Tag',
            'type' => 'Type',
            'sort' => 'Sort',
            'is_use' => 'Is Use',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
