<?php

namespace frontend\controllers;


use common\library\loan\LoanReferralFollowingService;
use common\library\loan\LoanService;
use common\library\utilitytoken\fee\DigitalCollectorFeeService;
use common\models\loan\Loan;
use common\models\loan\LoanReferral;
use frontend\models\loan\LoanSearch;
use frontend\forms\loan\LoanCreateForm;
use itmaster\core\controllers\frontend\FrontController;
use Yii;

use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Loan model.
 */
class LoanController extends FrontController
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

    public function actionCreate()
    {
        $model = new LoanCreateForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->getLoan()->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewOverdue($id)
    {
        $loan = $this->findModel($id);
        $loanService = new LoanService($loan);

        return $this->render('view-overdue', [
            'model' => $loan,
            'blockchainPersonal' => $loanService->getBlockchainPersonal(),
            'loanReferral' => LoanReferral::findByLoanIdAndCollectorId($id, Yii::$app->user->id),
        ]);
    }

    public function actionSign($id)
    {
        $model = $this->findModel($id);
        $model->borrower_id = Yii::$app->user->id;
        $model->status = Loan::STATUS_SIGNED;
        $loanService = new LoanService($model);
        $loanService->sign();

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionSetAsPaid($id)
    {
        $model = $this->findModel($id);
        $DigitalCollectorFeeService = new DigitalCollectorFeeService($model);
        $DigitalCollectorFeeService->withdrawBatch();

        $model->status = Loan::STATUS_PAID;
        $loanService = new LoanService($model);
        $loanService->setStatus();

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Action only for digital-collector
     * @param $id
     * @return \yii\web\Response
     */
    public function actionJoinAsCollector($id)
    {
        if (LoanReferral::findByLoanIdAndCollectorId($id, Yii::$app->user->id) === null) {
            $loanReferral = new LoanReferral();
            $loanReferral->loan_id = $id;
            $loanReferral->digital_collector_id = Yii::$app->user->id;
            $loanReferral->save();
        }

        return $this->redirect(['view-overdue', 'id' => $id]);
    }

    /**
     * Action only for borrower
     * @param $slug
     * @return \yii\web\Response
     */
    public function actionFollow($slug)
    {
        $loanReferralFollowingService = new LoanReferralFollowingService($slug, Yii::$app->user->id);
        $loanReferralFollowingService->register();

        return $this->redirect(['view', 'id' => $loanReferralFollowingService->getLoanReferral()->loan_id]);
    }

    public function actionOffers()
    {
        $searchModel = new LoanSearch();
        $searchModel->init_type_strong = Loan::INIT_TYPE_OFFER;
        $searchModel->status_strong = Loan::STATUS_STARTED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('offers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRequests()
    {
        $searchModel = new LoanSearch();
        $searchModel->init_type_strong = Loan::INIT_TYPE_REQUEST;
        $searchModel->status_strong = Loan::STATUS_STARTED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('requests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLoansOverdue()
    {
        $searchModel = new LoanSearch();
        $searchModel->status_strong = Loan::STATUS_OVERDUE;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('loans-overdue', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Loan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Loan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Loan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
