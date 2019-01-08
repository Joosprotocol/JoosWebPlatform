<?php


namespace common\library\loan;


use common\models\loan\LoanFollowing;
use common\models\loan\LoanReferral;
use common\models\user\User;
use itmaster\core\exceptions\DataNotFoundException;
use Yii;

class LoanReferralFollowingService
{
    /** @var LoanReferral */
    private $loanReferral;
    /** @var LoanFollowing */
    private $loanFollowing;

    /**
     * @param string $slug
     * @param integer $userId
     * @throws DataNotFoundException
     * @throws \LogicException
     */
    public function __construct($slug, $userId)
    {
        $this->loanReferral = LoanReferral::findBySlug($slug);
        if (empty($this->loanReferral)) {
            throw new DataNotFoundException('Referral link not found.');
        }

        if (!$this->canUserFollowReferralLink($userId)) {
            throw new \LogicException('Only borrower can follow referral link.');
        }
    }

    /**
     * @return bool
     */
    public function register()
    {
        $this->loanFollowing = LoanFollowing::findByReferral($this->loanReferral->id);

        if (empty($this->loanFollowing)) {
            $this->loanFollowing = new LoanFollowing();
            $this->loanFollowing->borrower_id = Yii::$app->user->id;
            $this->loanFollowing->loan_referral_id = $this->loanReferral->id;
            return $this->loanFollowing->save();
        }
        return true;
    }

    /**
     * @param integer $userId
     * @return bool
     * @throws DataNotFoundException
     */
    private function canUserFollowReferralLink($userId)
    {
        $user = User::findOne($userId);
        if (empty($user)) {
            throw new DataNotFoundException('User not found.');
        }
        if ($user->roleName === User::ROLE_BORROWER) {
            return true;
        }
        return false;
    }

    /**
     * @return LoanReferral
     */
    public function getLoanReferral(): LoanReferral
    {
        return $this->loanReferral;
    }

}
