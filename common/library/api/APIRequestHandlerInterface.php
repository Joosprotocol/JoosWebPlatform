<?php

namespace common\library\api;


use common\library\exceptions\APICallException;
use common\library\exceptions\ParseException;

interface APIRequestHandlerInterface
{
    /**
     * @param array $data
     * @param string $url
     * @return object|string
     * @throws APICallException
     * @throws \HttpException
     * @throws ParseException
     */
    public function post(string $url, array $data = []);

    /**
     * @param string $url
     * @return object|string
     * @throws APICallException
     * @throws \HttpException
     * @throws ParseException
     */
    public function get(string $url);

}
