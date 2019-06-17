<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190607135644CollateralLoanAddCollateralAmount
 */
class M190607135644CollateralLoanAddCollateralAmount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%collateral_loan}}', 'collateral_amount', $this->bigInteger()->defaultValue(0)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%collateral_loan}}', 'collateral_amount');
        return true;
    }
}
