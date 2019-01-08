<?php


namespace common\library\crypt;


interface CryptorInterface
{
    /**
     * @param string $string
     * @param $secretKey
     * @return string
     */
    public function encode($string, $secretKey) : string;

    /**
     * @param string $string
     * @param $secretKey
     * @return string
     */
    public function decode($string, $secretKey) : string;
}
