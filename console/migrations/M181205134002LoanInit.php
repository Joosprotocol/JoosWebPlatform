<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M181205134002LoanInit
 */
class M181205134002loanInit extends Migration
{
    const SECONDS_IN_MONTHS = 24 * 60 * 60;

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
        $this->createTable('{{%loan}}', [
            'id' => $this->primaryKey(),
            'lender_id' => $this->integer()->defaultValue(null),
            'borrower_id' => $this->integer()->defaultValue(null),
            'status' => $this->integer(2)->notNull(),
            'amount' => $this->decimal(14, 4)->defaultValue(0),
            'period' => $this->integer()->defaultValue(self::SECONDS_IN_MONTHS),
            'type' => $this->integer(1)->notNull(),
            'secret_key' => $this->string(255)->notNull(),
            'ref_slug' => $this->string(10)->notNull()->unique(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('fk_loan_lender', '{{%loan}}', 'lender_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_loan_borrower', '{{%loan}}', 'borrower_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        /* Loan status history table*/
        $this->createTable('{{%loan_status_history}}', [
            'id' => $this->primaryKey(),
            'loan_id' => $this->integer()->notNull(),
            'status' => $this->integer(2)->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('fk_status_loan', '{{%loan_status_history}}', 'loan_id', '{{%loan}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_status_loan', '{{%loan_status_history}}');
        $this->dropTable('{{%loan_status_history}}');

        $this->dropForeignKey('fk_loan_borrower', '{{%loan}}');
        $this->dropForeignKey('fk_loan_lender', '{{%loan}}');
        $this->dropTable('{{%loan}}');

        return true;
    }


}
