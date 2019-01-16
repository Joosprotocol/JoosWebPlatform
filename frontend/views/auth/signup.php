<?php

use common\models\user\User;
use common\models\user\UserPersonal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \frontend\forms\user\UserSignUpForm */

$this->title = Yii::t('app', 'Sign Up') ;

$fieldOptions = [
    'options' => ['class' => 'col-lg-6'],
    'inputOptions' => ['class' => 'form-control input-lg']
]
?>

<p>
    <?= Yii::t('app', 'Please fill out the following fields to signup:') ?>
</p>

<div class="site-signup">

    <div id="signup-data-container"
         data-role_names='<?= json_encode([
             "lender_name" => User::ROLE_LENDER,
             "borrower_name" => User::ROLE_BORROWER,
             "digital_collector_name" => User::ROLE_DIGITAL_COLLECTOR
         ])?>'
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-lg-10 col-lg-offset-1 alert alert-info">

        <div class="signup-form">

            <?php $form = ActiveForm::begin([
                'enableAjaxValidation' => true,
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'class' => 'form-horizontal'
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

                <h2><?= Yii::t('app', 'Borrower personal data.') ?></h2>

                <div class="col-lg-12">
                    <?= $this->render('_image', [
                        'model' => (new UserPersonal()),
                        'form' => $form,
                        'attributeName' => "issuedId"
                    ]) ?>
                </div>

                <?= $form->field($model, 'socialUrl', $fieldOptions)->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'mobileNumber', $fieldOptions)->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'facebookFriendFirstUrl', $fieldOptions)->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'facebookFriendSecondUrl', $fieldOptions)->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'facebookFriendThirdUrl', $fieldOptions)->textInput(['maxlength' => true]) ?>

            </div>

            <div id="signup-digital-collector-mode" style="display: none">

                <div class="clearfix"></div>

                <h2><?= Yii::t('app', 'Digital Collector data.') ?></h2>

                <?= $form->field($model, 'address', $fieldOptions)->textInput(['maxlength' => true]) ?>

            </div>

            <div class="clearfix"></div>

            <div class="pull-right">
                <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <div class="clearfix"></div>

        </div>
    </div>

</div>
