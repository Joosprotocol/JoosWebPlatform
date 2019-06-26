<?php

use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\models\collateral\Collateral;
use frontend\library\GridHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\loan\LoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Collateral */

$this->title = Yii::t('app', 'Collaterals');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="loan-index">

    <div id="title-line">
        <div class="title-text"><?= Html::encode($this->title) ?></div>
        <div class="clearfix"></div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizeStorageKey' => 'collateralGrid',
        'options' => [
            'class' => 'white-grid-table'
        ],
        'columns' => [
            'hash_id',
            [
                'attribute' => 'status',
                'filter' => Collateral::statusList(),
                'value' => 'statusName'
            ],
            [
                'label' => 'amount',
                'value' => function ($model) {
                    /* @var $model Collateral */
                    return ($model->getFormattedAmount());
                }
            ],
            [
                'label' => 'start_amount',
                'value' => function ($model) {
                    /* @var $model Collateral */
                    return ($model->start_amount / CryptoCurrencyTypes::precisionList()[$model->currency_type]);
                }
            ],
            [
                'attribute' => 'currency_type',
                'filter' => Collateral::currencyPostingTypeList(),
                'value' => 'currencyName'
            ],
            [
                'attribute' => 'created_at',
                'value' => 'created'
            ],
            [
                'label' => null,
                'format' => 'html',
                'value' => function ($model) {
                    /* @var $model Collateral */
                    return Html::a(Yii::t('app', 'View'), \yii\helpers\Url::to(['collateral/view', 'hashId' => $model->hash_id]));
                }
            ],
        ],
        'panel' => [
            'heading' => false,
        ],
        // set your toolbar
        'toolbar' => [
            GridHelper::getPerPageDropdown($dataProvider)
        ],
    ]); ?>
</div>
