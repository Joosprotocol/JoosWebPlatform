<?php

namespace frontend\forms\collateral;


use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\notification\NotificationService;
use common\models\collateral\Collateral;
use common\models\user\User;
use itmaster\core\models\Setting;
use Yii;
use yii\base\Model;
use yii\web\UnauthorizedHttpException;

class CollateralCreateForm extends Model
{

    const PERIOD_DAY = 24 * 60 * 60;
    const DAYS_IN_YEAR = 365;

    const REQUIRED_AMOUNT_MAX = 50000;
    const REQUIRED_AMOUNT_MIN = 10;
    const REQUIRED_AMOUNT_STEP = 10;
    const REQUIRED_AMOUNT_PRECISION = 100;

    /** @var number */
    public $amount;
    /** @var integer */
    public $amountRequired;
    /** @var integer */
    public $currency_type;
    /** @var User  */
    private $user;
    /** @var Collateral  */
    private $collateral;
    /** @var int  */
    private $lvr;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_type'], 'integer'],
            [['amountRequired'], 'integer', 'min' => self::REQUIRED_AMOUNT_MIN, 'max' => self::REQUIRED_AMOUNT_MAX],
            [['amount'], 'number'],
            [['amount'], 'number'],
            [['currency_type'], 'in', 'range' => array_keys(Collateral::currencyPostingTypeList())],
        ];
    }

    /**
     * LoanOfferForm constructor.
     * @throws UnauthorizedHttpException
     * @internal param int $init_type
     */
    public function __construct()
    {
        parent::__construct([]);
        if (Yii::$app->user->isGuest) {
            throw new UnauthorizedHttpException('User must be authorized.');
        }

        $this->lvr = (int) Setting::getValue('collateral_loan_lvr');

        $this->user = User::findOne(Yii::$app->user->id);

        $this->collateral = new Collateral();
        $this->collateral->loadDefaultValues();
        if ($this->user->roleName === User::ROLE_BORROWER) {
            $this->collateral->investor_id = Yii::$app->user->id;
        }
    }

    /**
     * @return bool
     */
    public function save()
    {
        $this->collateral->currency_type = $this->currency_type;
        if ($this->currency_type == CryptoCurrencyTypes::CURRENCY_TYPE_BTC) {
            $this->collateral->amount = (integer) ($this->amount * CryptoCurrencyTypes::SATOSHI_PRICE);
        }
        if ($this->currency_type == CryptoCurrencyTypes::CURRENCY_TYPE_ETH) {
            $this->collateral->amount = (integer) ($this->amount * CryptoCurrencyTypes::GWEI_PRICE);
        }
        $this->collateral->start_amount = $this->collateral->amount;

        $this->collateral->status = Collateral::STATUS_STARTED;
        if ($this->collateral->save()) {
            $this->sendCollateralCreatedNotification();
            return true;
        }
        return false;
    }

    /**
     * @return Collateral
     */
    public function getCollateral() : Collateral
    {
        return $this->collateral;
    }

    /**
     * @return void
     */
    protected function sendCollateralCreatedNotification()
    {
        NotificationService::sendCollateralCreatedNotification($this->collateral);
    }

    /**
     * @return int
     */
    public function getLvr(): int
    {
        return $this->lvr;
    }

}
