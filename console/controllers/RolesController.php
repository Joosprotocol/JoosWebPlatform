<?php

namespace console\controllers;

use common\models\user\User;
use itmaster\core\access\AccessChainsDependencies;
use itmaster\core\access\AccessManager;
use itmaster\core\access\AccessRoleApplicant;
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

    const CUSTOM_ROLE_PERMISSION_PREFIX = 'custom.permission';

    /**
     * @return void
     */
    public function actionUpdate()
    {
        $this->auth = \Yii::$app->authManager;

        $this->addJoosMainRoles();
        $this->addPermissionsForMainRoles();
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

    private function addPermissionsForMainRoles()
    {
        $accessManager = new AccessManager();

        if ($this->auth->getPermission(self::CUSTOM_ROLE_PERMISSION_PREFIX . '.' . User::ROLE_LENDER) === null) {
            $this->auth->add($lenderPermission = $this->auth->createPermission(self::CUSTOM_ROLE_PERMISSION_PREFIX . '.' . User::ROLE_LENDER));
            $this->stdout('Permission: "' . $lenderPermission->name . '" added.' .  PHP_EOL, Console::FG_YELLOW);

            $lenderRole = $this->auth->getRole(User::ROLE_LENDER);
            if ($lenderRole !== null) {
                $accessManager->assignPermissions(
                    new AccessChainsDependencies($lenderPermission->name),
                    new AccessRoleApplicant($lenderRole->name),
                    AccessManager::VIEW
                );
                $this->stdout('Permission: "' . $lenderPermission->name . '" assign to: ' . $lenderRole->name . '.' .  PHP_EOL, Console::FG_YELLOW);

            }
        }

        if ($this->auth->getPermission(self::CUSTOM_ROLE_PERMISSION_PREFIX . '.' . User::ROLE_BORROWER) === null) {
            $this->auth->add($borrowerPermission = $this->auth->createPermission(self::CUSTOM_ROLE_PERMISSION_PREFIX . '.' . User::ROLE_BORROWER));
            $this->stdout('Permission: "' . $borrowerPermission->name . '" added.' .  PHP_EOL, Console::FG_YELLOW);

            $borrowerRole = $this->auth->getRole(User::ROLE_BORROWER);
            if ($borrowerRole !== null) {
                $accessManager->assignPermissions(
                    new AccessChainsDependencies($borrowerPermission->name),
                    new AccessRoleApplicant($borrowerRole->name),
                    AccessManager::VIEW
                );
                $this->stdout('Permission: "' . $borrowerPermission->name . '" assign to: ' . $borrowerRole->name . '.' .  PHP_EOL, Console::FG_YELLOW);

            }
        }

        if ($this->auth->getPermission(self::CUSTOM_ROLE_PERMISSION_PREFIX . '.' . User::ROLE_DIGITAL_COLLECTOR) === null) {
            $this->auth->add($digitalCollectorPermission = $this->auth->createPermission(self::CUSTOM_ROLE_PERMISSION_PREFIX . '.' . User::ROLE_DIGITAL_COLLECTOR));
            $this->stdout('Permission: "' . $digitalCollectorPermission->name . '" added.' .  PHP_EOL, Console::FG_YELLOW);

            $digitalCollectorRole = $this->auth->getRole(User::ROLE_DIGITAL_COLLECTOR);
            if ($digitalCollectorRole !== null) {
                $accessManager->assignPermissions(
                    new AccessChainsDependencies($digitalCollectorPermission->name),
                    new AccessRoleApplicant($digitalCollectorRole->name),
                    AccessManager::VIEW
                );
                $this->stdout('Permission: "' . $digitalCollectorPermission->name . '" assign to: ' . $digitalCollectorRole->name . '.' .  PHP_EOL, Console::FG_YELLOW);

            }
        }

    }
}
