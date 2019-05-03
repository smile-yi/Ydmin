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
use App\Utils\Http;
use App\Utils\Upload;

class DebugController extends Controller {

    //入口
    function index(Request $request){
        return response()->api([
            'result' => $this->push()
        ]);
    }

    //推送
    function push(){
        return Http::post(
            'http://data.zz.baidu.com/urls?appid=1595441886555530&token=q578s6atGrtBNSqt&type=homepage',
            'http://faq.uuu9.com/faq/show/4.html'
        );
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
