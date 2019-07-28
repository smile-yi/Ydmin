<template>
    <div class='row'>
        <div class='col-sm-6 box-upload-show'>
            <img :src='self_src'>
        </div>
        <div class='col-sm-6'>
            <div class='box-upload'>
                <button class='btn btn-primary btn-upload-show'>上传</button>
                <button class='btn-upload' :id='self_id'></button>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import Request from '@/common/Request'

export default {
    data : function() {
        return {
            self_src : this.src,
            self_id : this.id
        }
    },
    props : ['src', 'id'],
    methods : {
        
    },
    created : function(){
        this.self_id = this.self_id || 'upload';
    },
    watch : {
        src : function(n, o){
            this.self_src   = n;
        }
    },
    mounted : function(){
        //上传插件声明
        $('#' + this.self_id).uploadifive({
            'auto'              : true,
            'uploadScript'      : Request.getApiUrl('/upload'),
            'removeCompleted'   : true,
            'onUploadComplete'  : function(file, res){
                var res = JSON.parse(res);
                var code = res.res_code;
                var data = res.res_data;
                var file = data.list[0];
                if(Math.floor(code/100) != 2){
                    alert(data.message);
                    return false;
                }

                self.self_src   = file.url;
                self.$emit('update:src', file.url);
            }
        });
        var self = this;
    }
}
</script>