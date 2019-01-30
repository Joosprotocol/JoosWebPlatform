<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190123161703LoanSignedAt
 */
class M190123161703LoanSignedAt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%loan}}', 'signed_at', $this->integer()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%loan}}', 'signed_at');
        return true;
    }
}
