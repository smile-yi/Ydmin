<?php
/**
 * NormalException.php
 * 常见错误
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-05-10
 */

namespace App\Exceptions;

class NormalException extends \Exception {

    const MAP_CODE  = [
        //通用
        '601' => 'Token参数无效或已被禁用',
        '602' => '短信验证码错误',
        '603' => '缺少必需参数',
        '604' => '参数格式错误',
        '605' => '密码格式错误',
        '606' => '位置操作指令',
        '607' => '链接已失效',
        '608' => '邮箱验证码错误',
        '609' => '图形验证码错误',

        '701' => '游戏条目未找到',
        '801' => '问答条目未找到',
        '802' => '问答条目更改失败',
    ];

    function __construct($code, $remark = ''){
        $message    = isset(self::MAP_CODE[$code]) ? self::MAP_CODE[$code] : '未知错误';
        if($remark)
            $message    = $message.'['.$remark.']';

        parent::__construct($message, $code);
    }
}
