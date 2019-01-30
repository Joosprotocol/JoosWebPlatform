<?php


namespace common\library\loan;


use common\library\loan\contract_structures\LoanInfoObject;
use common\models\loan\ethereum\LoanManagerBlockChainAdapter;
use common\models\loan\Loan;
use Yii;

class LoanBlockchainExtractor
{
    const LOG_CATEGORY_BLOCKCHAIN = 'blockchain';
    const LOG_UPDATE_MESSAGE = 'update_message';
    const LOG_UPDATE_RESULT_MESSAGE = 'update_result_message';

    /** @var LoanManagerBlockChainAdapter  */
    private $loanManagerAdapter;

    public function __construct()
    {
        $this->loanManagerAdapter = new LoanManagerBlockChainAdapter(Yii::$app->ethereumAPI);
    }

    /**
     * @return int
     */
    public function update() : int
    {
        Yii::info($this->getMessages()[self::LOG_UPDATE_MESSAGE], self::LOG_CATEGORY_BLOCKCHAIN);
        $loans = $this->getSignedRoles();
        $counter = 0;
        foreach ($loans as $loan) {
            $counter += (int) $this->updateOne($loan);
        }
        Yii::info($this->getMessages()[self::LOG_UPDATE_RESULT_MESSAGE] . $counter, self::LOG_CATEGORY_BLOCKCHAIN);

        return $counter;
    }

    /**
     * @return Loan[]
     */
    private function getSignedRoles() : array
    {
        return Loan::find()
            ->where(['status' => Loan::STATUS_SIGNED])
            ->all();
    }

    /**
     * @param Loan $loan
     * @return bool
     */
    public function updateOne(Loan $loan) : bool
    {
        Yii::info(json_encode(['loan_id' => $loan->id]), self::LOG_CATEGORY_BLOCKCHAIN);
        try {
            $loanStatus = $this->loanManagerAdapter->getStatus($loan->id);
            $loanInfo = (array) $this->loanManagerAdapter->getLoanInfo($loan->id);
        } catch (\Exception $exception) {
            Yii::error(json_encode($exception), self::LOG_CATEGORY_BLOCKCHAIN);
            return false;
        }
        Yii::info(json_encode(['status' => $loanStatus, 'info' => $loanInfo]), self::LOG_CATEGORY_BLOCKCHAIN);
        $loanInfoObject = new LoanInfoObject();
        $loanInfoObject->setAttributes($loanInfo, false);

        return $this->updateModel($loan, $loanStatus, $loanInfoObject);
    }

    /**
     * @return array
     */
    protected function getMessages() : array
    {
        return [
            self::LOG_UPDATE_MESSAGE => Yii::t('app', 'Batch loans updating from blockchain.'),
            self::LOG_UPDATE_RESULT_MESSAGE => Yii::t('app', 'Updated loans:') . ' '
        ];
    }

    /**
     * @param int $loanStatus
     * @return bool
     */
    private function isAvailableStatus($loanStatus) : bool
    {
        if (in_array($loanStatus, $this->getAvailableLoanStatuses())) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    private function getAvailableLoanStatuses() : array
    {
        return [
            Loan::STATUS_SIGNED,
            Loan::STATUS_PAID,
            Loan::STATUS_PAUSED,
            Loan::STATUS_OVERDUE
        ];
    }

    /**
     * @param Loan $loan
     * @param int $status
     * @param LoanInfoObject $loanInfoObject
     * @return bool
     */
    private function updateModel(Loan $loan, int $status, LoanInfoObject $loanInfoObject) : bool
    {
        if (!$this->isAvailableStatus($status)) {
            return false;
        }

        $loan->status = $status;
        $loan->signed_at = $loanInfoObject->created_at;
        return $loan->save();
    }
}
