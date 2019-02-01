<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\user\User */
/* @var $personal \common\models\user\UserPersonal */
/* @var $blockchainProfile common\models\user\BlockchainProfile */


$this->title = Yii::t('app', 'Edit Profile') . ': ' . $model->username;
?>

<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'personal' => $personal,
        'blockchainProfile' => $blockchainProfile,
    ]) ?>

</div>
