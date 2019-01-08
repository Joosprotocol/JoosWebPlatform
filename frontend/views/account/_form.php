<?php

use common\models\user\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\user\User */
/* @var $personal common\models\user\UserPersonal */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal']]); ?>

    <?= $this->render('_avatar', [
        'model' => $model,
        'form' => $form
    ]) ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

        <?php if ($model->roleName === User::ROLE_BORROWER): ?>

            <?= $form->field($personal, 'facebook_url')->textInput(['maxlength' => true]) ?>

            <?= $form->field($personal, 'social_url')->textInput(['maxlength' => true]) ?>

            <?= $form->field($personal, 'mobile_number')->textInput(['maxlength' => true]) ?>

            <?= $form->field($personal, 'facebook_friend_first_url')->textInput(['maxlength' => true]) ?>

            <?= $form->field($personal, 'facebook_friend_second_url')->textInput(['maxlength' => true]) ?>

            <?= $form->field($personal, 'facebook_friend_third_url')->textInput(['maxlength' => true]) ?>

        <?php endif; ?>

        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>

</div>
