<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'frontend',
    'name' => 'Yii2 CMS',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl' => '/',
    'defaultRoute' => 'site/default/index',
    'layout' => 'main.twig',
    'components' => [
        'request' => [
            'baseUrl' => '',
        ],
        'user' => [
            'class' => 'itmaster\core\models\UserAccessManager',
            'identityClass' => 'itmaster\core\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/auth/login']
        ],
        'urlManager' => [
            'rules' => [],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_COOKIE', '_SESSION'],
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@frontend/views' => '@frontend/views',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => YII_DEBUG ? 'core/error/error' : 'core/error/index',
        ],
        'assetManager' => [
            'forceCopy' => YII_DEBUG,
        ]
    ],
    'params' => $params,
    'modules' => require(__DIR__ . '/modules.php'),
];
