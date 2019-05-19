<?php
/**
 * admin.php
 * 管理后台模块路由
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-04-17
 */

use Illuminate\Http\Request;

//登录
Route::any('login', 'AdminController@login');
//详情信息
Route::any('detail', 'AdminController@detail');
//修改信息
Route::any('update', 'AdminController@update');
//修改密码
Route::any('repwd', 'AdminController@repwd');
//菜单获取
Route::any('menus', 'AdminController@menus');

//权限模块
Route::namespace('Auth')->prefix('auth')->group(function(){

    //用户列表获取
    Route::any('user/list', 'UserController@list');

    //用户添加
    Route::any('user/add', 'UserController@add');

    //用户详情获取
    Route::any('user/detail', 'UserController@detail');

    //用户信息更改
    Route::any('user/update', 'UserController@update');
    
    //更改用户组
    Route::any('user/update-groups', 'UserController@updateGroups');

    //用户组列表
    Route::any('group/list', 'GroupController@list');

    //用户组添加
    Route::any('group/add', 'GroupController@add');

    //用户组详情
    Route::any('group/detail', 'GroupController@detail');

    //用户组编辑
    Route::any('group/update', 'GroupController@update');

    //更改组权限
    Route::any('group/update-rules', 'GroupController@updateRules');

    //菜单列表
    Route::any('rule/list', 'RuleController@list');

    //菜单添加
    Route::any('rule/add', 'RuleController@add');

    //菜单编辑
    Route::any('rule/update', 'RuleController@update');
});

//faq管理
Route::any('faq/list', 'FaqController@list');
Route::any('faq/add', 'FaqController@add');
Route::any('faq/update', 'FaqController@update');
Route::any('faq/push', 'FaqController@push');

//游戏管理
Route::any('game/list', 'GameController@list');
Route::any('game/add', 'GameController@add');
Route::any('game/update', 'GameController@update');
//上传问答模板
Route::any('game/upload-faq-tpl', 'GameController@uploadFaqTpl');
//下载问答模板
Route::any('game/download-faq-tpl', 'GameController@downloadFaqTpl');
