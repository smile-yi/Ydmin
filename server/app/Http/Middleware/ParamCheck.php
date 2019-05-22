<?php

namespace App\Http\Middleware;
use App\Exceptions\NormalException;
use App\Utils\Format;
use App\Utils\Sms;
use App\Utils\EmailCode;
use App\Utils\ImageCode;
use Closure;

class ParamCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        //参数格式检测
        $params = $request->all();
        $this->format($params);

        //图形图形验证码检测
        if($request->filled(['image_code_key', 'image_code'])){
            if(!ImageCode::verify($request->input('image_code_key'), $request->input('image_code'))){
                throw new NormalException(609);
            }
        }

        return $next($request);
    }

    /**
     * 参数格式检测
     * @param   $params 参数列表
     * @return  boolean [<description>]
     */
    function format($params, $wrapKey = ''){
        $paramIntegers  = ['id'];
        $paramNumbers   = [];
        $paramUrls      = ['image'];
        $paramArrays    = ['info', 'where'];

        $wrapKey and $wrapKey .= '.';

        foreach($params as $key => $val){
            if(!$val){
                continue;
            }

            //类型检测
            if(in_array($key, $paramIntegers) and !Format::isInteger($val)){
                throw new NormalException(604, $wrapKey.$key);
            }else if(in_array($key, $paramNumbers) and !is_numeric($val)){
                throw new NormalException(604, $wrapKey.$key);
            }else if(in_array($key, $paramUrls) and !Format::isUrl($val)){
                throw new NormalException(604, $wrapKey.$key);
            }else if(in_array($key, $paramArrays)){
                if(!is_array($val)){
                    throw new NormalException(604, $wrapKey.$key);
                }
                $this->format($val, $wrapKey.$key);
            }

            //指定值检测
            if(in_array($key, ['mobile', 'phone']) and !Format::isMobile($val)){
                throw new NormalException(604, $wrapKey.$key);
            }else if($key == 'id_card' and !Format::isIdCard($val)){
                throw new NormalException(604, $wrapKey.$key);
            }else if($key == 'username' && !Format::isUsername($val)){
                throw new NormalException(604, $wrapKey.$key);
            }else if($key == 'password' && !Format::isPassword($val)){
                throw new NormalException(604, $wrapKey.$key);
            }
        }
    }
}
