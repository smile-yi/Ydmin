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

    const STATUS_NORMAL = 1;
    const STATUS_FORBID = 0;
    const STATUS_DELETE = -1;

    //关联规则信息
    function rules() {
        return Rule::whereIn('id', $this->rule_ids)->get();
    }
 }
