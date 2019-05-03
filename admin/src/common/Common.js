// export var Name = 'Wangzhongyi';

// function Common(){

//     this.test   = function(){
//         console.log('run common test');
//     }
// }

// export var common   = new Common();

export default {

    //批量获取字符串
    getBatchString : function(string, count){
        var s   = '';
        for(let i = 0; i < count; i++){
            s += string;
        }

        return s;
    },

    //克隆对象
    clone : function(object){
        return JSON.parse(JSON.stringify(object));
    },

    //路由
    route : function(anchor) {
        if(anchor){
            window.open('#' + anchor, '_self');
            return true;
        }else{
            return '';
        }
    },
    
    //去页面
    toUrl : function(url){
        window.open('/#' + url, '_self')
    },

    //字符串裁剪
    stringCut : function(string, length, postfix){
        var postfix = postfix || '...'

        if(string.length > length){
            return string.substr(0, length) + postfix
        }else{
            return string
        }
    }
}