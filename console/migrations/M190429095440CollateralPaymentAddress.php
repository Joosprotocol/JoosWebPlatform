<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190429095440CollateralPaymentAddress
 */
class M190429095440CollateralPaymentAddress extends Migration
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
        $this->createTable('{{%payment_address}}', [
            'id' => $this->primaryKey(),
            'address' => $this->string(255)->notNull(),
            'currency_type' => $this->integer(1)->notNull(),
            'additional' => $this->text(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        $this->addColumn('{{%collateral}}', 'payment_address_id', $this->integer()->defaultValue(null));

        $this->addForeignKey('fk_collateral_payment_address', '{{%collateral}}', 'payment_address_id', '{{%payment_address}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_collateral_payment_address', '{{%collateral}}');

        $this->dropColumn('{{%collateral}}', 'payment_address_id');

        $this->dropTable('{{%payment_address}}');

        return true;
    }
}
