<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190703085527NotificationsStatus
 */
class M190703085527NotificationsStatus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%notification}}', 'status', $this->integer(1)->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%notification}}', 'status');
        return true;
    }

}
