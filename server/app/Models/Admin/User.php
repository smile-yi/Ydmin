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
use SmileYi\Utils\ArrTool;

class User extends Base {

    protected $table    = 'ad_user';
    protected $guarded  = [];

    //默认头像
    const DEFAULT_AVATAR = 'http://default.smileyi.cn/resource/avatar.jpg';

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
            'mobile' => $info['mobile'] ?? null,
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
    function groups(){
        return $this->belongsToMany(
            'App\Models\Admin\Group', 'ad_user_group'
        )->where('status', '!=', Group::STATUS_DELETE);
    }

    /**
     * 字段转义
     * @param   $level 标识
     * @return  array [<description>]
     */
    function convert($level = 1) {
        $item = parent::convert($level);
        if (isset($item['last_login_ip'])){
            $item['last_login_ip'] = long2ip(intval($item['last_login_ip']));
        }
        $item['avatar'] = $item['avatar'] ?? self::DEFAULT_AVATAR;

        //根据隔离级别隐藏字段
        if ($level == 1) {
            unset($item['password'], $item['mobile'], $item['token'], $item['token_deadline']);
        }
        if ($level == 2) {
            unset($item['password']);
        }

        return $item;
    }
}
