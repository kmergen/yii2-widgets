<?php

/**
 * KM Websolutions Projects
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2010 KM Websolutions
 * @license http://www.yiiframework.com/license/
 */

namespace kmergen\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use Yii;

/**
 * CharCounter count chars in an input or textarea html tag.
 *
 * @author Klaus Mergen <kmergenweb@gmail.com>
 * @since 2.0.0
 */
class CharCounter extends InputWidget
{
    /**
     * Textarea type.
     */
    const TYPE_TEXTAREA = 'textarea';

    /**
     * Input text type.
     */
    const TYPE_TEXTINPUT = 'textInput';

    /**
     *
     * @var string Type of the input field. It must be one of the [[TYPE_TEXTAREA]]
     * or [[TYPE_TEXT_INPUT]] constants.
     */
    public $type = self::TYPE_TEXTAREA;

    /**
     * @var string The text when the char limit is reached.
     */
    public $textLimitReached = 'Maximale Anzahl von {n} Zeichen erreicht';

    /**
     * @var string The text when the char limit is not reached.
     */
    public $textLimitNotReached = 'Noch {n} Zeichen';

    /**
     * @var integer The count of how many chars are allowed in input or textarea.
     */
    public $limit = 1000;

    /**
     * @var array The html options for the char counter.
     */
    public $counterOptions = [];

    /**
     * @var string The selector after which the charCounter tag should be placed.
     * Default is after the input or textarea.
     */
    public $selector;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();

        $this->counterOptions = ArrayHelper::merge([
            'tag' => 'div',
            'id' => null,
            'class' => 'char-counter'
        ], $this->counterOptions);

        if ($this->counterOptions['id'] === null) {
            $this->counterOptions['id'] = $this->options['id'] . '-counter';
        }

        $this->options['maxlength'] = $this->limit;

        $this->textLimitReached = Yii::t('charcounter', 'Maximum number of {n} characters reached');
        $this->textLimitNotReached = Yii::t('charcounter', 'Still {n} characters');

        $this->registerClientScript();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            $method = $this->type === self::TYPE_TEXTAREA ? 'activeTextarea' : 'activeTextInput';
            if ($this->field !== null) {
                $this->options = array_merge($this->field->inputOptions, $this->options);
            }
            return Html::$method($this->model, $this->attribute, $this->options);
        } else {
            if ($this->type === self::TYPE_TEXTAREA) {
                return Html::textarea($this->name, $this->value, $this->options);
            } else {
                return Html::textInput($this->name, $this->value, $this->options);
            }
        }
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['charcounter'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@vendor/kmergen/yii2-widgets/messages',
            'sourceLanguage' => 'en',
        ];
    }

    /**
     * Register Js.
     */
    protected function registerClientScript()
    {
        $options = Json::encode([
            'counterOptions' => $this->counterOptions,
            'inputId' => $this->options['id'],
            'selector' => $this->selector,
            'limit' => $this->limit,
            'textLimitReached' => $this->textLimitReached,
            'textLimitNotReached' => $this->textLimitNotReached,
        ]);

        $view = $this->getView();
        CharCounterAsset::register($view);
        $view->registerJs("KMCharCounter.init($options)");
    }
}

