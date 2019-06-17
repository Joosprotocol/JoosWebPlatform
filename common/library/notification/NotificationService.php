<?php


namespace common\library\notification;


use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoan;
use common\models\loan\Loan;
use common\models\loan\LoanFollowing;
use common\models\loan\LoanReferral;
use common\models\notification\Notification;
use common\models\payment\Payment;
use common\models\user\BlockchainProfile;
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
     * @param CollateralLoan $collateralLoan
     */
    public static function sendCollateralLoanSignNotification(CollateralLoan $collateralLoan)
    {
        $message = Notification::getMessages()[Notification::MESSAGE_COLLATERAL_LOAN_SIGNED] . '&nbsp;'
            . HTML::a(Yii::t('app', 'Collateral Loan'), Url::to(['collateral/loan', 'hashId' => $collateralLoan->hash_id]));
        Notification::create($collateralLoan->collateral->investor_id, $message);
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

    public static function sendChangeCollateralLoanStatusNotification(CollateralLoan $collateralLoan) : void
    {
        $message = Notification::getMessages()[Notification::MESSAGE_COLLATERAL_LOAN_STATUS_CHANGED] . '&nbsp;'
            . HTML::a(Yii::t('app', 'Loan'), Url::to(['collateral/loan', 'hashId' => $collateralLoan->hash_id])) . '&nbsp;'
            . Yii::t('app', 'Status') . ': ' . $collateralLoan->getStatusName();

        Notification::create($collateralLoan->collateral->investor_id, $message);
    }

    /**
     * @param Collateral $collateral
     */
    public static function sendCollateralCreatedNotification($collateral)
    {
        $message = Notification::getMessages()[Notification::MESSAGE_NEW_COLLATERAL_CREATED] . '&nbsp;'
            . HTML::a('Collateral', Url::to(['collateral/view', 'hashId' => $collateral->hash_id]));
        Notification::create($collateral->lender_id ?? $collateral->investor_id, $message);
    }

    /**
     * @param Collateral $collateral
     */
    public static function sendCollateralPaidNotification($collateral)
    {
        $message = Notification::getMessages()[Notification::MESSAGE_NEW_COLLATERAL_PAID] . '&nbsp;'
            . HTML::a('Collateral', Url::to(['collateral/view', 'hashId' => $collateral->hash_id]));
        Notification::create($collateral->lender_id ?? $collateral->investor_id, $message);
    }

    /**
     * @param Payment $payment
     * @internal param CollateralLoan $collateralLoan
     */
    public static function sendCollateralLoanNewPaymentNotification(Payment $payment)
    {
        $message = Notification::getMessages()[Notification::MESSAGE_COLLATERAL_LOAN_NEW_PAYMENT] . '&nbsp;'
            . HTML::a('Loan (for Collateral)', Url::to(['collateral/loan', 'hashId' => $payment->collateralLoan->hash_id]));
        Notification::create($payment->collateralLoan->collateral->investor_id, $message);
        if (!empty($payment->collateralLoan->lender_id)) {
            Notification::create($payment->collateralLoan->lender_id, $message);
        }
    }

    /**
     * @param Collateral $collateral
     * @internal param Loan $loan
     */
    public static function sendCollateralLoanPaymentAddressErrorNotification(Collateral $collateral)
    {
        $message = Notification::getMessages()[Notification::MESSAGE_COLLATERAL_LOAN_PAYMENT_ADDRESS_ERROR] . '&nbsp;'
            . HTML::a('Collateral', Url::to(['collateral/view', 'id' => $collateral->hash_id]));
        Notification::create($collateral->investor_id, $message);
    }

    /**
     * @param CollateralLoan $collateralLoan
     */
    public static function sendNewCollateralLoanPaymentNotification(CollateralLoan $collateralLoan)
    {
        $message = Notification::getMessages()[Notification::MESSAGE_NEW_COLLATERAL_LOAN_PAYMENT_PLATFORM] . '&nbsp;' . $collateralLoan->getFormattedAmountWithCurrency() . '.&nbsp;'
            . HTML::a('Collateral', Url::to(['collateral/view', 'hashId' => $collateralLoan->collateral->hash_id]));
        Notification::create($collateralLoan->collateral->investor_id, $message);
    }

    /**
     * @param CollateralLoan $collateralLoan
     * @param BlockchainProfile $blockchainProfile
     */
    public static function sendCollateralLoanWithdrawNotification(CollateralLoan $collateralLoan, BlockchainProfile $blockchainProfile)
    {
        $message = Notification::getMessages()[Notification::MESSAGE_COLLATERAL_LOAN_WITHDRAWN] . '&nbsp;'
            . HTML::a('Collateral Loan', Url::to(['collateral/loan', 'hashId' => $collateralLoan->hash_id])) . '&nbsp;'
            . Yii::t('app', 'Address:') . '&nbsp;' . $blockchainProfile->address;
        Notification::create($collateralLoan->collateral->investor_id, $message);
    }
}
