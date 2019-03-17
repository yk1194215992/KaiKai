<?php


namespace backend\common\components\security;

use yii\base\Component;

/**
 * Class Rsa
 * @package backend\common\components\security
 */
class Rsa extends Component
{
    /**
     * @var openssl 只能加密最大 64/128 位数据
     */
    const ENCODE_MAX_SIZE = 64;
    const DECODE_MAX_SIZE = 128;

    const BLOCK_SIZE = 8;

    const BLANK_STRING = '';

    public $publicKey;
    public $privateKey;
    public $password;


    /**
     * 初始化公私钥
     *
     * @param null $publicKey
     * @param null $password
     * @param string $organizationName
     *
     * @return array
     */
    public function genNew($publicKey = null, $password = null, $organizationName = '')
    {
        $config = array(
            "config" => \Yii::getAlias('@common'). '/config/openssl.cnf'
        );
        if ($publicKey === null && $password === null) {
            $configure  = [
                'digest_alg'       => 'sha1',
                'private_key_bits' => 1024,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
                'encrypt_key'      => true,
                'config'           => $config['config'],
            ];
            $password   = sprintf("%06X", rand(100, 0xFFFFFF));
            $privateKey = openssl_pkey_new($configure);
        } else {
            $privateKey = openssl_pkey_get_private($publicKey, $password);
        }
        $publicKey = openssl_pkey_get_details($privateKey);
        $publicKey = $publicKey['key'];

        $distinguishedName = [
            'countryName'         => 'CN',
            'stateOrProvinceName' => 'shanghai',
            'localityName'        => 'shanghai',
        ];

        if (!empty($organizationName)) {
            $distinguishedName['organizationName'] = $organizationName . ' Co.,Ltd.';
        }


        $expire  = 3650; // 十年
        $csr     = openssl_csr_new($distinguishedName, $privateKey,$config);
        $csrSign = openssl_csr_sign($csr, null, $privateKey, $expire,$config);

        openssl_csr_export($csr, $csr);
        openssl_x509_export($csrSign, $csrSign);
        openssl_pkey_export($privateKey, $privateKey, $password);

        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->password = $password;
        return [
            'csr'         => $csr,
            'csr_sign'    => $csrSign,
            'public_key'  => $publicKey,
            'private_key' => $privateKey,
            'password'    => $password,
        ];
    }

    /**
     * 公钥加密
     *
     * @param $string
     *
     * @return bool|string
     */
    public function encodeP($string)
    {
        if (strlen($string) == 0) return self::BLANK_STRING;

        $array  = str_split($string, self::ENCODE_MAX_SIZE);
        $buffer = self::BLANK_STRING;
        $keyid  = openssl_pkey_get_public($this->publicKey);
        foreach ($array as $trunk) {
            $temp = self::BLANK_STRING;
            if (openssl_public_encrypt($trunk, $temp, $keyid, OPENSSL_PKCS1_PADDING)) {
                $buffer .= $temp;
            } else {
                return false;
            }
        }

        return base64_encode($buffer);
    }

    /**
     * 公钥解密
     *
     * @param $strBase64
     *
     * @return bool|string
     */
    public function decodeP($strBase64)
    {
        if (empty($strBase64)) return self::BLANK_STRING;

        $string = base64_decode($strBase64);
        if ($string === false) {
            return false;
        }

        if (empty($string)) return self::BLANK_STRING;

        $array  = str_split($string, self::DECODE_MAX_SIZE);
        $buffer = self::BLANK_STRING;
        $keyid  = openssl_pkey_get_public($this->publicKey);
        foreach ($array as $trunk) {
            $temp = self::BLANK_STRING;
            if (openssl_public_decrypt($trunk, $temp, $keyid, OPENSSL_PKCS1_PADDING)) {
                $buffer .= $temp;
            } else {
                return false;
            }
        }

        return $buffer;
    }

    /**
     * 私钥加密
     *
     * @param $string
     *
     * @return bool|string
     */
    public function encode($string)
    {
        if (strlen($string) == 0) return self::BLANK_STRING;

        $array  = str_split($string, self::ENCODE_MAX_SIZE);
        $buffer = self::BLANK_STRING;
        $keyid  = openssl_pkey_get_private($this->privateKey, $this->password);

        foreach ($array as $trunk) {
            $temp = self::BLANK_STRING;

            if (openssl_private_encrypt($trunk, $temp, $keyid, OPENSSL_PKCS1_PADDING)) {
                $buffer .= $temp;
            } else {
                return false;
            }
        }

        return base64_encode($buffer);
    }

    /**
     * 私钥解密
     *
     * @param $strBase64
     *
     * @return bool|string
     */
    public function decode($strBase64)
    {
        if (empty($strBase64)) return self::BLANK_STRING;

        $string = base64_decode($strBase64);
        if ($string === false) {
            return $strBase64;
        }

        if (empty($string)) return self::BLANK_STRING;

        $array  = str_split($string, self::DECODE_MAX_SIZE);
        $buffer = self::BLANK_STRING;
        $keyid  = openssl_pkey_get_private($this->privateKey, $this->password);

        foreach ($array as $trunk) {
            $temp = self::BLANK_STRING;

            if (openssl_private_decrypt($trunk, $temp, $keyid, OPENSSL_PKCS1_PADDING)) {
                $buffer .= $temp;
            } else {
                return $strBase64;
            }
        }
        return $buffer;
    }

}
