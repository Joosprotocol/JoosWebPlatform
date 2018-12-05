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
    'defaultRoute' => 'core/backend/dashboard/index',
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
        'errorHandler' => [
            'errorAction' => YII_DEBUG ? 'core/error/error' : 'core/error/index',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [],
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
