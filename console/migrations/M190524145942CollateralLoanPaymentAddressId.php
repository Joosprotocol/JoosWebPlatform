<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190524145942CollateralLoanPaymentAddressId
 */
class M190524145942CollateralLoanPaymentAddressId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%collateral_loan_payment}}', 'payment_address_id', $this->integer()->defaultValue(null));
        $this->addForeignKey('fk_collateral_loan_payment_payment_address', '{{%collateral_loan_payment}}', 'payment_address_id', '{{%payment_address}}', 'id', 'SET NULL', 'CASCADE');
        $this->dropForeignKey('fk_collateral_loan_payment_payment', '{{%collateral_loan_payment}}');
        $this->alterColumn('{{%collateral_loan_payment}}', 'payment_id', $this->integer()->defaultValue(null));
        $this->addForeignKey('fk_collateral_loan_payment_payment', '{{%collateral_loan_payment}}', 'payment_id', '{{%payment}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190524145942CollateralLoanPaymentAddressId cannot be reverted.\n";

        return true;
    }

}
