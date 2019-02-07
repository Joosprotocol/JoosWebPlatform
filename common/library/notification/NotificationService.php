<?php


namespace common\library\notification;


use common\models\loan\Loan;
use common\models\loan\LoanFollowing;
use common\models\loan\LoanReferral;
use common\models\notification\Notification;
use common\models\user\User;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;

class NotificationService
{

    /**
     * @param Loan $loan
     * @param User $user
     */
    public static function sendLoanSignNotification(Loan $loan, User $user)
    {
        $message = Notification::getMessages()[Notification::MESSAGE_LOAN_SIGNED] . '&nbsp;'
            . HTML::a(Yii::t('app', 'Loan'), Url::to(['loan/view', 'id' => $loan->id])) . '&nbsp;'
            . HTML::a(Yii::t('app', 'Significant'), Url::to(['profile/public', 'id' => $user->id]));
        Notification::create($loan->borrower_id, $message);
        Notification::create($loan->lender_id, $message);
    }

    /**
     * @param Loan $loan
     */
    public static function sendLoanCreatedNotification(Loan $loan)
    {
        $message = Notification::getMessages()[Notification::MESSAGE_NEW_LOAN_CREATED] . '&nbsp;'
            . HTML::a('Loan', Url::to(['loan/view', 'id' => $loan->id]));
        Notification::create($loan->lender_id ?? $loan->borrower_id, $message);
    }

    /**
     * @param LoanReferral $loanReferral
     */
    public static function sendDigitalCollectorAddedNotification(LoanReferral $loanReferral)
    {
        if (empty($loanReferral->loan)) {
            return;
        }

        $message = Notification::getMessages()[Notification::MESSAGE_DIGITAL_COLLECTOR_JOINED] . '&nbsp;'
            . HTML::a(Yii::t('app', 'Loan'), Url::to(['loan/view', 'id' => $loanReferral->loan_id])) . '&nbsp;'
            . HTML::a(Yii::t('app', 'Digital Collector'), Url::to(['profile/public', 'id' => $loanReferral->digital_collector_id]));


        Notification::create($loanReferral->loan->borrower_id, $message);
        Notification::create($loanReferral->loan->lender_id, $message);

        if (!empty($loanReferral) && !empty($loanReferral->digitalCollector)) {
            Notification::create($loanReferral->digital_collector_id, $message);
        }
    }

    /**
     * @param LoanFollowing $loanFollowing
     */
    public static function sendBorrowerFollowedLinkNotification(LoanFollowing $loanFollowing)
    {
        if (empty($loanFollowing->loanReferral) || empty($loanFollowing->loanReferral->loan)) {
            return;
        }

        $message = Notification::getMessages()[Notification::MESSAGE_BORROWER_FOLLOWED_LINK] . '&nbsp;'
        . HTML::a(Yii::t('app', 'Loan'), Url::to(['loan/view', 'id' => $loanFollowing->loanReferral->loan_id])) . '&nbsp;'
        . HTML::a(Yii::t('app', 'Borrower'), Url::to(['profile/public', 'id' => $loanFollowing->borrower_id]));

        Notification::create($loanFollowing->borrower_id, $message);
        Notification::create($loanFollowing->loanReferral->loan->lender_id, $message);

        if (!empty($loanReferral) && !empty($loanReferral->digitalCollector)) {
            Notification::create($loanFollowing->loanReferral->digital_collector_id, $message);
        }
    }

    /**
     * @param Loan $loan
     */
    public static function sendChangeLoanStatusNotification(Loan $loan) : void
    {
        $message = Notification::getMessages()[Notification::MESSAGE_LOAN_STATUS_CHANGED] . '&nbsp;'
            . HTML::a(Yii::t('app', 'Loan'), Url::to(['loan/view', 'id' => $loan->id])) . '&nbsp;'
            . Yii::t('app', 'Status') . ': ' . $loan->getStatusName();

        Notification::create($loan->borrower_id, $message);
        Notification::create($loan->lender_id, $message);

        if (empty($loan->loanReferrals)) {
            return;
        }

        foreach ($loan->loanReferrals as $loanReferral) {
            Notification::create($loanReferral->digital_collector_id, $message);
        }
    }
}
