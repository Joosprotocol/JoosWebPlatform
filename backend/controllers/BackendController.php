<?php

namespace backend\controllers;

use itmaster\core\controllers\BaseController;
use Yii;

class BackendController extends BaseController
{
    public function getViewPath()
    {
        return Yii::getAlias('@backend/views' . DIRECTORY_SEPARATOR . $this->id);
    }
}
