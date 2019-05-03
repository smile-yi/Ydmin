<?php
/**
 * GameController.php
 * 游戏控制器
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-09-11 17:57:17
 */

namespace App\Http\Controllers\Admin;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Exceptions\NormalException;
use App\Utils\ArrayUtil;
use App\Utils\Upload;
use App\Http\Resources\Game as GameRe;

class GameController extends Controller {

    /**
     * 列表
     * @param   $page 
     * @param   $limit 
     * @return  list
     */
    function list(Request $request){
        $where = $request->input('where', []);
        $page  = $request->input('page', 1);
        $pageInfo = [
            'limit' => $request->input('limit', config('app.page.limit'))
        ];

        $list = Game::list($where, $page, $pageInfo)->get();

        return response()->api([
            'list' => GameRe::collection($list),
            'page_info' => $pageInfo
        ]);
    }

    /**
     * 添加
     * @param   $name 游戏名
     * @param   $faq_tpl 游戏模板
     * @return  info [<description>]
     */
    function add(Request $request){
        if(!$request->filled('name')){
            throw new NormalException(603, 'name');
        }

        $game = Game::create([
            'name' => $request->input('name'),
            'faq_tpl' => $request->input('faq_tpl', Game::DEFAULT_FAQ_TPL)
        ]);

        return response()->api([
            'info' => $game
        ]);
    }

    /**
     * 修改
     * @param   $id 游戏ID
     * @param   $info 游戏信息
     * @return  boolean [<description>]
     */
    function update(Request $request){
        if(!$request->filled(['id', 'info'])){
            throw new NormalException(603, 'id|info');
        }

        $info = ArrayUtil::leach($request->input('info'), ['name', 'faq_tpl', 'status']);
        $result = Game::where([
            'id' => $request->input('id'),
        ])->update($info);
        if($result < 1){
            throw new NormalException(701);
        }

        return response()->api([
            'result' => 1
        ]);
    }

    /**
     * 上传问答模版
     * @param   $_FILE 
     * @return  info [<description>]
     */
    function uploadFaqTpl(Request $request){
        $upload = new Upload([
            'save_path' => config('view.paths.0') . '/upload',
            'file_exts' => ['php'],
            'save_ext' => 'blade.php'
        ]);

        $info = current($upload->upload());

        return response()->api([
            'info' => ['tpl_name' => 'upload.' . date('Y-m-d') . '.' . $info['save_name']]
        ]);
    }

    /**
     * 下载问答模板
     * @param   $id 
     * @return  file [<description>]
     */
    function downloadFaqTpl(Request $request){
        $faqTpl = Game::DEFAULT_FAQ_TPL;
        if($request->input('id')){
            $game = Game::where([
                'id' => $request->input('id'),
                'status' => Game::STATUS_NORMAL
            ])->first();
            if($game){
                $faqTpl = $game->faq_tpl;
            }
        }

        $faqTpl = str_replace('.', '/', $faqTpl);

        return response()->download(config('view.paths.0') . '/' . $faqTpl . '.blade.php');
    }
}
