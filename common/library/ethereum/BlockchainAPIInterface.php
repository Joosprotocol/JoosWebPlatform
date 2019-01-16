<?php

namespace common\library\ethereum;


interface BlockchainAPIInterface
{

    /**
     * @param string $contractName
     * @param string $requestType
     * @param string $method
     * @param array $params
     * @return object|string
     */
    public function executeContract($contractName, $requestType, $method, array $params);

    /**
     * @param string $requestType
     * @param string $method
     * @param array $params
     * @return object|string
     */
    public function executeWeb3($requestType, $method, array $params);

    /**
     * @return string
     */
    public function getRequestTypeContractCall();

    /**
     * @return string
     */
    public function getRequestTypeContractSend();

    /**
     * @return string
     */
    public function getRequestTypeWeb3Custom();
}
