<?php

use common\models\loan\Loan;
use common\models\user\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\loan\Loan */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-view">

    <h1><?= Yii::t('app', $model->getInitTypeName()) ?>: <?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'formattedPeriod',
            'currencyTypeName',
            'initTypeName',
            'statusName',
            'created',
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

    <?php if ($model->status === Loan::STATUS_STARTED && !Yii::$app->user->isGuest): ?>
        <?php if (
            ($model->init_type === Loan::INIT_TYPE_OFFER && Yii::$app->user->identity->roleName === User::ROLE_BORROWER)
            || ($model->init_type === Loan::INIT_TYPE_REQUEST && Yii::$app->user->identity->roleName === User::ROLE_LENDER)
        ): ?>

            <?= Html::a(Yii::t('app', 'Sign'), ['loan/sign', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to sign this contract?'),
                    'method' => 'post',
                ],
            ]) ?>

        <?php endif; ?>
    <?php endif; ?>

    <?php if (in_array($model->status, [Loan::STATUS_SIGNED, Loan::STATUS_OVERDUE]) && !Yii::$app->user->isGuest): ?>
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
