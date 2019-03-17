<?php


namespace backend\common\models;
use common\models\BaseMysql;

class Menu extends BaseMysql
{

    /**
     * @const string Tree Cache Name
     */
    const CACHE_TAG = 'menu.items';
    /**
     * @const int Tree Cache time
     */
    const CACHE_DURATION = 3600;

    const PARENT_DEFAULT = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * 字段查询
     * @param $array  array 查询时不需要的字段
     * @return mixed
     */
    private static function Table_field()
    {
        return [
            'zmid',
            'name',
            'parent',
            'route',
            'sort',
            'icon',
        ];
    }


    /**
     * @inheritdoc
     */
    public function getMenuParent()
    {
        return $this->hasOne(static::className(), ['zmid' => 'parent']);
    }


    public static function exists($parent)
    {
        $select = static::Table_field();
        return static::find()->select($select)->where('zmid = :zmid AND route IS NULL', [':zmid' => $parent])->exists();
    }

    public static function search()
    {
        $query = static::find()
            ->from(static::tableName() . ' a')
            ->joinWith(['menuParent' => function ($q) {
                $q->from(static::tableName() . ' b');
            }]);
        return $query;
    }

    public static function maxSort($parent)
    {
        return static::find()->where(['parent' => $parent])->max('sort');
    }

    public static function maxSortOne()
    {
        return Menu::find()->where(['parent' => 0])->andWhere(['!=', 'zmid', 1])->max("sort");
    }

    public static function findId($id, $asArray = false)
    {
        $select = static::Table_field();
        return static::find()->select($select)->select(static::Table_field())->where(['zmid' => $id])->asArray($asArray)->one();
    }

    /**
     * 获取菜单下拉
     *
     * @param bool $asArray
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findParent()
    {
        return static::find()
            ->select(['zmid', 'name'])
            ->where('parent = :parent AND route IS NULL', [
                ':parent' => static::PARENT_DEFAULT,
            ])
            ->asArray()
            ->all();
    }

    public static function isParent($id)
    {
        return static::find()->select(['zmid'])->where(['parent' => $id])->one();
    }

    public static function del($id)
    {
        return static::deleteAll(['zmid' => $id]);
    }

    public static function sort($sort, $parent)
    {
        $select = static::Table_field();
        return static::find()->select($select)->where(['sort' => $sort, 'parent' => $parent])->asArray()->one();
    }

    public static function sortUpdate($sort, $zmid)
    {
        return static::updateAll(['sort' => $sort], ['zmid' => $zmid]);
    }

    public static function query()
    {
        return static::find()->select(['zmid'])->asArray();
    }

    public static function zmid()
    {
        $select = static::Table_field();
        return static::find()->select($select)->asArray()->indexBy('zmid')->all();
    }

}