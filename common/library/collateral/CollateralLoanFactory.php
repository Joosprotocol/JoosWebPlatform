<?php


namespace common\library\collateral;


use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoan;
use itmaster\core\models\Setting;

class CollateralLoanFactory
{
    const SETTING_LVR = 'collateral_loan_lvr';
    const SETTING_FEE = 'collateral_loan_fee';
    const SETTING_MAX_LOAN = 'collateral_loan_max_amount';
    const DEFAULT_PERIOD = 60 * 60 * 24 * 356;

    /**
     * @param Collateral $collateral
     * @param int $currencyType
     * @param int $amount
     * @param int $collateralAmount
     * @param float|null $lvr
     * @param float|null $fee
     * @return CollateralLoan
     */
    public static function createByCollateral(Collateral $collateral, int $currencyType, int $amount, int $collateralAmount, float $lvr = null, float $fee = null) : CollateralLoan
    {
        $collateralLoan = new CollateralLoan();
        $collateralLoan->lvr = $lvr ?? (float)  Setting::getValue(self::SETTING_LVR);
        $collateralLoan->fee = $fee ?? (float) Setting::getValue(self::SETTING_FEE);
        $collateralLoan->is_platform = true;
        $collateralLoan->status = CollateralLoan::STATUS_SIGNED;
        $collateralLoan->period = self::DEFAULT_PERIOD;
        $collateralLoan->currency_type = $currencyType;
        $collateralLoan->amount = $amount;
        $collateralLoan->collateral_amount = $collateralAmount;
        $collateralLoan->link('collateral', $collateral);
        return $collateralLoan;
    }
}
