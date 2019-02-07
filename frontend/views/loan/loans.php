<?php

use common\models\user\User;
use itmaster\core\helpers\Toolbar;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\loan\LoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Offers');
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
                    $route = \yii\helpers\Url::to(['loan/view', 'id' => $model->id]);
                    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->roleName === User::ROLE_DIGITAL_COLLECTOR) {
                        $route = \yii\helpers\Url::to(['loan/view-overdue', 'id' => $model->id]);
                    }
                    return Html::a(Yii::t('app', 'View'), $route);
                }
            ],
        ],
        'panel' => [
            'footer' => Toolbar::paginationSelect($dataProvider),
        ],
    ]); ?>
</div>
