<?php
/**
 * VerifyCode.php
 * 图形验证码工具
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-08-25 22:50:44
 */

namespace App\Utils;
use Illuminate\Support\Facades\Cache;
use Gregwar\Captcha\CaptchaBuilder;

class ImageCode {

    /**
     * 生成验证码
     * @param   $key 
     * @return  image
     */
    static function create($key){
        $image = new CaptchaBuilder();
        $image->build($width = 100, $height = 40, $font = null);
        $text = $image->getPhrase();

        //缓存记录内容
        Cache::put('image_code_'.$key, $text, 10);

        return $image->get();
    }

    /**
     * 验证
     * @param   $key 
     * @param   $text [<description>]
     * @return  boolean [<description>]
     */
    static function verify($key, $text){
        if(\App::environment('local')){
            return true;
        }
        if(!$key){
            return false;
        }
        $right = Cache::get('image_code_'.$key);
        if(!$right){
            return false;
        }
        if($right == $text){
            Cache::forget('image_code_'.$key);
            return true;
        }
        return false;
    }
}
