<?php

namespace common\library\loan;

use common\library\crypt\CryptorInterface;
use common\library\ethereum\EthereumAPI;
use common\library\notification\NotificationService;
use common\library\project\Standard;
use common\models\loan\smartcontract\LoanManagerBlockChainAdapter;
use common\models\loan\Loan;
use common\models\user\User;
use Yii;
use yii\base\Exception;

class LoanService
{

    const FIELD_CREATED_AT = 'created_at';

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
                $loanManagerAdapter->initLoan($this->loan->hash_id, $this->loan->amount, $this->loan->currency_type, $this->loan->period, $this->loan->fee * Standard::PERCENT_PRECISION, $this->loan->init_type);
                $loanManagerAdapter->setLoanParticipants($this->loan->hash_id, $this->loan->lender->id, $this->loan->lender->fullName, $this->loan->borrower->id, $this->loan->borrower->fullName, $personalEncoded);
                $transaction->commit();
                $this->createSignNotification();
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
        $loanParticipants = $loanManagerAdapter->getLoanParticipants($this->loan->hash_id);
        if (empty($loanParticipants->{LoanManagerBlockChainAdapter::FIELD_PERSONAL})) {
            return false;
        }

        $personalEncoded = $loanParticipants->{LoanManagerBlockChainAdapter::FIELD_PERSONAL};
        $basicCryptor = Yii::$app->basicCryptor;
        $personalDecoded = $basicCryptor->decode($personalEncoded, $this->loan->secret_key);
        return json_decode($personalDecoded, true);
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
    }

    /**
     * @return bool
     */
    public function isLenderOfLoan() : bool
    {
        return $this->user->id === $this->loan->lender_id;
    }

    /**
     * @return void
     */
    protected function createSignNotification() : void
    {
        NotificationService::sendLoanSignNotification($this->loan, $this->user);
    }

    /**
     * @return void
     */
    protected function createChangeLoanStatusNotification() : void
    {
        NotificationService::sendChangeLoanStatusNotification($this->loan);
    }

    /**
     * @return bool
     * @throws \common\library\exceptions\APICallException
     * @throws \common\library\exceptions\ParseException
     * @throws \yii\web\NotFoundHttpException
     */
    public function updateStatus()
    {
        $collateralLoanManagerAdapter = new LoanManagerBlockChainAdapter($this->ethereumApi);
        $newStatus = (int) $collateralLoanManagerAdapter->getStatus($this->loan->hash_id);
        if ($newStatus === 0) {
            return false;
        }
        if ($newStatus === $this->loan->status) {
            return true;
        }
        $this->loan->status = $newStatus;
        if ($this->loan->save()) {
            $this->createChangeLoanStatusNotification();
            return true;
        }
        return false;
    }

    /**
     * @return bool
     * @throws \common\library\exceptions\APICallException
     * @throws \common\library\exceptions\ParseException
     * @throws \yii\web\NotFoundHttpException
     */
    public function updateSignedAt()
    {
        $loanManagerAdapter = new LoanManagerBlockChainAdapter($this->ethereumApi);
        $loanInfo = (array) $loanManagerAdapter->getLoanInfo($this->loan->hash_id);
        if (empty($loanInfo[self::FIELD_CREATED_AT])) {
            return false;
        }
        $this->loan->signed_at = $loanInfo[self::FIELD_CREATED_AT];
        return $this->loan->save();
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}
