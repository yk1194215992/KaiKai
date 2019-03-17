<?php
/**
 * AdminLteAsset
 *
 * @author yanghan
 * @version $Id: AdminLteAsset.php 1 2017-10-26 10:02:33Z zhangshuai $
 */

namespace backend\assets;

use yii\web\AssetBundle as BaseAdminLteAsset;
use yii\base\Exception;

/**
 * AdminLte AssetBundle
 */
class AdminLteAsset extends BaseAdminLteAsset
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $depends    = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
        'backend\assets\AdminLtePluginsAsset',
    ];
    public $css = [
        'css/AdminLTE.min.css',
    ];
    public $js = [
        'js/adminlte.min.js',
    ];


    /**
     * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
     * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
     */
    public $skin = '_all-skins';

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Append skin color file if specified
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }

            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }

        parent::init();
    }

}
