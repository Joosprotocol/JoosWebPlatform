<?php

namespace common\library\collateral;

use common\config\constant\Blockchain;
use common\library\blockchain\EthereumUsdtCryptoManager;
use common\library\cryptocurrency\CryptoCurrencyRateService;
use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\exceptions\InvalidModelException;
use common\library\notification\NotificationService;
use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoan;
use itmaster\core\models\Setting;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class CollateralLoanPlatformProvider
 * @package common\library\collateral
 */
class CollateralLoanPlatformProvider
{
    const SETTING_COLLATERAL_LOAN_MAX_AMOUNT = 'collateral_loan_max_amount';
    const SETTING_COLLATERAL_LVR = 'collateral_loan_lvr';

    /** @var  CollateralLoan */
    private $collateralLoan;
    /** @var  Collateral */
    private $collateral;
    /** @var  float */
    private $lvrPercent;
    /** @var  int */
    private $allowableAmount;
    /** @var  string */
    private $hubAddress;
    /** @var  string */
    private $hubPrivateKey;
    /** @var  int */
    private $loanAmount;
    /** @var  int */
    private $collateralLoanAmount;


    public function __construct()
    {
        $this->lvrPercent = (float) Setting::getValue(self::SETTING_COLLATERAL_LVR);
        $this->allowableAmount = (int) Setting::getValue(self::SETTING_COLLATERAL_LOAN_MAX_AMOUNT) * CryptoCurrencyTypes::TETHER_MICROCENT_PRICE;
        $this->setAccessParams();
    }

    /**
     * @param Collateral $collateral
     * @return bool
     */
    public function provide(Collateral $collateral)
    {
        $this->collateral = $collateral;

        if (empty($this->collateral->investor->ethereumProfile)) {
            NotificationService::sendCollateralLoanPaymentAddressErrorNotification($this->collateral);
            return false;
        }

        $this->collateralLoan = null;
        $this->calculateCollateralAmount();
        return $this->createAndPay();

    }


    private function calculateCollateralAmount()
    {
        $requiredAmount = $this->getRequiredAmount();

        if ($requiredAmount < $this->allowableAmount) {
            $this->loanAmount = $requiredAmount;
            $this->collateralLoanAmount = $this->collateral->amount;
            $this->collateral->amount = 0;
            $this->collateral->status = Collateral::STATUS_FULLY_LOANED;
        } else {
            $this->loanAmount = $this->allowableAmount;
            $this->collateralLoanAmount = (int) CryptoCurrencyRateService::convertAmount((int) ($this->allowableAmount * 100 / $this->lvrPercent), CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT, $this->collateral->currency_type);
            $this->collateral->amount -= $this->collateralLoanAmount;
            $this->collateral->status = Collateral::STATUS_PARTIALLY_LOANED;
        }
    }

    /**
     * @return bool
     */
    private function createAndPay()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {

            if ($this->loanAmount === 0) {
                throw new \LogicException('Too small loan amount');
            }

            if (!$this->collateral->save()) {
                throw new InvalidModelException($this->collateral);
            }
            $this->collateralLoan = CollateralLoanFactory::createByCollateral(
                $this->collateral,
                CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT,
                $this->loanAmount,
                $this->collateralLoanAmount
            );

            $collateralLoanService = new CollateralLoanService($this->collateralLoan);

            if (!$collateralLoanService->sign()) {
                throw $collateralLoanService->getLastException();
            }
            if (!$collateralLoanService->updateStatus()) {
                throw new InvalidModelException($collateralLoanService->getCollateralLoan());
            }

            $collateralLoanService->updateSignedAt();

            $usdtManager = new EthereumUsdtCryptoManager();
            NotificationService::sendNewCollateralLoanPaymentNotification($this->collateralLoan);
            $usdtManager->sendAmount($this->hubAddress, $this->collateral->investor->ethereumProfile->address, $this->loanAmount, $this->hubPrivateKey);

            $transaction->commit();
            return true;

        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::error($exception->getMessage(), 'collateral');
            return false;
        }
    }

    /**
     * @return float
     */
    private function getRequiredAmount()
    {
        $convertedAmount = CryptoCurrencyRateService::convertAmount($this->collateral->amount, $this->collateral->currency_type, CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT);
        $requiredAmountNotRounded = $convertedAmount * $this->lvrPercent / 100;
        $currencyPrecision = CryptoCurrencyTypes::precisionList()[CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT];
        return (int) floor($requiredAmountNotRounded / $currencyPrecision) * $currencyPrecision  ;
    }

    /**
     * @throws InvalidConfigException
     */
    public function setAccessParams()
    {
        if (empty(Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM_USDT][Blockchain::PARAM_HUB_ADDRESS])) {
            throw new InvalidConfigException(Blockchain::PARAM_HUB_ADDRESS . ' param is not configured.');
        }
        $this->hubAddress = Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM_USDT][Blockchain::PARAM_HUB_ADDRESS];

        if (empty(Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM_USDT][Blockchain::PARAM_HUB_PRIVATE_KEY])) {
            throw new InvalidConfigException(Blockchain::PARAM_HUB_PRIVATE_KEY . ' param is not configured.');
        }
        $this->hubPrivateKey = Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM_USDT][Blockchain::PARAM_HUB_PRIVATE_KEY];
    }

    /**
     * @return CollateralLoan
     */
    public function getCollateralLoan(): CollateralLoan
    {
        return $this->collateralLoan;
    }

    /**
     * @return float
     */
    public function getLvrPercent(): float
    {
        return $this->lvrPercent;
    }

    /**
     * @param float $lvrPercent
     */
    public function setLvrPercent(float $lvrPercent)
    {
        $this->lvrPercent = $lvrPercent;
    }

    /**
     * @return int
     */
    public function getAllowableAmount(): int
    {
        return $this->allowableAmount;
    }

    /**
     * @param int $allowableAmount
     */
    public function setAllowableAmount(int $allowableAmount)
    {
        $this->allowableAmount = $allowableAmount;
    }

}
