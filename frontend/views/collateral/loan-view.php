<?php

use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\models\blockchain\PaymentAddress;
use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoan;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $paymentAddress PaymentAddress */
/* @var int $paymentsTotalAmount */
/* @var $model CollateralLoan */
/* @var $isAllowedToPay bool */

$this->title = Yii::t('app', 'Collateral Loan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collateral Loan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collateral-loan-view">

    <div id="title-line">
        <div class="title-text"><?= Html::encode($this->title) ?></div>
        <div class="clearfix"></div>

    </div>

    <div id="collateral-loan-payment-data"
         data-collateral-loan-hash-id = "<?= $model->hash_id ?>">
    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="panel collateral-loan-block">
                <div class="panel-body">

                    <div class="panel-body-inner">

                        <p>
                            <b>
                                <?= Yii::t('app', 'Amount To Pay') . ': ' . $model->getFormattedAmountToPay() . ' ' . $model->currencyName ?>
                            </b>
                        </p>

                        <p>
                            <b>
                                <?= Yii::t('app', 'Amount') . ': ' . $model->getFormattedAmountWithCurrency() ?>
                            </b>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Status') . ': ' . $model->statusName;  ?>
                        </p>

                        <?php
                        $lender = ($model->is_platform == true) ? Yii::t('app', 'Joos Platform') : $model->lender->fullName;
                        ?>

                        <p>
                            <?= Yii::t('app', 'Sponsor') . ': ' . $lender; ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Period') . ': ' . $model->formattedPeriod; ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Fee') . ': ' . $model->fee . ' %'; ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'LVR') . ': ' . $model->lvr . ' %'; ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Created') . ': ' . $model->created;  ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Back to') . ' ' . Html::a(Yii::t('app', 'Collateral'), ['collateral/view', 'hashId' => $model->collateral->hash_id]) ?>
                        </p>


                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel panel-white collateral-loan-payments-block">
                <div class="panel-heading">
                    <?= Yii::t('app', 'Payment Info') ?>
                </div>
                <div class="panel-heading-line"></div>
                <div class="panel-body">

                    <p>
                        <?= Yii::t('app', 'Total Paid') . ': ' . $paymentsTotalAmount / CryptoCurrencyTypes::precisionList()[$model->currency_type] . ' ' . $model->currencyName;; ?>

                    </p>

                    <?php if ($isAllowedToPay ?? !empty($paymentAddress)): ?>
                        <p>
                            <?= Yii::t('app', 'Payment Address') . ': ' . $paymentAddress->address; ?>
                        </p>

                        <?= Html::a(Yii::t('app', 'Refresh') . ' <span class="spin-icon glyphicon glyphicon-refresh"></span>', '#', [
                            'class' => 'btn btn-success js-refresh-payment'
                        ]) ?>
                    <?php endif;?>

                    <p class="error-line"></p>

                    <?php if ($model->status === CollateralLoan::STATUS_PAID): ?>
                        <?= Html::a(Yii::t('app', 'Withdraw') . ' <span class="spin-icon glyphicon glyphicon-refresh"></span>', '#', [
                            'class' => 'btn btn-success js-withdraw'
                        ]) ?>
                    <?php endif;?>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <?php if (!empty($model->payments)): ?>

                <div class="panel panel-white collateral-loan-payments-block">
                    <div class="panel-heading">
                        <?= Yii::t('app', 'Payments') ?>
                    </div>
                    <div class="panel-heading-line"></div>
                    <div class="panel-body">
                        <?php foreach($model->payments as $payment): ?>
                            <p>
                                <b>
                                    <?= Yii::t('app', 'Amount') . ': ' . $payment->formattedAmountWithCurrency ?>
                                </b>
                            </p>

                            <p>
                                <?= Yii::t('app', 'Hash') . ': ' . $payment->hash; ?>
                            </p>

                            <p>
                                <?= Yii::t('app', 'Paid') . ': ' . $payment->created; ?>
                            </p>

                            <hr>

                        <?php endforeach;?>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>

</div>
