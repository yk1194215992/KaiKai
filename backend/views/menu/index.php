<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\application\models\MenuForm */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\web\View;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '菜单列表';
$this->params['breadcrumbs'][] = $this->title;

$tableLayout = <<< HTML
<div class="dataTables_wrapper form-inline dt-bootstrap">
    <div class="row">
        <div class="col-sm-12">{items}</div>
    </div>
    <div class="row">
        <div class="col-sm-5 dataTables_info">{summary}</div>
        <div class="col-sm-7 dataTables_paginate paging_simple_numbers">{pager}</div>
    </div>
</div>
HTML;

?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header bg-gray color-palette">

                <?php $form = ActiveForm::begin([
                    'id'      => 'admin-form',
                     'method'  => 'post',
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class'   => 'form-inline',
                    ],
                    'fieldConfig' => [
                        'template' => '{label}：{input}',
                    ],
                ]); ?>

                <div class="row">
                    <div class="col-xs-3">

                        <?= $form->field($searchModel, 'parent')->dropDownList(\backend\models\MenuForm::findParent(), ['prompt' => '无',]) ?>

                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">

                            <?= Html::submitButton('搜索', ['class' => 'btn bg-light-blue color-palette']) ?>

                        </div>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
            <div class="box-body">

                <?= GridView::widget([
                    'layout'       => $tableLayout,
                    'tableOptions' => [
                        'class' => 'table table-bordered dataTable table-hover table-condensed',
                    ],
                    'dataProvider'   => $dataProvider,
                    'rowOptions'=>function($model){
                        if($model->parent == 0){
                            return ['class' => 'danger'];
                        }
                    },
                    'filterModel'    => $searchModel,
                    'filterPosition' => null,
                    'columns'        => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'name',
                            'label' => '菜单名称',
                        ],
                        [
                            'attribute' => 'menuParent.name',
                            'filter' => Html::activeTextInput($searchModel, 'parent', [
                                'class' => 'form-control', 'id' => null
                            ]),
                            'label' => '父级',
                        ],
                        [
                            'attribute' => 'route',
                            'label'     => '路由',
                        ],
                        [
                            'attribute' => 'sort',
                            'label'     => '排序',
                        ],
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'header'   => '操作',
                            'template' => '{update} {del} {sort}',
                            'buttons'  => [
                                'update' => function ($url) {
                                    return Html::a('编辑', $url, [
                                        'class' => 'orange',
                                        'title' => '编辑',
                                    ]);
                                },
                                'del' => function ($url,$model,$key) {
                                    return Html::a('删除',
                                        ['del', 'id' => $key],
                                        [
                                            'data' => ['confirm' => '确定要删除' .$model->name  . '吗？',]
                                        ]
                                    );
                                },
                                'sort' => function ($url, $model, $key) {
                                    // if (Yii::$app->user->can('/banner/sort')) {
                                    return Html::a('排序&nbsp;&nbsp;', '#', [
                                        'id' => $key,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#sort-modal',
                                        'data-url' => Url::toRoute(['sort', 'id' => $key]),
                                        'class' => 'content-sort',
                                    ]);
                                    // }
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<?php
echo Dialog::widget([
    'jsPosition' => View::POS_END,
    'libName'    => 'krajeeDialogCust',
    'options'    => [
        'draggable' => true,
        'closable'  => true
    ],
]);

echo Dialog::widget(['jsPosition' => View::POS_END]);
$this->registerJsFile('@web/js/order.js', ['depends' => ['backend\assets\AppAsset']]);
