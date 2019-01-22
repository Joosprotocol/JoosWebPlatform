<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190121174527UserPersonalUserIdNotUnique
 */
class M190121174527UserPersonalUserIdNotUnique extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_user_personal_user', '{{%user_personal}}');
        $this->dropColumn('{{%user_personal}}', 'user_id');
        $this->addColumn('{{%user_personal}}', 'user_id', $this->integer()->notNull());
        $this->addForeignKey('fk_user_personal_user', '{{%user_personal}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_personal_user', '{{%user_personal}}');
        $this->dropColumn('{{%user_personal}}', 'user_id');
        $this->addColumn('{{%user_personal}}', 'user_id', $this->integer()->unique()->notNull());
        $this->addForeignKey('fk_user_personal_user', '{{%user_personal}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        return true;
    }
}
