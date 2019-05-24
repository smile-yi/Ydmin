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
use SmileYi\Utils\ArrTool;
use SmileYi\Utils\Format;
use App\Models\Admin\User;
use App\Models\Admin\UserGroup;
use App\Exceptions\NormalException;

class UserController extends Controller {

    /**
     * 列表获取
     * @param   $condition 
     * @param   $page
     * @return  list and page_html
     */
    function list(Request $request){
        $where = $request->input('where', []);
        $page = $request->input('page', 1);
        $pageInfo = true;

        $list   = User::list($where, $page, $pageInfo)->get();

        return response()->api([
            'list' => $list->convert(),
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

        //用户注册
        $user = User::register(
            $request->input('username'),
            $request->input('password'),
            $request->all()
        );

        return response()->api(['info' => $user->convert()]);
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

        $user = User::find($request->input('id'));
        if(!$user){
            throw new NormalException(621, 'aduser:' . $request->input('id'));
        }

        return response()->api(['info' => $user->convert()]);
    }

    /**
     * 用户编辑
     * @param   $id
     * @param   $info
     * @return  boolean
     */
    function update(Request $request){
        if (!$request->filled(['id', 'info'])) {
            throw new NormalException(603, 'id|info');
        }

        //根用户不能修改
        if (in_array($request->input('id'), config('auth.admin.root_ids'))) {
            throw new NormalException(609);
        }

        //status字段限定
        if ($request->has('info.status') 
            && !isset(Group::MAP_STATUS[$request->input('info.status')])) {
            throw new NormalException(611, 'info.status');
        }
        
        $info = ArrTool::leach($request->input('info'), [
            'nickname', 'truename', 'mobile', 'email', 'status'
        ]);
        if (empty($info)) {
            throw new NormalException(610, 'info');
        }

        $result = User::where(['id' => $request->input('id')])->update($info);
        if($result <= 0){
            throw new NormalException(622, 'aduser');
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
            $data = [];
            foreach($request->input('group_ids') as $groupId){
                if (!Format::isInteger($group)) {
                    throw new NormalException(604, 'group_ids');
                }
                $data[] = [
                    'user_id' => $request->input('id'),
                    'group_id' => $groupId
                ];
            }
            if (empty($data)) {
                throw new NormalException(610, 'group_ids');
            }
            UserGroup::insert($data);

            DB::commit();
        }catch(\Exception $E){
            DB::rollBack();
            throw $E;
        }

        return response()->api();
    }
}