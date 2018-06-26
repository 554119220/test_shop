<?php
/**
 * RSA类
 * 需要支持openssl
 */
namespace lbzy\sdk\erp;;
use think\Exception;

class Rsa {

    const PUBKEY_KEY    = '-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBALxuLFrFJhbRAmkXgsLvHcZR61e9eZCp
IYSBKTLVw2ZeaNOvKWQTvr9gVS9FSotGK4dk2S+NsbGJgx2p72m3UokCAwEAAQ==
-----END PUBLIC KEY-----';    //公钥

    const PRIVATE_KEY = '-----BEGIN PRIVATE KEY-----
MIIBVQIBADANBgkqhkiG9w0BAQEFAASCAT8wggE7AgEAAkEAvG4sWsUmFtECaReC
wu8dxlHrV715kKkhhIEpMtXDZl5o068pZBO+v2BVL0VKi0Yrh2TZL42xsYmDHanv
abdSiQIDAQABAkAIEVeI02QcGfrWcRFCM2a89Qj0isJHtVYgDD+tU4W4PkO9VLFy
Qb+W5mmS2DK6Cy8Mh4vejLm1x6syOZUqGXPhAiEA53533bA2rMhZcjkVASGQEFq9
vUHyYADSsuLHF1ZR6vUCIQDQYKwAzgeZS7Dm3HtRyMjM7DAa0xhI4sQ6EfFQiEz0
xQIgde6EzChYQj+y6bDQ1YmupdZEcSUmf+gLbtx/BpGQG/0CIQCGPlFxb0or8zxz
boWwCad7hYn0LhzOD/GU66xIUpcpdQIhAMnuecWiMhn+jkBMfhZlkieOOjaSSu9G
QBFIA5/2HlXY
-----END PRIVATE KEY-----';
    /**
     * 生成一对公私密钥 成功返回 公私密钥数组 失败 返回 false
     */

    static public function createKey() {
        $res = openssl_pkey_new();
        if($res == false) return false;
        openssl_pkey_export($res, $private_key);
        $public_key = openssl_pkey_get_details($res);
        return array('public_key'=>$public_key["key"],'private_key'=>$private_key);
    }


    /**
     * 私钥签名
     * $data    签名数据(需要先排序，然后拼接)
     * 签名用商户私钥，必须是没有经过pkcs8转换的私钥
     * 最后的签名，需要用base64编码
     * return Sign签名
     */
    static public function priSign($data) {
        if(is_array($data)){
            ksort($data);
            $data = http_build_query($data);
        }

        $priKey = self::PRIVATE_KEY;
        //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);

        //调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res,OPENSSL_ALGO_MD5);

        //释放资源
        openssl_free_key($res);

        //base64编码
        $sign = base64_encode($sign);

        return $sign;
    }

    /********************************************************************************/

    /**
     * 用公钥验签
     * $data    待签名数据(需要先排序，然后拼接)
     * $sign    需要验签的签名,需要base64_decode解码
     * 验签用连连支付公钥
     * return 验签是否通过 bool值
     */
    static public function pubVerifyPri($data)  {
        if(is_array($data)){
            if(isset($data['sign'])) $sign=$data['sign'];unset($data['sign']);
            ksort($data);
            $data = http_build_query($data);
        }
        //转换为openssl格式密钥
        $res = openssl_get_publickey(self::PUBKEY_KEY);
        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($data, base64_decode($sign), $res,OPENSSL_ALGO_MD5);
        //释放资源
        openssl_free_key($res);
        //返回资源是否成功，为true表示验通过
        return $result;
    }

}

