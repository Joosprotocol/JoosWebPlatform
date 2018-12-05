<?php

/* @var $manager itmaster\core\module\ModuleManagerInterface */
$manager = Yii::$container->get('configManager');

$modules = [];
foreach ($manager->allActive() as $module) {
    $modules[$module] = [
        'class' => 'itmaster\core\modules\\' . $module . '\Module',
        'controllerNamespace' => 'itmaster\core\modules\\' . $module . '\controllers\backend',
        'viewPath' => '@modules/' . $module . '/views/backend',
    ];
}

return $modules;
