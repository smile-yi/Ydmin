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
use App\Exceptions\NormalException;
use SmileYi\Utils\Common;
use SmileYi\Utils\Format;

class User extends Base {

    protected $table    = 'ad_user';
    protected $guarded  = [];

    const STATUS_NORMAL = 1;
    const STATUS_FORBID = 0;
    const STATUS_DELETE = -1;

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
            throw new NormalException(608);
        }

        $user   = self::create([
            'username'  => $username,
            'nickname'  => isset($info['nickname']) ? $info['nickname'] : $username,
            'truename'  => isset($info['truename']) ? $info['truename'] : null,
            'password'  => Common::md5($password),
            'status'    => self::STATUS_NORMAL,
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
        if($user->password != Common::md5($password)){
            throw new NormalException(605);
        }

        if(!Format::isPassword($newPass)){
            throw new NormalException(604, 'new_pass');
        }

        $user->password = Common::md5($newPass);
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

        if(!$user || $user->password != Common::md5($password)){
            throw new NormalException(605);
        }
        if($user->status != 1){
            throw new NormalException(607);
        }

        $user->token    = self::createToken();
        $user->token_deadline   = date('Y-m-d H:i:s', strtotime('+1 month'));
        $user->login_count++;
        $user->last_login_time  = date('Y-m-d H:i:s');
        $user->last_login_ip    = ip2long($_SERVER['REMOTE_ADDR']);
        $user->save();

        return $user;
    }

    static function createToken(){
        return strtoupper(md5(time().'.'.rand(10000, 99999)));
    }

    //关联组信息
    public function groups(){
        return $this->belongsToMany('App\Models\Admin\Group', 'ad_user_group')->where('status', 1);
    }
}
