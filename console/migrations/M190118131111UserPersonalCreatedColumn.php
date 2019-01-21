<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190118131111UserPersonalCreatedColumn
 */
class M190118131111UserPersonalCreatedColumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_personal}}', 'updated_at', $this->integer(1)->defaultValue(null)->after('facebook_friend_third_url'));
        $this->addColumn('{{%user_personal}}', 'created_at', $this->integer(1)->defaultValue(null)->after('facebook_friend_third_url'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_personal}}', 'updated_at');
        $this->dropColumn('{{%user_personal}}', 'created_at');
        return true;
    }
}
