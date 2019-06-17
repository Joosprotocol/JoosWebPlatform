<?php


namespace console\controllers;


use common\library\collateral\CollateralLoanPlatformProvider;
use common\library\collateral\CollateralQueryLibrary;
use yii\console\Controller;
use yii\helpers\Console;


class CollateralController extends Controller
{

    public function actionMakeLoansFromPlatform()
    {
        $collaterals = CollateralQueryLibrary::getCollateralsWithoutLoans();
        $collateralLoanPlatformProvider = new CollateralLoanPlatformProvider();


        foreach ($collaterals as $collateral) {
            if ($collateralLoanPlatformProvider->provide($collateral)) {
                $this->stdout('Collateral Loan created. | ID: ' . $collateralLoanPlatformProvider->getCollateralLoan()->id . ' | Amount: ' . $collateralLoanPlatformProvider->getCollateralLoan()->getFormattedAmountWithCurrency() ." |\n"  .  PHP_EOL, Console::FG_YELLOW);
            }
        };
        return true;
    }
}
