<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190107153356LoanReferralInit
 */
class M190107153356LoanReferralInit extends Migration
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

        /* Loan Referral table*/
        $this->createTable('{{%loan_referral}}', [
            'id' => $this->primaryKey(),
            'loan_id' => $this->integer()->notNull(),
            'digital_collector_id' => $this->integer()->notNull(),
            'slug' => $this->string(10)->notNull()->unique(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('fk_loan_referral_loan', '{{%loan_referral}}', 'loan_id', '{{%loan}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_loan_referral_digital_collector', '{{%loan_referral}}', 'digital_collector_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        /* Loan Following table*/
        $this->createTable('{{%loan_following}}', [
            'id' => $this->primaryKey(),
            'borrower_id' => $this->integer()->notNull(),
            'loan_referral_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('fk_loan_following_borrower', '{{%loan_following}}', 'borrower_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_loan_following_loan_referral', '{{%loan_following}}', 'loan_referral_id', '{{%loan_referral}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_loan_following_borrower', '{{%loan_following}}');
        $this->dropForeignKey('fk_loan_following_loan_referral', '{{%loan_following}}');
        $this->dropTable('{{%loan_following}}');

        $this->dropForeignKey('fk_loan_referral_loan', '{{%loan_referral}}');
        $this->dropForeignKey('fk_loan_referral_digital_collector', '{{%loan_referral}}');
        $this->dropTable('{{%loan_referral}}');

        return true;
    }

}
