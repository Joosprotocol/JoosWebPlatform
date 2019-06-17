<?php

namespace common\behaviors;


use common\library\cryptocurrency\CryptoCurrencyTypes;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 *
 * @property-read ActiveRecord $owner
 *
 * Class AmountBehavior
 * @package common\behaviors
 */
class AmountBehavior extends Behavior
{
    /**
     * @return float|int
     */
    public function getFormattedAmount()
    {
        return $this->owner->amount / CryptoCurrencyTypes::precisionList()[$this->owner->currency_type];
    }

    /**
     * @return array
     */
    public function getCurrencyName()
    {
        return CryptoCurrencyTypes::currencyTypeList()[$this->owner->currency_type];
    }

    /**
     * @return string
     */
    public function getFormattedAmountWithCurrency()
    {
        return (string) $this->owner->getFormattedAmount() . ' ' . $this->owner->currencyName;
    }
}
