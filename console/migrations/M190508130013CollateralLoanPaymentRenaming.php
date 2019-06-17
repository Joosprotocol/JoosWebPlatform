<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190508130013CollateralLoanPaymentRenaming
 */
class M190508130013CollateralLoanPaymentRenaming extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->dropForeignKey('fk_payment_collateral_loan', '{{%collateral_payment}}');
        $this->dropForeignKey('fk_collateral_payment_payment', '{{%collateral_payment}}');

        $this->renameTable('{{%collateral_payment}}', '{{%collateral_loan_payment}}');

        $this->addForeignKey('fk_collateral_loan_payment_collateral_loan', '{{%collateral_loan_payment}}', 'collateral_loan_id', '{{%collateral_loan}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_collateral_loan_payment_payment', '{{%collateral_loan_payment}}', 'payment_id', '{{%payment}}', 'id', 'CASCADE', 'CASCADE');


        /* Collateral Payment table*/
        $this->createTable('{{%collateral_payment}}', [
            'id' => $this->primaryKey(),
            'collateral_id' => $this->integer()->notNull(),
            'payment_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_collateral_payment_collateral', '{{%collateral_payment}}', 'collateral_id', '{{%collateral}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_collateral_payment_payment', '{{%collateral_payment}}', 'payment_id', '{{%payment}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190508130013CollateralLoanPaymentRenaming cannot be reverted.\n";

        return false;
    }

}
