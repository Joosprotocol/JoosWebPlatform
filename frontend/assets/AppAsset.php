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
    public $css = [
        'css/styles.css'
    ];
    public $js = [
        'js/SignUpHelper.js',
        'js/CollateralCalcHelper.js',
        'js/CollateralPaymentHelper.js',
        'js/CollateralLoanPaymentHelper.js',
        'js/NotificationHelper.js',

    ];
    public $depends = [
        'itmaster\core\assets\FrontendAsset\FrontendAsset',
    ];
}
