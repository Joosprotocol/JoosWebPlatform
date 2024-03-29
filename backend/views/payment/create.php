<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\payment\Payment */

$this->title = Yii::t('app', 'Add Payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
