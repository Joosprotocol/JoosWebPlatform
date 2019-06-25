<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190620083543LoanAddFee
 */
class M190620083543LoanAddFee extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%loan}}', 'fee', $this->decimal(5, 2)->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%loan}}', 'fee');

        return false;
    }

}
