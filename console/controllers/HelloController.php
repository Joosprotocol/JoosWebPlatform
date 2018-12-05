<?php

namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class HelloController
 * @package console\controllers
 */
class HelloController extends Controller
{
    /**
     * @return void
     */
    public function actionIndex()
    {
        $this->stdout(\Yii::t('app', 'Hello!'), Console::BOLD);
        $this->stdout(PHP_EOL);
        $this->stdout(\Yii::t('app', 'This is console hello index action'));
        $this->stdout(PHP_EOL);

    }
}