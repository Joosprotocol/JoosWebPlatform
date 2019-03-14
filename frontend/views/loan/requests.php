<?php

use common\library\date\DateIntervalEnhanced;
use frontend\library\GridHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\loan\LoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Requests');
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
        'resizeStorageKey' => 'requestGrid',
        'options' => [
            'class' => 'white-grid-table'
        ],
        'columns' => [
            'id',
            'borrower.fullName',
            'amount',
            'formattedPeriod',
            'currencyTypeName',
            'created',
            [
                'attribute' => 'Sign',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a(Yii::t('app', 'View'), \yii\helpers\Url::to(['loan/view', 'id' => $model->id]));
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
