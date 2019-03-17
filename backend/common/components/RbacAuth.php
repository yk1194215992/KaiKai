<?php


namespace backend\common\components;

use yii\base\ActionFilter;
use yii\helpers\ArrayHelper;
use yii\web\UnauthorizedHttpException;


class RbacAuth extends ActionFilter
{

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $authManager = \Yii::$app->authManager;
        $roles       = '/' . $action->getUniqueId();
        if (!ArrayHelper::isIn($roles, $authManager->defaultRoles) && !\Yii::$app->user->can($roles)) {
            throw new UnauthorizedHttpException('没有权限访问，请联系管理员！~~');
        }

        return true;
    }

}
