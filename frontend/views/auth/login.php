<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model itmaster\core\models\forms\LoginForm */

$this->title = Yii::t('app', 'Login') ;

$fieldOptions = [
    'options' => ['class' => ''],
    'inputOptions' => ['class' => 'form-control input-lg']
]
?>

<div class="site-login">


    <div class="row">
        <div class="site-login-inner col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">

            <div class="title text-center">
                <h3> <?= Html::encode($this->title) ?> </h3>
            </div>

            <div class="subtitle text-center">
                <p><?= Yii::t('app', 'Please fill out the following fields to login'); ?></p>
            </div>
            <br>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => [
                    'class' => 'inverse-color'
                ]
            ]); ?>

            <?= $form->field($model, 'email', $fieldOptions)->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'password', $fieldOptions)->passwordInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'rememberMe', $fieldOptions)->checkbox() ?>


            <div class="note form-group">
                <a href="<?= Url::to(['auth/request-password-reset']); ?>"><?= Yii::t('app', 'If you forgot your password you can reset it'); ?></a>
            </div>

            <br>

            <div class="form-group text-center">
                <a href="<?= Url::to('/auth/signup')?>" class="btn btn-success">Sign Up</a>
                <input type="submit" class="btn btn-primary" name="login-button" value="Login">
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>

</div>
