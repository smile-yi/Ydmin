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

class DebugController extends Controller {

    //入口
    function index(Request $request){
        return $request->input('name');
        return $request->existNull('name') ? 'Yes' : 'No';
        if ($request->has(['info.name']) && !$request->filled(['info.name'])) {
            return 'Error';
        }
        return 'Success';
        var_dump(data_get(['name' => ''], 'name'));
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
