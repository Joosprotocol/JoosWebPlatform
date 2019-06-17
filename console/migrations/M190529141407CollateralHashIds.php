<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190529141407CollateralHashIds
 */
class M190529141407CollateralHashIds extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%collateral_loan}}', 'hash_id', $this->string(34)->notNull()->unique());

        $this->createIndex(
            'idx-collateral_loan_hash_id',
            '{{%collateral_loan}}',
            'hash_id'
        );

        $this->addColumn('{{%collateral}}', 'hash_id', $this->string(34)->notNull()->unique());

        $this->createIndex(
            'idx-collateral_hash_id',
            '{{%collateral}}',
            'hash_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190529141407CollateralHashIds cannot be reverted.\n";

        return false;
    }
}
