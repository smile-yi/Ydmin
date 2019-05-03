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
use App\Utils\ArrayUtil;
use App\Exceptions\NormalException;

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
            'name'  => $request->input('name'),
            'depict'    => $request->input('depict'),
            'rule_ids'  => $request->input('rule_ids'),
            'status'    => 1
        ]);

        return response()->api(['info' => $group]);
    }

    /**
     * 列表信息获取
     * @param   $user_id   附加用户是否存在与改组中
     * @param   $page
     * @return  list and page_html
     */
    function list(Request $request){
        $userId     = $request->input('user_id');
        $page   = $request->input('page', false);
        $pageInfo   = true;

        $list   = Group::list([], $page, $pageInfo)->get()->toArray();
        $userId && $list = Group::attachUserInIt($list, $userId, true);
        $list   = Group::dealColumn($list, true);

        return response()->api([
            'list'  => $list,
            'page_info' => $pageInfo
        ]);
    }

    /**
     * 编辑
     * @param   $id
     * @param   $info
     * @return  boolean
     */
    function update(Request $request){
        if(!$request->filled(['id', 'info'])){
            throw new NormalException(603, 'id|info');
        }

        $info   = ArrayUtil::fetchValues(
            $request->input('info'),
            ['name', 'depict', 'status'],
            true
        );
        isset($info['rule_ids']) && $info['rule_ids'] = json_encode($info['rule_ids']);
        $result     = Group::where(['id' => $request->input('id')])->update($info);
        if($result <= 0){
            throw new NormalException(903);
        }

        return response()->api();
    }

    /** 
     * 更改权限
     * @param   $id
     * @param   $rule_ids
     * @return  boolean
     */
    function updateRules(Request $request){
        if(!$request->filled(['id', 'rule_ids'])){
            throw new NormalException(603, 'id|rule_ids');
        }

        $result     = Group::where(['id' => $request->input('id')])->update([
            'rule_ids'  => json_encode($request->input('rule_ids'))
        ]);

        if($result <= 0){
            throw new \Exception(906);
        }

        return response()->api();
    }
}