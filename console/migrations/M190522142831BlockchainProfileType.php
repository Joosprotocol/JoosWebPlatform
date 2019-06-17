<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190522142831BlockchainProfileType
 */
class M190522142831BlockchainProfileType extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%blockchain_profile}}', 'network', $this->integer()->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%blockchain_profile}}', 'network');

        return true;
    }

}
