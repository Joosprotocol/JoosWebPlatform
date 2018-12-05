<?php

namespace frontend\controllers;

use itmaster\core\controllers\frontend\FrontController;

/**
 * Class HelloController
 * @package frontend\controllers
 */
class HelloController extends FrontController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index.twig');
    }
}