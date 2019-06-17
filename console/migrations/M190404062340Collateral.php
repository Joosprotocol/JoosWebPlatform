<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190404062340Collateral
 */
class M190404062340Collateral extends Migration
{
    const SECONDS_IN_YEAR = 365 * 60 * 60;
    const PERCENT = 365 * 60 * 60;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* Loan table*/
        $this->createTable('{{%collateral}}', [
            'id' => $this->primaryKey(),
            'lender_id' => $this->integer()->defaultValue(null),
            'investor_id' => $this->integer()->defaultValue(null),
            'status' => $this->integer(2)->notNull(),
            'amount' => $this->decimal(14, 4)->defaultValue(0),
            'period' => $this->integer()->defaultValue(self::SECONDS_IN_YEAR),
            'currency_type' => $this->integer(1)->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        /* Collateral Loan table*/
        $this->createTable('{{%collateral_loan}}', [
            'id' => $this->primaryKey(),
            'lender_id' => $this->integer()->defaultValue(null),
            'borrower_id' => $this->integer()->defaultValue(null),
            'collateral_id' => $this->integer()->defaultValue(null),
            'status' => $this->integer(2)->notNull(),
            'amount' => $this->decimal(14, 4)->defaultValue(0),
            'percent_int' => $this->integer()->defaultValue(1000),
            'currency_type' => $this->integer(1)->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null)
        ], $tableOptions);

        $this->addForeignKey('fk_collateral_loan_collateral', '{{%collateral_loan}}', 'collateral_id', '{{%collateral}}', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk_collateral_loan_lender', '{{%collateral_loan}}', 'lender_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_collateral_loan_borrower', '{{%collateral_loan}}', 'borrower_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk_collateral_lender', '{{%collateral}}', 'lender_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_collateral_investor', '{{%collateral}}', 'investor_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('fk_payment_loan', '{{%payment}}');
        $this->dropColumn('{{%payment}}', 'loan_id');

        $this->addColumn('{{payment}}', 'currency_type', $this->integer(1)->notNull()->after('amount'));
        $this->addColumn('{{%payment}}', 'hash', $this->string(255)->after('currency_type'));


        /* Collateral Payment table*/
        $this->createTable('{{%collateral_payment}}', [
            'id' => $this->primaryKey(),
            'collateral_loan_id' => $this->integer()->notNull(),
            'payment_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_payment_collateral_loan', '{{%collateral_payment}}', 'collateral_loan_id', '{{%collateral_loan}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_collateral_payment_payment', '{{%collateral_payment}}', 'payment_id', '{{%payment}}', 'id', 'CASCADE', 'CASCADE');

        $this->addColumn('{{collateral_loan}}', 'sponsor', $this->integer(1)->notNull()->after('lender_id'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190404062340Collateral cannot be reverted.\n";

        return false;
    }

}
