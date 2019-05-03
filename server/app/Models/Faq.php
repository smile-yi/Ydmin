<?php
/**
 * Faq.php
 * 问答模型
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-09-11 17:03:54
 */

namespace App\Models;
use App\Utils\Http;

class Faq extends Base {

    protected $table = 'ap_faq';
    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_FORBID = 0;
    const STATUS_DELETE = -1;

    //推送至百度
    function push(){
        Http::post(
	    'http://data.zz.baidu.com/urls?appid=1595441886555530&token=q578s6atGrtBNSqt&type=homepage',
	    $this->getLink()
	);
        return $this;
    }

    //Url获取
    function getLink(){
        return config('app.url').'/faq/show/'.$this->id.'.html';
    }

    //关联游戏
    function game(){
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * 查询条件设置
     * @param   $where
     * @param   $query
     * @return  query
     */
    static function setWhere($where, $query = null){
        $whereRaw = [];
        $binds = [];

        //关键词查询
        if(isset($where['keyword'])){
            if(isset($where['keyword']) && $where['keyword'] !== ''){
                $whereRaw[] = '(title like ?)';
                $binds = array_merge($binds, [
                    '%'.$where['keyword'].'%'
                ]);
            }
            unset($where['keyword']);
        }

        $query = parent::setWhere($where, $query);
        if($whereRaw){
            $query->whereRaw(implode(' and ', $whereRaw), $binds);
        }

        return $query;
    }

}
