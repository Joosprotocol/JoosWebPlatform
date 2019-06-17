<?php

use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\models\collateral\Collateral;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $collateralPostForm frontend\forms\collateral\CollateralPaymentService */
/* @var $currencyRateList array */
/* @var $model Collateral */

$this->title = Yii::t('app', 'Collateral');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collaterals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collateral-view">

    <div id="title-line">
        <div class="title-text"><?= Html::encode($this->title) ?></div>
        <div class="clearfix"></div>

    </div>

    <div class="panel collateral-block">
        <div class="panel-body">

            <div class="panel-body-inner">

                <p>
                    <b>
                        <?= Yii::t('app', 'Amount') . ': ' . $model->amount / CryptoCurrencyTypes::precisionList()[$model->currency_type] . ' ' . $model->currencyName ?>
                    </b>
                </p>

                <p>
                    <?= Yii::t('app', 'Start Amount') . ': ' . $model->start_amount / CryptoCurrencyTypes::precisionList()[$model->currency_type] . ' ' . $model->currencyName ?>
                </p>

                <p>
                    <?= Yii::t('app', 'Status') . ': ' . $model->statusName;  ?>
                </p>

                <p>
                    <?= Yii::t('app', 'Created') . ': ' . $model->created;  ?>
                </p>


            </div>
        </div>
    </div>

    <?php if (!empty($model->collateralLoans)):?>
    <div class="panel panel-white collateral-loan-block">
        <div class="panel-heading">
            <?= Yii::t('app', 'Loans') ?>
        </div>
        <div class="panel-heading-line"></div>
        <div class="panel-body">
            <?php foreach($model->collateralLoans as $collateralLoan): ?>
                <p>
                    <b>
                        <?= Yii::t('app', 'Amount') . ': ' . $collateralLoan->formattedAmount . ' ' . $collateralLoan->currencyName ?>
                    </b>
                </p>

                <?php
                $lender = ($collateralLoan->is_platform == true) ? Yii::t('app', 'Joos Platform') : $collateralLoan->lender->fullName;
                ?>
                <p>
                    <?= Yii::t('app', 'Sponsor') . ': ' . $lender; ?>
                </p>

                <p>
                    <?= Yii::t('app', 'Status') . ': ' . $collateralLoan->statusName; ?>
                </p>

                <p>
                    <?= Yii::t('app', 'Period') . ': ' . $collateralLoan->formattedPeriod; ?>
                </p>

                <p>
                    <?= Yii::t('app', 'Fee') . ': ' . $collateralLoan->fee . ' %'; ?>
                </p>

                <p>
                    <?= Yii::t('app', 'LVR') . ': ' . $collateralLoan->lvr . ' %'; ?>
                </p>

                <p>
                    <?= Yii::t('app', 'Created') . ': ' . $collateralLoan->created; ?>
                </p>

                <hr>

                <?= Html::a(Yii::t('app', 'Details'), ['collateral/loan', 'hashId' => $collateralLoan->hash_id], [
                    'class' => 'btn btn-success'
                ]) ?>

            <?php endforeach;?>
        </div>
    </div>
    <?php endif;?>

</div>
