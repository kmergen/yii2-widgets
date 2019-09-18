<?php
/**
 * KM Websolutions Projects
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2010 KM Websolutions
 * @license http://www.yiiframework.com/license/
 */

namespace kmergen\widgets;

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

/**
 * A Bootstrap 4 enhanced version of [[\yii\widgets\ActiveField]].
 * This includes all functions of [[\yii\bootstrap4\ActiveField]] and enhanced it with the custom radios and checkboxes.
 *
 * @author Klaus Mergen <kmergenweb@gmail.com>
 * @since 2.0.0
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * @var bool whether to render [[checkboxList()]] and [[radioList()]] inline.
     */
    public $inline = false;
    /**
     * @var string|null optional template to render the `{input}` placeholder content
     */
    public $inputTemplate;
    /**
     * @var array options for the wrapper tag, used in the `{beginWrapper}` placeholder
     */
    public $wrapperOptions = [];
    /**
     * {@inheritdoc}
     */
    public $options = ['class' => ['widget' => 'form-group']];
    /**
     * {@inheritdoc}
     */
    public $inputOptions = ['class' => ['widget' => 'form-control']];
    /**
     * {@inheritdoc}
     */
    public $errorOptions = ['class' => 'invalid-feedback'];
    /**
     * {@inheritdoc}
     */
    public $labelOptions = [];
    /**
     * {@inheritdoc}
     */
    public $hintOptions = ['class' => ['widget' => 'form-text', 'text-muted'], 'tag' => 'small'];
    /**
     * @var null|array CSS grid classes for horizontal layout. This must be an array with these keys:
     *  - 'offset' the offset grid class to append to the wrapper if no label is rendered
     *  - 'label' the label grid class
     *  - 'wrapper' the wrapper grid class
     *  - 'error' the error grid class
     *  - 'hint' the hint grid class
     */
    public $horizontalCssClasses = [];
    /**
     * @var string the template for checkboxes and radios in default layout
     */
    public $checkTemplate = "<div class=\"form-check\">\n{input}\n{label}\n{error}\n{hint}\n</div>";
    /**
     * @var string the template for checkboxes and radios in horizontal layout
     */
    public $checkHorizontalTemplate = "{beginWrapper}\n<div class=\"form-check\">\n{input}\n{label}\n{error}\n{hint}\n</div>\n{endWrapper}";
    /**
     * @var string the `enclosed by label` template for checkboxes and radios in default layout
     */
    public $checkEnclosedTemplate = "<div class=\"form-check\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";

    /**
     * @var string the template for checkboxes in default layout
     */
    public $customCheckTemplate = "<div class=\"custom-control custom-checkbox\">\n{input}\n{label}\n{error}\n{hint}\n</div>";
    /**
     * @var string the template for checkboxes in horizontal layout
     */
    public $customCheckHorizontalTemplate = "{beginWrapper}\n<div class=\"custom-control custom-checkbox\">\n{input}\n{label}\n{error}\n{hint}\n</div>\n{endWrapper}";
    /**
     * @var string the `enclosed by label` template for checkboxes in default layout
     */
    public $customCheckEnclosedTemplate = "<div class=\"custom-control custom-checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";

    /**
     * @var string the template for radios in default layout
     */
    public $customRadioTemplate = "<div class=\"custom-control custom-checkbox\">\n{input}\n{label}\n{error}\n{hint}\n</div>";
    /**
     * @var string the template for radios in horizontal layout
     */
    public $customRadioHorizontalTemplate = "{beginWrapper}\n<div class=\"custom-control custom-checkbox\">\n{input}\n{label}\n{error}\n{hint}\n</div>\n{endWrapper}";
    /**
     * @var string the `enclosed by label` template for radios in default layout
     */
    public $customRadioEnclosedTemplate = "<div class=\"custom-control custom-checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
    /**
     * @var bool whether to render the error. Default is `true` except for layout `inline`.
     */
    public $enableError = true;
    /**
     * @var bool whether to render the label. Default is `true`.
     */
    public $enableLabel = true;


    /**
     * {@inheritdoc}
     */
    public function __construct($config = [])
    {
        $layoutConfig = $this->createLayoutConfig($config);
        $config = ArrayHelper::merge($layoutConfig, $config);
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function render($content = null)
    {
        if ($content === null) {
            if (!isset($this->parts['{beginWrapper}'])) {
                $options = $this->wrapperOptions;
                $tag = ArrayHelper::remove($options, 'tag', 'div');
                $this->parts['{beginWrapper}'] = \yii\bootstrap4\Html::beginTag($tag, $options);
                $this->parts['{endWrapper}'] = Html::endTag($tag);
            }
            if ($this->enableLabel === false) {
                $this->parts['{label}'] = '';
                $this->parts['{beginLabel}'] = '';
                $this->parts['{labelTitle}'] = '';
                $this->parts['{endLabel}'] = '';
            } elseif (!isset($this->parts['{beginLabel}'])) {
                $this->renderLabelParts();
            }
            if ($this->enableError === false) {
                $this->parts['{error}'] = '';
            }
            if ($this->inputTemplate) {
                $input = isset($this->parts['{input}'])
                    ? $this->parts['{input}']
                    : Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
                $this->parts['{input}'] = strtr($this->inputTemplate, ['{input}' => $input]);
            }
        }
        return parent::render($content);
    }

    /**
     * {@inheritdoc}
     */
    public function checkbox($options = [], $enclosedByLabel = false)
    {
        Html::removeCssClass($options, 'form-control');
        Html::addCssClass($options, 'form-check-input');
        Html::addCssClass($this->labelOptions, 'form-check-label');

        if (!isset($options['template'])) {
            $this->template = ($enclosedByLabel) ? $this->checkEnclosedTemplate : $this->checkTemplate;
        } else {
            $this->template = $options['template'];
        }
        if ($this->form->layout === ActiveForm::LAYOUT_HORIZONTAL) {
            if (!isset($options['template'])) {
                $this->template = $this->checkHorizontalTemplate;
            }
            Html::removeCssClass($this->labelOptions, $this->horizontalCssClasses['label']);
            Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
        }
        unset($options['template']);

        if ($enclosedByLabel) {
            if (isset($options['label'])) {
                $this->parts['{labelTitle}'] = $options['label'];
            }
        }

        return parent::checkbox($options, false);
    }

    /**
     * {@inheritdoc}
     */
    public function radio($options = [], $enclosedByLabel = false)
    {
        Html::removeCssClass($options, 'form-control');
        Html::addCssClass($options, 'form-check-input');
        Html::addCssClass($this->labelOptions, 'form-check-label');

        if (!isset($options['template'])) {
            $this->template = ($enclosedByLabel) ? $this->checkEnclosedTemplate : $this->checkTemplate;
        } else {
            $this->template = $options['template'];
        }
        if ($this->form->layout === ActiveForm::LAYOUT_HORIZONTAL) {
            if (!isset($options['template'])) {
                $this->template = $this->checkHorizontalTemplate;
            }
            Html::removeCssClass($this->labelOptions, $this->horizontalCssClasses['label']);
            Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
        }
        unset($options['template']);

        if ($enclosedByLabel) {
            if (isset($options['label'])) {
                $this->parts['{labelTitle}'] = $options['label'];
            }
        }

        return parent::radio($options, false);
    }

    /**
     * {@inheritdoc}
     */
    public function checkboxList($items, $options = [])
    {
        if (!isset($options['item'])) {
            $itemOptions = isset($options['itemOptions']) ? $options['itemOptions'] : [];
            $encode = ArrayHelper::getValue($options, 'encode', true);
            $wrapperOptions = ['class' => ['form-check']];
            if ($this->inline) {
                Html::addCssClass($wrapperOptions, 'form-check-inline');
            }
            $options['item'] = function ($i, $label, $name, $checked, $value) use (
                $itemOptions,
                $encode,
                $wrapperOptions
            ) {
                $options = array_merge([
                    'class' => 'form-check-input',
                    'label' => $encode ? Html::encode($label) : $label,
                    'labelOptions' => ['class' => 'form-check-label'],
                    'value' => $value
                ], $itemOptions);

                return
                    Html::beginTag('div', $wrapperOptions) . "\n" .
                    Html::checkbox($name, $checked, $options) . "\n" .
                    Html::endTag('div') . "\n";
            };
        }

        parent::checkboxList($items, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function radioList($items, $options = [])
    {
        if (!isset($options['item'])) {
            $itemOptions = isset($options['itemOptions']) ? $options['itemOptions'] : [];
            $encode = ArrayHelper::getValue($options, 'encode', true);
            $wrapperOptions = ['class' => ['form-check']];
            if ($this->inline) {
                Html::addCssClass($wrapperOptions, 'form-check-inline');
            }
            $options['item'] = function ($i, $label, $name, $checked, $value) use (
                $itemOptions,
                $encode,
                $wrapperOptions
            ) {
                $options = array_merge([
                    'class' => 'form-check-input',
                    'label' => $encode ? Html::encode($label) : $label,
                    'labelOptions' => ['class' => 'form-check-label'],
                    'value' => $value
                ], $itemOptions);

                return
                    Html::beginTag('div', $wrapperOptions) . "\n" .
                    Html::radio($name, $checked, $options) . "\n" .
                    Html::endTag('div') . "\n";
            };
        }

        parent::radioList($items, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listBox($items, $options = [])
    {
        if ($this->form->layout === ActiveForm::LAYOUT_INLINE) {
            Html::removeCssClass($this->labelOptions, 'sr-only');
        }

        return parent::listBox($items, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function dropdownList($items, $options = [])
    {
        if ($this->form->layout === ActiveForm::LAYOUT_INLINE) {
            Html::removeCssClass($this->labelOptions, 'sr-only');
        }

        return parent::dropdownList($items, $options);
    }

    /**
     * Renders Bootstrap static form control.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. There are also a special options:
     *
     * - encode: bool, whether value should be HTML-encoded or not.
     *
     * @return \yii\bootstrap4\ActiveField the field object itself
     * @see https://getbootstrap.com/docs/4.2/components/forms/#readonly-plain-text
     */
    public function staticControl($options = [])
    {
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeStaticControl($this->model, $this->attribute, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function label($label = null, $options = [])
    {
        if (is_bool($label)) {
            $this->enableLabel = $label;
            if ($label === false && $this->form->layout === ActiveForm::LAYOUT_HORIZONTAL) {
                Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
            }
        } else {
            $this->enableLabel = true;
            $this->renderLabelParts($label, $options);
            parent::label($label, $options);
        }
        return $this;
    }

    /**
     * @param bool $value whether to render a inline list
     * @return $this the field object itself
     * Make sure you call this method before [[checkboxList()]] or [[radioList()]] to have any effect.
     */
    public function inline($value = true)
    {
        $this->inline = (bool)$value;
        return $this;
    }

    /**
     * @param array $instanceConfig the configuration passed to this instance's constructor
     * @return array the layout specific default configuration for this instance
     */
    protected function createLayoutConfig($instanceConfig)
    {
        $config = [
            'hintOptions' => [
                'tag' => 'small',
                'class' => ['form-text', 'text-muted'],
            ],
            'errorOptions' => [
                'tag' => 'div',
                'class' => 'invalid-feedback',
            ],
            'inputOptions' => [
                'class' => 'form-control'
            ]
        ];

        $layout = $instanceConfig['form']->layout;

        if ($layout === ActiveForm::LAYOUT_HORIZONTAL) {
            $config['template'] = "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}";
            $config['wrapperOptions'] = [];
            $config['labelOptions'] = [];
            $config['options'] = [];
            $cssClasses = [
                'offset' => ['col-sm-10', 'offset-sm-2'],
                'label' => ['col-sm-2', 'col-form-label'],
                'wrapper' => 'col-sm-10',
                'error' => '',
                'hint' => '',
                'field' => 'form-group row'
            ];
            if (isset($instanceConfig['horizontalCssClasses'])) {
                $cssClasses = ArrayHelper::merge($cssClasses, $instanceConfig['horizontalCssClasses']);
            }
            $config['horizontalCssClasses'] = $cssClasses;

            Html::addCssClass($config['wrapperOptions'], $cssClasses['wrapper']);
            Html::addCssClass($config['labelOptions'], $cssClasses['label']);
            Html::addCssClass($config['errorOptions'], $cssClasses['error']);
            Html::addCssClass($config['hintOptions'], $cssClasses['hint']);
            Html::addCssClass($config['options'], $cssClasses['field']);
        } elseif ($layout === ActiveForm::LAYOUT_INLINE) {
            $config['inputOptions']['placeholder'] = true;
            $config['enableError'] = false;

            Html::addCssClass($config['labelOptions'], 'sr-only');
        }

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    public function fileInput($options = [])
    {
        Html::addCssClass($options, ['widget' => 'form-control-file']);
        return parent::fileInput($options);
    }

    /**
     * @param string|null $label the label or null to use model label
     * @param array $options the tag options
     */
    protected function renderLabelParts($label = null, $options = [])
    {
        $options = array_merge($this->labelOptions, $options);
        if ($label === null) {
            if (isset($options['label'])) {
                $label = $options['label'];
                unset($options['label']);
            } else {
                $attribute = Html::getAttributeName($this->attribute);
                $label = Html::encode($this->model->getAttributeLabel($attribute));
            }
        }
        if (!isset($options['for'])) {
            $options['for'] = Html::getInputId($this->model, $this->attribute);
        }
        $this->parts['{beginLabel}'] = Html::beginTag('label', $options);
        $this->parts['{endLabel}'] = Html::endTag('label');
        if (!isset($this->parts['{labelTitle}'])) {
            $this->parts['{labelTitle}'] = $label;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function customCheckbox($options = [], $enclosedByLabel = false)
    {
        Html::removeCssClass($options, 'form-control');
        Html::addCssClass($options, 'custom-control-input');
        Html::addCssClass($this->labelOptions, 'custom-control-label');

        if (!isset($options['template'])) {
            $this->template = ($enclosedByLabel) ? $this->customCheckEnclosedTemplate : $this->customCheckTemplate;
        } else {
            $this->template = $options['template'];
        }
        if ($this->form->layout === ActiveForm::LAYOUT_HORIZONTAL) {
            if (!isset($options['template'])) {
                $this->template = $this->customCheckHorizontalTemplate;
            }
            Html::removeCssClass($this->labelOptions, $this->horizontalCssClasses['label']);
            Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
        }
        unset($options['template']);

        if ($enclosedByLabel) {
            if (isset($options['label'])) {
                $this->parts['{labelTitle}'] = $options['label'];
            }
        }

        return parent::checkbox($options, false);
    }

    /**
     * {@inheritdoc}
     */
    public function customRadio($options = [], $enclosedByLabel = false)
    {
        Html::removeCssClass($options, 'form-control');
        Html::addCssClass($options, 'custom-control-input');
        Html::addCssClass($this->labelOptions, 'custom-control-label');

        if (!isset($options['template'])) {
            $this->template = ($enclosedByLabel) ? $this->customRadioEnclosedTemplate : $this->customRadioTemplate;
        } else {
            $this->template = $options['template'];
        }
        if ($this->form->layout === ActiveForm::LAYOUT_HORIZONTAL) {
            if (!isset($options['template'])) {
                $this->template = $this->customRadioHorizontalTemplate;
            }
            Html::removeCssClass($this->labelOptions, $this->horizontalCssClasses['label']);
            Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
        }
        unset($options['template']);

        if ($enclosedByLabel) {
            if (isset($options['label'])) {
                $this->parts['{labelTitle}'] = $options['label'];
            }
        }

        return parent::radio($options, false);
    }

    /**
     * {@inheritdoc}
     */
    public function customCheckboxList($items, $options = [])
    {
        if (!isset($options['item'])) {
            $itemOptions = isset($options['itemOptions']) ? $options['itemOptions'] : [];
            $encode = ArrayHelper::getValue($options, 'encode', true);
            $wrapperOptions = ['class' => ['custom-control custom-checkbox']];
            if ($this->inline) {
                Html::addCssClass($wrapperOptions, 'custom-control-inline');
            }
            $options['item'] = function ($i, $label, $name, $checked, $value) use (
                $itemOptions,
                $encode,
                $wrapperOptions
            ) {
                $options = array_merge([
                    'class' => 'custom-control-input',
                    'label' => $encode ? Html::encode($label) : $label,
                    'labelOptions' => ['class' => 'custom-control-label'],
                    'value' => $value
                ], $itemOptions);

                return
                    Html::beginTag('div', $wrapperOptions) . "\n" .
                    Html::checkbox($name, $checked, $options) . "\n" .
                    Html::endTag('div') . "\n";
            };
        }

        parent::checkboxList($items, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function customRadioList($items, $options = [])
    {
        if (!isset($options['item'])) {
            $itemOptions = isset($options['itemOptions']) ? $options['itemOptions'] : [];
            $encode = ArrayHelper::getValue($options, 'encode', true);
            $wrapperOptions = ['class' => ['custom-control custom-radio']];
            if ($this->inline) {
                Html::addCssClass($wrapperOptions, 'custom-control-inline');
            }
            $options['item'] = function ($i, $label, $name, $checked, $value) use (
                $itemOptions,
                $encode,
                $wrapperOptions
            ) {
                $options = array_merge([
                    'class' => 'custom-control-input',
                    'label' => $encode ? Html::encode($label) : $label,
                    'labelOptions' => ['class' => 'custom-control-label'],
                    'value' => $value
                ], $itemOptions);

                return
                    Html::beginTag('div', $wrapperOptions) . "\n" .
                    Html::radio($name, $checked, $options) . "\n" .
                    Html::endTag('div') . "\n";
            };
        }

        parent::radioList($items, $options);
        return $this;
    }

}
