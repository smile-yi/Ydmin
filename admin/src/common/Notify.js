import Vue from 'vue'
export default {

    //成功
    success : function(text, title){
        title   = title || '成功';
        text    = text || '操作成功！';
        
        Vue.prototype.$notify({
            'title' : title,
            'message'   : text,
            'type'  : 'success'
        })
    },

    //失败
    error : function(text, title){
        title   = title || '错误';
        text    = text || '操作失败，请重试！';

        Vue.prototype.$notify({
            'title' : title,
            'message'   : text,
            'type'  : 'warning',
        })
    },

    //通知
    notice : function(text, title){
        title   = title || '通知';
        text    = text || '消息获取失败！';

        Vue.prototype.$notify({
            'title' : title,
            'message'   : text,
            'type'  : 'info'
        })
    }
}