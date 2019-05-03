<?php
/**
 * GameController.php
 * 游戏控制器
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-09-17 20:33:04
 */

namespace App\Http\Controllers\App;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Game;
use App\Models\Faq;
use App\Exceptions\NormalException;

class GameController extends Controller {

    //游戏问答首页
    function showListFaq(Request $request, $id){
        $game = Game::where([
            'id' => $id,
            'status' => Game::STATUS_NORMAL
        ])->first();
        if(!$game){
            throw new NormalException(701);
        }

        $page = $request->input('page', 1);
        $pageInfo = true;

        $faqs = Faq::list(['game_id' => $game->id], $page, $pageInfo)->orderBy('id', 'desc')->get();

        return view('game.list_faq', [
            'faqs' => $faqs,
            'page_info' => $pageInfo
        ]);
        return response()->api([
            'list' => $faqs,
            'page_info' => $pageInfo
        ]);
    }
}
