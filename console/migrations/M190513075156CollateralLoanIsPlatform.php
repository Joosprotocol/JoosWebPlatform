<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190513075156CollateralLoanIsPlatform
 */
class M190513075156CollateralLoanIsPlatform extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{collateral_loan}}', 'sponsor', $this->boolean()->defaultValue(true)->notNull());
        $this->renameColumn('{{collateral_loan}}', 'sponsor', 'is_platform');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{collateral_loan}}', 'is_platform', $this->integer(1)->notNull());
        $this->renameColumn('{{collateral_loan}}', 'is_platform', 'sponsor');
        return true;
    }

}
