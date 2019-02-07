<?php

namespace frontend\controllers;


use common\library\loan\LoanReferralFollowingService;
use common\library\loan\LoanService;
use common\library\notification\NotificationService;
use common\library\utilitytoken\fee\DigitalCollectorFeeService;
use common\models\loan\Loan;
use common\models\loan\LoanReferral;
use common\models\user\User;
use frontend\models\loan\LoanSearch;
use frontend\forms\loan\LoanCreateForm;
use itmaster\core\access\AccessManager;
use itmaster\core\controllers\frontend\FrontController;
use Yii;

use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Loan model.
 */
class LoanController extends FrontController
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
                    'actions' => ['permission'],
                    'roles' => ['@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['view-overdue', 'loans-overdue', 'join-as-collector'],
                    'roles' => ['custom.permission.digital-collector:' . AccessManager::VIEW],
                ],
                [
                    'allow' => true,
                    'actions' => ['set-as-paid', 'requests'],
                    'roles' => ['custom.permission.lender:' . AccessManager::VIEW],
                ],
                [
                    'allow' => true,
                    'actions' => ['offers', 'follow'],
                    'roles' => ['custom.permission.borrower:' . AccessManager::VIEW],
                ],
                [
                    'allow' => true,
                    'actions' => ['view', 'create', 'sign'],
                    'roles' => [
                        'custom.permission.lender:' . AccessManager::VIEW,
                        'custom.permission.borrower:' . AccessManager::VIEW
                    ],
                ],
                [
                    'allow' => true,
                    'actions' => ['my-loans'],
                    'roles' => [
                        'custom.permission.lender:' . AccessManager::VIEW,
                        'custom.permission.borrower:' . AccessManager::VIEW,
                        'custom.permission.digital-collector:' . AccessManager::VIEW
                    ],
                ],

              ],
        ];
        return $behaviors;
    }

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
        /** @var  $user */
        $user = User::findOne(Yii::$app->user->id);
        $loanService = new LoanService($model);
        $loanService->sign($user);

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionSetAsPaid($id)
    {
        $model = $this->findModel($id);
        /** @var  $user */
        $user = User::findOne(Yii::$app->user->id);
        $DigitalCollectorFeeService = new DigitalCollectorFeeService($model);
        $DigitalCollectorFeeService->withdrawBatch();

        $loanService = new LoanService($model);
        $loanService->setStatus($user, Loan::STATUS_PAID);

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
            NotificationService::sendDigitalCollectorAddedNotification($loanReferral);
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
        $searchModel->initTypeStrong = Loan::INIT_TYPE_OFFER;
        $searchModel->statusStrong = Loan::STATUS_STARTED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('offers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRequests()
    {
        $searchModel = new LoanSearch();
        $searchModel->initTypeStrong = Loan::INIT_TYPE_REQUEST;
        $searchModel->statusStrong = Loan::STATUS_STARTED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('requests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLoansOverdue()
    {
        $searchModel = new LoanSearch();
        $searchModel->statusStrong = Loan::STATUS_OVERDUE;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('loans-overdue', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMyLoans()
    {
        $searchModel = new LoanSearch();
        $searchModel->ownerId = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('loans', [
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
