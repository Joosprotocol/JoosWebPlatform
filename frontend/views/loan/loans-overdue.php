<?php

use common\library\date\DateIntervalEnhanced;
use itmaster\core\helpers\Toolbar;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\loan\LoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Loans Overdue');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizeStorageKey' => 'loanGrid',
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'headerOptions' => ['class'=>'skip-export'],
                'contentOptions' => ['class'=>'skip-export'],
            ],
            'id',
            'borrower.fullName',
            'amount',
            'formattedPeriod',
            'currencyTypeName',
            'timeOverdue',
            'created',
            [
                'attribute' => 'Sign',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a(Yii::t('app', 'View'), \yii\helpers\Url::to(['loan/view-overdue', 'id' => $model->id]));
                }
            ],

            /*[
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class'=>'skip-export'],
                'contentOptions' => ['class'=>'skip-export'],
            ],*/
        ],
        'panel' => [
            'footer' => Toolbar::paginationSelect($dataProvider),
        ],
    ]); ?>
</div>
