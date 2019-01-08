<?php

use common\models\loan\Loan;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\forms\loan\LoanCreateForm */

$this->title = Yii::t('app', 'New') . ' ' . Yii::t('app', $model->getLoan()->getInitTypeName());
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="loan-form col-lg-8 alert alert-info">

        <?php $form = ActiveForm::begin(); ?>

        <div class="panel panel-default">

            <div class="panel-heading">
                <b>
                    <?= Yii::t('app', 'New') . ' ' . Yii::t('app', $model->getLoan()->getInitTypeName()) ?>
                </b>
            </div>

            <div class="panel-body">

                <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'period_days')->textInput(['value' => 30]) ?>

                <?= $form->field($model, 'currency_type')->dropdownList(Loan::currencyTypeList()) ?>

            </div>

        </div>

        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="clearfix"></div>

    </div>

</div>
