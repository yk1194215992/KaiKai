<?php


namespace backend\common\models;

use common\components\DbManager;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use common\models\BaseMysql;


class Item extends BaseMysql
{

    const TYPE_DEFAULT_ROLE = 3;
    const TYPE_DEFAULT_PERMISSION = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getAuthManager()->itemTable;
    }

    /**
     * 字段查询
     * @param $array  array 查询时不需要的字段
     * @return mixed
     */
    private static function Table_field()
    {
        return [
            'name',
            'type',
            'description',
            'rule_name',
            'data',
            'created_at',
            'updated_at',
        ];
    }

    public static function name($name)
    {
        $select = static::Table_field();
        return static::find()->select($select)->where('name = :name and type = :type', [':name' => $name, ':type' => \yii\rbac\Item::TYPE_PERMISSION])->exists();
    }

    //模块父级
    public static function parents()
    {
        $select = static::Table_field();
        return static::find()->select($select)->where(['type' => [DbManager::TYPE_DEFAULT_ROLE]])->all();
    }

    public static function search()
    {
        $parents = static::parents();
        $ItemChild = ItemChild::tableName();
        $Item = static::tableName();
        $query = (new Query())
            ->from($Item . ' a')
            ->select([
                'a.name AS name',
                'a.type AS type',
                'a.description AS description',
                'c.name AS parentName',
                'c.description AS parentDescription',
                'a.updated_at AS update_time',
                'a.created_at AS create_time',
            ])
            ->leftJoin("{$ItemChild} b", 'a.name = b.child')
            ->leftJoin("{$Item} c", 'b.parent = c.name');
        $query = $query->orWhere(['in', 'a.type', [DbManager::TYPE_DEFAULT_ROLE, DbManager::TYPE_DEFAULT_PERMISSION]]);
        $query->orWhere(['in', 'b.parent', ArrayHelper::getColumn($parents, 'name')]);
        return $query;
    }

    public static function getParentSelect()
    {
        $select = static::Table_field();
        $result = (new Query())->from(static::tableName())
            ->select($select)
            ->where(['type' => DbManager::TYPE_DEFAULT_ROLE])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        return $result;
    }

    public static function getItemGroup()
    {
        $select = static::Table_field();
        return (new Query())->from(static::tableName())
            ->select($select)
            ->where(['type' => [DbManager::TYPE_DEFAULT_ROLE]])
            ->all();
    }

    public static function ItemGroup($parents)
    {
        $item = static::tableName();
        $itemChild = ItemChild::tableName();
        return (new Query())->from("{$item} a")
            ->select([
                'a.name',
                'a.type',
                'a.description',
                'a.rule_name',
                'a.data',
                'a.created_at',
                'a.updated_at',
                'b.parent',
                'b.child',
            ])
            ->leftJoin("{$itemChild} b", 'a.name = b.child')
            ->where('b.parent = :parent', [':parent' => $parents])
            ->all();
    }

    public static function findModel($name)
    {
        $item = static::tableName();
        $itemChild = ItemChild::tableName();
        return (new Query())->from("{$item} a")
            ->select([
                'a.name AS name',
                'a.type AS type',
                'a.description AS description',
                'c.name AS parentName',
                'c.description AS parentDescription',
                'a.updated_at AS update_time',
                'a.created_at AS create_time',
            ])->leftJoin("{$itemChild} b", 'a.name = b.child')
            ->leftJoin("{$item} c", 'b.parent = c.name')
            ->where('a.name = :name', [':name' => $name])
            ->one();
    }

    public static function roleSearch()
    {
        $where = "type = " . \yii\rbac\Item::TYPE_ROLE . ' and name !="administrator"';
        $select = static::Table_field();
        return (new Query())->from(static::tableName())
            ->select($select)
            ->where($where)
            ->orderBy(['created_at' => SORT_DESC]);
    }

    public static function role_search()
    {
        $select = static::Table_field();
        return (new Query())->from(static::tableName())
            ->select($select)
            ->where(['type' => \yii\rbac\Item::TYPE_ROLE])
            ->orderBy(['created_at' => SORT_DESC]);
    }

    public static function ruleName($name)
    {
        $select = static::Table_field();
        return (new Query())->from(static::tableName())
            ->select($select)
            ->where('name = :name and type = :type', [':name' => $name, ':type' => \yii\rbac\Item::TYPE_ROLE])
            ->exists();
    }
}