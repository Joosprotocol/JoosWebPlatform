<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\loan\LoanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="loan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lender_id') ?>

    <?= $form->field($model, 'borrower_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'period') ?>

    <?= $form->field($model, 'type') ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
