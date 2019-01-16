<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190110092938BlockchainProfileInit
 */
class M190110092938BlockchainProfileInit extends Migration
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

        /* Loan table*/
        $this->createTable('{{%blockchain_profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(null),
            'address' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_blockchain_profile_user', '{{%blockchain_profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_blockchain_profile_user', '{{%blockchain_profile}}');
        $this->dropTable('{{%blockchain_profile}}');

        return true;
    }
}
