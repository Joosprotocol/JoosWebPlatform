<?php

namespace common\library\collateral;

use common\library\ethereum\EthereumAPI;
use common\library\notification\NotificationService;
use common\library\project\Standard;
use common\models\collateral\CollateralLoan;
use common\models\collateral\smartcontract\CollateralLoanManagerBlockChainAdapter;
use Yii;
use \Exception;

class CollateralLoanService
{
    /** @var CollateralLoan  */
    private $collateralLoan;
    /** @var EthereumAPI */
    private $ethereumApi;
    /** @var  Exception */
    private $lastException;


    public function __construct(CollateralLoan $collateralLoan)
    {
        $this->ethereumApi = Yii::$app->ethereumAPI;
        $this->collateralLoan = $collateralLoan;
    }

    /**
     * Create record in blockchain with two signers and
     * save the loan
     * @return bool
     */
    public function sign() : bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        $valid = $this->collateralLoan->save();
        $collateralLoanManagerAdapter = new CollateralLoanManagerBlockChainAdapter($this->ethereumApi);

        if ($valid) {
            try {
                $collateralLoanManagerAdapter->initLoan(
                    $this->collateralLoan->hash_id,
                    $this->collateralLoan->collateral->hash_id,
                    $this->collateralLoan->amount,
                    $this->collateralLoan->currency_type,
                    $this->collateralLoan->collateral_amount,
                    $this->collateralLoan->collateral->currency_type,
                    $this->collateralLoan->period,
                    $this->collateralLoan->fee * Standard::PERCENT_PRECISION
                );

                $collateralLoanManagerAdapter->setLoanParticipants(
                    $this->collateralLoan->hash_id,
                    $this->collateralLoan->is_platform,
                    $this->collateralLoan->collateral->investor->id,
                    $this->collateralLoan->collateral->investor->fullName,
                    $this->collateralLoan->lender->id ?? 0,
                    $this->collateralLoan->lender->fullName ?? ''
                );

                $transaction->commit();
                $this->createSignNotification();
                return true;
            } catch (\Exception $exception) {
                $this->lastException = $exception;
                $transaction->rollBack();
            }
        }
        return false;
    }


    /**
     * @return CollateralLoan
     */
    public function getCollateralLoan() : CollateralLoan
    {
        return $this->collateralLoan;
    }

    /**
     * @return void
     */
    protected function createSignNotification() : void
    {
        NotificationService::sendCollateralLoanSignNotification($this->collateralLoan);
    }

    /**
     * @return void
     */
    protected function createChangeLoanStatusNotification() : void
    {
        NotificationService::sendChangeCollateralLoanStatusNotification($this->collateralLoan);
    }

    /**
     * @return bool
     */
    public function updateStatus()
    {
        $collateralLoanManagerAdapter = new CollateralLoanManagerBlockChainAdapter($this->ethereumApi);
        $this->collateralLoan->status = $collateralLoanManagerAdapter->getStatus($this->collateralLoan->hash_id);
        if ($this->collateralLoan->save()) {
            $this->createChangeLoanStatusNotification();
            return true;
        }
        return false;
    }

    /**
     * @return Exception
     */
    public function getLastException(): Exception
    {
        return $this->lastException;
    }

}
