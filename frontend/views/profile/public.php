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

    <div id="title-line">
        <div class="title-text"><?= Yii::t('app', 'Profile') ?></div>
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Cancel'), ['view'], ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success', 'form']) ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="panel">
        <div class="panel-body">

            <div class="panel-body-inner">
                <div class="avatar-image">
                    <div class="avatar-image-img" style="background-image: url(<?= $model->avatarUrl ?>)"></div>
                </div>

                <div class="profile-info">

                    <p class="user-name">
                        <?= $model->username ?>
                    </p>

                    <p>
                        <?= Yii::t('app', 'Role') . ': ' . ucfirst($model->roleName) ?>
                    </p>


                    <hr>

                </div>
            </div>
        </div>
    </div>

</div>
