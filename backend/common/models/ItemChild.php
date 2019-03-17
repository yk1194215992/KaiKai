<?php


namespace backend\common\models;
use common\models\BaseMysql;

use Yii;

class ItemChild extends BaseMysql
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getAuthManager()->itemChildTable;
    }

}