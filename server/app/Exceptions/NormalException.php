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
        '601' => 'Token参数无效',
        '602' => '短信验证码错误',
        '603' => '缺少必需参数',
        '604' => '参数格式错误',
        '605' => '密码错误',
        '606' => '用户不存在',
        '607' => '用户已被禁用',
        '608' => '用户名已存在',
        '609' => '无操作权限',

        '621' => '条目不存在',
        '622' => '条目修改失败',
    ];

    function __construct(int $code, string $remark = ''){
        $message    = isset(self::MAP_CODE[$code]) ? self::MAP_CODE[$code] : '未知错误';
        if($remark){
            $message    = $message.'['.$remark.']';
        }

        parent::__construct($message, $code);
    }
}
