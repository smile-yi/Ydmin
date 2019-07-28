<?php
/**
 * BaseModel.php
 * 基类模型
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-09-18
 */

namespace App\Models;
use App\Utils\Page;
use Illuminate\Database\Eloquent\Model;

class Base extends Model {

    //状态映射
    const MAP_STATUS = [
        1 => '正常',
        2 => '禁用',
        9 => '删除'
    ];

    const STATUS_NORMAL = 1;
    const STATUS_BORBID = 2;
    const STATUS_DELETE = 9;

    /**
     * 分页设置
     * @param   &$query
     * @param   $page
     * @param   &$pageInfo
     * @return  null
     */
    static function setPage($query, $page = 1, &$pageInfo = false){
        $page = $page >= 1 ? intval($page) : 1;
        $limit = is_array($pageInfo) && !empty($pageInfo['limit']) ? 
            intval($pageInfo['limit']) : config('app.page.limit');
        $offset = ($page - 1) * $limit;

        if($pageInfo !== false){
            $count  = (clone $query)->count();
            $pageInfo   = [
                'count' => $count,
                'max'   => ceil($count/$limit),
                'now'   => $page,
                'limit' => $limit
            ];
        }

        $query->offset($offset)->limit($limit);
    }

    /**
     * 列表信息获取
     * @param   $where
     * @param   $with   关联查询
     * @param   $page
     * @param   &$pageInfo
     * @param   $queryCallBack
     * @return  list
     */
    static function list($where, $page = false, &$pageInfo = false, $queryCallBack = null){
        $where  = is_array($where) ? $where : [];

        //使用子类调用条件解析函数
        $query  = static::setWhere($where);

        //语句回调处理
        $queryCallBack && $queryCallBack($query);

        //分页处理
        $page && static::setPage($query, $page, $pageInfo);
        $page || $query->limit(config('app.page.max_limit'));

        return $query;
    }

    /**
     * 查询条件设置
     * @param   $where
     * @param   $query 
     * @return  $query
     */
    static function setWhere($where, $query = null){
        if($query){
            return $query->where($where)->where('status', '!=', static::STATUS_DELETE);
        }else{
            return static::where($where)->where('status', '!=', static::STATUS_DELETE);
        }
    }

    /**
     * 字段转义
     * @param   $level 转义等级
     * @return  array
     */
    function convert($level = 1) {
        $item = parent::toArray();
        if (isset($item['status'])) {
            $item['status_text'] = static::MAP_STATUS[$item['status']];
        }

        return $item;
    }
}
