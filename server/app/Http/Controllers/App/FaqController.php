<?php
/**
 * FaqController.php
 * 问答控制器
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-09-12 11:03:42
 */

namespace App\Http\Controllers\App;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Game;
use App\Models\Faq;
use App\Exceptions\NormalException;

class FaqController extends Controller {

    /**
     * 问答展示
     * @param   $id
     * @return  html [<description>]
     */
    function show(Request $request, $id){
        $faq = Faq::where(['id' => $id, 'status' => Faq::STATUS_NORMAL])->first();
        if(!$faq){
            throw new NormalException(801);
        }

        if(!$faq->game || $faq->game->status != Game::STATUS_NORMAL){
            throw new NormalException(701);
        }

        if(!$faq->headline){
            $faq->headline = $faq->title;
        }
        
        $faqTpl = View::exists($faq->game->faq_tpl) ? $faq->game->faq_tpl : Game::DEFAULT_FAQ_TPL;
        return view($faqTpl, ['faq' => $faq]);
        //return view('default.faq', ['faq' => $faq]);
    }
}
