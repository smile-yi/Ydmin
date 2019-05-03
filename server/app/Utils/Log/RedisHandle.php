<?php
/**
 * RedisHandle.php
 * redis日志类
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-06-30 16:16:44
 */

namespace App\Utils\Log;
use App\Utils\Log;
use Illuminate\Support\Facades\Redis;

class RedisHandle extends Log {

    private $redis;

    function __construct(){
        $this->redis = Redis::connection();
    }

    /**
     * 写入日志
     * @param   $name
     * @param   $content
     * @return  boolean [<description>]
     */
    function put($name, $content){
        $name = 'log:'.$name;
        $content = $this->formatContent($content);

        $this->redis->lpush($name, json_encode($content));

        return true;
    }

    /**
     * 获取日志
     * @param   $name
     * @param   $page 
     * @param   $count
     * @return  list [<description>]
     */
    function get($name, $page = 1, $count = 10){
        $name = 'log:'.$name;
        $page = $page <= 1 ? 1 : intval($page);
        $count = $count <= 1 ? 10 : intval($count);
        $start = ($page - 1) * $count;

        $list = $this->redis->lrange($name, $start, $start + $count);
        foreach($list as &$val){
            $val = json_decode($val, true);
        }

        return $list;
    }
}