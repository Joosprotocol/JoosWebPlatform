<?php


namespace common\models\loan\ethereum;


use common\library\ethereum\BlockchainAPIInterface;
use common\library\exceptions\APICallException;
use common\library\exceptions\ParseException;
use yii\web\NotFoundHttpException;

class Web3BlockChainAdapter
{

    const METHOD_UTILS_IS_ADDRESS = 'utils.isAddress';

    private $blockchain;

    public function __construct(BlockchainAPIInterface $blockchain)
    {
        $this->blockchain = $blockchain;
    }

    /**
     * @param string $address
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function isAccount($address)
    {
        return $this->blockchain->executeWeb3(
            $this->blockchain->getRequestTypeWeb3Custom(),
            self::METHOD_UTILS_IS_ADDRESS,
            func_get_args()
        );
    }

}
