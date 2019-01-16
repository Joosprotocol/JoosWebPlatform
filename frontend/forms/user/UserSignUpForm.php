<?php


namespace frontend\forms\user;


use common\models\loan\ethereum\Web3BlockChainAdapter;
use common\models\user\BlockchainProfile;
use common\models\user\User;
use common\models\user\UserPersonal;
use itmaster\core\mail\Mailer;
use Yii;
use yii\base\Model;

class UserSignUpForm extends Model
{
    /** @var  string */
    public $username;
    /** @var  string */
    public $password;
    /** @var  string */
    public $confirmPassword;
    /** @var  string */
    public $firstName;
    /** @var  string */
    public $lastName;
    /** @var  string */
    public $email;
    /** @var User  */
    public $roleName;
    /** @var User  */
    private $user;
    /** @var \yii\rbac\ManagerInterface  */
    private $auth;
    /** @var UserPersonal */
    private $personal;
    /** @var  string */
    public $facebookUrl;
    /** @var  string */
    public $socialUrl;
    /** @var  string */
    public $mobileNumber;
    /** @var  string */
    public $facebookFriendFirstUrl;
    /** @var  string */
    public $facebookFriendSecondUrl;
    /** @var  string */
    public $facebookFriendThirdUrl;
    /** @var  BlockchainProfile */
    public $blockchainProfile;
    /** @var  string */
    public $address;

    /**
     * @inheritdoc
     */
    public function rules() : array
    {
        return [
            [['roleName'], 'in', 'range' => array_keys(User::accessibleSignUpRoleList())],
            [['username', 'email'], 'required'],
            [['username', 'email', 'firstName', 'lastName'], 'trim'],

            [['username'], 'string', 'min' => 2],
            [
                ['username', 'email'],
                'string',
                'max' => 255
            ],
            [['firstName', 'lastName'], 'string', 'max' => 100],
            [['username'], 'unique', 'targetClass' => User::class],
            [['email'], 'unique', 'targetClass' => User::class],
            [['email'], 'email'],
            [['password', 'confirmPassword'], 'string', 'min' => 6],
            [['confirmPassword'], 'compare', 'compareAttribute' => 'password'],

            /*BORROWER FIELDS*/
            [['facebookUrl', 'socialUrl', 'mobileNumber', 'facebookFriendFirstUrl', 'facebookFriendSecondUrl', 'facebookFriendThirdUrl'],
                'required',
                'when' => function ($model) {
                    return $model->roleName == User::ROLE_BORROWER;
                },
                'whenClient' => 'function (attribute, value) { return false; }',
            ],
            [['facebookUrl', 'socialUrl', 'facebookFriendFirstUrl', 'facebookFriendSecondUrl', 'facebookFriendThirdUrl'], 'string', 'max' => 255],
            [['mobileNumber'], 'string', 'max' => 15],

            /*DIGITAL COLLECTOR FIELDS*/
            [['address'],
                'required',
                'when' => function ($model) {
                    return $model->roleName == User::ROLE_DIGITAL_COLLECTOR;
                },
                'whenClient' => 'function (attribute, value) { return false; }',
            ],
            [['address'], 'validateAddress'],
            [['address'], 'string', 'max' => 255],
        ];
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->auth = \Yii::$app->authManager;

        $this->personal = new UserPersonal();
    }


    /**
     * @return bool
     */
    public function signUp() : bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        $valid = $this->validate();
        $valid = $valid && $this->saveUser();
        if ($this->roleName === User::ROLE_BORROWER) {
            $valid = $valid && $this->saveUserPersonal();
        }
        if ($this->roleName === User::ROLE_DIGITAL_COLLECTOR) {
            $valid = $valid && $this->saveBlockchainProfile();
        }


        if ($valid) {
            $transaction->commit();
            (new Mailer())->sendSignupEmail($this->user);
            return true;
        }
        $transaction->rollBack();
        return false;

    }

    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }

    /**
     * @return bool
     */
    private function saveUser() : bool
    {
        $this->user = new User();
        $this->user->loadDefaultValues();
        $this->user->generateAccessToken();
        $this->user->username = $this->username;
        $this->user->email = $this->email;
        $this->user->password = $this->password;
        $this->user->first_name = $this->firstName;
        $this->user->last_name = $this->lastName;
        $this->user->confirm_password = $this->confirmPassword;
        $this->user->roleName = $this->roleName;
        return $this->user->save();
    }

    /**
     * @return UserPersonal
     */
    public function getPersonal() : UserPersonal
    {
        return $this->personal;
    }

    /**
     * @return bool
     */
    private function saveUserPersonal() : bool
    {
        $this->personal = new UserPersonal();
        $this->personal->loadDefaultValues();
        $this->personal->user_id = $this->user->id;
        $this->personal->facebook_url = $this->facebookUrl;
        $this->personal->social_url = $this->socialUrl;
        $this->personal->mobile_number = $this->mobileNumber;
        $this->personal->facebook_friend_first_url = $this->facebookFriendFirstUrl;
        $this->personal->facebook_friend_second_url = $this->facebookFriendSecondUrl;
        $this->personal->facebook_friend_third_url = $this->facebookFriendThirdUrl;

        return $this->personal->save();
    }

    /**
     * @return bool
     */
    private function saveBlockchainProfile() : bool
    {
        $this->blockchainProfile = new BlockchainProfile();
        $this->blockchainProfile->loadDefaultValues();
        $this->blockchainProfile->user_id = $this->user->id;
        $this->blockchainProfile->address = $this->address;
        return $this->blockchainProfile->save();
    }

    /**
     * Validate address
     *
     * @param $attribute
     */
    public function validateAddress($attribute)
    {
        $web3BlockChainAdapter = new Web3BlockChainAdapter(Yii::$app->ethereumAPI);
        if (!$web3BlockChainAdapter->isAccount($this->$attribute)) {
            $this->addError($attribute, 'The address does not exist in the blockchain.');
        }
    }
}
