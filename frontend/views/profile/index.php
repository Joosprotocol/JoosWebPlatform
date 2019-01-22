<?php

use common\models\user\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\user\User */
/* @var $personal common\models\user\UserPersonal */


$this->title = $model->username;

?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update'], ['class' => 'btn btn-primary']) ?>
    </p>

    <img src="<?= $model->avatarUrl ?>" alt="">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email',
            'first_name',
            'last_name',
            'roleName'
        ],
    ]) ?>

    <?php if ($model->roleName === User::ROLE_BORROWER): ?>

        <img src="<?= $personal->issuedIdUrl ?>" alt="">

        <?= DetailView::widget([
            'model' => $personal,
            'attributes' => [
                'facebook_url',
                'social_url',
                'mobile_number',
                'facebook_friend_first_url',
                'facebook_friend_second_url',
                'facebook_friend_third_url',
            ],
        ]) ?>

    <?php endif; ?>

</div>
