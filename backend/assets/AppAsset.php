<?php
/**
 * AppAsset.php
 *
 * @author apple
 * @version $Id: AppAsset.php 19 2017-12-14 11:19:59Z zhangshuai $
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web';
    public $depends  = [
        'backend\assets\AdminLteAsset',
    ];
    public $css = [
    ];
    public $js = [


  ];
}
