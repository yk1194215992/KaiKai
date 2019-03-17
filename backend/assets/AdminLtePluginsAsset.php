<?php
/**
 * AdminLtePluginsAsset
 *
 * @author yanghan
 * @version $Id: AdminLtePluginsAsset.php 1 2017-10-26 10:02:33Z zhangshuai $
 */

namespace backend\assets;

use yii\web\AssetBundle as BaseAdminLteAsset;

/**
 * AdminLte Plugins AssetBundle
 */
class AdminLtePluginsAsset extends BaseAdminLteAsset
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $css = [
        'datatables/dataTables.bootstrap4.min.css',
    ];
}
