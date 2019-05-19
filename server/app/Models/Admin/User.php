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
use SmileYi\Utils\Token;

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
        $isExt  = static::where(['username' => $username])->first();
        if($isExt){
            throw new NormalException(608);
        }

        $user = static::create([
            'username' => $username,
            'nickname' => $info['nickname'] ?? $username,
            'truename' => $info['truename'] ?? null,
            'email' => $info['email'] ?? null, 
            'password' => Common::md5($password),
            'status' => static::STATUS_NORMAL,
        ]);

        $user->save();

        return $user;
    }


    /**
     * 修改密码
     * @param   $pwdOld 原密码
     * @param   $pwdNew 新密码
     * @return  null [<description>]
     */
    function repwd($pwdOld, $pwdNew) {
        if ($this->password != Common::md5($pwdOld)) {
            throw new NormalException(605);
        }

        if (!Format::isPassword($pwdNew)) {
            throw new NormalException(604);
        }

        $this->password = Common::md5($pwdNew);
        $this->save();
    }

    /**
     * 用户登录
     * @param   $username
     * @param   $password
     * @return  $user
     */
    static function login($username, $password){
        $user = User::where(['username' => $username])->first();
        // var_dump( $user);exit;
        if(!$user || $user->password != Common::md5($password)){
            throw new NormalException(605);
        }
        if($user->status != static::STATUS_NORMAL){
            throw new NormalException(607);
        }

        $user->fill([
            'token' => Token::create(),
            'token_deadline' => date('Y-m-d H:i:s', strtotime('+1 month')),
            'login_count' => $user->login_count + 1,
            'last_login_time' => date('Y-m-d H:i:s'),
            'last_login_ip' => Common::getClientIp(true)
        ]);
        $user->save();

        return $user;
    }

    //关联组信息
    public function groups(){
        return $this->belongsToMany('App\Models\Admin\Group', 'ad_user_group')->where('status', Group::STATUS_NORMAL);
    }
}
