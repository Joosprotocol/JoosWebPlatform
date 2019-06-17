<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190612143307CollateralLoanWithdranAddress
 */
class M190612143307CollateralLoanWithdranAddress extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%collateral_loan}}', 'withdrawn_profile_id', $this->integer());
        $this->addForeignKey('fk_collateral_loan_withdrawn_profile', '{{%collateral_loan}}', 'withdrawn_profile_id', '{{%blockchain_profile}}', 'id', null, 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_collateral_loan_withdrawn_profile', '{{%collateral_loan}}');
        $this->dropColumn('{{%collateral_loan}}', 'withdrawn_profile_id');

        return true;
    }

}
