<?php

namespace console\controllers;

use common\library\loan\LoanBlockchainExtractor;
use yii\console\Controller;


/**
 * Class LoanBlockchainExtractorController
 * @package console\controllers
 */
class LoanBlockchainExtractorController extends Controller
{
    public function actionUpdate()
    {
        $loanBlockchainExtractor = new LoanBlockchainExtractor();
        $loanBlockchainExtractor->update();
    }
}
