<?php

/* @var $this yii\web\View */

use yii\web\View;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title                 = '权限列表';
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
                    'filterModel'    => $searchModel,
                    'filterPosition' => GridView::FILTER_POS_FOOTER,
                    'dataProvider'   => $dataProvider,
                    'columns'        => [
                        'description',
                        'name',
                        'parentDescription',
                        [
                            'label'     => '创建日期',
                            'value' => function ($model) {
                                return $model['create_time'];
                            },
                            'format' => ['date', 'Y-M-d'],
                        ],
                        [
                            'label' => '修改时间',
                            'value' => function ($model) {
                                return $model['update_time'];
                            },
                            'format' => ['date', 'Y-M-d'],
                        ],
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'header'   => '操作',
                            'template' => '{update}',
                            'buttons'  => [
                                'update' => function ($url, $model, $key) {
                                    return Html::a('编辑', ['update', 'name' => $model['name']]);
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
