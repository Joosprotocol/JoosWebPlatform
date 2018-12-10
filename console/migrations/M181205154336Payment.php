<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M181205154336Payment
 */
class M181205154336Payment extends Migration
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
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'loan_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(14, 4)->defaultValue(0),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('fk_payment_loan', '{{%payment}}', 'loan_id', '{{%loan}}', 'id', 'CASCADE', 'CASCADE');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_payment_loan', '{{%payment}}');
        $this->dropTable('{{%payment}}');

        return true;
    }

}
