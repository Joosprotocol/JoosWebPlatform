<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\loan\Loan */

$this->title = Yii::t('app', 'Add Loan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
