<?php
/**
 * DateTimePicker.php
 *
 * @listen https://github.com/xdan/datetimepicker
 */

namespace backend\common\components;

use backend\application\assets\LayuiAsset;
use yii\base\InvalidConfigException;
use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;
use yii\helpers\Json;

/**
 * Class DateTimePicker
 * @package backend\common\widgets\xdan
 */
class DateTimePicker extends InputWidget
{
    const TYPE_PICKER = 1;
    const TYPE_RANGE  = 2;

    const ADDON_PICKER = 'picker';
    const ADDON_RANGE = 'range';

    public $type = self::TYPE_PICKER;

    public $attribute2;

    public $name2;
    public $value2;

    public $options2 = [];
    public $clientOptions2 = [];


    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        echo $this->renderInput();

        $this->registerAssets();
    }

    protected function renderInput()
    {
        Html::addCssClass($this->options, 'form-control');
        Html::addCssClass($this->options2, 'form-control');

        $input = $this->hasModel() ?
            Html::activeTextInput($this->model, $this->attribute, $this->options) :
            Html::textInput($this->name, $this->value, $this->options);

        if ($this->type === static::TYPE_RANGE) {
            if (empty($this->options2['id'])) {
                $this->options2['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute2) :
                    $this->getId();
            }
            $addonRange = $this->renderAddon(static::ADDON_RANGE);
            $input .= $addonRange . ($this->hasModel() ?
                Html::activeTextInput($this->model, $this->attribute2, $this->options2) :
                Html::textInput($this->name2, $this->value2, $this->options2));
        }

        $addonPicker = $this->renderAddon();

        return Html::tag('div', $addonPicker . $input, ['class' => 'input-group']);
    }

    /**
     * Returns the addon for prepend or append.
     *
     * @param string $type whether the addon is the picker or remove
     *
     * @return string
     */
    protected function renderAddon($type = self::ADDON_PICKER)
    {
        if ($type == 'picker') {
            $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-calendar']);
        } else {
            $icon = 'to';
        }

        return Html::tag('span', $icon, ['class' => 'input-group-addon']);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();

        $view->registerAssetBundle(LayuiAsset::className());

        $id      = $this->options['id'];
        $options = Json::htmlEncode($this->clientOptions);

        $js = "layui.use('laydate', function () {";

        $js .= <<< SCRIPT
var laydate = layui.laydate,
options = $options;
options.elem = '#$id';
laydate.render(options);
SCRIPT;
        if ($this->type === static::TYPE_RANGE) {
            $id2 = $this->options2['id'];

            if (empty($this->clientOptions2)) {
                $this->clientOptions2 = $this->clientOptions;
            }

            $options2 = Json::htmlEncode($this->clientOptions2);

            $js .= <<< SCRIPT
var options2 = $options2;
options2.elem = '#$id2';
laydate.render(options2);
SCRIPT;
        }

        $js .= '});';

        $view->registerJs($js, $view::POS_END);
    }

}
