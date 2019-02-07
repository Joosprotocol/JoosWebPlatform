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
    const PERIOD_DAY = 24 * 60 * 60;

    /** @var number */
    public $amount;
    /** @var integer */
    public $period_days;
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
            [['currency_type', 'period_days'], 'integer'],
            [['amount'], 'number'],
            [['period_days'], 'default', 'value' => 30],
            [['currency_type'], 'in', 'range' => array_keys(Loan::currencyTypeList())],
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
        $this->loan->period = $this->period_days * self::PERIOD_DAY;
        $this->loan->status = Loan::STATUS_STARTED;
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
