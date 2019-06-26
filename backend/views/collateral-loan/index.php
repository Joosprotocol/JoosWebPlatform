<?php

use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\project\Standard;
use common\models\collateral\CollateralLoan;
use common\models\user\User;
use itmaster\core\helpers\Toolbar;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\collateral\CollateralLoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Collateral Loans');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collateral-loan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizeStorageKey' => 'CollateralLoanGrid',
        'panel' => [
            'footer' => Toolbar::paginationSelect($dataProvider),
        ],
        'toolbar' => [
            //Toolbar::toggleButton($dataProvider),
            Toolbar::refreshButton(),
            //Toolbar::createButton(Yii::t('app', 'Add CollateralLoan')),
            //Toolbar::deleteButton(),
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
            'hash_id',
            'collateral_id',
            [
                'attribute' => 'lvr',
                'value' => function ($model) {
                    /* @var $model CollateralLoan */
                    return $model->lvr . '%';
                }
            ],
            [
                'attribute' => 'fee',
                'value' => function ($model) {
                    /* @var $model CollateralLoan */
                    return $model->fee . '%';
                }
            ],
            [
                'attribute' => 'is_platform',
                'filter' => Standard::booleanList(),
                'value' => function ($model) {
                    /* @var $model CollateralLoan */
                    return Standard::booleanList()[$model->is_platform];
                }
            ],
            [
                'attribute' => 'lender_id',
                'format' => 'html',
                'filter' => User::getList(),
                'value' => function ($model) {
                    /* @var $model CollateralLoan */
                    if (!empty($model->investor)) {
                        return Html::a($model->investor->fullName, Url::to(['/user/view', 'id' => $model->investor->id]));
                    }
                    return '';
                }
            ],
            [
                'attribute' => 'status',
                'filter' => CollateralLoan::statusList(),
                'value' => 'statusName'
            ],
            [
                'attribute' => 'amount',
                'value' => function ($model) {
                    /* @var $model CollateralLoan */
                    return ($model->getFormattedAmount());
                }
            ],
            [
                'attribute' => 'currency_type',
                'filter' => CryptoCurrencyTypes::currencyTypeList(),
                'value' => 'currencyName'
            ],
            [
                'attribute' => 'collateral_amount',
                'value' => function ($model) {
                    /* @var $model CollateralLoan */
                    return ($model->collateral_amount / CryptoCurrencyTypes::precisionList()[$model->collateral->currency_type]);
                }
            ],
            [
                'attribute' => 'collateral_currency_type',
                'filter' => CryptoCurrencyTypes::currencyTypeList(),
                'value' => 'collateral.currencyName'
            ],
            'formattedPeriod',
            [
                'attribute' => 'created_at',
                'value' => 'created'
            ]
        ],
    ]); ?>
</div>
