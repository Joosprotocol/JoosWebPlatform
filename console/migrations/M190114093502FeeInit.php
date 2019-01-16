<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190114093502FeeInit
 */
class M190114093502FeeInit extends Migration
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

        /* Payment table*/
        $this->createTable('{{%fee}}', [
            'id' => $this->primaryKey(),
            'loan_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(14, 4)->defaultValue(0),
            'status' => $this->integer(2)->notNull(),
            'currency_type' => $this->integer(1)->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('fk_fee_loan', '{{%fee}}', 'loan_id', '{{%loan}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_fee_user', '{{%fee}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_fee_loan', '{{%fee}}');
        $this->dropForeignKey('fk_fee_user', '{{%fee}}');
        $this->dropTable('{{%fee}}');

        return true;
    }

}
