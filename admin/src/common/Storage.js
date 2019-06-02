/**
 * Storage.js
 * 本地存储功能
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-05-09
 */

export default new (function(){

    this.handle  = window.localStorage

    this.time   = function(){
        return Date.parse(new Date())/1000
    }

    /**
     * 设置数据
     * @param  key 键
     * @param  val 值
     */
    this.set    = function(key, val, expire){
        var val     = {
            'data'  : val,
            'expire': 0
        }
        if(expire){
            val.expire  = this.time() + expire
        }
        this.handle.setItem(key, JSON.stringify(val))
    }

    /**
     * 获取数据
     * @param {string} key 键
     * @param {unknown} def 默认值
     * @return {unknown} 返回值
     */
    this.get    = function(key, def){
        var def = def === undefined ? null : def
        var val = this.handle.getItem(key)
        if(val){
            val     = JSON.parse(val)
            if(val.expire && val.expire < this.time()){
                this.remove(key)
                return def
            }else{
                return val.data
            }
        }else{
            return def
        }
    }

    /**
     * 删除
     * @param {string} key 键
     */
    this.remove     = function(key){
        this.handle.removeItem(key)
    }
})
