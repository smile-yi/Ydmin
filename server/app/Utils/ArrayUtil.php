<?php
/**
 * ArrayUtil.php
 * 数组处理工具
 * 
 * @author  wangzhongyi <wangzy_smile@qq.com>
 * @date    2017-10-12
 **/

namespace App\Utils;

class ArrayUtil {

    /**
     * 抓取数组指定值
     * @param   $array
     * @param   $keys
     * @param   $withKey    是否携带key
     * @param   $withInvalid    是否处理空值
     * @return  array
     */
    static function fetchValues(array $array, $keys, $withKey = true, $withInvalid = false){
        $keys   = (array)$keys;
        $values     = [];
        foreach($keys as $key){
            if(array_key_exists($key, $array)){
                $val    = $array[$key];
                if($withInvalid || ($val !== '' && $val !== null)){
                    $withKey && $values[$key] = $val;
                    $withKey || $values[] = $val;
                }
            }
        }

        return count($keys) == 1 && !$withKey ? $values[0] : $values;
    }

    /**
     * 批量抓取数组指定值
     * @param   $array
     * @param   $keys
     * @return  array
     */
    static function batchFetchValues(array $list, $keys, $withKey = false, $withInvalid = true){
        $values     = [];
        foreach($list as $array){
            $values[]   = self::fetchValues($array, $keys, $withKey);
        }

        return $values;
    }

    /**
     * 过滤数组
     * @param   $array 数组
     * @param   $columns 字段
     * @return  array [<description>]
     */
    static function leach(array $array, array $columns, $withNone = false, $default = null){
        $result     = [];
        if(is_array(reset($array))){
            foreach($array as $key => $val){
                $result[$key]   = self::_leachAction($val, $columns, $withNone, $default);
            }
        }else{
            $result     = self::_leachAction($array, $columns, $withNone, $default);
        }

        return $result;
    }

    static private function _leachAction(array $array, array $columns, $withNone, $default){
        $result     = [];
        foreach($array as $key => $val){
            foreach($columns as $column){
                if(isset($array[$column]) && $array[$column] !== ''){
                    $result[$column]    = $array[$column];
                }else{
                    if($withNone){
                        $result[$column]    = $default;
                    }
                }
            }
        }

        return $result;
    }
}
