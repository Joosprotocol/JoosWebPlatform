<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\collateral\Collateral */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collaterals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collateral-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => Yii::t('app', 'Lender'),
                'value' => $model->lender->fullName ?? null
            ],
            [
                'label' => Yii::t('app', 'Investor'),
                'value' => $model->investor->fullName ?? null
            ],
            'statusName',
            'amount',
            'formattedPeriod',
            [
                'label' => Yii::t('app', 'Payment address'),
                'value' => $model->paymentAddress->address
            ],
            'currencyName',
            'created',
        ],
    ]) ?>

</div>
