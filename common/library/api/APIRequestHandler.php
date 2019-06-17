<?php

namespace common\library\api;

use common\library\exceptions\APICallException;

use common\library\exceptions\ParseException;
use yii\base\Component;
use yii\web\HttpException;

class APIRequestHandler extends Component implements APIRequestHandlerInterface
{
    const RESPONSE_ERROR_PROPERTY = 'error';
    const RESPONSE_MESSAGE_PROPERTY = 'message';

    const RESPONSE_SUCCESS_CODE = 200;
    const RESPONSE_NOT_FOUND_CODE = 404;

    private $_header;

    /**
     * @param array $data
     * @param string $url
     * @return object|string
     * @throws APICallException
     * @throws \HttpException
     * @throws ParseException
     */
    public function post(string $url, array $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            $this->_header
        );
        return $this->getResponse($ch);
    }

    /**
     * @param string $url
     * @return object|string
     * @throws APICallException
     * @throws \HttpException
     * @throws ParseException
     */
    public function get(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            $this->_header
        );
        return $this->getResponse($ch);
    }

    /**
     * @param $ch resource a cURL handle on success, false on errors.
     * @return object|string
     * @throws APICallException
     * @throws HttpException
     * @throws ParseException
     */
    private function getResponse($ch)
    {
        $result = curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        $error = curl_error($ch);
        curl_close($ch);
        $ch = null;
        \Yii::info([$result, $curlInfo, $error], "api");
        if ((int)$curlInfo['http_code'] === self::RESPONSE_NOT_FOUND_CODE) {
            throw new HttpException('Unable to connect to the blockchain service');
        }
        if ((int)$curlInfo['http_code'] !== self::RESPONSE_SUCCESS_CODE) {
            throw new HttpException('API Error.');
        }
        if ($result === false) {
            throw new APICallException($error);
        }
        $resultObject = json_decode($result);
        return $resultObject;
    }

    /**
     * @param array $header
     */
    public function setHeader($header)
    {
        $this->_header = $header;
    }
}
