<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190620144335LoanAddHashId
 */
class M190620144335LoanAddHashId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%loan}}', 'hash_id', $this->string(34)->notNull()->unique());

        $this->createIndex(
            'idx-loan_hash_id',
            '{{%loan}}',
            'hash_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-loan_hash_id', '{{%loan}}');

        $this->dropColumn('{{%loan}}', 'hash_id');

        return false;
    }
}
