<?php

namespace backend\controllers;

use common\models\collateral\CollateralLoan;
use common\models\collateral\CollateralLoanSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Collateral model.
 */
class CollateralLoanController extends BackendController
{
    /**
     * Lists all Collateral models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CollateralLoanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the CollateralLoan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CollateralLoan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CollateralLoan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
