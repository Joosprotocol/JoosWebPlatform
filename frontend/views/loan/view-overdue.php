<?php

use common\models\loan\Loan;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\loan\Loan */
/* @var $blockchainPersonal array */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-view">

    <h1><?= Yii::t('app', 'Overdue Loan') ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'formattedPeriod',
            'period',
            'currencyTypeName',
            'initTypeName',
            'created',
            'statusName',
            'referralLink'
        ],
    ]) ?>

    <?php if (!empty($model->lender_id)): ?>
        <h3><?= Yii::t('app', 'Lender') ?></h3>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'lender.fullName',
            ],
        ]) ?>
    <?php endif; ?>

    <?php if (!empty($model->borrower_id)): ?>
        <h3><?= Yii::t('app', 'Borrower') ?></h3>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'borrower.fullName',
            ],
        ]) ?>
    <?php endif; ?>

    <?php if ($model->status === Loan::STATUS_OVERDUE && $blockchainPersonal !== false): ?>
        <h3><?= Yii::t('app', 'Personal data') ?></h3>

    <p>
        <b>
            email
        </b>
        <?= $blockchainPersonal['email'] ?>
    </p>

    <p>
        <b>
            avatar
        </b>
        <img src="<?= $blockchainPersonal['avatarUrl'] ?>" alt="">
    </p>

    <p>
        <b>
            issued_id
        </b>
        <img src="<?= $blockchainPersonal['issuedIdUrl'] ?>" alt="">
    </p>

    <p>
        <b>
            facebook_url
        </b>
        <?= $blockchainPersonal['facebook_url'] ?>
    </p>

    <p>
        <b>
            social_url
        </b>
        <?= $blockchainPersonal['social_url'] ?>
    </p>

    <p>
        <b>
            mobile_number
        </b>
        <?= $blockchainPersonal['mobile_number'] ?>
    </p>

    <p>
        <b>
            facebook_friend_first_url
        </b>
        <?= $blockchainPersonal['facebook_friend_first_url'] ?>
    </p>

    <p>
        <b>
            facebook_friend_second_url
        </b>
        <?= $blockchainPersonal['facebook_friend_second_url'] ?>
    </p>

    <p>
        <b>
            facebook_friend_third_url
        </b>
        <?= $blockchainPersonal['facebook_friend_third_url'] ?>
    </p>

    <?php else: ?>

        <p><?php Yii::t('app', 'Unable to load personal data.') ?></p>

    <?php endif; ?>

    <?php if ($model->status === Loan::STATUS_SIGNED && !Yii::$app->user->isGuest): ?>
        <?php if ($model->lender_id === Yii::$app->user->id): ?>

            <?= Html::a(Yii::t('app', 'Set as paid'), ['loan/set-as-paid', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to set as paid this contract?'),
                    'method' => 'post',
                ],
            ]) ?>

        <?php endif; ?>
    <?php endif; ?>
</div>
