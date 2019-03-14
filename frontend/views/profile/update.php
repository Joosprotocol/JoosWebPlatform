<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\user\User */
/* @var $personal \common\models\user\UserPersonal */
/* @var $blockchainProfile common\models\user\BlockchainProfile */


$this->title = Yii::t('app', 'Edit Profile') . ': ' . $model->username;
?>

<div class="user-update">

    <div id="title-line">
        <div class="title-text"><?= Html::encode($this->title) ?></div>
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Cancel'), ['view'], ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success', 'form' => 'user-update']) ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'personal' => $personal,
        'blockchainProfile' => $blockchainProfile,
    ]) ?>

</div>
