<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\user\UserPersonal */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Borrower Personal Info'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-personal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'active',
            'issuedIdUrl:image',
            'facebook_url',
            'social_url',
            'mobile_number',
            'facebook_friend_first_url',
            'facebook_friend_second_url',
            'facebook_friend_third_url',
        ],
    ]) ?>

    <?= Html::a(Yii::t('app', 'Confirm'), ['/user-personal/confirm', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

</div>
