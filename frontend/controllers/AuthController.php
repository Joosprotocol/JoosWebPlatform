<?php

namespace frontend\controllers;

use common\models\user\User;
use frontend\forms\user\UserSignUpForm;
use itmaster\core\controllers\AuthController as BaseAuthController;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Request;
use yii\web\Response;


/**
 * Class AuthController
 * @package itmaster\core\controllers
 */
class AuthController extends BaseAuthController
{
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
     * @param $errorId
     * @param array $options
     * @param string $type
     */
    private function showMessage($errorId, $options = [], $type = 'error')
    {
        Yii::$app->getSession()->setFlash($type, [self::getMessages($options)[$errorId]]);
    }
}
