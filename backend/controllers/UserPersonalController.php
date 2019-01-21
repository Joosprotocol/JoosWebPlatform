<?php

namespace backend\controllers;

use common\models\user\UserPersonal;
use common\models\user\UserPersonalSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for UserPersonal model.
 */
class UserPersonalController extends BackendController
{
    /**
     * Lists all UserPersonal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserPersonalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserPersonal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionConfirm($id)
    {
        $model = $this->findModel($id);
        $model->active = UserPersonal::ACTIVE_YES;

        if ($model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Finds the UserPersonal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserPersonal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserPersonal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
