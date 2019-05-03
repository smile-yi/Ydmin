<?php
/**
 * Game.php
 * 游戏模型
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-09-11 17:52:59
 */

namespace App\Models;

class Game extends Base {

    protected $table = 'ap_game';
    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_FORBID = 0;
    const STATUS_DELETE = -1;

    //默认问答模板
    const DEFAULT_FAQ_TPL = 'default.faq';

    //获取问答首页地址
    function getFaqLink(){
        return config('app.url').'/game/'.$this->id.'/faq-list.html';
    }

}
