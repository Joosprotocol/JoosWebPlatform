<?php

namespace frontend\controllers;

use common\library\notification\NotificationReadService;
use common\models\Notification\Notification;
use common\models\notification\NotificationSearch;
use common\models\user\User;
use itmaster\core\access\AccessManager;
use itmaster\core\controllers\frontend\FrontController;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Notification model.
 */
class NotificationController extends FrontController
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
                    'actions' => ['view', 'index', 'set-as-read'],
                    'roles' => [
                        'custom.permission.digital-collector:' . AccessManager::VIEW,
                        'custom.permission.lender:' . AccessManager::VIEW,
                        'custom.permission.borrower:' . AccessManager::VIEW,
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

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionSetAsRead()
    {
        if (!Yii::$app->request->isAjax) {
            return null;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $list = Yii::$app->request->post('list');
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $result = NotificationReadService::setAsRead($user, $list);
        return [
            'result' => $result,
        ];

    }

    public function actionIndex()
    {
        $searchModel = new NotificationSearch();
        $searchModel->userIdStrong = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
