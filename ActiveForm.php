<?php

/**
 * KM Websolutions Projects
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2010 KM Websolutions
 * @license http://www.yiiframework.com/license/
 */

namespace kmergen\widgets;

use yii\bootstrap4\ActiveForm as BaseActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use Yii;
use yii\widgets\ActiveFormAsset;

/**
 * Add Float labels to ActiveForm
 *
 * @author Klaus Mergen <kmergenweb@gmail.com>
 * @since 2.0.0
 */
class ActiveForm extends BaseActiveForm
{

    /**
     * @var boolean Enables the float labels for this form
     */
    public $enableFloatLabels = false;

    /**
     * @var array The options for the float labels.
     * @see https://github.com/pryley/float-labels.js
     */
    public $floatLabelOptions = [];

    /**
     * @var string the default field class name when calling [[field()]] to create a new field.
     * @see fieldConfig
     */
    public $fieldClass = 'kmergen\widgets\ActiveField';



    /**
     * {@inheritDoc}
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->getClientOptions());
        $attributes = Json::htmlEncode($this->attributes);
        $view = $this->getView();
        ActiveFormAsset::register($view);
        $view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
        if ($this->enableFloatLabels) {
            FloatLabelAsset::register($view);
            $floatLabelOptions = Json::htmlEncode($this->floatLabelOptions);
            $view->registerJs("var floatlabels = new FloatLabels(document.getElementById('$id'), $floatLabelOptions);");
        }
    }


}

