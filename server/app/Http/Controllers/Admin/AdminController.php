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
use SmileYi\Utils\Common;
use App\Models\Admin\User;
use App\Models\Admin\Rule;
use App\Exceptions\NormalException;
use App\Services\Admin\Auth;

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

        $admin = User::login(
            $request->input('username'),
            $request->input('password')
        );

        return response()->api(['info' => $admin->convert(2)]);
    }

    /**
     * 详情信息获取
     * @return  $info
     */
    function detail(Request $request){
        return response()->api(['info' => $request->admin->convert(2)]);
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

        //过滤字段
        $info = ArrTool::leach($request->input('info'), [
            'nickname', 'truename', 'mobile', 'email'
        ]);
        if (empty($info)) {
            throw new NormalException(610, 'info');
        }

        $admin  = $request->admin->fill($info);
        $admin->save();

        return response()->api(['info' => $admin->convert(2)]);
    }

    /**
     * 用户菜单列表
     * @return  $list
     */
    function menus(Request $request){
        $admin = $request->admin;

        $list = Rule::where([
            'is_menu' => 1,
            'status' => Rule::STATUS_NORMAL
        ])->get();
        
        //过滤无权限菜单
        $list = $list->filter(function($item) use ($admin){
            return Auth::check($admin, $item->url);
        });

        return response()->api(['list' => Rule::toTree($list)]);
    }

    /**
     * 用户密码修改
     * @param   $pwd_old 原密码
     * @param   $pwd_new 新密码
     * @return  boolean [<description>]
     */
    function repwd(Request $request){
        if(!$request->filled(['pwd_old', 'pwd_new'])){
            throw new NormalException(603, 'pwd_old|pwd_new');
        }

        $request->admin->repwd(
            $request->input('pwd_old'),
            $request->input('pwd_new')
        );

        return response()->api();
    }
}
