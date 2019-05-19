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
use App\Exceptions\NormalException;

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
            'pid' => $request->input('pid', 0),
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'is_menu' => $request->input('is_menu', 0),
            'icon' => $request->input('icon', ''),
            'status' => Rule::STATUS_NORMAL
        ]);

        return response()->api(['info' => $rule]);
    }

    /**
     * 列表获取
     * @param   $user_id
     * @param   $group_id
     * @param   $where 
     * @return  list of tree
     */
    function list(Request $request){
        $where = $request->input('where', []);
        $page = $request->input('page', false);
        $pageInfo = true;

        $userId = $request->input('user_id', false);
        $groupId = $request->input('group_id', false);
        
        $list = Rule::list($where, $page, $pageInfo)->get()->keyBy('id')->toArray();

        return response()->api(['list' => Rule::toTree($list)]);
    }

    /**
     * 信息编辑
     * @param   $id
     * @param   $info
     * @return  boolean
     */
    function update(Request $request){
        if(!$request->filled(['id', 'info'])){
            throw new NormalException(603, 'id|info');
        }
        $info   = ArrayUtil::leach($request->input('info'), [
            'pid', 'name', 'url', 'show', 'status', 'icon'
        ]);

        DB::beginTransaction();
        try {
            //菜单信息修改
            $menu   = Menu::find($request->input('id'));
            $menu->fill($info);
            $menu->save();

            //权限信息修改
            $info   = ArrayUtil::fetchValues($info, ['name', 'url', 'status'], true);
            Rule::where('id', $menu->rule_id)->update($info);

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return response()->api();
    }
}