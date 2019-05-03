<?php
/**
 * Log.php
 * 日志工具
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-09-21
 */

namespace App\Utils;
use App\Utils\Log\RedisHandle;
use App\Utils\Log\FileHandle;
use App\Exceptions\NormalException;
use Illuminate\Support\Facades\Redis;

class Log {

    private static $drivers = [];

    //单例获取
    static function getInstance($driver = 'redis'){
        if(!isset(self::$drivers[$driver])){
            switch($driver){
                case 'redis':
                    self::$drivers[$driver] = new RedisHandle();
                break;
                case 'file':
                    self::$drivers[$driver] = new FileHandle();
                break;
                default:
                    throw new NormalException(612);
                break;
            }
        }

        return self::$drivers[$driver];
    }

    /**
     * 格式化日志内容
     * @param   $content 
     * @return  content [<description>]
     */
    function formatContent($content){
        is_string($content) && $content = ['content' => $content];
        $content = array_merge([
            'datetime' => date('Y-m-d H:i:s')
        ], $content);

        return $content;
    }    
}
