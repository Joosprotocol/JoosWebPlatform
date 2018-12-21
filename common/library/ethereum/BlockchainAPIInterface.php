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
    public function execute($contractName, $requestType, $method, array $params);

    /**
     * @return string
     */
    public function getRequestTypeCall();

    /**
     * @return string
     */
    public function getRequestTypeSend();
}
