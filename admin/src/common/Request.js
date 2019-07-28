import axios from 'axios';
import Common from '@/common/Common'
import Config from '@/common/Config'
import Notify from '@/common/Notify'
import App from '@/App'

export default {
    name    : 'request',
    token   : '',
    prefix  : Config.api_url,

    /**
     * 发送get请求
     * @param  url
     * @param  callback
     */
    get : function(url, callback){
        //发送请求
        axios.get(url).then(res => {
            var data    = this.parse(res);
            data && callback(data);
        }).catch(error => {
            console.log(url, error)
            Notify.error('网络请求异常，请稍候重试！');
            return false;
        });
    },

    /**
     * 发送post请求
     * @param   url
     * @param   param
     * @param   callback
     */
    post : function(url, param, callback){
        axios.post(url, param).then(res => {
            var data    = this.parse(res);
            data && callback(data);
        }).catch(error => {
            console.log(url, error)
            Notify.error('网络请求异常，请稍候重试！');
            return false;
        });
    },

    //解析响应结果
    parse : function(res){
        var res = res.data;
        if( res.errno != '0'){
            //特殊错误码处理
            switch(res.errno){
                case 601:
                    Common.route('/login')
                break;
                default:
                    Notify.error(res.errno + ':' + res.errmsg);
                break;
            }

            return false;
        }
        
        return res.data;
    },

    //拼接get参数
    httpBuildQuery : function(param, key = null, encode = null){
        if(param==null) return '';
        var paramStr = '';
        var t = typeof (param);
        if (t == 'string' || t == 'number' || t == 'boolean') {
            paramStr += '&' + key + '=' + ((encode==null||encode) ? encodeURIComponent(param) : param);
        } else {
            for (var i in param) {
                var k = key == null ? i : key + '[' + i + ']';
                paramStr += this.httpBuildQuery(param[i], k, encode);
            }
        }
        return paramStr;
    },

    //获取api接口地址
    createApi : function(url){
        var url     = this.prefix  + url;

        //拼接token
        var split   = url.indexOf('?') == -1 ? '?' : '&';
        var url     = url + split + 'token=' + this.token;

        return url;
    }
}