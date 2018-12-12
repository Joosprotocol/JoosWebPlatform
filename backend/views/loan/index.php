<?php

use itmaster\core\helpers\Toolbar;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\loan\LoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Loans');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizeStorageKey' => 'loanGrid',
        'panel' => [
            'footer' => Html::tag('div', Toolbar::createButton(Yii::t('app', 'Add Loan')), ['class' => 'pull-left'])
                . Toolbar::paginationSelect($dataProvider),
        ],
        'toolbar' => [
            Toolbar::toggleButton($dataProvider),
            Toolbar::refreshButton(),
            Toolbar::createButton(Yii::t('app', 'Add Loan')),
            Toolbar::deleteButton(),
            //Toolbar::showSelect(),
            Toolbar::exportButton(),
        ],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'headerOptions' => ['class'=>'skip-export'],
                'contentOptions' => ['class'=>'skip-export'],
            ],
            'id',
            'lender_id',
            'borrower_id',
            'status',
            'amount',
            'period',
            'currency_type',
            'init_type',
            'created',

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class'=>'skip-export'],
                'contentOptions' => ['class'=>'skip-export'],
            ],
        ],
    ]); ?>
</div>
