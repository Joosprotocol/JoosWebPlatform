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
    public function actionFetchOverdue()
    {
        $loanBlockchainExtractor = new SmartContractFetcher();
        $counter = $loanBlockchainExtractor->fetchOverdue();
        $this->stdout('Loans updated: ' . $counter . "\n"  .  PHP_EOL, Console::FG_GREEN);
    }
}
