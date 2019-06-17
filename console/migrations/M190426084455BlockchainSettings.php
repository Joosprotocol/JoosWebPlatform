<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190426084455BlockchainSettings
 */
class M190426084455BlockchainSettings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%setting}}', ['type', 'value_type', 'title', 'key', 'value'], [
            [0, 3, 'Bitcoin fee price (sat/byte)', 'bitcoin_fee_price_per_byte', 65],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190426084455BlockchainSettings cannot be reverted.\n";

        return false;
    }
}
