<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190513105326CollateralAmountRequired
 */
class M190513105326CollateralAmountRequired extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%loan}}', 'amount', $this->bigInteger()->defaultValue(0)->notNull());
        $this->alterColumn('{{%payment}}', 'amount', $this->bigInteger()->defaultValue(0)->notNull());
        $this->alterColumn('{{%fee}}', 'amount', $this->bigInteger()->defaultValue(0)->notNull());
        $this->alterColumn('{{%collateral}}', 'amount', $this->bigInteger()->defaultValue(0)->notNull());
        $this->alterColumn('{{%collateral_loan}}', 'amount', $this->bigInteger()->defaultValue(0)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190513105326CollateralAmountRequired cannot be reverted.\n";

        return false;
    }

}
