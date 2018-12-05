<?php

/* @var $manager itmaster\core\module\ModuleManagerInterface */
$manager = Yii::$container->get('configManager');

$modulesBootstrap = [];
// catch some none lethal exceptions to prevent default 500 in web
try {
    foreach ($manager->allActive() as $module) {
        $className = 'itmaster\core\modules\\' . $module . '\Bootstrap';
        if (!class_exists($className)) {
            continue;
        }

        $modulesBootstrap[$module] = [
            'class' => $className,
        ];
    }
} catch (itmaster\core\exceptions\FileWriteException $ex) {
    // There is an file error. Most likely it will be resolved in request
    // so it's totally ok to ignore this kind of errors
} catch (itmaster\core\exceptions\ParseIniFileException $ex) {
    // This means that file doesn't contain valid ini settings
    // It is appropriate to skip this error at bootstrap, to get shiny message later
}

return $modulesBootstrap;
