<?php

namespace frontend\controllers;

use common\models\user\User;
use common\models\user\UserPersonal;
use itmaster\core\controllers\frontend\FrontController;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * AccountController implements the CRUD actions for Page model.
 * @package itmaster\core\controllers\frontend
 */
class AccountController extends FrontController
{
    /**
     * @param $action
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/auth/login')->send();
        }
        return parent::beforeAction($action);
    }

    /**
     * @return mixed
     */
    public function actionView()
    {

        $model = $this->findModel(Yii::$app->user->getIdentity()->getId());
        $personal = (!empty($model->personal)) ? $model->personal : new UserPersonal();

        return $this->render('index', [
            'model' => $model,
            'personal' => $personal,
        ]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = $this->findModel(Yii::$app->user->getIdentity()->getId());

        $personal = (!empty($model->personal)) ? $model->personal : new UserPersonal();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $valid = $model->save();
            if ($model->roleName === User::ROLE_BORROWER) {

                $valid = $valid && $personal->load(Yii::$app->request->post());
                $personal->user_id = $model->id;
                $valid = $valid && $personal->save();
            }

            if ($valid) {
                $transaction->commit();
                return $this->redirect(['view']);
            }
            $transaction->rollBack();
        }

        return $this->render('update', [
            'model' => $model,
            'personal' => $personal,
        ]);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var User $model */
        if (($model = User::find()->where(['id' => $id])->with('personal')->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
