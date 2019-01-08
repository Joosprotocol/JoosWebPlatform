<?php

namespace console\controllers;

use common\models\user\User;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class HelloController
 * @package console\controllers
 */
class RolesController extends Controller
{
    /** @var \yii\rbac\ManagerInterface  */
    private $auth;

    /**
     * @return void
     */
    public function actionUpdate()
    {
        $this->auth = \Yii::$app->authManager;

        $this->addJoosMainRoles();
    }

    private function addJoosMainRoles()
    {
        if ($this->auth->getRole(User::ROLE_LENDER) === null) {
            $this->auth->add($user = $this->auth->createRole(User::ROLE_LENDER));
            $this->stdout('Role: "' . User::ROLE_LENDER . '" added.' .  PHP_EOL, Console::FG_YELLOW);
        }
        if ($this->auth->getRole(User::ROLE_BORROWER) === null) {
            $this->auth->add($user = $this->auth->createRole(User::ROLE_BORROWER));
            $this->stdout('Role: "' . User::ROLE_BORROWER . '" added.' .  PHP_EOL, Console::FG_YELLOW);
        }
        if ($this->auth->getRole(User::ROLE_DIGITAL_COLLECTOR) === null) {
            $this->auth->add($user = $this->auth->createRole(User::ROLE_DIGITAL_COLLECTOR));
            $this->stdout('Role: "' . User::ROLE_DIGITAL_COLLECTOR . '" added.' .  PHP_EOL, Console::FG_YELLOW);
        }
    }
}
