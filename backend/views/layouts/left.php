<?php

/* @var $this \yii\web\View */

use yii\helpers\Url;

$moduleName     = isset($this->params['navName']) ? $this->params['navName'] : '';
$controller     = Yii::$app->controller;
$controllerName = $controller->id;
$actionName     = $controllerName . ucfirst($controller->action->id);


?>

<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::$app->user->identity->head ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->realname ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> <?= Yii::$app->user->identity->username ?></a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->

        <?= \backend\common\components\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
                'items'   => \yii\helpers\ArrayHelper::merge(
                    [
                        [
                            'label'   => '欢迎使用财知道后台',
                            'options' => ['class' => 'header'],
                        ],
                        [
                            'label' => '首页',
                            'url'   => ['/'],
                            'icon'=>'circle-o text-red'
                        ],
                    ],
                    \backend\models\MenuForm::getAssignedMenu(Yii::$app->user->id, 0, function ($menu) {
                        $items  = $menu['children'];
                        $return = [
                            'label' => $menu['name'],
                            'url'   => [$menu['route']],
                        ];

                        // 处理我们的配置
                        $data['icon'] = $menu['icon'];
                        if ($data) {
//                            //visible
//                            isset($data['visible']) && $return['visible'] = $data['visible'];
                            //icon
                            isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];
                            //other attribute e.g. class...
                            $return['options'] = $data;
                        }

                        // 没配置图标的显示默认图标
                        (!isset($return['icon']) || !$return['icon']) && $return['icon'] = 'circle-o';

                        $items && $return['items'] = $items;

                        if (isset($menu['route']) && !empty($menu['route'])) {
                            $return['url'] = [$menu['route']];
                        } else {
                            $return['url'] = '#';

                            (!isset($return['options']['class']) || !$return['options']['class']) && $return['options']['class'] = 'treeview';
                        }

                        return $return;
                    }, true)
                ),
            ]
        ) ?>

    </section>
</aside>
