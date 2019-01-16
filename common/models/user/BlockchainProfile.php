<?php

namespace common\models\user;

use common\models\loan\ethereum\Web3BlockChainAdapter;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%blockchain_profile}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $address
 *
 * @property User $user
 */
class BlockchainProfile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blockchain_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['address'], 'required'],
            [['address'], 'validateAddress'],
            [['address'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'address' => Yii::t('app', 'Address'),
        ];
    }

    /**
     *
     * Validator check existing address as account in blockchain.
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
