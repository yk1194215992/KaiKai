<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title                 = '用户列表';
$this->params['navName']     = 'admin';
$this->params['breadcrumbs'] = [$this->title];

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
                     'method'  => 'get',
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
                        <?= $form->field($searchModel, 'username')->dropDownList(\backend\models\AdminForm::select(), ['prompt' => '请选择']) ?>
                    </div>
                    <div class="col-xs-4">
                        <?= $form->field($searchModel, 'usernames')->textInput()->label('用户搜索') ?>
                    </div>
                    <div class="col-xs-3">
                        <?= $form->field($searchModel, 'roleName')->dropDownList(\backend\models\ItemForm::getList(), ['prompt' => '请选择']) ?>
                    </div>
                </div>
                <br/>
                <div class="row">
                    
                    <div class="col-xs-4">
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
                    'filterModel'    => $searchModel,
                    'filterPosition' => GridView::FILTER_POS_FOOTER,
                    
                    'columns'        => [
                        [
                            'label'     => '#',
                            'attribute' => 'id',
                        ],
                        [
                            'label'     => '账户',
                            'attribute' => 'username',
                        ],
                        [
                            'label' => '轮播图',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $model->head = Yii::getAlias('@imgbackUrl').$model->head;
                                return Html::img($model->head, ['width' => '120', 'height' => '80']);
                            }
                        ],
                        [
                            'label'     => '姓名',
                            'attribute' => 'realname',
                        ],
                        [
                            'label' => '角色',
                            'value' => function ($model) {
                                return \backend\models\ItemForm::getList($model->roleName);
                            },
                        ],
                        [
                            'label' => '状态',
                            'value' => function ($model) {
                                return \backend\models\AdminForm::status($model->status);
                            },
                        ],
                        [
                            'label'     => '创建时间',
                            'attribute' => 'create_time',
                            'format'    => ['date', 'Y-M-d'],
                        ],
                        [
                            'label'     => '最后登录时间',
                            'attribute' => 'last_time',
                            'format'    => ['date', 'Y-M-d'],
                        ],
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'header'   => '操作',
                            'template' => '{update} {file} {password}',
                            'buttons'  => [
                                'update' => function ($url, $model, $key) {
                                     if (Yii::$app->user->can('/admin/update')) {
                                    return Html::a('编辑',
                                        ['update', 'id' => $key],
                                        [
                                            'class' => 'orange',
                                            'title' => '编辑',
                                        ]
                                    );
                                };
                                },
                                'file' => function ($url, $model, $key) {
                                    if (Yii::$app->user->can('/admin/file')) {
                                    return Html::a('头像修改',
                                        ['file', 'id' => $key],
                                        [
                                            'class' => 'orange',
                                            'title' => '封面修改',
                                        ]
                                    );
                                };
                                },
                                'password' => function ($url, $model, $key) {
                                    if (Yii::$app->user->can('/admin/password')) {
                                    return Html::a('密码修改',
                                        ['password', 'id' => $key],
                                        [
                                            'class' => 'orange',
                                            'title' => '密码修改',
                                        ]
                                    );
                                };
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
