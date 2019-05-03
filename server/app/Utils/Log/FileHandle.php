<?php
/**
 * FileHandle.php
 * 文件日志类
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-06-30 16:24:09
 */

namespace App\Utils\Log;
use App\Utils\Log;
use App\Utils\Common;

class FileHandle extends Log {

    private $logPath;

    function __construct($path = null){
        //文件保存路径设置
        $this->logPath = $path ? $path : dirname(__FILE__).'/../../../storage/logs/';
    }

    /**
     * 日志写入
     * @param   $name 
     * @param   $content
     * @return  boolean [<description>]
     */
    function put($name, $content){
        $logFile = $this->logPath.$name.'.'.date('Ymd').'.log';

        //格式化日志
        if(is_array($content) || is_object($content)){
            $content = $this->formatContent($content);
        }else{
            $content .= "\n";
        }

        file_put_contents($logFile, $content, FILE_APPEND);

        return true;
    }

    /**
     * 格式化日志
     * @param   $content
     * @return  $content
     */
    function formatContent($content){
        $content = parent::formatContent($content);
        $content = json_decode(json_encode($content), true);
        $content = Common::arrayToText($content);

        return $content."\n\n";
    } 

    /**
     * 日志读取
     * @param   $name
     * @return  text [<description>]
     */
    function get($name){
        $logFile = $this->logPath.$name.'.log';
        if(!file_exists($logFile)){
            return '';
        }

        return file_get_contents($logFile);
    }
}
