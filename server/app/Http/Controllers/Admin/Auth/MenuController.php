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
use App\Models\Admin\Rule;
use App\Models\Admin\Menu;
use App\Utils\ArrayUtil;
use App\Exceptions\NormalException;

class MenuController extends Controller {

    /** 
     * 菜单添加
     * @param   $pid
     * @param   $name
     * @param   $url
     * @param   $show
     * @param   $icon
     * @return  $menu
     */
    function add(Request $request){
        if(!$request->filled(['name', 'url'])){
            throw new NormalException(603, 'name|url');
        }

        DB::beginTransaction();
        try {
            //创建权限
            $rule   = Rule::create([
                'name'  => $request->input('name'),
                'url'  => $request->input('url'),
                'status'    => 1
            ]);

            //创建菜单
            $menu   = Menu::create([
                'pid'   => $request->input('pid', 0),
                'name'  => $request->input('name'),
                'url'   => $request->input('url'),
                'rule_id'   => $rule->id,
                'show'  => $request->input('show', 1),
                'icon'  => $request->input('icon'),
                'status'    => 1
            ]);

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return response()->api(['info' => $menu]);
    }

    /**
     * 列表获取
     * @param   $group_id
     * @return  list of tree
     */
    function list(Request $request){
        $groupId    = $request->input('group_id');
        
        $list   = Menu::where('status', '!=', -1)->get()->keyBy('id')->toArray();
        $groupId && $list = Menu::attachGroupInIt($list, $groupId, true);
        $list   = Menu::tidyToTree($list);

        return response()->api(['list' => $list]);
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