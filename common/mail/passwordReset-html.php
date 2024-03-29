<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user itmaster\core\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p><?= Yii::t('app', 'Hello') ?> <?= Html::encode($user->username) ?>,</p>

    <p><?= Yii::t('app', 'Follow the link below to reset your password') ?>:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
