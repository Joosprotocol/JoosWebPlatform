<?php

namespace common\library\crypt;


use yii\base\Component;

class BasicCryptor extends Component implements CryptorInterface
{
    /** @var string */
    public $cipherMode;
    /** @var string */
    public $algorithm;

    /**
     * @param string $string
     * @param $secretKey
     * @return string
     */
    public function encode($string, $secretKey) : string
    {
        $key = hash($this->algorithm, $secretKey);
        $iv = substr(hash($this->algorithm, $secretKey), 0, 16);
        $encryptedString = openssl_encrypt($string, $this->cipherMode, $key, 0, $iv);
        unset($string, $cipher_method, $key, $encIv);
        return $encryptedString;
    }

    /**
     * @param string $string
     * @param $secretKey
     * @return string
     */
    public function decode($string, $secretKey) : string
    {
        $key = hash($this->algorithm, $secretKey);
        $iv = substr(hash($this->algorithm, $secretKey), 0, 16);
        $decryptedString = openssl_decrypt($string, $this->cipherMode, $key, 0, $iv);
        unset($string, $key, $encIv);
        return $decryptedString;
    }
}
