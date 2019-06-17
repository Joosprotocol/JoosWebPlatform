<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190513080946CollateralSettings
 */
class M190513080946CollateralSettings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%setting}}', ['type', 'value_type', 'title', 'key', 'value'], [
            [0, 0, 'Collateral Loan LVR (%)', 'collateral_loan_lvr', '10.00'],
            [0, 0, 'Collateral Loan Fee (%)', 'collateral_loan_fee', '10.00'],
            [0, 3, 'Max Collateral Loan Amount (USD)', 'collateral_loan_max_amount', 20000],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190513080946CollateralSettings cannot be reverted.\n";

        return false;
    }
}
