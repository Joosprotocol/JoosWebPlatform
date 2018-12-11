<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M181210080327UserPersonal
 */
class M181210080327UserPersonal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* Personal User table*/
        $this->createTable('{{%user_personal}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->unique()->notNull(),
            'facebook_id' => $this->string(255)->notNull(),
            'social_id' => $this->string(255)->notNull(),
            'mobile_number' => $this->string(15)->notNull(),
            'facebook_friend_first_url' => $this->string(255)->notNull(),
            'facebook_friend_second_url' => $this->string(255)->notNull(),
            'facebook_friend_third_url' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_user_personal_user', '{{%user_personal}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_personal_user', '{{%user_personal}}');
        $this->dropTable('{{%user_personal}}');

        return true;
    }
}
