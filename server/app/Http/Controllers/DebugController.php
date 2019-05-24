<?php
/**
 * DebugController.php
 * 调制控制器
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-09-12 19:33:50
 */

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use SmileYi\Utils\Http;
use SmileYi\Utils\Upload;
use SmileYi\Utils\ArrTool;
use SmileYi\Utils\Format;
use App\Models\Admin\User as AdUser;
use App\Models\Admin\Group as AdGroup;

class DebugController extends Controller {

    //入口
    function index(Request $request){
        $user = AdUser::where('id', 5)->first();
        return response()->api([
            'list' => $user->groups()->where('status', AdGroup::STATUS_NORMAL)->get()
        ]);
    }

    //推送
    function push(){
    }

    //上传
    function upload(){
        $upload = new Upload([
            'save_path' => config('view.paths.0') . '/upload',
            'file_exts' => ['php'],
            'save_ext' => 'blade.php'
        ]);

        $info = current($upload->upload());

        return date('Y-m-d') . '.' . $info['save_name'];
    }
}
