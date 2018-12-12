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
     * @param int|null $id
     * @return string
     */
    public function actionIndex(int $id = null): string
    {
        return $this->render('index.twig', ['id' => $id]);
    }
}