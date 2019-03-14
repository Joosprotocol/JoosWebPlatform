<?php

namespace frontend\controllers;

use common\models\user\User;
use frontend\forms\user\UserSignUpForm;
use http\Exception\InvalidArgumentException;
use itmaster\core\controllers\AuthController as BaseAuthController;
use itmaster\core\models\forms\LoginForm;
use itmaster\core\models\forms\PasswordForm;
use itmaster\core\models\forms\PasswordResetRequestForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Response;


/**
 * Class AuthController
 * @package itmaster\core\controllers
 */
class AuthController extends BaseAuthController
{

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['signup', 'login', 'request-password-reset', 'password-reset'],
                    'roles' => ['?'],
                ],

            ],
        ];
        return $behaviors;
    }

    /**
     * @param $options
     * @return array
     */
    private static function getMessages($options)
    {
        return [
            self::SIGNUP_THANK_YOU => Yii::t(
                'app',
                'Thank you for signup. An activation link is sent to {email} to verify your email address.',
                $options
            ),
            self::SIGNUP_ERROR => Yii::t(
                'app',
                'There is a registering user error. Please correct validation errors.'
            )
        ];
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();

        } else {
            return $this->render('login', [
                'model' => $model
            ]);
        }
    }



    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new UserSignUpForm();
        if ($model->load(Yii::$app->request->post())) {
            if (\Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->signup()) {
                $this->showMessage(self::SIGNUP_THANK_YOU, ['email' => $model->email], 'success');
                return $this->goHome();
            } else {
                $this->showMessage(self::SIGNUP_ERROR);
            }
        }

        return $this->render('signup', [
            'model' => $model
        ]);
    }


    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->showMessage(self::PASSWORD_RESET_LINK, null, 'success');
                return $this->refresh();
            } else {
                $this->showMessage(self::PASSWORD_RESET_ERROR);
            }
        }

        return $this->render('request_password_reset', [
            'model' => $model
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token = null)
    {

        try {
            $model = new PasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            $this->showMessage(self::PASSWORD_SAVED, null, 'success');
            if ($model->isGuest) {
                return $this->goHome();
            }
            $this->trigger(self::EVENT_USER_LOGIN);
        }

        return $this->render('reset_password.twig', [
            'model' => $model
        ]);
    }

    /**
     * @param $errorId
     * @param array $options
     * @param string $type
     */
    private function showMessage($errorId, $options = [], $type = 'error')
    {
        Yii::$app->getSession()->setFlash($type, [self::getMessages($options)[$errorId]]);
    }
}
