<?php
/**
 * @copyright Copyright &copy; Klaus Mergen, 2014
 * @package yii2-widgets
 * @version 2.0
 */

namespace kmergen\widgets;

use yii\helpers\Json;
use yii\widgets\LinkSorter as BaseSorter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Dropdown Sorter for bootstrap 4
 *
 * @author Klaus Mergen <kmergenweb@gmail.com>
 * @since 1.0
 */
class DropdownSorter extends BaseSorter
{
    /**
     * @inheritdoc
     */
    public $options = ['class' => 'dropdown'];

    /**
     * @inheritdoc
     */
    public $linkOptions = ['class' => 'dropdown-item'];

    /**
     * @var array Options for the dropdown menu
     */
    public $dropdownMenuOptions = ['class' => 'dropdown-menu'];

    /**
     * @var string The tag for the toggler a|button
     */
    public $togglerTag = 'a';
    /**
     * @var string The default text for the Toggler
     */
    public $togglerLabel = 'Sort By';

    /**
     * @var array The options for the dropdown toggle
     */
    public $togglerOptions = [
        'href' => '#',
        'class' => 'dropdown-toggle',
        'data-toggle' => 'dropdown',
        'aria-haspopup' => 'true',
        'aria-expanded' => 'false'
    ];

    /**
     * {@inheritDoc}
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!isset($this->options['id'])) {
            $this->options['id'] = 'dropdownSorter' . $this->getId();
        }
        if (!isset($this->togglerOptions['id'])) {
            $this->togglerOptions['id'] = 'dropdownToggler' . $this->getId();
        }
        if (!isset($this->dropdownMenuOptions['id'])) {
            $this->dropdownMenuOptions['id'] = 'dropdownMenu' . $this->getId();
        }

        $sort = $this->sort;
        $attributeOrders = $sort->getAttributeOrders();
        if (!empty($attributeOrders)) {
            reset($attributeOrders);
            $orderKey = key($attributeOrders);
            $this->togglerLabel = $sort->attributes[$orderKey]['label'];
        }
    }

    /**
     * Executes the widget.
     * This method renders the sort links.
     */
    public function run()
    {
        echo $this->renderSortLinks();
    }

    /**
     * @inheritdoc
     */
    protected function renderSortLinks()
    {
        $attributes = empty($this->attributes) ? array_keys($this->sort->attributes) : $this->attributes;
        $links = '';
        foreach ($attributes as $name) {
            $links .= $this->sort->link($name, $this->linkOptions) . "\n";
        }
        $dropdownMenu = Html::tag('div', $links, $this->dropdownMenuOptions);
        $toggler = Html::tag($this->togglerTag, $this->togglerLabel, $this->togglerOptions);

        return Html::tag('div', $toggler . $dropdownMenu, $this->options);
    }

}
