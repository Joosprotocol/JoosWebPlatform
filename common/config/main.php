<?php

use kartik\datecontrol\Module;

$config = [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=dbname',
            'username' => 'username',
            'password' => 'password',
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => '{{%i18n_message}}',
                    'messageTable' => '{{%i18n_translation}}',
                ],
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    //https://console.developers.google.com/apis/credentials?project=yii2-cms
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '882986957416-kjngefqsqq937pmldbuk4ec20b943ius.apps.googleusercontent.com',
                    'clientSecret' => 'vsHz7lkEqOQq5WEFvRyh2ksh',
                ],
                'facebook' => [
                    //https://developers.facebook.com/apps/1677872889155104/dashboard/
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '1677872889155104',
                    'clientSecret' => '79b3e34fe33a78d7b3d6f18c3c08cde9',
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => 'YFY3D4L6C2xNI0WpNtZYVIwPt',
                    'consumerSecret' => 'RbQgeHVU2AP1cGnTsWHK9sh0cjwGc2cTnyhtlE8d3EOjbxk3vL',
                ],
                'vkontakte' => [
                    //http://vk.com/editapp?id=5225211&section=info
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '5225211',
                    'clientSecret' => 'wzefF2RwBDU5TnJpL1Yq',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'itmaster\core\rbac\DbIcoManager',
            'defaultRoles' => ['user'],
        ],
        'accessManager' => [
            'class' => 'itmaster\core\access\AccessManager',
        ],
        'formatter' => [
            'datetimeFormat' => 'php:M d, Y H:i',
            'nullDisplay' => '',
            'currencyCode' => 'USD',
        ],
        'paypal' => [
            'class' => 'itmaster\core\paypal\PayPal',
            'clientId' => 'AWPb8IKXmaW5j9e8j4SpT5cyTeAjFFRS6wWtK3eF8sdibVGiWoQXG5svT-ugbnL0iwP_u6JZ2sexxsrC',
            'clientSecret' => 'EMnk9X8m_X4g9JzyhyfCgGjaS-0Qf8zwJb5d4yIJL7hLOx8P-Ne6JcQ1ANua5jNk2F8YMM9J05W3G5ZM',
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => YII_DEBUG ? [
                        'debug' => true,
                        'auto_reload' => true,
                    ] : [],
                    'extensions' => YII_DEBUG ? [
                        '\Twig_Extension_Debug',
                    ] : [],
                    'globals' => [
                        'Yii' => '\Yii',
                        'Html' => '\yii\helpers\Html',
                        'Url' => '\yii\helpers\Url',
                        'Language' => 'itmaster\core\modules\i18n\models\Language',
                        'User' => 'itmaster\core\models\User',
                        'Module' => 'itmaster\core\modules\module\models\Module',
                        'SnippetFrontend' => 'itmaster\core\modules\snippet\models\frontend\Snippet',
                        'Toolbar' => 'itmaster\core\helpers\Toolbar',
                        'CallbackHelper' => 'itmaster\core\helpers\CallbackHelper',
                    ],
                    'functions' => [
                        't' => 'Yii::t',
                    ],
                    'uses' => ['yii\bootstrap'],
                ],
            ],
        ],
    ],
    'params' => [
        // format settings for displaying each date attribute (ICU format example)
        'dateControlDisplay' => [
            Module::FORMAT_DATE => 'php:M d, Y',
            Module::FORMAT_TIME => 'HH:mm:ss',
        ],
        // format settings for saving each date attribute (PHP format example)
        'dateControlSave' => [
            Module::FORMAT_DATE => 'php:U',
            Module::FORMAT_TIME => 'php:H:i:s',
        ],
        'autoWidgetSettings' => [
            Module::FORMAT_DATE => [
                'pluginOptions' => [
                    'autoclose' => true,
                    'dateSettings' => [
                        'longDays' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                        'shortDays' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        'shortMonths' => [
                            'Jan',
                            'Feb',
                            'Mar',
                            'Apr',
                            'May',
                            'Jun',
                            'Jul',
                            'Aug',
                            'Sep',
                            'Oct',
                            'Nov',
                            'Dec'
                        ],
                        'longMonths' => [
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November',
                            'December',
                        ],
                        'meridiem' => ['AM', 'PM']
                    ]
                ]
            ],
        ],
    ],
    'bootstrap' => require(__DIR__ . '/modules-bootstrap.php'),
    'modules' => require(__DIR__ . '/modules.php'),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
];

// Development environment helper modules
if (YII_ENV_DEV) {
    // Debug module itself should be available if DEBUG mode is enabled
    if (YII_DEBUG) {
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.56.*'],
        ];
    }

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'crud' => [
                'class' => 'generators\crud\Generator',
                'templates' => [
                    'cms' => '@generators/crud/cms',
                ]
            ],
            'model' => [
                'class' => 'generators\model\Generator',
                'templates' => [
                    'cms' => '@generators/model/cms',
                ]
            ]
        ],
    ];
}

return $config;