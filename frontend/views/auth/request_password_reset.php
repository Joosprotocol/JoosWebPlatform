<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */


$this->title = Yii::t('app', 'Reset password') ;

$fieldOptions = [
    'options' => ['class' => ''],
    'inputOptions' => ['class' => 'form-control input-lg']
]
?>

<div class="site-request-password-reset">

    <div class="site-request-password-reset-inner col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">

        <div class="title text-center">
            <h3> <?= Html::encode($this->title) ?> </h3>
        </div>

        <div class="subtitle text-center">
            <p><?= Yii::t('app', 'Please fill out your email. A link to reset password will be sent there.'); ?></p>
        </div>
        <br>

        <?php $form = ActiveForm::begin([
            'id' => 'request-password-reset-form',
            'options' => [
                'class' => 'inverse-color'
            ]
        ]); ?>

        <?= $form->field($model, 'email', $fieldOptions)->textInput(['maxlength' => true]) ?>

        <div class="pull-right">
            <?= Yii::t('app', 'Return to the') ?> <a href="<?= Url::to('/auth/login')?>"><?= Yii::t('app', 'login form') ?></a>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="form-group text-center">
            <input type="submit" class="btn btn-primary" name="login-button" value="Send">
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
