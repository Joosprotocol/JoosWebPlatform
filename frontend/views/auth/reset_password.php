<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $model itmaster\core\models\forms\PasswordForm */

$this->title = Yii::t('app', 'Reset password') ;

$fieldOptions = [
'options' => ['class' => ''],
'inputOptions' => ['class' => 'form-control input-lg']
]
?>

<div class="site-signup">
    <div class="row">
        <div class="site-signup-inner col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">

            <div class="title text-center">
                <h3> <?= Html::encode($this->title) ?> </h3>
            </div>

            <div class="subtitle text-center">
                <p><?= Yii::t('app', 'Please fill out your email. A link to reset password will be sent there.'); ?></p>
            </div>
            <br>

            <?php $form = ActiveForm::begin([
                'id' => 'password-reset-form',
                'options' => [
                    'class' => 'inverse-color'
                ]
            ]); ?>

            <?php if ($model->isGuest == false && !empty($model->identity->password_hash)): ?>

                <?= $form->field($model, 'old_password', $fieldOptions)->passwordInput() ?>

            <?php endif; ?>


            <?= $form->field($model, 'password', $fieldOptions)->passwordInput() ?>

            <?= $form->field($model, 'confirm_password', $fieldOptions)->passwordInput() ?>



            <div class="form-group text-center">
                <input type="submit" class="btn btn-primary" name="login-button" value="Save">
            </div>

            <div class="clearfix"></div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>

</div>
