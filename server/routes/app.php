<?php
/**
 * admin.php
 * 管理后台模块路由
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-04-17
 */

use Illuminate\Http\Request;

//问答
Route::any('faq/show/{id}.html', 'FaqController@show');

//游戏首页
Route::any('game/{id}/faq-list.html', 'GameController@showListFaq');
