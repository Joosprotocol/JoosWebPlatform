<?php


namespace frontend\forms\user;


use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\exceptions\InvalidModelException;
use common\models\user\BlockchainProfile;
use common\models\user\User;
use yii\base\Model;

/**
 * Class BlockchainAddressForm
 * @package frontend\forms\user
 */
class BlockchainAddressForm extends Model
{

    /** @var  string */
    public $bitcoinAddress;
    /** @var  string */
    public $ethereumAddress;
    /**
     * @var User
     */
    private $user;

    /**
     * @inheritdoc
     */
    public function rules() : array
    {
        return [
            [['bitcoinAddress', 'ethereumAddress'], 'trim'],
            [
                ['bitcoinAddress', 'ethereumAddress'],
                'string',
                'max' => 255
            ],
        ];
    }

    public function __construct(User $user)
    {
        parent::__construct([]);
        $this->user = $user;
        $this->ethereumAddress = $user->ethereumProfile->address ?? null;
        $this->bitcoinAddress = $user->bitcoinProfile->address ?? null;
    }

    /**
     * @throws InvalidModelException
     */
    public function save()
    {
        if (!empty($this->bitcoinAddress)) {
            if (!empty($this->user->bitcoinProfile)) {
                $bitcoinProfile = $this->user->bitcoinProfile;
            } else {
                $bitcoinProfile = new BlockchainProfile();
                $bitcoinProfile->user_id = $this->user->id;
                $bitcoinProfile->network = CryptoCurrencyTypes::NETWORK_TYPE_BITCOIN;
            }
            $bitcoinProfile->address = $this->bitcoinAddress;
            if (!$bitcoinProfile->save()) {
                throw new InvalidModelException($bitcoinProfile);
            }

        } elseif (!empty($this->user->bitcoinProfile)) {
                $this->user->bitcoinProfile->delete();
        }

        if (!empty($this->ethereumAddress)) {
            if (!empty($this->user->ethereumProfile)) {
                $ethereumProfile = $this->user->ethereumProfile;
            } else {
                $ethereumProfile = new BlockchainProfile();
                $ethereumProfile->user_id = $this->user->id;
                $ethereumProfile->network = CryptoCurrencyTypes::NETWORK_TYPE_ETHEREUM;
            }
            $ethereumProfile->address = $this->ethereumAddress;
            if (!$ethereumProfile->save()) {
                throw new InvalidModelException($ethereumProfile);
            }

        } elseif (!empty($this->user->ethereumProfile)) {
            $this->user->ethereumProfile->delete();
        }

    }

}
