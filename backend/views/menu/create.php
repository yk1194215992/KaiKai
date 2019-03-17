<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \backend\application\models\MenuForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '创建菜单';

$this->params['breadcrumbs'][] = [
    'label' => '菜单管理',
    'url'   => ['list'],
];

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <?php $form = ActiveForm::begin([
                'id' => 'admin-form',
                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'template'     => '{label}<div class="col-sm-4">{input}</div><div class="col-sm-2">{hint}</div><div class="col-sm-4">{error}</div>',
                ],
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'parent')->dropDownList(
                    \backend\models\MenuForm::findParent(), [
                        'prompt' => '无',
                    ]
                ) ?>
                <?= $form->field($model, 'route')->textInput() ?>
                <?= $form->field($model, 'icon')->textInput() ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-3">
                    <?= Html::submitButton('保存', [
                        'class' => 'btn btn-primary pull-right',
                    ]) ?>
                </div>
                <div class="col-sm-1">
                    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
                </div>
                <div class="col-sm-8"></div>
            </div>
            <!-- /.box-footer -->
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
