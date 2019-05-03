<?php
/**
 * UserGroup.php
 * 用户组关联模型

 * @author  wangzhongyi <wangzy_smile@qq.com>
 * @date    2017-10-19
 */

namespace App\Models\Admin;
use App\Models\Base;

class UserGroup extends Base {

    protected $table    = 'ad_user_group';
    protected $guarded  = [];
    public $timestamps   = false;
}