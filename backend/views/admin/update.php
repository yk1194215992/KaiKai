<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title                 = '编辑用户';
$this->params['navName']     = 'admin';
$this->params['breadcrumbs'] = [
    [
        'label' => '修改用户',
        'url' => ['user/create'],
    ],
    $this->title,
];

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <!-- form start -->
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'template'     => '{label}<div class="col-sm-7">{input}</div><div class="col-sm-3">{error}</div>',
                ],
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'username')->textInput(['maxlength' => 30,'class'=>'form-control name']) ?>
                <?= $form->field($model, 'realname')->textInput(['maxlength' => 30,'class'=>'form-control name']) ?>
                <?= $form->field($model, 'roleName')->dropDownList(\backend\application\models\ItemForm::getList(), ['prompt'=>'请选择']) ?>
                <?= $form->field($model, 'status')->dropDownList(\backend\application\models\AdminForm::status()) ?>
                <?= $form->field($model, 'iPhone')->textInput(['maxlength' => 30,'class'=>'form-control name']) ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-3">
                    <?= Html::submitButton('保存', ['class' => 'btn btn-info pull-right']) ?>
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
