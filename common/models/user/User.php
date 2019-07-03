<?php

namespace common\models\user;

use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\models\loan\Loan;
use common\models\notification\Notification;
use itmaster\core\behaviors\TimestampBehavior;
use itmaster\core\models\User as CoreUser;
use itmaster\storage\behaviors\StorageUploadBehavior;
use Yii;


/**
 * User model class for the table "{{%user}}".
 *
 * @property Loan[] $loans
 * @property Loan[] $notifications
 * @property UserPersonal $personal
 * @property UserPersonal $personalActive
 * @property BlockchainProfile[] $blockchainProfiles
 * @property BlockchainProfile $ethereumProfile
 * @property BlockchainProfile $bitcoinProfile
 * @property string $avatarUrl
 * @property array $personalArray
 * @property string $fullName
 */

class User extends CoreUser
{
    const ROLE_LENDER = 'lender';
    const ROLE_BORROWER = 'borrower';
    const ROLE_DIGITAL_COLLECTOR = 'digital-collector';

    /**
     * Method for defining behaviors
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => StorageUploadBehavior::class,
                'rules' => [
                    [['avatar'], 'extensions' => 'png, jpg, jpeg, gif', 'skipOnEmpty' => true, 'maxSize' => 2048 * 2048],
                ],
            ],
        ];
    }

    /**
     * Returns key-value array of accessible rules for sign up
     *
     * @return array
     */
    public static function accessibleSignUpRoleList()
    {
        return [
            self::ROLE_LENDER => Yii::t('app', 'Lender'),
            self::ROLE_BORROWER => Yii::t('app', 'Borrower'),
            self::ROLE_DIGITAL_COLLECTOR => Yii::t('app', 'Digital Collector'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoans()
    {
        return $this->hasMany(Loan::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonal()
    {
        return $this->hasOne(UserPersonal::class, ['user_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonalActive()
    {
        return $this->hasOne(UserPersonal::class, ['user_id' => 'id'])
            ->where(['active' => UserPersonal::ACTIVE_YES])
            ->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlockchainProfiles()
    {
        return $this->hasMany(BlockchainProfile::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEthereumProfile()
    {
        return $this->hasOne(BlockchainProfile::class, ['user_id' => 'id'])
            ->where(['network' => CryptoCurrencyTypes::NETWORK_TYPE_ETHEREUM]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBitcoinProfile()
    {
        return $this->hasOne(BlockchainProfile::class, ['user_id' => 'id'])
            ->where(['network' => CryptoCurrencyTypes::NETWORK_TYPE_BITCOIN]);
    }


    /**
     * @return array
     */
    public function getPersonalArray()
    {
        return [
            'avatarUrl' => $this->avatarUrl,
            'email' => $this->email,
            'issuedIdUrl' => $this->personal->issuedIdUrl,
            'facebook_url' => $this->personal->facebook_url,
            'social_url' => $this->personal->social_url,
            'mobile_number' => $this->personal->mobile_number,
            'facebook_friend_first_url' => $this->personal->facebook_friend_first_url,
            'facebook_friend_second_url' => $this->personal->facebook_friend_second_url,
            'facebook_friend_third_url' => $this->personal->facebook_friend_third_url,
        ];
    }
}
