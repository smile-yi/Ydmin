/**
 * Cache.js 
 * 缓存工具(基于window.sessionStorage)
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-12-05
 */

export default {

    cache : window.sessionStorage,
    prefix : 'cache_',

    /**
     * 设置缓存
     * @param   key
     * @param   val
     * @return  boolean
     */
    set : function(key, val){
        if(key === undefined || val === undefined){
            return false;
        }

        key = this.prefix + key;
        this.cache.setItem(key, JSON.stringify(val));

        return true;
    },

    /**
     * 读取缓存
     * @param   key
     * @param   def
     * @return  val
     */
    get : function(key, def = null){
        if(key === undefined){
            return def;
        }

        key = this.prefix + key;
        var val = JSON.parse(this.cache.getItem(key));

        return val === null ? def : val;
    },

    /**
     * 清除缓存
     * @param   key
     * @return  boolean
     */
    remove : function(key){
        key = this.prefix + key;
        this.cache.removeItem(key);

        return true;
    },
}
     