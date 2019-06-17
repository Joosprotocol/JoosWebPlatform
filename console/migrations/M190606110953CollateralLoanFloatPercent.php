<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190606110953CollateralLoanFloatPercent
 */
class M190606110953CollateralLoanFloatPercent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%collateral_loan}}', 'fee', $this->decimal(5, 2)->defaultValue(0));
        $this->alterColumn('{{%collateral_loan}}', 'lvr', $this->decimal(5, 2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190606110953CollateralLoanFloatPercent cannot be reverted.\n";

        return false;
    }
}
