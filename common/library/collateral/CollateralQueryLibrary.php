<?php
namespace common\library\collateral;


use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoan;

class CollateralQueryLibrary
{

    /**
     * @return Collateral[]
     */
    public static function getCollateralsWithoutLoans()
    {
        return Collateral::find()
            ->leftJoin(CollateralLoan::tableName(), CollateralLoan::tableName() . '.collateral_id = ' . Collateral::tableName() . '.id')
            ->where(CollateralLoan::tableName() . '.id IS NULL')
            ->where([Collateral::tableName() . '.status' => Collateral::STATUS_POSTED])
            ->all();
    }

    /**
     * @param Collateral $collateral
     * @return mixed
     */
    public static function countUsedCollateralAmount(Collateral $collateral)
    {
        return Collateral::find()
            ->where([Collateral::tableName() . '.id'  => $collateral->id])
            ->leftJoin(CollateralLoan::tableName(), CollateralLoan::tableName() . '.collateral_id = ' . Collateral::tableName() . '.id')
            ->sum(CollateralLoan::tableName() . '.amount');
    }
}
