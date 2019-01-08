<?php

namespace common\library\loan;

use common\library\crypt\CryptorInterface;
use common\models\loan\ethereum\LoanManagerBlockChainAdapter;
use common\models\loan\Loan;
use Yii;
use yii\base\Exception;

class LoanService
{

    const COMMA_MULTIPLICATOR = 10000;

    /** @var Loan  */
    private $loan;
    private $ethereumApi;

    public function __construct(Loan $loan)
    {
        $this->ethereumApi = Yii::$app->ethereumAPI;
        $this->loan = $loan;
    }

    /**
     * Create record in blockchain with two signers and
     * save the loan
     * @return bool
     */
    public function sign(): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        $valid = $this->loan->save();
        $loanManagerAdapter = new LoanManagerBlockChainAdapter($this->ethereumApi);

        if ($valid) {
            try {
                if (!$this->isPersonalDataExists()) {
                    throw new Exception('No personal information is available.');
                }
                $personalEncoded = $this->getCryptedPersonalInfo();
                $loanManagerAdapter->initLoan($this->loan->id, $this->loan->amount * self::COMMA_MULTIPLICATOR, $this->loan->currency_type, $this->loan->period, 0, $this->loan->init_type);
                $loanManagerAdapter->setLoanParticipants($this->loan->id, $this->loan->lender->id, $this->loan->lender->fullName, $this->loan->borrower->id, $this->loan->borrower->fullName, $personalEncoded);
                $transaction->commit();
                return true;
            } catch (\Exception $exception) {
                $transaction->rollBack();
            }
        }
        return false;
    }

    /**
     * @return object|false
     */
    public function getBlockchainPersonal()
    {
        $loanManagerAdapter = new LoanManagerBlockChainAdapter($this->ethereumApi);
        $loanParticipants = $loanManagerAdapter->getLoanParticipants($this->loan->id);
        if (empty($loanParticipants->{LoanManagerBlockChainAdapter::FIELD_PERSONAL})) {
            return false;
        }

        $personalEncoded = $loanParticipants->{LoanManagerBlockChainAdapter::FIELD_PERSONAL};
        $basicCryptor = Yii::$app->basicCryptor;
        $personalDecoded = $basicCryptor->decode($personalEncoded, $this->loan->secret_key);
        return json_decode($personalDecoded, true);
    }

    /**
     * @return bool
     */
    public function setStatus() : bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        $valid = $this->loan->save();

        $ethereumApi = Yii::$app->ethereumAPI;
        $loanManagerAdapter = new LoanManagerBlockChainAdapter($ethereumApi);

        if ($valid) {
            try {
                $loanManagerAdapter->setStatus($this->loan->id, $this->loan->status);
                $transaction->commit();
                return true;
            } catch (\Exception $exception) {
                $transaction->rollBack();
            }
        }
        return false;
    }

    /**
     * @return Loan
     */
    public function getLoan() : Loan
    {
        return $this->loan;
    }

    /**
     * @return bool
     */
    private function isPersonalDataExists() : bool
    {
        return !empty($this->loan->borrower->personal);
    }

    /**
     * @return string
     */
    private function getCryptedPersonalInfo() : string
    {
        /** @var CryptorInterface $basicCryptor */
        $basicCryptor = Yii::$app->basicCryptor;
        $personal = $this->loan->borrower->personalArray;
        $personalJson = json_encode($personal);
        return $basicCryptor->encode($personalJson, $this->loan->secret_key);
    }

}
