<?php


namespace common\library\loan;

use common\models\loan\smartcontract\LoanManagerBlockChainAdapter;
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
        $loans = $this->getLoansExpectedOverdue();
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
    private function getLoansExpectedOverdue() : array
    {
        return Loan::find()
            ->where(['status' => [Loan::STATUS_SIGNED, Loan::STATUS_PARTIALLY_PAID]])
            ->andWhere('period < UNIX_TIMESTAMP() - signed_at')
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
            $loanService = new LoanService($loan);
            $loanService->updateStatus();
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
            self::LOG_UPDATE_RESULT_MESSAGE => Yii::t('app', 'Updated loans:') . ' '
        ];
    }

}
