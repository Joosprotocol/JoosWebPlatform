<?php

namespace console\controllers;

use common\library\smartcontract\SmartContractFetcher;
use yii\console\Controller;
use yii\helpers\Console;


/**
 * Class LoanBlockchainExtractorController
 * @package console\controllers
 */
class BlockchainController extends Controller
{
    /**
     * Action check status of expired loans from smart contracts.
     * If loan status is overdue it will be changed (update status) in database.
     */
    public function actionFetchOverdue()
    {
        $loanBlockchainExtractor = new SmartContractFetcher();
        $counter = $loanBlockchainExtractor->fetchOverdue();
        $this->stdout('Loans updated: ' . $counter . "\n"  .  PHP_EOL, Console::FG_GREEN);
    }
}
