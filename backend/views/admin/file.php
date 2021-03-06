<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

$this->title                 = '头像修改';
$this->params['navName']     = 'admin';
$this->params['breadcrumbs'] = [
    [
        'label' => 'admin',
        'url' => ['user/index'],
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
                <div style="padding-left:175px;padding-bottom:15px;"><img  width="150" height="150" src="<?= Yii::getAlias('@imgbackUrl').$model['head']?>" /></div>
                <?php 
                    echo $form->field($model, 'head')->fileInput(['maxlength' => 30,'class'=>'form-control'])->widget(FileInput::classname(), [
                        'options' => ['multiple' => true],
                    ])->label('头像修改');
                ?>
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
