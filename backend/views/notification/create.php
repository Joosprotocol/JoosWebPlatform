<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\notification\Notification */

$this->title = Yii::t('app', 'Add Notification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
