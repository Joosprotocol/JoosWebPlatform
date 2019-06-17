<?php

use common\models\user\User;
use common\models\user\UserPersonal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \frontend\forms\user\UserSignUpForm */

$this->title = Yii::t('app', 'Sign Up') ;

$fieldOptions = [
    'options' => ['class' => 'col-lg-12'],
    'inputOptions' => ['class' => 'form-control input-lg']
]
?>



<div class="site-signup">
    <div class="row">
        <div class="site-sign-in-inner col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">

            <div id="signup-data-container"
                 data-role_names='<?= json_encode([
                     "lender_name" => User::ROLE_LENDER,
                     "borrower_name" => User::ROLE_BORROWER,
                     "digital_collector_name" => User::ROLE_DIGITAL_COLLECTOR
                 ])?>'
            </div>

            <div class="title text-center">
                <h3> <?= Html::encode($this->title) ?> </h3>
            </div>


            <div class="subtitle text-center">
                <p><?= Yii::t('app', 'Please fill out the following fields to signup:') ?></p>
            </div>
            <br>

            <div class="signup-form">

                <?php $form = ActiveForm::begin([
                    'enableAjaxValidation' => true,
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class' => 'form-horizontal inverse-color'

                    ]
                ]); ?>

                <div class="col-lg-12">
                    <?= $this->render('_image', [
                        'model' => (new User()),
                        'form' => $form,
                        'attributeName' => "avatar"
                    ]) ?>
                </div>


                <?= $form->field($model, 'username', $fieldOptions)->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'email', $fieldOptions)->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'firstName', $fieldOptions)->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'lastName', $fieldOptions)->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'password', $fieldOptions)->passwordInput() ?>

                <?= $form->field($model, 'confirmPassword', $fieldOptions)->passwordInput() ?>

                <?= $form->field($model, 'roleName', $fieldOptions)->dropDownList(User::accessibleSignUpRoleList()) ?>

                <div id="signup-borrower-mode" style="display: none">

                    <div class="clearfix"></div>

                    <div class="title text-center"></div>

                    <div class="subtitle text-center"><p><?= Yii::t('app', 'Borrower personal data') ?></p></div>

                    <div class="col-lg-12">
                        <?= $this->render('_image', [
                            'model' => (new UserPersonal()),
                            'form' => $form,
                            'attributeName' => "issuedId"
                        ]) ?>
                    </div>

                    <?= $form->field($model, 'facebookUrl', $fieldOptions)->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'socialUrl', $fieldOptions)->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'mobileNumber', $fieldOptions)->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'facebookFriendFirstUrl', $fieldOptions)->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'facebookFriendSecondUrl', $fieldOptions)->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'facebookFriendThirdUrl', $fieldOptions)->textInput(['maxlength' => true]) ?>

                </div>

                <div id="signup-blockchain-profile">

                    <div class="clearfix"></div>

                    <div class="title text-center"></div>

                    <div class="subtitle text-center"><p><?= Yii::t('app', 'Blockchain Profiles') ?></p></div>

                    <?= $form->field($model, 'ethereumAddress', $fieldOptions)->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'bitcoinAddress', $fieldOptions)->textInput(['maxlength' => true]) ?>

                </div>

                <div class="clearfix"></div>

                <p></p>

                <div class="form-group text-center">
                    <a href="<?= Url::to('/auth/login')?>" class="btn btn-success"><?= Yii::t('app', 'I have account') ?></a>
                    <?= Html::submitButton(Yii::t('app', 'Sign Up'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <div class="clearfix"></div>

            </div>
        </div>
    </div>

</div>
