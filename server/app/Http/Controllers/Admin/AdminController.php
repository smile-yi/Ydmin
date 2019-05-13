<?php
/**
 * AdminController.php
 * 管理员控制器
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-10-18
 */

namespace App\Http\Controllers\Admin;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use SmileYi\Utils\ArrTool;
use App\Models\Admin\User;
use App\Exceptions\NormalException;
use App\Http\Resources\Admin\User as UserRe;

class AdminController extends Controller {

    /**
     * 管理员登录
     * @param   $username
     * @param   $password
     * @return  $info
     */
    function login(Request $request){
        if(!$request->filled(['username', 'password'])){
            throw new NormalException(603, 'username|password');
        }

        $admin   = User::login(
            $request->input('username'),
            $request->input('password')
        );

        return response()->api(['info' => new UserRe($admin)]);
    }

    /**
     * 详情信息获取
     * @return  $info
     */
    function detail(Request $request){
        return response()->api(['info' => new UserRe($request->admin)]);
    }

    /**
     * 更改用户信息
     * @param   $info
     * @return  $info
     */
    function update(Request $request){
        if(!$request->filled('info')){
            throw new NormalException(603, 'info');
        }

        $info = ArrTool::leach($request->input('info'), [
            'nickname', 'truename', 'mobile', 'email'
        ], true);

        $admin  = $request->admin->fill($info);
        $admin->save();

        return response()->api(['info' => new UserRe($admin)]);
    }

    /**
     * 用户菜单列表
     * @return  $list
     */
    function menus(Request $request){
        
    }

    /**
     * 用户密码修改
     * @param   $password 原密码
     * @param   $new_pass 新密码
     * @return  boolean [<description>]
     */
    function repass(Request $request){
        if(!$request->filled(['password', 'new_pass'])){
            throw new \Exception('缺少必需参数[password:new_pass]', 603);
        }

        $request->admin     = User::repass(
            $request->admin,
            $request->input('password'),
            $request->input('new_pass')
        );

        return response()->api();
    }
}
