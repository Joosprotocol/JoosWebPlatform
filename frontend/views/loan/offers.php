<?php

use frontend\library\GridHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\loan\LoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Offers');
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
        'resizeStorageKey' => 'offerGrid',
        'options' => [
            'class' => 'white-grid-table'
        ],
        'columns' => [
            'id',
            'lender.fullName',
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

            /*[
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class'=>'skip-export'],
                'contentOptions' => ['class'=>'skip-export'],
            ],*/
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
