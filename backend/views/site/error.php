<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title                 = '错误页';
$this->params['navName']     = 'site';
$this->params['breadcrumbs'] = [$this->title];

?>
<div class="error-page">
    <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

    <div class="error-content" style="margin-top: 150px;">
        <h3><?= $name ?></h3>

        <p style="margin-top: 10px;">
            出错啦！！！<?= nl2br(Html::encode($message)) ?>
        </p>

        <p style="margin-top: 10px;">
            <a href="<?= Yii::$app->homeUrl ?>">返回首页</a>
        </p>

    </div>
    <!-- /.error-content -->
</div>
