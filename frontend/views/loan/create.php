<?php

use common\models\loan\Loan;
use frontend\forms\loan\LoanCreateForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model LoanCreateForm */

$this->title = Yii::t('app', 'New') . ' ' . Yii::t('app', $model->getLoan()->getInitTypeName());
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-create">

    <div id="title-line">
        <div class="title-text"><?= Html::encode($this->title) ?></div>
        <div class="clearfix"></div>

    </div>

    <div class="row">
        <div class="col-lg-8">

            <?php $form = ActiveForm::begin(); ?>

            <div class="panel panel-black">

                <div class="panel-heading">
                    <div class="panel-heading-icon icon-plus"></div>
                    <span class="panel-heading-text">
                        <?= Yii::t('app', 'New') . ' ' . Yii::t('app', $model->getLoan()->getInitTypeName()) ?>
                    </span>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'period')->dropdownList(LoanCreateForm::periodList()) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'fee')->textInput(['value' => 10]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'currency_type')->dropdownList(Loan::currencyTypeList()) ?>
                        </div>
                    </div>

                    <div class="pull-right buttons-line">
                        <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary']) ?>
                        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
                    </div>

                </div>

            </div>

            <?php ActiveForm::end(); ?>

            <div class="clearfix"></div>

        </div>
    </div>

</div>
