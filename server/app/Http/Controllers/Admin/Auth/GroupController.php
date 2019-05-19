<?php
/**
 * GroupController.php 
 * 用户组控制器

 * @author  wangzhongyi <wangzy_smile@qq.com>
 * @date    2017-10-19
 */

namespace App\Http\Controllers\Admin\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Group;
use App\Models\Admin\UserGroup;
use App\Http\Resources\Auth\Group as GroupRe;
use App\Exceptions\NormalException;
use SmileYi\Utils\ArrTool;

class GroupController extends Controller {

    /**
     * 添加用户组
     * @param   $name
     * @param   $decipt
     * @param   $rule_ids
     * @return  $group
     */
    function add(Request $request){
        if(!$request->filled('name')){
            throw new NormalException(603, 'name');
        }

        $group  = Group::create([
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
            'rule_ids' => $request->input('rule_ids'),
            'status' => Group::STATUS_NORMAL
        ]);

        return response()->api(['info' => $group]);
    }

    /**
     * 列表信息获取
     * @param   $user_id   附加用户是否存在
     * @param   $page
     * @return  list and page_html
     */
    function list(Request $request){
        $userId = $request->input('user_id', false);
        $page = $request->input('page', false);
        $pageInfo = true;

        $list   = Group::list([], $page, $pageInfo)->get();
        // 判断用户是否在组中
        if ($userId) {
            $userGroupIds = UserGroup::where('user_id', $userId)->pluck('group_id')->toArray();
            foreach ($list as $item) {
                if (in_array($item->id, $userGroupIds)) {
                    $item->user_in = 1;
                } else {
                    $item->user_id = 0;
                }
            }
        }

        return response()->api([
            'list'  => GroupRe::collection($list),
            'page_info' => $pageInfo
        ]);
    }

    /**
     * 编辑 (包括编辑权限)
     * @param   $id
     * @param   $info
     * @return  boolean
     */
    function update(Request $request){
        if(!$request->filled(['id', 'info'])){
            throw new NormalException(603, 'id|info');
        }

        $info   = ArrTool::leach($request->input('info'), [
            'name', 'desc', 'status', 'rule_ids'
        ], true);

        //格式化权限信息
        isset($info['rule_ids']) && $info['rule_ids'] = json_encode($info['rule_ids']);
        $result = Group::where(['id' => $request->input('id')])->update($info);
        if($result <= 0){
            throw new NormalException(622);
        }

        return response()->api();
    }
}