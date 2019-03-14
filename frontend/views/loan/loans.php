<?php

use common\models\user\User;
use frontend\library\GridHelper;
use itmaster\core\helpers\Toolbar;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\loan\LoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'My Loans');
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
        'resizeStorageKey' => 'loanGrid',
        'options' => [
            'class' => 'white-grid-table'
        ],
        'columns' => [
            'id',
            'lender.fullName',
            'borrower.fullName',
            'amount',
            'formattedPeriod',
            'timeLeft',
            'timeOverdue',
            'statusName',
            'currencyTypeName',
            'created',
            'signed',
            [
                'attribute' => 'View',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a(Yii::t('app', 'View'), \yii\helpers\Url::to(['loan/view', 'id' => $model->id]));
                }
            ],
        ],
        'panel' => [
            'heading' => false,
        ],
        'toolbar' => [
            GridHelper::getPerPageDropdown($dataProvider)
        ],
    ]); ?>
</div>
