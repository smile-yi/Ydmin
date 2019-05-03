<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    //封装请求模拟
    function request($url, $param = null){
        //环境参数添加
        $url    .= strpos($url, '?') < 0 ? '?system=phpunit' : '&system=phpunit';
        if($param){
            $res    = $this->json('POST', $url, $param);
        }else{
            $res    = $this->json('GET', $url);
        }

        return $res->getData();
    }
}
