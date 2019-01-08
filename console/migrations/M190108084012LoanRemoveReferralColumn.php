<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190108084012LoanRemoveReferralColumn
 */
class M190108084012LoanRemoveReferralColumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%loan}}', 'ref_slug');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%loan}}', 'ref_slug', $this->string(10)->notNull()->unique());
        return true;
    }
}
