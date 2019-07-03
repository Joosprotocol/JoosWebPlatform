<?php


namespace console\controllers;

use common\library\paymentaddress\PaymentAddressQueryLibrary;
use common\library\paymentaddress\PaymentAddressService;
use Exception;
use yii\console\Controller;
use yii\helpers\Console;


class PaymentAddressController extends Controller
{

    /**
     * Check if exist temporary crypto wallets with any funds.
     * Resend funds from temporary to hub wallets.
     * @return bool
     */
    public function actionSendToHub()
    {
        $paymentAddresses = PaymentAddressQueryLibrary::getWithFunds();

        $paymentAddressService = new PaymentAddressService();
        foreach ($paymentAddresses as $paymentAddress) {
            try {
                if ($paymentAddressService->sendToHub($paymentAddress)) {
                    $this->stdout('Funds transferred from temporary address to hub address. | ID: ' . $paymentAddress->id . " | Hex: " . $paymentAddressService->getTransactionHex() . " |\n"  .  PHP_EOL, Console::FG_YELLOW);
                }
            }
            catch (Exception $exception) {
                $this->stdout('Error occurred. | ID: ' . $paymentAddress->id . " |\n"  .  PHP_EOL, Console::FG_RED);
            }
        }
        return true;
    }
}
