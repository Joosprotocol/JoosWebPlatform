<?php

namespace common\library\blockchain;

use common\library\exceptions\APICallException;

/**
 * Interface CryptoManagerInterface
 * @package cryptoAPI
 */
interface CryptoManagerInterface
{
    /**
     * @return object|string
     */
    public function generateAddress();

  /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param int $value
     * @param string $secretKey
     * @return int
     * @throws APICallException
     */
    public function sendAmount(string $addressFrom, string $addressTo, int $value, string $secretKey);

    /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param string $secretKey
     * @return int
     * @throws APICallException
     */
    public function sendAllAmount(string $addressFrom, string $addressTo, string $secretKey);

    /**
     * @param $addressInfo
     * @return mixed
     * @throws APICallException
     */
    public function getPaymentAddress($addressInfo);

    /**
     * @param string $address
     * @return int
     * @throws APICallException
     */
    public function getBalanceByAddress(string $address);
}
