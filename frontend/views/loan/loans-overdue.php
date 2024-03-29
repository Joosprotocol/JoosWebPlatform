<?php

use common\library\date\DateIntervalEnhanced;
use frontend\library\GridHelper;
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

    <div id="title-line">
        <div class="title-text"><?= Html::encode($this->title) ?></div>
        <div class="clearfix"></div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizeStorageKey' => 'loanOverdueGrid',
        'options' => [
            'class' => 'white-grid-table'
        ],
        'columns' => [
            'hash_id',
            'borrower.fullName',
            'amount',
            'formattedPeriod',
            'currencyTypeName',
            'timeOverdue',
            'created',
            [
                'attribute' => 'View',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a(Yii::t('app', 'View'), \yii\helpers\Url::to(['loan/view', 'hashId' => $model->hash_id]));
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
