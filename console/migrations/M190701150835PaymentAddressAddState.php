<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190701150835PaymentAddressAddState
 */
class M190701150835PaymentAddressAddState extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%payment_address}}', 'state', $this->integer(1)->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%payment_address}}', 'state');
        return true;
    }
}
