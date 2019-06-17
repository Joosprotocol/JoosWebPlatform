<?php

use itmaster\core\config\ConfigInterface;
use itmaster\core\models\Category;
use itmaster\core\models\Page;
use itmaster\core\module\ModuleIterator;
use itmaster\core\module\ModuleIteratorInterface;
use itmaster\core\module\ModuleManager;
use yii\base\Application;
use yii\base\Event;

define('ROOT', dirname(dirname(__DIR__)));
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('generators', dirname(dirname(__DIR__)) . '/generators');
Yii::setAlias('modules', dirname(dirname(__DIR__)) . '/modules');
Yii::setAlias('widgets', dirname(dirname(__DIR__)) . '/common/widgets');
Yii::setAlias('itmaster.core', dirname(dirname(__DIR__)) . '/vendor/itmaster/core/src/');
Yii::setAlias('itmaster.modules', '@itmaster.core/modules');
Yii::setAlias('itmaster.widgets', '@itmaster.core/widgets');
Yii::setAlias('image.default', '/images/default.png');
Yii::setAlias('image.url', '/storage');
Yii::setAlias('image.theme', '/themes/images');

// Register to return the same instance rather than creating new one on every get
Yii::$container->set(
    'configManager',
    new ModuleManager(
        new ModuleIterator(Yii::getAlias('@itmaster.modules')),
        Yii::getAlias(ModuleIteratorInterface::MODULE_CONFIG_PATH),
        ConfigInterface::ALLOW_CREATE | ConfigInterface::ALLOW_EMPTY
    )
);

Yii::$container->set('common\library\api\APIRequestHandlerInterface', 'common\library\api\APIRequestHandler');
Yii::$container->set('itmaster\core\module\ModuleManagerInterface', 'configManager');
Yii::$container->set('itmaster\core\config\ConfigInterface', 'configManager');
Yii::$container->set('dosamigos\tinymce\TinyMce', [
    'options' => ['rows' => 6],
    'language' => 'en',
    'clientOptions' => [
        'plugins' => [
            'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'save table contextmenu directionality emoticons template paste textcolor'
        ],
        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor"
    ]
]);

Event::on(Application::class, Application::EVENT_BEFORE_REQUEST, function (Event $event) {
    /** @var Application $app */
    $app = $event->sender;
    /** @var itmaster\seo\Module $moduleSeo */
    $moduleSeo = $app->getModule('seo');
    if ($moduleSeo !== null) {
        $moduleSeo->addPermittedModel(Category::class, ['record_id' => 'id', 'slug' => 'slug']);
        $moduleSeo->addPermittedModel(Page::class, ['record_id' => 'id', 'slug' => 'slug', 'title' => 'title']);
    }
});

