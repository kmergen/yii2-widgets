<?php
/**
 * @copyright Copyright (c) Klaus Mergen
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace kmergen\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\base\InvalidConfigException;

/**
 * Invisible Recaptcha
 *
 * Widget to use the google Invisible Recaptcha.
 * 
 * @see https://developers.google.com/recaptcha/docs/invisible
 *
 * @author Klaus Mergen <kmergenweb@gmail.com>
 */
class InvisibleRecaptcha extends Widget
{

    /**
     * @var string The id of the form.
     */
    public $formId;

    /**
     * @var string The html options of the submit button
     */
    public $buttonOptions = [];
    
    /**
     * @var string The content of the button
     */
    public $buttonContent = 'Submit';

    /**
     * @var string The google sitekey
     */
    public $sitekey;
    
    /**
     * @var string The javascript code for the callback function
     */
    public $callbackJs;

    /**
     * @inherit
     */
    public function init()
    {
        if ($this->formId === null) {
            throw new InvalidConfigException('The "formId" property must be set.');
        }
        
        if ($this->sitekey === null) {
            throw new InvalidConfigException('The "sitekey" property must be set.');
        }
        
        if ($this->callbackJs === null) {
            $this->callbackJs = "document.getElementById('{$this->formId}').submit();";
        }
    }

    /**
     * @inherit
     */
    public function run()
    {
        echo $this->renderButton();

        $js = 'function onSubmit(token) {' . $this->callbackJs . '}';
        $view = $this->getView();
        $view->registerJs($js, $view::POS_END);
        $view->registerJsFile('https://www.google.com/recaptcha/api.js', ['position' => $view::POS_HEAD, 'async' => true, 'defer' => true]);
    }

    /**
     * Render the Html for the submit button.
     */
    public function renderButton()
    {
        $options['class'] = 'g-recaptcha';
        $options['data'] = ['sitekey' => $this->sitekey, 'callback' => 'onSubmit'];
        
        if (isset($this->buttonOptions['class'])) {
           $options['class'] = $options['class'] . ' ' .  $this->buttonOptions['class'];
           unset($this->buttonOptions['class']);
        }
        
        return Html::button($this->buttonContent, array_merge($options, $this->buttonOptions));
    }

}
