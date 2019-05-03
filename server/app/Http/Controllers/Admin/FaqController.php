<?php
/**
 * FaqController.php
 * 问答控制器
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-09-11 17:13:03
 */

namespace App\Http\Controllers\Admin;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Game;
use App\Exceptions\NormalException;
use App\Utils\ArrayUtil;
use App\Http\Resources\Faq as FaqRe;

class FaqController extends Controller {

    /**
     * 列表
     * @param   $where 条件
     * @param   $page 
     * @param   $limit
     * @return  list
     */
    function list(Request $request){
        $where = ArrayUtil::leach(
            $request->input('where', []), 
            ['game_id', 'keyword', 'status']
        );
        // return response()->api([
        //     'where' => $where
        // ]);
        $page = $request->input('page', 1);
        $pageInfo = [
            'limit' => $request->input('limit', config('app.page.limit'))
        ];

        $list = Faq::list($where, $page, $pageInfo)->with('game')->orderBy('id', 'desc')->get();

        return response()->api([
            'list' => FaqRe::collection($list),
            'page_info' => $pageInfo
        ]);
    }

    /**
     * 添加
     * @param   $game_id 游戏ID
     * @param   $title 标题
     * @param   $description 描述
     * @param   $answer 答案
     * @param   $image 图片路径
     * @param   $author 作者
     * @return  info [<description>]
     */
    function add(Request $request){
        if(!$request->filled(['title', 'headline', 'description', 'answer', 'image', 'author', 'game_id'])){
            throw new NormalException(603, 'title|headline|description|answer|image|author|game_id');
        }

        //游戏模型获取
        $game = Game::where([
            'id' => $request->input('game_id'),
            'status' => Game::STATUS_NORMAL
        ])->first();
        if(!$game){
            throw new NormalException(701);
        }

        //faq添加
        $faq = new Faq();
        $faq->fill(ArrayUtil::leach($request->all(), [
            'title', 'headline', 'description', 'answer', 'image', 'author', 'game_id'
        ]));
        $faq->admin_id = $request->admin->id;
        $faq->save();

        //faq推送
        $faq->push();

        return response()->api([
            'info' => $faq
        ]);
    }

    /**
     * 更新
     * @param   $id 
     * @param   $info 信息
     * @return  info [<description>]
     */
    function update(Request $request){
        if(!$request->filled(['id', 'info'])){
            throw new NormalException(603, 'id|info');
        }

        $info = ArrayUtil::leach($request->input('info'), [
            'title', 'headline', 'game_id', 'description', 'answer', 'image', 'author', 'status'
        ]);
        //更新
        $result = Faq::where([
            'id' => $request->input('id')
        ])->update($info);
        if($result < 1){
            throw new NormalException(802);
        }

        return response()->api([
            'result' => 1
        ]);
    }

    /**
     * 推送
     * @param   $id 
     * @return  boolean [<description>]
     */
    function push(Request $request){
        if(!$request->filled('id')){
            throw new NormalException(603, 'id');
        }

        $faq = Faq::where([
            'id' => $request->input('id'),
            'status' => Faq::STATUS_NORMAL
        ])->first();
        if(!$faq){
            throw new NormalException(801);
        }

        $faq->push();

        return response()->api([
            'result' => 1
        ]);
    }
}
