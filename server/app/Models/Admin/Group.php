<?php
/** 
 * Group.php
 * 用户组模型
 * 
 * @author  wangzhongyi <wangzy_smile@qq.com>
 * @date    2017-10-19
 */

 namespace App\Models\Admin;
 use App\Models\Base;

 class Group extends Base {

    protected $table    = 'ad_group';
    protected $guarded  = [];
    protected $casts    = [
        'rule_ids'  => 'array'
    ];

    /**
     * 附加用户是否在组中
     * @param   $data
     * @param   $userId
     * @param   $batch
     */
    static function attachUserInIt($data, $userId, $batch = true){
        $userGroupIds   = UserGroup::where(['user_id' => $userId])->pluck('group_id')->toArray();
        
        if($batch){
            foreach($data as &$item){
                $item['user_in']  = in_array($item['id'], $userGroupIds) ? 1 : 0;
            }
        }else{
            $data['user_in']    = in_array($data['id'], $userGroupIds) ? 1 : 0;
        }

        return $data;
    }
    
    /**
     * 字段转义解析
     * @param   $data
     * @param   $batch  = true
     * @return  $data
     */
    static function dealColumn($data, $batch = true){
        $deal   = function($item){
            $item['status_text']    = ['禁用', '正常'][$item['status']];
            return $item;
        };

        if($batch){
            foreach($data as &$item){
                $item   = $deal($item);
            }
        }else{
            $data   = $deal($data);
        }

        return $data;
    }
 }
