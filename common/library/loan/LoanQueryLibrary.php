<?php


namespace common\library\loan;

use common\models\loan\LoanFollowing;
use common\models\loan\LoanReferral;
use common\models\user\User;

class LoanQueryLibrary
{

    /**
     * @param integer $loanId
     * @return User[]
     */
    public static function getDigitalCollectorsByLoan(int $loanId, bool $onlySuccessful = false) : array
    {
        $query = User::find()
            ->innerJoin(LoanReferral::tableName(), LoanReferral::tableName() . '.digital_collector_id = ' . User::tableName() . '.id')
            ->where([LoanReferral::tableName() . '.loan_id' => $loanId]);

        if ($onlySuccessful === true) {
            $query->innerJoin(LoanFollowing::tableName(), LoanFollowing::tableName() . '.loan_referral_id = ' . LoanReferral::tableName() . '.id');
        }

        return $query->all();
    }
}
