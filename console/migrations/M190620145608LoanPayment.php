<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190620145608LoanPayment
 */
class M190620145608LoanPayment extends Migration
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

        /* Loan Payment table*/
        $this->createTable('{{%loan_payment}}', [
            'id' => $this->primaryKey(),
            'loan_id' => $this->integer()->notNull(),
            'payment_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_payment_loan', '{{%loan_payment}}', 'loan_id', '{{%loan}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_loan_payment_payment', '{{%loan_payment}}', 'payment_id', '{{%payment}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190620145608LoanPayment cannot be reverted.\n";

        return false;
    }

}
