<?php

namespace frontend\forms\loan;


use common\library\notification\NotificationService;
use common\models\loan\Loan;
use common\models\user\User;
use LogicException;
use Yii;
use yii\base\Model;
use yii\web\UnauthorizedHttpException;

class LoanCreateForm extends Model
{
    const PERIOD_DAY_IN_SEC = 24 * 60 * 60;
    const PERIOD_DAYS_IN_YEAR = 365;
    const PERIOD_MONTHS_IN_YEAR = 12;

    const PERIOD_TYPE_WEEK = 1;
    const PERIOD_TYPE_MONTH = 2;
    const PERIOD_TYPE_TWO_MONTH = 3;
    const PERIOD_TYPE_THREE_MONTH = 4;
    const PERIOD_TYPE_SIX_MONTH = 5;
    const PERIOD_TYPE_YEAR = 6;

    /** @var float */
    public $amount;
    /** @var number */
    public $fee;
    /** @var integer */
    public $period;
    /** @var integer */
    public $currency_type;
    /** @var Loan */
    private $loan;
    /** @var User  */
    private $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_type', 'period'], 'integer'],
            [['amount'], 'number'],
            [['fee'], 'number', 'max' => 100, 'min' => 0],
            [['amount', 'period', 'currency_type', 'fee'], 'required'],
            [['period'], 'in', 'range' => array_keys(self::periodList())],
            [['currency_type'], 'in', 'range' => array_keys(Loan::currencyTypeList())],
        ];
    }

    /**
     * @return array
     */
    public static function periodList() : array
    {
        return [
            self::PERIOD_TYPE_WEEK => Yii::t('app', 'Week'),
            self::PERIOD_TYPE_MONTH => Yii::t('app', 'Month'),
            self::PERIOD_TYPE_TWO_MONTH => Yii::t('app', 'Two month'),
            self::PERIOD_TYPE_THREE_MONTH => Yii::t('app', 'Three month'),
            self::PERIOD_TYPE_SIX_MONTH => Yii::t('app', 'Six month'),
            self::PERIOD_TYPE_YEAR => Yii::t('app', 'Year'),
        ];
    }

    /**
     * @return array
     */
    public static function periodValues() : array
    {
        return [
            //self::PERIOD_TYPE_WEEK => self::PERIOD_DAY_IN_SEC * 7,
            self::PERIOD_TYPE_WEEK => 100,
            self::PERIOD_TYPE_MONTH => self::PERIOD_DAY_IN_SEC * self::PERIOD_DAYS_IN_YEAR * 1 / self::PERIOD_MONTHS_IN_YEAR,
            self::PERIOD_TYPE_TWO_MONTH => self::PERIOD_DAY_IN_SEC * self::PERIOD_DAYS_IN_YEAR * 2 / self::PERIOD_MONTHS_IN_YEAR,
            self::PERIOD_TYPE_THREE_MONTH => self::PERIOD_DAY_IN_SEC * self::PERIOD_DAYS_IN_YEAR * 3 / self::PERIOD_MONTHS_IN_YEAR,
            self::PERIOD_TYPE_SIX_MONTH => self::PERIOD_DAY_IN_SEC * self::PERIOD_DAYS_IN_YEAR / 2,
            self::PERIOD_TYPE_YEAR => self::PERIOD_DAY_IN_SEC * self::PERIOD_DAYS_IN_YEAR,
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

        $this->user = User::findOne(Yii::$app->user->id);
        $this->loan = new Loan();
        $this->loan->loadDefaultValues();
        if ($this->user->roleName === User::ROLE_LENDER) {
            $this->loan->lender_id = Yii::$app->user->id;
            $this->loan->init_type = Loan::INIT_TYPE_OFFER;
        }

        if ($this->user->roleName === User::ROLE_BORROWER) {
            $this->loan->borrower_id = Yii::$app->user->id;
            $this->loan->init_type = Loan::INIT_TYPE_REQUEST;
        }
    }

    /**
     * @return bool
     */
    public function save()
    {
        try {
            $this->checkCanCreateLoan($this->user);
        } catch (LogicException $exception) {
            Yii::$app->getSession()->setFlash('error', $exception->getMessage());
            return false;
        }
        $this->loan->currency_type = $this->currency_type;
        $this->loan->amount = $this->amount;
        $this->loan->period = self::periodValues()[$this->period];
        $this->loan->status = Loan::STATUS_STARTED;
        $this->loan->fee = $this->fee;
        if ($this->loan->save()) {
            $this->sendLoanCreatedNotification();
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @throws LogicException
     */
    private function checkCanCreateLoan($user)
    {
        if (!in_array($user->roleName, [User::ROLE_BORROWER, User::ROLE_LENDER])) {
            throw new LogicException($user->roleName . 'can\'t create loan.');
        }

        if ($user->roleName === User::ROLE_BORROWER && empty($user->personalActive)) {
            throw new LogicException('Personal information not found.');
        }
    }

    /**
     * @return Loan
     */
    public function getLoan() : Loan
    {
        return $this->loan;
    }

    protected function sendLoanCreatedNotification()
    {
        NotificationService::sendLoanCreatedNotification($this->loan);
    }

}
