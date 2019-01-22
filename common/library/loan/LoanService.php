<?php

namespace common\library\loan;

use common\library\crypt\CryptorInterface;
use common\library\ethereum\EthereumAPI;
use common\models\loan\ethereum\LoanManagerBlockChainAdapter;
use common\models\loan\Loan;
use common\models\user\User;
use Yii;
use yii\base\Exception;

class LoanService
{

    const COMMA_MULTIPLICATOR = 10000;

    /** @var Loan  */
    private $loan;
    /** @var EthereumAPI */
    private $ethereumApi;
    /** @var User */
    private $user;

    public function __construct(Loan $loan)
    {
        $this->ethereumApi = Yii::$app->ethereumAPI;
        $this->loan = $loan;
    }

    /**
     * Create record in blockchain with two signers and
     * save the loan
     * @param User $user
     * @return bool
     */
    public function sign(User $user) : bool
    {
        $this->user = $user;
        $this->loadSignerData();
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
     * @param User $lender
     * @param integer $status
     * @return bool
     */
    public function setStatus(User $lender, int $status) : bool
    {
        $this->loan->status = $status;
        $this->user = $lender;
        $transaction = Yii::$app->db->beginTransaction();
        if ($this->isLenderOfLoan()) {
            throw new \LogicException('Only lender owner can change status.');
        }

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
        return !empty($this->loan->borrower->personalActive);
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

    /**
     * @return void
     */
    private function loadSignerData() : void
    {
        if ($this->loan->init_type === Loan::INIT_TYPE_OFFER) {
            if ($this->user->roleName !== User::ROLE_BORROWER) {
                throw new \LogicException('Only borrower can sign offer.');
            }
            $this->loan->borrower_id = $this->user->id;
        }

        if ($this->loan->init_type === Loan::INIT_TYPE_REQUEST) {
            if ($this->user->roleName !== User::ROLE_LENDER) {
                throw new \LogicException('Only lender can sign request.');
            }
            $this->loan->lender_id = $this->user->id;
        }

        if ($this->loan->lender_id === null || $this->loan->borrower_id  === null) {
            throw new \LogicException('Invalid data for a signed loan.');
        }
        $this->loan->status = Loan::STATUS_SIGNED;
    }

    /**
     * @return bool
     */
    private function isLenderOfLoan() : bool
    {
        return $this->user->id === $this->loan->lender_id;
    }

}
