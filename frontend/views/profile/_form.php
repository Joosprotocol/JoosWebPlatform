<?php

use common\models\user\User;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\user\User */
/* @var $personal common\models\user\UserPersonal */
/* @var $blockchainProfile common\models\user\BlockchainProfile */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'user-update', 'class' => 'form-horizontal']]); ?>


    <div class="row">

        <div class="col-lg-8">
            <div class="panel panel-black">

                <div class="panel-heading">
                    <div class="panel-heading-icon icon-profile"></div>
                    <span class="panel-heading-text">
                <?= Yii::t('app', 'Profile Update') ?>
            </span>
                </div>

                <div class="panel-body">
                    <?= $this->render('_image', [
                        'model' => $model,
                        'form' => $form,
                        'attributeName' => 'avatar'
                    ]) ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                        </div>

                    </div>

                </div>

            </div>
        </div>
        <?php if ($model->roleName === User::ROLE_BORROWER): ?>

            <div class="col-lg-4">

                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <?= Yii::t('app', 'Issued ID') ?>
                        </div>
                        <div class="panel-heading-line"></div>

                        <div class="panel-body">

                            <?= $this->render('_image', [
                                'model' => $personal,
                                'form' => $form,
                                'attributeName' => "issuedId"
                            ]) ?>

                        </div>
                    </div>
            </div>

            <div class="col-lg-8">

                <div class="panel panel-white">
                    <div class="panel-heading">
                        <?= Yii::t('app', 'Personal information') ?>
                    </div>
                    <div class="panel-heading-line"></div>

                    <div class="panel-body">

                        <div class="row">

                            <div class="col-md-6">
                                <?= $form->field($personal, 'facebook_url')->textInput(['maxlength' => true]) ?>
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($personal, 'social_url')->textInput(['maxlength' => true]) ?>
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($personal, 'mobile_number')->textInput(['maxlength' => true]) ?>
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($personal, 'facebook_friend_first_url')->textInput(['maxlength' => true]) ?>
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($personal, 'facebook_friend_second_url')->textInput(['maxlength' => true]) ?>
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($personal, 'facebook_friend_third_url')->textInput(['maxlength' => true]) ?>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        <?php endif; ?>

        <?php if ($model->roleName === User::ROLE_DIGITAL_COLLECTOR): ?>

            <div class="col-lg-8">

                <div class="panel panel-white">
                    <div class="panel-heading">
                        <?= Yii::t('app', 'Blockchain information') ?>
                    </div>
                    <div class="panel-heading-line"></div>

                    <div class="panel-body">

                        <?= $form->field($blockchainProfile, 'address')->textInput(['maxlength' => true]) ?>

                    </div>

                </div>
            </div>


        <?php endif; ?>

    </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>

</div>
