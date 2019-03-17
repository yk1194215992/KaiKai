<?php

/* @var $this yii\web\View */

use yii\web\View;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title                 = '角色列表';
$this->params['navName']     = 'auth';
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
            <div class="box-body">
                <?= GridView::widget([
                    'layout'       => $tableLayout,
                    'tableOptions' => [
                        'class' => 'table table-bordered dataTable table-hover table-condensed',
                    ],
                    'dataProvider' => $dataProvider,
                    //'filterModel'  => $searchModel,
                    'columns' =>[
                        'name:text:标识',
                        'description:text:简介',
                        [
                        'label'=>'创建日期',
                        'attribute' => 'created_at',
                        'format' => ['date', 'Y-M-d'],
                        ],
                        [
                        'label'=>'修改时间',
                        'attribute' => 'updated_at',
                        'format' => ['date', 'Y-M-d'],
                        ],

                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'header'   => '操作',
                            'template' => '{update} {delete}',
                            'buttons'  => [
                                'update' => function ($url, $model, $key) {
                                    return Html::a('编辑', ['update','name' => $model['name']]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::a('删除', ['delete', 'name' => $model['name']]);
                                }
                            ]
                        ]
                    ],
                ]); ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
