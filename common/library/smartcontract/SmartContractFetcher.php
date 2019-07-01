<?php


namespace common\library\smartcontract;

use common\library\business\OverdueInterface;
use common\library\collateral\CollateralLoanQueryLibrary;
use common\library\collateral\CollateralLoanService;
use common\library\loan\LoanQueryLibrary;
use common\library\loan\LoanService;
use common\models\collateral\CollateralLoan;
use common\models\loan\Loan;
use Yii;

class SmartContractFetcher
{
    const LOG_CATEGORY_BLOCKCHAIN = 'blockchain';
    const LOG_UPDATE_MESSAGE = 'update_message';
    const LOG_UPDATE_RESULT_MESSAGE = 'update_result_message';

    /**
     * @return int
     */
    public function fetchOverdue() : int
    {
        Yii::info($this->getMessages()[self::LOG_UPDATE_MESSAGE], self::LOG_CATEGORY_BLOCKCHAIN);
        $loans = LoanQueryLibrary::getLoansExpectedOverdue();
        $collateralLoans = CollateralLoanQueryLibrary::getCollateralLoansExpectedOverdue();
        $counter = 0;
        foreach ($loans as $loan) {
            $counter += (int) $this->updateOne($loan);
        }

        foreach ($collateralLoans as $collateralLoan) {
            $counter += (int) $this->updateOne($collateralLoan);
        }
        Yii::info($this->getMessages()[self::LOG_UPDATE_RESULT_MESSAGE] . $counter, self::LOG_CATEGORY_BLOCKCHAIN);


        return $counter;
    }

    /**
     * @param  $entity
     * @return bool
     */
    public function updateOne(OverdueInterface $entity) : bool
    {
        Yii::info(json_encode(['entity' => get_class($entity) , 'id' => $entity->id]), self::LOG_CATEGORY_BLOCKCHAIN);
        try {
            if ($entity instanceof Loan) {
                $service = new LoanService($entity);
                $service->updateStatus();
            }
            if ($entity instanceof CollateralLoan) {
                $service = new CollateralLoanService($entity);
                $service->updateStatus();
            }

            Yii::info('Done!', self::LOG_CATEGORY_BLOCKCHAIN);
            return true;
        } catch (\Exception $exception) {
            Yii::error(json_encode($exception), self::LOG_CATEGORY_BLOCKCHAIN);
            return false;
        }

    }

    /**
     * @return array
     */
    protected function getMessages() : array
    {
        return [
            self::LOG_UPDATE_MESSAGE => Yii::t('app', 'Batch loans updating from blockchain.'),
            self::LOG_UPDATE_RESULT_MESSAGE => Yii::t('app', 'Updated:') . ' '
        ];
    }

}
