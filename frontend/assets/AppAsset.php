<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package frontend\assets
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'js/SignUpHelper.js',
    ];
    public $depends = [
        'itmaster\core\assets\FrontendAsset\FrontendAsset',
    ];
}
