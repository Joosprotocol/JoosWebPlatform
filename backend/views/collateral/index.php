<?php

use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\models\collateral\Collateral;
use common\models\user\User;
use itmaster\core\helpers\Toolbar;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\collateral\CollateralSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Collaterals');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collateral-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizeStorageKey' => 'CollateralGrid',
        'panel' => [
            'footer' => Html::tag('div', Toolbar::createButton(Yii::t('app', 'Add Collateral')), ['class' => 'pull-left'])
                . Toolbar::paginationSelect($dataProvider),
        ],
        'toolbar' => [
            Toolbar::toggleButton($dataProvider),
            Toolbar::refreshButton(),
            Toolbar::createButton(Yii::t('app', 'Add Collateral')),
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
            'hash_id',
            [
                'label' => Yii::t('app', 'Loans'),
                'format' => 'html',
                'value' => function ($model) {
                    /* @var $model Collateral */
                    $linksArray = [];
                    foreach ($model->collateralLoans as $collateralLoan) {
                        $linksArray[] = $collateralLoan->id;
                    }
                    return implode($linksArray, "&nbsp;|&nbsp;");
                }
            ],
            [
                'attribute' => 'investor_id',
                'format' => 'html',
                'filter' => User::getList(),
                'value' => function ($model) {
                    /* @var $model Collateral */
                    return Html::a($model->investor->fullName, Url::to(['/user/view', 'id' => $model->investor->id]));
                }
            ],
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
        ],
    ]); ?>
</div>
