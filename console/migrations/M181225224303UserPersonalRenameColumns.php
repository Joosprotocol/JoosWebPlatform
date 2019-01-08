<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M181225224303UserPersonalRenameColumns
 */
class M181225224303UserPersonalRenameColumns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%user_personal}}', 'facebook_id', 'facebook_url');
        $this->renameColumn('{{%user_personal}}', 'social_id', 'social_url');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%user_personal}}', 'facebook_url', 'facebook_id');
        $this->renameColumn('{{%user_personal}}', 'social_url', 'social_id');

        return true;
    }


}
