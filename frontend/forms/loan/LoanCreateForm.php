<?php

namespace frontend\forms\loan;


use common\models\loan\Loan;
use common\models\user\User;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
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
     * @param int $init_type
     * @throws UnauthorizedHttpException
     */
    public function __construct()
    {
        parent::__construct([]);
        if (Yii::$app->user->isGuest) {
            throw new UnauthorizedHttpException('User must be authorized.');
        }

        $this->checkAvailableUserRole(Yii::$app->user->identity->roleName);

        $this->loan = new Loan();
        $this->loan->loadDefaultValues();
        if (Yii::$app->user->identity->roleName === User::ROLE_LENDER) {
            $this->loan->lender_id = Yii::$app->user->id;
            $this->loan->init_type = Loan::INIT_TYPE_OFFER;
        }

        if (Yii::$app->user->identity->roleName === User::ROLE_BORROWER) {
            $this->loan->borrower_id = Yii::$app->user->id;
            $this->loan->init_type = Loan::INIT_TYPE_REQUEST;
        }
    }

    /**
     * @return bool
     */
    public function save()
    {
        $this->loan->currency_type = $this->currency_type;
        $this->loan->amount = $this->amount;
        $this->loan->period = $this->period_days * self::PERIOD_DAY;
        $this->loan->status = Loan::STATUS_STARTED;
        if ($this->loan->save()) {
            return true;
        }
        return false;
    }

    /**
     * @param string $roleName
     * @throws ForbiddenHttpException
     */
    private function checkAvailableUserRole($roleName)
    {
        if (!in_array($roleName, [User::ROLE_BORROWER, User::ROLE_LENDER])) {
            throw new ForbiddenHttpException($roleName);
        }
    }

    /**
     * @return Loan
     */
    public function getLoan(): Loan
    {
        return $this->loan;
    }

}
