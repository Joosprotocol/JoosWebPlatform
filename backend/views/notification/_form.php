<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\notification\Notification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notification-form col-lg-8 alert alert-info">

    <?php $form = ActiveForm::begin(); ?>

    <div class="panel panel-default">

        <div class="panel-heading"><b><?= Yii::t('app', 'Notification') ?></b></div>

        <div class="panel-body">

            <?= $form->field($model, 'user_id')->textInput() ?>

            <?= $form->field($model, 'type')->textInput() ?>

            <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

        </div>

    </div>

    <div class="pull-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>

</div>
