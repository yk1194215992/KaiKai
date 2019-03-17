<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model  */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'admin后台系统管理';

$usernameOptions = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];

$passwordOptions = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

?>
<style> .login-page, .register-page {background:url("../img/dl-bg.jpg") repeat fixed!important;} </style>
<div class="login-box">
    <div class="login-logo">
        <img src="../img/logo.png"/><br/><br/><a href="#"><b style="color:#ffffff">admin后台系统管理</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">开始</p>
        <?= \backend\common\components\Alert::widget() ?>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false, 'fieldConfig' => [
            'template' => '{input}{error}',
            'options' => ['class' => 'form-group has-feedback'],
        ]]); ?>

        <?= $form->field($model, 'username', $usernameOptions)->label(false)->error(false)->textInput([
            'placeholder' => $model->getAttributeLabel('username'),
        ]) ?>

        <?= $form->field($model, 'password', $passwordOptions)->label(false)->error(false)->passwordInput([
            'placeholder' => $model->getAttributeLabel('password'),
        ]) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>

        <?php ActiveForm::end(); ?>

        <!-- <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in
                using Facebook</a>
            <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign
                in using Google+</a>
        </div> -->
        <!-- /.social-auth-links -->

        <!-- <a href="#">I forgot my password</a><br>
        <a href="register.html" class="text-center">Register a new membership</a> -->

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
