<?php

namespace common\library\paymentaddress;


use common\models\blockchain\PaymentAddress;

/**
 * Class PaymentAddressQueryLibrary
 * @package common\library\paymentaddress
 */
class PaymentAddressQueryLibrary
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getWithFunds()
    {
        return PaymentAddress::find()
            ->where(['state' => PaymentAddress::STATE_WITH_FUNDS])
            ->all();
    }
}
