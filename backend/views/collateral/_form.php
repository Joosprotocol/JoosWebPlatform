<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\collateral\Collateral */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collateral-form col-lg-8 alert alert-info">

    <?php $form = ActiveForm::begin(); ?>

    <div class="panel panel-default">

        <div class="panel-heading"><b><?= Yii::t('app', 'Collateral') ?></b></div>

        <div class="panel-body">

            <?= $form->field($model, 'lender_id')->textInput() ?>

            <?= $form->field($model, 'investor_id')->textInput() ?>

            <?= $form->field($model, 'status')->dropdownList($model->statusList()) ?>

            <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'period')->textInput() ?>

            <?= $form->field($model, 'currency_type')->dropdownList($model->currencyPostingTypeList()) ?>



        </div>

    </div>

    <div class="pull-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>

</div>
