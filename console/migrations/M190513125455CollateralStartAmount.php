<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190513125455CollateralStartAmount
 */
class M190513125455CollateralStartAmount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%collateral}}', 'start_amount', $this->bigInteger()->defaultValue(0)->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%collateral}}', 'start_amount');
        return true;
    }

}
