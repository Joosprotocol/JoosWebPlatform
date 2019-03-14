<?php

use frontend\library\GridHelper;

use kartik\grid\GridView;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel common\models\notification\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">

    <div id="title-line">
        <div class="title-text"><?= Html::encode($this->title) ?></div>
        <div class="clearfix"></div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizeStorageKey' => 'notificationGrid',
        'options' => [
            'class' => 'white-grid-table'
        ],
        'columns' => [
            'text:html',
            'created'

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
