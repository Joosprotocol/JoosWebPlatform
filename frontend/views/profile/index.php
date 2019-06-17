<?php

use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\models\user\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\user\User */
/* @var $personal common\models\user\UserPersonal */
/* @var $blockchainProfiles common\models\user\BlockchainProfile[] */


$this->title = $model->username;

?>
<div class="user-view">

    <div id="title-line">
        <div class="title-text"><?= Yii::t('app', 'Profile') ?></div>
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
                        <?= Yii::t('app', 'Email') . ': ' . $model->email ?>
                    </p>
                    <p>
                        <?= Yii::t('app', 'Role') . ': ' . ucfirst($model->roleName) ?>
                    </p>


                    <hr>

                    <div class="pull-right">
                        <?= Html::a('Update Profile', ['update'], ['class' => 'btn btn-success']) ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if ($model->roleName === User::ROLE_BORROWER): ?>

        <div class="row">

            <div class="col-md-6">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        Issued ID
                    </div>
                    <div class="panel-heading-line"></div>
                    <div class="panel-body text-center">
                        <img src="<?= $personal->issuedIdUrl ?>" alt="">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        Personal info
                    </div>
                    <div class="panel-heading-line"></div>
                    <div class="panel-body">
                        <p>
                            <?= Yii::t('app', 'Facebook URL') . ': ' . HTML::a($personal->facebook_url, $personal->facebook_url);  ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Social URL') . ': ' . HTML::a($personal->social_url, $personal->social_url) ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Mobile Number') . ': ' . $personal->mobile_number ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Facebook First Friend') . ': ' . HTML::a($personal->facebook_friend_first_url, $personal->facebook_friend_first_url) ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Facebook Second Friend') . ': ' . HTML::a($personal->facebook_friend_second_url, $personal->facebook_friend_second_url) ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Facebook Third Friend') . ': ' . HTML::a($personal->facebook_friend_third_url, $personal->facebook_friend_third_url) ?>
                        </p>
                    </div>
                </div>
            </div>

        </div>

    <?php endif; ?>


    <?php if (!empty($blockchainProfiles)): ?>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <?= Yii::t('app', 'Blockchain information') ?>
                    </div>
                    <div class="panel-heading-line"></div>
                    <div class="panel-body">

                        <?php foreach($blockchainProfiles as $blockchainProfile): ?>
                            <p>
                                <?= CryptoCurrencyTypes::networksNameList()[$blockchainProfile->network] . ' ' . Yii::t('app', 'address') . ': ' . $blockchainProfile->address ?>
                            </p>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>
