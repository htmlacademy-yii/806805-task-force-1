<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend site asset bundle.
 */
class mainAsset extends AssetBundle
{
    public $basePath = '@frontend';
    public $css = [
        'css/style.css',
        'css/normalize.css',
    ];
    public $js = [
        // 'js/dropzone.js',
        'js/main.css',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
