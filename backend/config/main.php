<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'homeUrl' => '/admin',
    'bootstrap' => ['log'],
    'defaultRoute' => 'core/dashboard/index',
    'layout' => 'main.twig',
    'components' => [
        'request' => [
            'baseUrl' => '/admin'
        ],
        'user' => [
            'class' => 'itmaster\core\models\UserAccessManager',
            'identityClass' => 'itmaster\core\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'baseUrl' => '/admin',
            'rules' => [
            ],
        ],
        'errorHandler' => [
            'errorAction' => YII_DEBUG ? 'core/error/error' : 'core/error/index',
        ],
        /*'i18n' => [
            'translations' => [
                'app*' => [
                    'basePath' => '@backend/messages',
                ],
            ],
        ],*/
    ],
    'params' => $params,
    'modules' => require(__DIR__ . '/modules.php'),
];
