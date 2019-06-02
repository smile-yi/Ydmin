<template>
    <div class='clearfix'>
        <br>
        <ul class="pagination pull-right">
            <li class="page-item" :class='{"active" : now == item}' v-for='item in list'>
                <a class="page-link" href='javascript:void(0)' v-html='item' @click='GoPage(item)'></a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            count : 3,
            list : [],
            // list : ['&laquo;', '&laquo;']
        }
    },
    props : ['max', 'now'],
    methods : {
        //前往页面
        GoPage : function(page){
            if(page == this.now){
                return true;
            }
            if(page == '&raquo;'){
                page = this.now + 1;
            }
            if(page == '&laquo;'){
                page = this.now - 1;
            }

            if(page < 1) page = 1;
            if(page > this.max) page = this.max;

            this.$emit('go-page', page);
        },
        //生成页面
        CreatePage : function(){
            if(this.max <= 1 || !this.now || !this.max){
                this.list   = [];
                return true;
            }
            this.list   = [];
            //页码计算
            let start   = this.now - this.count;
            if(start < 1){
                start = 1;
            }
            let end     = this.now + this.count;
            let surplus = (this.count + 1 - this.now) > 0 ? (this.count + 1 - this.now) : 0;
                end     = end + surplus;
            if(end > this.max){
                end     = this.max;
            }
            for(let i = start; i <= end; i++){
                this.list.push(i)
            }

            //上一页插入
            if(this.now != 1){
                this.list.splice(0, 0, '&laquo;');
                if(start > 2){
                    this.list.splice(0, 0, 1);
                }
            }
            //下一页插入
            if(this.now != this.max){
                this.list.push('&raquo;');
                if(this.max - end > 1){
                    this.list.push(this.max);
                }
            }
        }
    },
    created : function(){
        if(this.max && this.now){
            this.CreatePage();
        }
        
    },
    watch : {
        now : function(n, o){
            this.CreatePage();
        },
        max : function(n, o){
            this.CreatePage();
        }
    }
}
</script>