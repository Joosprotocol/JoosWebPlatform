<?php

namespace common\library\utilitytoken\fee;

use common\library\ethereum\EthereumAPI;
use common\library\exceptions\APICallException;
use common\library\exceptions\ParseException;
use common\library\loan\LoanQueryLibrary;
use common\models\loan\ethereum\JoosUtilityTokenBlockChainAdapter;
use common\models\loan\Loan;
use common\models\user\User;
use common\models\utilitytoken\Fee;
use itmaster\core\exceptions\DataNotFoundException;
use Yii;
use yii\web\NotFoundHttpException;

class DigitalCollectorFeeService
{

    const FEE_MANUAL_PERCENT_CONFIG_NAME = 'loan.feeManualPercent';
    const FEE_JOOS_PERCENT_CONFIG_NAME = 'loan.feeJoosPercent';
    const FEE_PERCENT_DEFAULT = 5;

    /** @var User */
    private $user;
    /** @var Loan */
    private $loan;
    /** @var Fee */
    private $fee;
    /** @var EthereumAPI */
    private $ethereumApi;

    /**
     * DigitalCollectorFeeService constructor.
     * @param Loan $loan
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
        $this->ethereumApi = Yii::$app->ethereumAPI;
    }

    /**
     * @return bool
     */
    public function withdrawBatch() : bool
    {
        $successfulDigitalCollectors = (array) $this->getSuccessfulDigitalCollectorsByLoan();
        $successfulCounter = 0;
        foreach ($successfulDigitalCollectors as $successfulDigitalCollector) {
            $result =  $this->withdrawForOne($successfulDigitalCollector);
            if ($result === true) {
                $successfulCounter++;
            }
        }
        return $successfulCounter;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function withdrawForOne(User $user) : bool
    {
        $this->user = $user;
        if (!$this->canUserReceiveTokens()) {
            return false;
        }
        if ($this->isFeeExists()) {
            return true;
        }
        
        $this->fee = new Fee();
        $this->fee->user_id = $this->user->id;
        $this->fee->loan_id = $this->loan->id;
        $this->fee->currency_type = $this->loan->currency_type;
        $this->fee->status = Fee::STATUS_PAID;
        $this->fee->amount = $this->calculateAmount();

        try {
            $this->mintUtilityTokens();
        } catch (\Exception $exception) {
            $this->fee->status = Fee::STATUS_PENDING;
        }

        return $this->fee->save();
    }

    /**
     * @return bool
     */
    private function canUserReceiveTokens() : bool
    {
        return in_array($this->user->roleName, $this->accessibleRoles());
    }

    /**
     * @return array
     */
    private function accessibleRoles() : array
    {
        return [
            User::ROLE_DIGITAL_COLLECTOR
        ];
    }

    /**
     * @return int
     */
    protected function calculateAmount() : int
    {
        if (!empty(Yii::$app->params[$this->getFeeConfigNameByLoanCurrencyType()])) {
            return Yii::$app->params[$this->getFeeConfigNameByLoanCurrencyType()];
        }
        return self::FEE_PERCENT_DEFAULT;
    }

    /**
     * @return array
     */
    private function getFeeConfigNamesAssociation() : array
    {
        return [
            Loan::CURRENCY_TYPE_MANUAL => self::FEE_MANUAL_PERCENT_CONFIG_NAME,
            Loan::CURRENCY_TYPE_JOOS => self::FEE_JOOS_PERCENT_CONFIG_NAME
        ];
    }

    /**
     * @return string
     */
    private function getFeeConfigNameByLoanCurrencyType() : string
    {
        if (!array_key_exists($this->loan->currency_type, $this->getFeeConfigNamesAssociation())) {
            throw new \InvalidArgumentException('Incorrect currency type of loan.');
        }
        return $this->getFeeConfigNamesAssociation()[$this->loan->currency_type];
    }

    /**
     * @return object|string
     * @throws DataNotFoundException
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    private function mintUtilityTokens()
    {
        if (empty($this->user->blockchainProfile)) {
            throw new DataNotFoundException('Blockchain profile not found.');
        }

        $loanManagerAdapter = new JoosUtilityTokenBlockChainAdapter($this->ethereumApi);
        return $loanManagerAdapter->mint($this->user->blockchainProfile->address, $this->fee->amount);
    }

    /**
     * @return bool
     */
    private function isFeeExists() : bool
    {
        return (bool) Fee::findByUserIdAndLoanId($this->user->id, $this->loan->id);
    }

    /**
     * @return array|User[]
     */
    protected function getSuccessfulDigitalCollectorsByLoan() : array
    {
        return LoanQueryLibrary::getSuccessfulDigitalCollectorsByLoan($this->loan->id);
    }

}
