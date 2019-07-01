<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190627144325CollateralLoanSignedAt
 */
class M190627144325CollateralLoanSignedAt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%collateral_loan}}','signed_at', $this->integer()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%collateral_loan}}','signed_at');
        return true;
    }

}
