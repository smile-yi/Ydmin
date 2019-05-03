<?php
/**
 * Http.php
 * 网络请求类
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-08-11 12:34:45
 */

namespace App\Utils;

class Http {

    /**
     * post请求
     * @param   $url 地址
     * @param   $param
     * @return  result
     */
    static function post($url, $params, $cert = false){
        $ch = curl_init(); 

        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //导入证书
        if($cert){
            curl_setopt($ch,CURLOPT_SSLCERT, $cert['ssl_cert']);
            curl_setopt($ch,CURLOPT_SSLKEY,  $cert['ssl_key']);
            curl_setopt($ch,CURLOPT_CAINFO,  $cert['ca_info']);
        }

        $info = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);

        $log = Log::getInstance('file');
        $log->put('other_api', [
            'url' => $url,
            'method' => 'post',
            'params' => $params,
            'response' => $info
        ]);

        return $info;
    }
} 
