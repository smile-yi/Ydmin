<template>
    <div>
        <div id='toolbar' class='tool'></div>
        <div id='text' class='text'></div>
    </div>
</template>

<script>
import WangEditor from 'wangeditor'
import Request from '@/common/Request'

export default {
    data : function() {
        return {
            content : this.text,
            editor : null
        }
    },
    props : ['text'],
    methods : {
        
    },
    created : function(){
        // this.CreatePage();
    },
    watch : {
        text : function(n, o){
            this.editor.txt.html(n);
        }
    },
    mounted : function(){
        this.editor = new WangEditor('#toolbar', '#text');

        //文件上传路径配置
        this.editor.customConfig.uploadImgServer = Request.createApi('/public/upload?type=we3');
        this.editor.customConfig.height = 600;
        this.editor.customConfig.zIndex = 1;
        //文本变动监听
        this.editor.customConfig.onchange = (html) => {
            this.content = html;
            this.$emit('update:text', html);
        }

        this.editor.create();

        this.editor.txt.html(this.content);
    }
}
</script>

<style>
    .tool {
        border: #ddd 1px solid;
        border-radius: 4px 4px 0px 0px;
    }
    .text {
        border: #ddd 1px solid;
        border-top: none;
        border-radius: 0px 0px 4px 4px;
        height: 400px;
    }
</style>