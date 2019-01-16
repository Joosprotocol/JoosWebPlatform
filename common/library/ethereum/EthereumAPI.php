<?php

namespace common\library\ethereum;

use common\library\exceptions\APICallException;

use common\library\exceptions\ParseException;
use yii\base\Component;
use yii\web\NotFoundHttpException;

class EthereumAPI extends Component implements BlockchainAPIInterface
{

    const REQUEST_TYPE_SEND_CONTRACT = 'send-contract';
    const REQUEST_TYPE_CALL_CONTRACT = 'call-contract';
    const REQUEST_TYPE_CALL_WEB3_CUSTOM = 'call-web3-custom';

    const RESPONSE_ERROR_PROPERTY = 'error';
    const RESPONSE_RESULT_PROPERTY = 'result';
    const RESPONSE_DATA_PROPERTY = 'data';
    const RESPONSE_MESSAGE_PROPERTY = 'message';

    const RESPONSE_SUCCESS_CODE = 200;

    private $_url;

    /**
     * @param string $contractName
     * @param string $requestType
     * @param string $method
     * @param array $params
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function executeContract($contractName, $requestType, $method, array $params)
    {
        $data = [
            "contract_name" => $contractName,
            "type" => $requestType,
            "method" => $method,
            "params" => $params
        ];

        return $this->execute($data);
    }

    /**
     * @param string $requestType
     * @param string $method
     * @param array $params
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    public function executeWeb3($requestType, $method, array $params)
    {
        $data = [
            "type" => $requestType,
            "method" => $method,
            "params" => $params
        ];

        return $this->execute($data);
    }

    /**
     * @param array $data
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    private function execute(array $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type:application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]
        );
        return $this->getResponse($ch);
    }

    /**
     * @param $ch resource a cURL handle on success, false on errors.
     * @return object|string
     * @throws APICallException
     * @throws NotFoundHttpException
     * @throws ParseException
     */
    private function getResponse($ch)
    {
        $result = curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        $error = curl_error($ch);
        curl_close($ch);
        $ch = null;
        \Yii::trace([$result, $curlInfo, $error], "EthereumAPI");
        if ((int)$curlInfo['http_code'] !== self::RESPONSE_SUCCESS_CODE) {
            throw new NotFoundHttpException();
        }
        if ($result === false) {
            throw new APICallException($error);
        }
        $resultObject = json_decode($result);
        if ($resultObject === null) {
            throw new ParseException(json_last_error());
        }
        if (!property_exists($resultObject, self::RESPONSE_RESULT_PROPERTY)) {
            throw new APICallException('Response does not contain a "result" field.');
        }
        if (!empty($resultObject->{self::RESPONSE_ERROR_PROPERTY}->{self::RESPONSE_MESSAGE_PROPERTY})) {

            throw new APICallException($resultObject->{self::RESPONSE_ERROR_PROPERTY}->{self::RESPONSE_MESSAGE_PROPERTY});
        }
        if ($resultObject->{self::RESPONSE_RESULT_PROPERTY} === false) {
            throw new APICallException('Unknown error.');
        }
        return $resultObject->{self::RESPONSE_DATA_PROPERTY};
    }

    /**
     * @return string
     */
    public function getRequestTypeContractCall()
    {
        return self::REQUEST_TYPE_CALL_CONTRACT;
    }

    /**
     * @return string
     */
    public function getRequestTypeContractSend()
    {
        return self::REQUEST_TYPE_SEND_CONTRACT;
    }

    /**
     * @return string
     */
    public function getRequestTypeWeb3Custom()
    {
        return self::REQUEST_TYPE_CALL_WEB3_CUSTOM;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }
}
