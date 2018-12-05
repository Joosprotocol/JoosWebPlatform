<?php

namespace backend\controllers;

use itmaster\core\controllers\BaseController;

/**
 * Class HelloController
 * @package backend\controllers
 */
class HelloController extends BaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index.twig');
    }
}