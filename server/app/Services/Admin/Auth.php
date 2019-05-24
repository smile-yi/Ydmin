<?php
/**
 * Auth.php 
 * 权限服务

 * @author  wangzhongyi <wangzy_smile@qq.com>
 * @date    2017-10-20
 */

namespace App\Services\Admin;
use Illuminate\Support\Facades\Cache;
use App\Models\Admin\{
    UserGroup, Group, Rule, User
};

class Auth {

    /**
     * 获取用户权限url列表
     * @param   User    $user
     * @return  $list of url
     */
    static function list(User $user){
        $cacheKey = 'admin:user:rules:' . $user->id;
        $list = Cache::get($cacheKey);
        if($list === null){
            $groups = $user->groups;
            $ruleIds = [];
            foreach($groups as $group){
                if ($group->status != Group::STATUS_NORMAL) {
                    continue;
                }
                $ruleIds = array_merge($ruleIds, $group->rule_ids);
            }
            $ruleIds = array_filter($ruleIds);
    
            $list = Rule::whereIn('id', $ruleIds)->where('status', Rule::STATUS_NORMAL)->pluck('url')->toArray();
            Cache::put($cacheKey, $list, 1);
        }    

        return $list;
    }

    /**
     * 检测用户权限
     * @param   User $user
     * @param   $url
     * @return  boolean
     */
    static function check(User $user, $url){
        if(!in_array($user->id, config('auth.admin.root_ids', []))){
            $rules  = self::list($user);
            return in_array($url, $rules);
        }

        return true;
    }
}
