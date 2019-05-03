<?php
/**
 * UserController.php
 * 后台用户控制器
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-10-18
 */

namespace App\Http\Controllers\Admin\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\User;
use App\Models\Admin\UserGroup;
use App\Utils\ArrayUtil;
use App\Exceptions\NormalException;

class UserController extends Controller {

    /**
     * 列表获取
     * @param   $condition 
     * @param   $page
     * @return  list and page_html
     */
    function list(Request $request){
        $where  = $request->input('condition', []);
        $page   = $request->input('page', 1);
        $pageInfo   = true;

        $list   = User::list($where, $page, $pageInfo)->get();
        $list   = User::dealColumn($list->toArray(), true);
        return response()->api([
            'list' => $list, 
            'page_info' => $pageInfo
        ]);
    }

    /**
     * 用户添加
     * @param   $username
     * @param   $password
     * @return  $info
     */
    function add(Request $request){
        if(!$request->filled(['username', 'password'])){
            throw new NormalException(603, 'username|password');
        }

        $user   = User::register(
            $request->input('username'),
            $request->input('password'),
            $request->all()
        );

        $user   = User::dealColumn($user->toArray(), false);

        return response()->api(['info' => $user]);
    }
    
    /**
     * 用户详情
     * @param   $id
     * @return  $info
     */
    function detail(Request $request){
        if(!$request->filled('id')){
            throw new NormalException(603, 'id');
        }

        $user   = User::find($request->input('id'));
        if(!$user){
            throw new NormalException(805, $request->input('id'));
        }

        return response()->api(['info' => $user]);
    }

    /**
     * 用户编辑
     * @param   $id
     * @param   $info
     * @return  boolean
     */
    function update(Request $request){
        if(!$request->filled(['id', 'info'])){
            throw new NormalException(603, 'id|info');
        }

        if($request->input('id') == 1){
            throw new NormalException(807);
        }
        $info   = ArrayUtil::fetchValues(
            $request->input('info'),
            ['nickname', 'truename', 'mobile', 'email', 'status'],
            true
        );

        $result     = User::where(['id' => $request->input('id')])->update($info);
        if($result <= 0){
            throw new NormalException(804);
        }

        return response()->api();
        
    }

    /**
     * 编辑用户组
     * @param   $id
     * @param   $group_ids
     * @return  boolean
     */
    function updateGroups(Request $request){
        if(!$request->filled(['id', 'group_ids'])){
            throw new NormalException(603, 'id|group_ids');
        }
        
        DB::beginTransaction();
        try {
            //删除原有组信息
            UserGroup::where(['user_id' => $request->input('id')])->delete();

            //添加组信息
            $data   = [];
            foreach($request->input('group_ids') as $groupId){
                $data[]     = [
                    'user_id'   => $request->input('id'),
                    'group_id'  => $groupId
                ];
            }
            UserGroup::insert($data);

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return response()->api();
    }
}