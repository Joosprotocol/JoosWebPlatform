<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\user\User */
/* @var $personal common\models\user\UserPersonal */
/* @var $blockchainProfile common\models\user\BlockchainProfile */


$this->title = $model->username;

?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <img src="<?= $model->avatarUrl ?>" alt="">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'first_name',
            'last_name',
            'roleName'
        ],
    ]) ?>

</div>
