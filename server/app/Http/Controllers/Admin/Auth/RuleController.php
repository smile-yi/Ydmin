<?php
/**
 * MenuController.php 
 * 权限、菜单控制器

 * @author  wangzhongyi <wangzy_smile@qq.com>
 * @date    2017-10-19
 */

namespace App\Http\Controllers\Admin\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SmileYi\Utils\ArrTool;
use App\Models\Admin\Rule;
use App\Models\Admin\User;
use App\Models\Admin\Group;
use App\Exceptions\NormalException;
use App\Services\Admin\Auth;

class RuleController extends Controller {

    /** 
     * 菜单添加
     * @param   $pid
     * @param   $name
     * @param   $url
     * @param   $is_menu 是否为菜单
     * @param   $icon
     * @return  info
     */
    function add(Request $request){
        if(!$request->filled(['name', 'url'])){
            throw new NormalException(603, 'name|url');
        }

        $rule = Rule::create([
            'pid' => $request->input('pid') ?? 0,
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'is_menu' => $request->input('is_menu') ?? 0,
            'icon' => $request->input('icon') ?? '',
            'status' => Rule::STATUS_NORMAL
        ]);

        return response()->api(['info' => $rule]);
    }

    /**
     * 列表获取
     * @param   $user_id
     * @param   $group_id
     * @param   $where 
     * @param   $format 格式
     * @return  list of tree
     */
    function list(Request $request){
        $where = $request->input('where', []);
        $page = $request->input('page', false);
        $pageInfo = [];

        $userId = $request->input('user_id', false);
        $groupId = $request->input('group_id', false);
        
        $list = Rule::list($where, $page, $pageInfo)->get();

        //用户权限附加
        if ($userId) {
            $user = User::where('id', $userId)->first();
            if (!$user) {
                throw new NormalException(621, 'aduser');
            }
            foreach ($list as $item) {
                $item->user_id = Auth::check($user, $item->url);
            }
        }

        //组权限附加
        if ($groupId) {
            $group = Group::where('id', $groupId)->first();
            if (!$group) {
                throw new NormalException(621, 'adgroup');
            }
            foreach ($list as $item) {
                $item->group_in = in_array($item->id, $group->rule_ids ?? []);
            }
        }

        //树形格式返回
        if ($request->input('format') == 'tree') {
            return response()->api(['list' => Rule::toTree($list)]);
        }

        return response()->api([
            'list' => $list->convert(), 
            'page_info' => $pageInfo
        ]);
    }

    /**
     * 信息编辑
     * @param   $id
     * @param   $info
     * @return  boolean
     */
    function update(Request $request){
        //必须存在
        if(!$request->filled(['id', 'info'])){
            throw new NormalException(603, 'id|info');
        }

        //若存在，不能为null
        if (ArrTool::existNull($request->input('info'), ['name', 'url', 'status'])) {
            throw new NormalException(610, 'info.name|info.url');
        }

        //status字段限定
        if ($request->has('info.status') 
            && !isset(Rule::MAP_STATUS[$request->input('info.status')])) {
            throw new NormalException(611, 'info.status');
        }

        //提取字段
        $info = ArrTool::leach($request->input('info'), [
            'pid', 'name', 'url', 'is_menu', 'status', 'icon'
        ]);
        if (empty($info)) {
            throw new NormalException(610, 'info');
        }

        //sql操作
        $result = Rule::where('id', $request->input('id'))->update($info);
        if ($result <= 0) {
            throw new NormalException(622, 'adrule');
        }

        return response()->api();
    }
}