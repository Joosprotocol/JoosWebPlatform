<?php


namespace common\library\loan\contract_structures;


use yii\base\Model;

/**
 * Class LoanInfoObject
 * @package common\library\loan\contract_structures
 */
class LoanInfoObject extends Model
{
    public $amount;
    public $currency_type;
    public $period;
    public $percent;
    public $init_type;
    public $created_at;
}
