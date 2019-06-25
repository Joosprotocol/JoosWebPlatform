<?php

use common\models\loan\Loan;
use common\models\user\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\loan\Loan */
/* @var $blockchainPersonal array */
/* @var $loanReferral \common\models\loan\LoanReferral */

$this->title = $model->hash_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-view">

    <div id="title-line">
        <div class="title-text"><?= Yii::t('app', 'Loan Overdue') ?></div>
        <div class="clearfix"></div>
    </div>

    <div class="panel">
        <div class="panel-body">

            <div class="panel-body-inner">

                <p>
                    <b>
                        <?= Yii::t('app', 'Amount') . ': ' . $model->amount;  ?>
                    </b>
                </p>

                <p>
                    <?= Yii::t('app', 'Status') . ': ' . $model->statusName;  ?>
                </p>
                <p>
                    <?= Yii::t('app', 'Currency') . ': ' . $model->currencyTypeName;  ?>
                </p>

                <p>
                    <?= Yii::t('app', 'Type') . ': ' . $model->initTypeName;  ?>
                </p>
                <p>
                    <?= Yii::t('app', 'Created') . ': ' . $model->created;  ?>
                </p>
                <p>
                    <?= Yii::t('app', 'Period') . ': ' . $model->formattedPeriod;  ?>
                </p>
                <p>
                    <?= Yii::t('app', 'Time Overdue') . ': ' . $model->timeOverdue;  ?>
                </p>

            </div>
        </div>
    </div>


    <div class="row">

        <?php if (!empty($model->lender_id)): ?>

            <div class="col-md-4">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <?= Yii::t('app', 'Lender') ?>
                    </div>
                    <div class="panel-heading-line"></div>
                    <div class="panel-body text-center">
                        <div class="avatar-circle">
                            <div class="avatar-circle-img" style="background-image: url(<?= $model->lender->avatarUrl ?>)"></div>
                        </div>
                        <div>
                            <?= HTML::a($model->lender->fullName, Url::to(['profile/public', 'id' => $model->lender->id])) ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <?php if (!empty($model->borrower_id)): ?>
            <div class="col-md-4">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <?= Yii::t('app', 'Borrower') ?>
                    </div>
                    <div class="panel-heading-line"></div>
                    <div class="panel-body text-center">
                        <div class="avatar-circle">
                            <div class="avatar-circle-img" style="background-image: url(<?= $model->borrower->avatarUrl ?>)"></div>
                        </div>
                        <div>
                            <?= HTML::a($model->borrower->fullName, Url::to(['profile/public', 'id' => $model->borrower->id])) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>


    </div>




    <?php if ($model->status === Loan::STATUS_OVERDUE && $blockchainPersonal !== false && Yii::$app->user->identity->roleName === User::ROLE_DIGITAL_COLLECTOR): ?>

        <?php if (!empty($loanReferral)): ?>

            <div class="panel panel-white">
                <div class="panel-heading">
                    <?= Yii::t('app', 'Personal info') ?>
                </div>
                <div class="panel-heading-line"></div>
                <div class="panel-body">
                    <div class="panel-body-inner">

                        <p>
                            <b>
                                Payment Referral
                            </b>
                            <?= HTML::a('Link', Url::to(['loan/follow', 'slug' => $loanReferral->slug])); ?>

                        </p>
                        <p></p>
                        <p>
                            <b>
                                <?= Yii::t('app', 'Issued ID') . ': ' ?>
                            </b>
                        </p>

                        <p>
                            <img src="<?= $blockchainPersonal['issuedIdUrl'] ?>" alt="">
                        </p>

                        <p>
                            <?= Yii::t('app', 'Email') . ': ' . $blockchainPersonal['email'] ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Facebook URL') . ': ' . HTML::a($blockchainPersonal['facebook_url'], $blockchainPersonal['facebook_url']);  ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Social URL') . ': ' . HTML::a($blockchainPersonal['social_url'], $blockchainPersonal['social_url']) ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Mobile Number') . ': ' . $blockchainPersonal['mobile_number'] ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Facebook First Friend') . ': ' . HTML::a($blockchainPersonal['facebook_friend_first_url'], $blockchainPersonal['facebook_friend_first_url']) ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Facebook Second Friend') . ': ' . HTML::a($blockchainPersonal['facebook_friend_second_url'], $blockchainPersonal['facebook_friend_second_url']) ?>
                        </p>

                        <p>
                            <?= Yii::t('app', 'Facebook Third Friend') . ': ' . HTML::a($blockchainPersonal['facebook_friend_third_url'], $blockchainPersonal['facebook_friend_third_url']) ?>
                        </p>

                    </div>
                </div>
            </div>





        <?php else: ?>

            <?= Html::a(Yii::t('app', 'Join'), ['loan/join-as-collector', 'hashId' => $model->hash_id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to join as collector to this contract?'),
                    'method' => 'post',
                ],
            ]) ?>

        <?php endif; ?>

    <?php else: ?>

        <p><?= Yii::t('app', 'Unable to load personal data.') ?></p>

    <?php endif; ?>

    <?php if ($model->status === Loan::STATUS_SIGNED && !Yii::$app->user->isGuest): ?>
        <?php if ($model->lender_id === Yii::$app->user->id): ?>

            <?= Html::a(Yii::t('app', 'Set as paid'), ['loan/set-as-paid', 'hsahId' => $model->hash_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to set as paid this contract?'),
                    'method' => 'post',
                ],
            ]) ?>

        <?php endif; ?>
    <?php endif; ?>
</div>
