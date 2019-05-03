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

    private static $authList;

    /**
     * 获取用户权限url列表
     * @param   User    $user
     * @return  $list of url
     */
    static function getListOfUser(User $user){
        $cache  = 'user_auth_list_'.$user->id;
        $list   = Cache::get($cache);
        if($list === null){
            $groups     = $user->groups;
            $rule_ids   = [];
            foreach($groups as $group){
                $rule_ids   = array_merge($rule_ids, $group->rule_ids);
            }
            $rule_ids   = array_filter($rule_ids);
    
            $list   = Rule::whereIn('id', $rule_ids)->where('status', 1)->pluck('url')->toArray();
            Cache::put($cache, $list, 1);
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
	return true;
        if(!in_array($user->id, config('auth.admin.root_ids', []))){
            $auths  = self::getListOfUser($user);
            return in_array($url, $auths);
        }

        return true;
    }

}
