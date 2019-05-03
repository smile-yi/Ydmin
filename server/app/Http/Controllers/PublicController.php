<?php
/**
 * PublicController.php
 * 公共控制器
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-09-11 18:08:05
 */

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Utils\Upload;

class PublicController extends Controller {

    /**
     * 上传文件
     * @param   $_FILE 
     * @return  info [<description>]
     */
    function upload(Request $request){
        $upload = new Upload();
        $list = $upload->upload();

        switch($request->input('type')){
            case 'we3':
                return ['errno' => 0, 'data' => [
                        reset($list)['url']
                    ]
                ];
            break;
        }

        return response()->api([
            'list' => $list
        ]);
    }
}
