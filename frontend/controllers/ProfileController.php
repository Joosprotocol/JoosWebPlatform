<?php

namespace frontend\controllers;

use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\exceptions\InvalidModelException;
use common\models\user\BlockchainProfile;
use common\models\user\User;
use common\models\user\UserPersonal;
use frontend\forms\user\BlockchainAddressForm;
use itmaster\core\controllers\frontend\FrontController;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * AccountController implements the CRUD actions for Page model.
 * @package itmaster\core\controllers\frontend
 */
class ProfileController extends FrontController
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
                    'actions' => ['view', 'update', 'public'],
                    'roles' => ['@'],
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
            'blockchainProfiles' => $model->blockchainProfiles,
        ]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function actionPublic($id)
    {
        $model = $this->findModel($id);
        return $this->render('public', [
            'model' => $model
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

        $personal = new UserPersonal();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->save()) {
                    throw new InvalidModelException($personal);
                }

                if ($model->roleName === User::ROLE_BORROWER) {
                    $personal->load(Yii::$app->request->post());
                    $personal->user_id = $model->id;
                    if (!$personal->save()) {
                        throw new InvalidModelException($personal);
                    }
                }

                if ($model->roleName === User::ROLE_DIGITAL_COLLECTOR
                    || $model->roleName === User::ROLE_BORROWER) {
                    $blockchainAddressForm = new BlockchainAddressForm($model);
                    $blockchainAddressForm->load(Yii::$app->request->post());
                    $blockchainAddressForm->save();
                }

                $transaction->commit();
                return $this->redirect(['view']);
            } catch (\Exception $exception) {
                $transaction->rollBack();

            }
        }

        return $this->render('update', [
            'model' => $model,
            'personal' => (!empty($model->personal)) ? $model->personal : $personal,
            'blockchainAddressForm' => new BlockchainAddressForm($model),
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
