<?php
/**
 * Rule.php 
 * 权限模型

 * @author  wangzhongyi <wangzy_smile@qq.com>
 * @date    2017-10-19
 */

namespace App\Models\Admin;
use App\Models\Base;

class Rule extends Base {

    protected $table    = 'ad_rule';
    protected $guarded  = [];

    /**
     * 菜单转化为树形结构
     * @param   $list
     * @return tree
     */
    static function toTree($list) {
        $list = $list->keyBy('id')->convert();
        foreach ($list as &$item) {
            if (!isset($item['pid'])) {
                continue;
            }
            $list[$item['pid']]['childs'][] = &$item;
        }
        
        return $list[0] ?? [];
    }
}