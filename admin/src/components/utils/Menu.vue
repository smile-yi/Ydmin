<template>
    <div class='sider'>
        <el-collapse accordion>
            <el-collapse-item v-for='menu in menus' v-if='menu.show && menu.user_in' :key='menu.id'>
                <template slot='title' class='title info'>
                    <div class='box-shake'>
                        <i :class='menu.icon + " hover-shake"' class='shake-target'></i>{{menu.name}}
                    </div>
                </template>
                <a v-for='child in menu.child' v-if='child.show' :key='child.id' class='child' 
                    :href='"#/"+child.url'>
                    <i class='fa fa-circle'></i>{{child.name}}
                </a>
            </el-collapse-item>
        </el-collapse>
    </div>
</template>

<script type="text/javascript">
import Request from '@/common/Request'
import Common from '@/common/Common'
export default {
    data    : function(){
        return {
            'menus' : {},
            'msg'   : 'Hello side menu',
            'menu_active'   : 1
        }
    }, 
    created : function(){
        var url     = Request.createApi('/admin/menus')
        Request.get(url, res => {
            console.log(res)
            this.menus  = res['list']
        })
    }
}
</script>