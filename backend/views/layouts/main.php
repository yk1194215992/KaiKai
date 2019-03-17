<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

$directoryAsset = backend\assets\AppAsset::register($this)->baseUrl;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->name . '-' . $this->title) ?></title>
    <?php $this->head() ?>
</head>

<?php if (Yii::$app->user->isGuest) { ?>

    <body class="login-page">
    <?= $this->beginBody() ?>
    <?= $content ?>
    <?= $this->endBody() ?>
    </body>

<?php } else { ?>

    <body class="hold-transition skin-blue sidebar-mini">
    <?= $this->beginBody() ?>
        <div class="wrapper">
            <?= $this->render('header.php', ['directoryAsset' => $directoryAsset]) ?>
            <?= $this->render('left.php', ['directoryAsset' => $directoryAsset]) ?>
            <?= $this->render('content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]) ?>
        </div>
    <?= $this->endBody() ?>
    </body>

<?php } ?>
</html>
<?= $this->endPage() ?>
