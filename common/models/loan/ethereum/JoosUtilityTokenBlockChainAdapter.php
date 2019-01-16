<?php


namespace common\models\loan\ethereum;


use common\library\ethereum\BlockchainAPIInterface;
use common\library\exceptions\APICallException;
use common\library\exceptions\ParseException;
use yii\web\NotFoundHttpException;

class JoosUtilityTokenBlockChainAdapter
{
    const CONTRACT_NAME = 'JoosUtilityToken';

    private $blockchain;

    public function __construct(BlockchainAPIInterface $blockchain)
    {
        $this->blockchain = $blockchain;
    }

    /**
     * @param string $address
     * @param integer $amount
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function mint($address, $amount)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @param string $address
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function balanceOf($address)
    {
        return $this->blockchain->executeContract(
            self::CONTRACT_NAME,
            $this->blockchain->getRequestTypeContractSend(),
            __FUNCTION__,
            func_get_args()
        );
    }
}
