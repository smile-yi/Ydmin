<?php
/**
 * User.php
 * 后台用户模型
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-10-18
 */

namespace App\Models\Admin;
use App\Models\Base;
use App\Utils\Common;
use App\Utils\Format;
use App\Exceptions\NormalException;

class User extends Base {

    protected $table    = 'ad_user';
    protected $guarded  = [];

    /**
     * 注册用户
     * @param   $username
     * @param   $password
     * @param   $info   用户其它信息
     * @return  $user
     */
    static function register($username, $password, $info = []){
        $isExt  = self::where(['username' => $username])->first();
        if($isExt){
            throw new NormalException(901);
        }

        $user   = self::create([
            'username'  => $username,
            'nickname'  => isset($info['nickname']) ? $info['nickname'] : $username,
            'truename'  => isset($info['truename']) ? $info['truename'] : null,
            'password'  => Common::md5pwd($password),
            'status'    => 1
        ]);

        $user->save();

        return $user;
    }

    /**
     * 修改密码
     * @param   $user
     * @param   $password 原密码
     * @param   $newPass 新密码
     * @return  $user [<description>]
     */
    static function repass($user, $password, $newPass){
        if($user->password != Common::md5pwd($password)){
            throw new NormalException(801);
        }

        if(!Format::isPassword($newPass)){
            throw new NormalException(605);
        }

        $user->password     = Common::md5pwd($newPass);
        $user->save();

        return $user;
    }

    /**
     * 用户登录
     * @param   $username
     * @param   $password
     * @return  $user
     */
    static function login($username, $password){
        $user   = self::where(['username' => $username])->first();

        if(!$user || $user->password != Common::md5pwd($password)){
            throw new NormalException(801);
        }
        if($user->status != 1){
            throw new NormalException(802);
        }

        $user->token    = self::createToken();
        $user->token_deadline   = date('Y-m-d H:i:s', strtotime('+1 month'));
        $user->login_count++;
        $user->last_login_time  = date('Y-m-d H:i:s');
        $user->last_login_ip    = ip2long($_SERVER['REMOTE_ADDR']);
        $user->save();

        return $user;
    }

	/** 
     * 查询条件设置
     * @param   $where
     * @param   $query 
     * @return  $query
     */
    static function setWhere($where, $query = null){
        if($query){
            return $query->where($where)->where('status', '!=', -1);
        }else{
            return static::where($where)->where('status', '!=', -1);
        }   
    }

    /**
     * 字段转义处理
     * @param   $data
     * @param   $batch
     * @return  $data
     */
    static function dealColumn($data, $batch = true){
        $deal   = function($item) {
            $item['status_text']    = ['禁用', '正常'][$item['status']];
            isset($item['last_login_ip']) && $item['last_login_ip']  = long2ip($item['last_login_ip']);
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

    static function createToken(){
        return strtoupper(md5(time().'.'.rand(10000, 99999)));
    }

    //关联组信息
    public function groups(){
        return $this->belongsToMany('App\Models\Admin\Group', 'ad_user_group')->where('status', 1);
    }
}
