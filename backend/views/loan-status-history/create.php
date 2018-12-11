<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\loan\LoanStatusHistory */

$this->title = Yii::t('app', 'Add Loan Status History');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loan Status Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-status-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
