<?php

namespace frontend\controllers;


use common\library\collateral\CollateralLoanPaymentService;
use common\library\collateral\CollateralLoanService;
use common\library\collateral\CollateralPaymentService;
use common\library\cryptocurrency\CryptoCurrencyRateService;
use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoan;
use frontend\forms\collateral\CollateralCreateForm;
use frontend\models\collateral\CollateralSearch;
use itmaster\core\access\AccessManager;
use itmaster\core\controllers\frontend\FrontController;
use Yii;

use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Collateral model.
 */
class CollateralController extends FrontController
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
                    'actions' => ['permission', 'refresh-payment', 'loan-refresh-payment', 'loan-withdraw'],
                    'roles' => ['@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create', 'post', 'view', 'my-collaterals', 'loan'],
                    'roles' => ['custom.permission.borrower:' . AccessManager::VIEW],
                ],

              ],
        ];
        return $behaviors;
    }

    /**
     * @param $action
     * @return mixed
     *
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
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CollateralCreateForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['post', 'hashId' => $model->getCollateral()->hash_id]);
        }
        return $this->render('create', [
            'model' => $model,
            'currencyRateList' => CryptoCurrencyRateService::getRateList()
        ]);
    }

    public function actionPost($hashId)
    {
        $collateral = $this->findModel($hashId);

        if ($collateral->status !== Collateral::STATUS_STARTED) {
            return $this->redirect(['view', 'hashId' => $collateral->hash_id]);
        }

        $collateralPaymentService = new CollateralPaymentService($collateral);
        $collateralPaymentService->prepareAddress();

        return $this->render('post', [
            'collateral' => $collateralPaymentService->getCollateral()
        ]);
    }

    public function actionView($hashId)
    {
        $collateral = $this->findModel($hashId);
        if ($collateral->status === Collateral::STATUS_STARTED) {
            return $this->redirect(['post', 'hashId' => $collateral->hash_id]);
        }
        return $this->render('view', [
            'model' => $collateral
        ]);
    }

    public function actionLoan($hashId)
    {
        $paymentAddress = null;
        $collateralLoan = $this->findLoanModel($hashId);
        $collateralLoanPaymentService = new CollateralLoanPaymentService($collateralLoan);
        $paymentsTotalAmount = $collateralLoanPaymentService->getPaymentsTotalAmount();
        $isAllowedToPay = $collateralLoanPaymentService->isAllowedToPay();
        if (in_array($collateralLoan->status, [CollateralLoan::STATUS_SIGNED, CollateralLoan::STATUS_PARTIALLY_PAID])) {
            $collateralLoanPaymentService->prepareAddress();
            $paymentAddress = $collateralLoanPaymentService->getPaymentAddress();
        }
        return $this->render('loan-view', [
            'model' => $collateralLoan,
            'paymentAddress' => $paymentAddress,
            'paymentsTotalAmount' => $paymentsTotalAmount,
            'isAllowedToPay' => $isAllowedToPay
        ]);
    }

    public function actionRefreshPayment()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $collateral = $this->findModel(\Yii::$app->request->post()['hashId']);
            $collateralPaymentService = new CollateralPaymentService($collateral);
            $collateralPaymentService->refreshPayment();
            return [
                'isAlreadyPaid' => $collateralPaymentService->isAlreadyPaid(),
                'paidAmount' => $collateralPaymentService->getPaidAmount()
            ];
        }
        return false;
    }

    public function actionLoanRefreshPayment()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $collateralLoan = $this->findLoanModel(\Yii::$app->request->post()['hashId']);
            $collateralLoanPaymentService = new CollateralLoanPaymentService($collateralLoan);
            $isAlreadyPaid = false;
            $paidAmount = 0;
            if ($collateralLoanPaymentService->isAllowedToPay()) {
                $collateralLoanPaymentService->prepareAddress();
                $collateralLoanPaymentService->refreshPayment();
                $isAlreadyPaid = $collateralLoanPaymentService->isAlreadyPaid();
                $paidAmount = $collateralLoanPaymentService->getPaidAmount();
            }

            $collateralLoanService = new CollateralLoanService($collateralLoan);
            $collateralLoanService->updateStatus();
            return [
                'isAlreadyPaid' => $isAlreadyPaid,
                'paidAmount' => $paidAmount
            ];
        }
        return false;
    }

    public function actionLoanWithdraw()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $collateralLoan = $this->findLoanModel(\Yii::$app->request->post()['hashId']);
            $collateralLoanPaymentService = new CollateralLoanPaymentService($collateralLoan);
            $isWithdrawn = false;
            $errorMessage = null;
            if ($collateralLoanPaymentService->withdraw()) {
                $isWithdrawn = true;
                $collateralLoanService = new CollateralLoanService($collateralLoan);
                $collateralLoanService->updateStatus();
            } else {
                $errorMessage = $collateralLoanPaymentService->getLastException()->getMessage();
            }

            return [
                'isWithdrawn' => $isWithdrawn,
                'errorMessage' => $errorMessage
            ];
        }
        return false;
    }

    public function actionMyCollaterals()
    {
        $searchModel = new CollateralSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->filterByInvestor($dataProvider, Yii::$app->user->id);

        return $this->render('index-personal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Collateral model based on its hash_id value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $hashId
     * @return Collateral the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($hashId)
    {
        if (($model = Collateral::findOne(['hash_id' => $hashId])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Finds the Collateral Loan model based on its hash_id value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $hashId
     * @return CollateralLoan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findLoanModel($hashId)
    {
        if (($model = CollateralLoan::findOne(['hash_id' => $hashId])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
