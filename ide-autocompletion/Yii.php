<?php
/**
 * Yii bootstrap file.
 * Used for enhanced IDE code auto-completion.
 * @link https://github.com/samdark/yii2-cookbook/blob/master/book/ide-autocompletion.md
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;
}

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 *
 * @property common\rbac\IcoManagerInterface $authManager
 * @property itmaster\storage\Storage $storage The storage library
 */
abstract class BaseApplication extends yii\base\Application
{
}

/**
 * Class WebApplication
 * Include only Web application related components here
 */
class WebApplication extends yii\web\Application
{
}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 *
 */
class ConsoleApplication extends yii\console\Application
{
}
