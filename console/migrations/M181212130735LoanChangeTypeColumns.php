<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M181212130735LoanChangeTypeColumns
 */
class M181212130735LoanChangeTypeColumns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%loan}}', 'type', 'init_type');
        $this->addColumn('{{%loan}}', 'currency_type', $this->integer(1)->notNull()->after('init_type'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%loan}}', 'currency_type');
        $this->renameColumn('{{%loan}}', 'init_type', 'type');

        return true;
    }

}
