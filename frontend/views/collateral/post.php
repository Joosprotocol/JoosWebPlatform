<?php

use common\models\collateral\Collateral;
use yii\helpers\Html;

/* @var $this yii\web\View */

/* @var $currencyRateList array */
/* @var $collateral Collateral */

$this->title = Yii::t('app', 'Collateral payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collaterals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collateral-post">

    <div id="title-line">
        <div class="title-text"><?= Html::encode($this->title) ?></div>
        <div class="clearfix"></div>

    </div>


    <div id="collateral-payment-data"
         data-collateral-hash-id = "<?= $collateral->hash_id ?>">
    </div>

    <div class="row">
        <div class="col-lg-8">


            <div class="panel panel-white collateral-post-block">
                <div class="panel-heading">
                    <?= Yii::t('app', 'Collateral Info') ?>
                </div>
                <div class="panel-heading-line"></div>
                <div class="panel-body">

                    <?php if (!empty($collateral->paymentAddress)): ?>
                        <p>
                            <?= Yii::t('app', 'Address') . ': ' ?>
                            <?= $collateral->paymentAddress->address ?>
                        </p>
                        <p>
                            <?= Yii::t('app', 'Need to pay') . ': ' ?>
                            <?= $collateral->getFormattedAmountWithCurrency() ?>
                        </p>

                    <?php endif; ?>

                    <p class="error-line"></p>

                        <?= Html::a(Yii::t('app', 'Refresh') . ' <span class="spin-icon glyphicon glyphicon-refresh"></span>', '#', [
                            'class' => 'btn btn-success js-refresh-post'
                        ]) ?>

                </div>
            </div>

        </div>
    </div>

</div>
