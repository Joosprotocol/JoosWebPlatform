<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190507122510AmountRounding
 */
class M190507122510AmountRounding extends Migration
{
    const SECONDS_IN_YEAR = 365 * 24 * 60 * 60;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%loan}}', 'amount', $this->bigInteger()->defaultValue(0));
        $this->alterColumn('{{%payment}}', 'amount', $this->bigInteger()->defaultValue(0));
        $this->alterColumn('{{%fee}}', 'amount', $this->bigInteger()->defaultValue(0));
        $this->alterColumn('{{%collateral}}', 'amount', $this->bigInteger()->defaultValue(0));
        $this->alterColumn('{{%collateral_loan}}', 'amount', $this->bigInteger()->defaultValue(0));

        $this->dropForeignKey('fk_collateral_lender', '{{%collateral}}');
        $this->dropColumn('{{%collateral}}', 'lender_id');

        $this->dropForeignKey('fk_collateral_loan_borrower', '{{%collateral_loan}}');
        $this->dropColumn('{{%collateral_loan}}', 'borrower_id');

        $this->dropColumn('{{%collateral}}', 'period');
        $this->addColumn('{{%collateral_loan}}', 'period', $this->integer()->defaultValue(self::SECONDS_IN_YEAR));

        $this->renameColumn('{{%collateral_loan}}', 'percent_int', 'lvr');
        $this->addColumn('{{%collateral_loan}}', 'fee', $this->integer()->defaultValue(1000));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190507122510AmountRounding cannot be reverted.\n";

        return false;
    }

}
