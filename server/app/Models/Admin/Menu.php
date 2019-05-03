<?php
/**
 * Menu.php 
 * 菜单模型

 * @author  wangzhongyi <wangzy_smile@qq.com>
 * @date    2017-10-19
 */

namespace App\Models\Admin;
use App\Models\Base;
use App\Services\Admin\Auth;
use App\Exceptions\NormalException;

class Menu extends Base {

    protected $table    = 'ad_menu';
    protected $guarded  = [];
    
    /**
     * 树状格式整理
     * @param   $list
     * @return  $list
     */
    static function tidyToTree($list){
        foreach($list as &$item){
            $pid    = &$item['pid'];
            if($pid !== null){
                isset($list[$pid]) || $list[$pid] = [];
                $list[$pid]['child'][]  = &$item;
            }
        }
        
        return $list[0]['child'];
    }

    /**
     * 附加组状态
     * @param   $data
     * @param   $groupId
     * @param   $batch  = true
     * @return  $data
     */
    static function attachGroupInIt($data, $groupId, $batch = true){
        $group  = Group::where(['id' => $groupId])->first();
        if(!$group){
            throw new NormalException(902, $groupId);
        }
        $group->rule_ids || $group->rule_ids = [];
        
        if($batch){
            foreach($data as &$item){
                $item['group_in']   = in_array($item['id'], $group->rule_ids) ? 1 : 0;
            }
        }else{
            $data['group_in']   = in_array($data['id'], $group->rule_ids);
        }

        return $data;
    }

    /**
     * 附加用户状态
     * @param   $data
     * @param   $admin
     * @param   $batch  = true
     */
    static function attachUserInIt($data, User $admin, $batch = true){
        if($batch){
            foreach($data as &$item){
                $item['user_in']    = Auth::check($admin, $item['url']) ? 1 : 0;
            }
        }else{
            $data['user_in']    = Auth::check($admin, $item['url']) ? 1 : 0;
        }

        return $data;
    }

}
