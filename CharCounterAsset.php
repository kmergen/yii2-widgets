<?php
/**
 * KM Websolutions Projects
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2010 KM Websolutions
 * @license http://www.yiiframework.com/license/
 */

namespace kmergen\widgets;

use yii\web\AssetBundle;

/**
 * CharCounterAsset
 *
 * @author Klaus Mergen <kmergenweb@gmail.com>
 * @since 2.0.0
 */
class CharCounterAsset extends AssetBundle
{
    public $sourcePath = '@vendor/kmergen/yii2-widgets/assets';
    public $js = [
        'charcounter.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (YII_DEBUG) {
            $this->js = [
                'charcounter.js'
            ];
        }
    }
}
