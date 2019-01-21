<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190118092416UserPersonalActiveColumn
 */
class M190118092416UserPersonalActiveColumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_personal}}', 'active', $this->integer(1)->notNull()->defaultValue(0)->after('user_id'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_personal}}', 'active');
        return true;
    }
}
