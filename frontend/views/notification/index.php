<?php

use common\models\notification\Notification;
use frontend\library\GridHelper;

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
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


    <div id="notification-data"
         data-notification-list = '<?= json_encode(ArrayHelper::getColumn($dataProvider->models, 'id')) ?>'>

    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizeStorageKey' => 'notificationGrid',
        'options' => [
            'class' => 'white-grid-table'
        ],
        'columns' => [
            [
                'attribute' => 'text',
                'format' => 'html',
                'value' => function ($model) {
                    /* @var Notification $model */
                    if ($model->status === Notification::STATUS_UNREAD) {
                        return Html::tag('b', $model->text);
                    }
                    return $model->text;
                }
            ],
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
