<?php

namespace common\library\blockchain;

use common\library\api\APIRequestHandler;
use common\library\api\APIRequestHandlerInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class CryptoAPI
 * @package cryptoAPI
 */
class CryptoAPI extends Component
{

    /** @var string  */
    public $apiUrl;
    /** @var string  */
    public $apiKey;
    /** @var array  */
    public $networks;
    /** @var string  */
    public $environment;
    /** @var APIRequestHandler  */
    private $apiRequestHandler;
    /** @var  string */
    public $apiRequestHandlerClass;


    /**
     * @param string $endpoint
     * @param array $endpointData
     * @return string
     */
    public function buildEndpoint(string $endpoint, array $endpointData = [])
    {

        foreach ($endpointData as $placeholder => $value) {
            $endpoint = str_replace($placeholder, $value, $endpoint);
        }
        return $endpoint;
    }

    /**
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->setApiRequestHandler(new $this->apiRequestHandlerClass);
        $this->apiRequestHandler->setHeader(
            [
                'Content-Type:application/json',
                'X-API-Key:' . $this->apiKey
            ]
        );
    }


    /**
     * @param $blockchain
     * @return string
     * @throws InvalidConfigException
     */
    public function getNetworkByConfig(string $blockchain)
    {
        if (empty($this->networks)) {
            throw new InvalidConfigException('\'networks\' config is not defined.');
        }
        if (empty($this->environment)) {
            throw new InvalidConfigException('\'environment\' config is not defined.');
        }
        if (!array_key_exists($blockchain, $this->networks)) {
            throw new InvalidConfigException($blockchain . ' network is unknown.');
        }
        if (!array_key_exists($this->environment, $this->networks[$blockchain])) {
            throw new InvalidConfigException($this->environment . ' network is not configured for ' . $blockchain . '.');
        }

        return $this->networks[$blockchain][$this->environment];
    }

    /**
     * @param APIRequestHandlerInterface $apiRequestHandler
     */
    public function setApiRequestHandler(APIRequestHandlerInterface $apiRequestHandler)
    {
        $this->apiRequestHandler = $apiRequestHandler;
    }

    /**
     * @return APIRequestHandlerInterface
     */
    public function getApiRequestHandler(): APIRequestHandlerInterface
    {
        return $this->apiRequestHandler;
    }

}
