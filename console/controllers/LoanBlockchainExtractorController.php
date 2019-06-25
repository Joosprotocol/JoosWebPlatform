<?php

namespace console\controllers;

use common\library\loan\LoanBlockchainExtractor;
use yii\console\Controller;
use yii\helpers\Console;


/**
 * Class LoanBlockchainExtractorController
 * @package console\controllers
 */
class LoanBlockchainExtractorController extends Controller
{
    public function actionUpdate()
    {
        $loanBlockchainExtractor = new LoanBlockchainExtractor();
        $counter = $loanBlockchainExtractor->update();
        $this->stdout('Loans updated: ' . $counter . "\n"  .  PHP_EOL, Console::FG_GREEN);
    }
}
