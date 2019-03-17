<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title =  '创建权限';

$this->params['navName']     = 'auth';
$this->params['breadcrumbs'] = [
    [
        'label' => '权限列表',
        'url' => ['item/list'],
    ],
    $this->title,
];

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <!-- form start -->
            <?php $form = ActiveForm::begin([
                'id' => 'item-form',
                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'template'     => '{label}<div class="col-sm-5">{input}</div><div class="col-sm-5">{error}</div>',
                ],
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'parentName')->dropDownList(\backend\models\ItemForm::getParentSelect(), ['prompt' => '请选择']) ?>
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'description')->textInput() ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-3">
                    <?= Html::submitButton(( '保存'), ['class' => 'btn btn-info pull-right']) ?>
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
