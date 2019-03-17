<?php
/**
 * DbManager.php
 */

namespace common\components;

use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class DbManager
 * @package common\rbac\components
 */
class DbManager extends \yii\rbac\DbManager
{
    /**
     * @const int Default Role
     */
    const TYPE_DEFAULT_ROLE = 3;
    /**
     * @const int Default Permission
     */
    const TYPE_DEFAULT_PERMISSION = 4;


    /**
     * Initializes the application component.
     * This method overrides the parent implementation by establishing the database connection.
     */
    public function init()
    {
        parent::init();

        $this->defaultRoles = ArrayHelper::merge($this->defaultRoles, [
            '/site/login',
            '/site/logout',
            '/site/index',
            '/site/error',
            '/site/save',
        ]);
    }

}
