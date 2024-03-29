<?php

namespace backend\controllers;

use common\models\collateral\Collateral;
use common\models\collateral\CollateralSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Collateral model.
 */
class CollateralController extends BackendController
{
    /**
     * Lists all Collateral models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CollateralSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Collateral model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Collateral the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collateral::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
