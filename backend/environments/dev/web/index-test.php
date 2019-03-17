<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

define('COMMON_PATH', realpath(__DIR__ . '/../../common'));

require COMMON_PATH . '/vendor/autoload.php';
require COMMON_PATH . '/vendor/yiisoft/yii2/Yii.php';
require COMMON_PATH . '/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = require __DIR__ . '/../config/test-local.php';

(new yii\web\Application($config))->run();
