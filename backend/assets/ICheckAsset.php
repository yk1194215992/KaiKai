<?php
/**
 * ICheckAsset.php
 */

namespace backend\assets;

use yii\helpers\Json;
use yii\web\AssetBundle;

/**
 * Class ICheckAsset
 * @package backend\assets
 */
class ICheckAsset extends AssetBundle
{
    const SKIN_ALL = 'all';
    const SKIN_FLAT = 'flat/_all';
    const SKIN_FLAT_AERO = 'flat/aero';
    const SKIN_FLAT_BLUE = 'flat/blue';
    const SKIN_FLAT_FLAT = 'flat/flat';
    const SKIN_FLAT_GREEN = 'flat/green';
    const SKIN_FLAT_GREY = 'flat/grey';
    const SKIN_FLAT_ORANGE = 'flat/orange';
    const SKIN_FLAT_PINK = 'flat/pink';
    const SKIN_FLAT_PURPLE = 'flat/purple';
    const SKIN_FLAT_RED = 'flat/red';
    const SKIN_FLAT_YELLOW = 'flat/yellow';
    const SKIN_FUTURICO = 'futurico/futurico';
    const SKIN_LINE = 'line/_all';
    const SKIN_LINE_AERO = 'line/aero';
    const SKIN_LINE_BLUE = 'line/blue';
    const SKIN_LINE_GREEN = 'line/green';
    const SKIN_LINE_GREY = 'line/grey';
    const SKIN_LINE_LINE = 'line/line';
    const SKIN_LINE_ORANGE = 'line/orange';
    const SKIN_LINE_PINK = 'line/pink';
    const SKIN_LINE_PURPLE = 'line/purple';
    const SKIN_LINE_RED = 'line/red';
    const SKIN_LINE_YELLOW = 'line/yellow';
    const SKIN_MINIMAL = 'minimal/_all';
    const SKIN_MINIMAL_AERO = 'minimal/aero';
    const SKIN_MINIMAL_BLUE = 'minimal/blue';
    const SKIN_MINIMAL_GREEN = 'minimal/green';
    const SKIN_MINIMAL_GREY = 'minimal/grey';
    const SKIN_MINIMAL_LINE = 'minimal/minimal';
    const SKIN_MINIMAL_ORANGE = 'minimal/orange';
    const SKIN_MINIMAL_PINK = 'minimal/pink';
    const SKIN_MINIMAL_PURPLE = 'minimal/purple';
    const SKIN_MINIMAL_RED = 'minimal/red';
    const SKIN_MINIMAL_YELLOW = 'minimal/yellow';
    const SKIN_POLARIS = 'polaris/polaris';
    const SKIN_SQUARE = 'square/_all';
    const SKIN_SQUARE_AERO = 'square/aero';
    const SKIN_SQUARE_BLUE = 'square/blue';
    const SKIN_SQUARE_GREEN = 'square/green';
    const SKIN_SQUARE_GREY = 'square/grey';
    const SKIN_SQUARE_LINE = 'square/square';
    const SKIN_SQUARE_ORANGE = 'square/orange';
    const SKIN_SQUARE_PINK = 'square/pink';
    const SKIN_SQUARE_PURPLE = 'square/purple';
    const SKIN_SQUARE_RED = 'square/red';
    const SKIN_SQUARE_YELLOW = 'square/yellow';

    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins/iCheck';
    public $js = [
        'icheck.js',
    ];

    public $skin = 'minimal/green';


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->css[] = sprintf('%s.css', $this->skin);

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        $json = '';
        if ('all' !== $this->skin) {
            $cssClassSuffix = str_replace('/', '-', $this->skin);
            $json = Json::encode([
                'checkboxClass' => sprintf('icheckbox_%s', $cssClassSuffix),
                'radioClass' => sprintf('iradio_%s', $cssClassSuffix),
                'increaseArea' => '20%',
            ]);
        }

        $view->registerJs('$("input").iCheck(' . $json .');', $view::POS_END);

        parent::registerAssetFiles($view);
    }

}
