<template>
    <div id="app">
        <el-container class='app' v-if='user.id'>
            <el-header height="70px">
                <me-header :_user='user' @user:update='setUser'></me-header>
            </el-header>
            <el-container>
                <el-aside width='225px'>
                    <me-menu></me-menu>
                </el-aside>
                <el-main>
                    <router-view/>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
import Header from '@/components/utils/Header'
import Menu from '@/components/utils/Menu'
import Common from '@/common/Common'
import Notify from '@/common/Notify'
import Request from '@/common/Request'
import Cache from '@/common/Cache'
import Storage from '@/common/Storage'
export default {
    name: 'App',
    data: function(){
        return {
            user: {}
        }
    },
    methods : {
        //设置用户信息
        setUser(user) {
            console.log('set user', user)
            if(user){
                this.user = user;
                this.user.nickname_show = Common.stringCut(this.user.nickname, 5)
                Request.token = this.user.token
            }
        }
    },
    created() {
        //加载用户信息
        var user    = Storage.get('login:user');
        if(user){
            this.setUser(user)
        } else {
            this.$router.push({path: '/login'})
        }
    },
    mounted() {
        //IE11 路由跳转
        if ('-ms-scroll-limit' in document.documentElement.style 
            && '-ms-ime-align' in document.documentElement.style) {
            window.addEventListener('hashchange', () => {
                var currentPath = window.location.hash.slice(1)
                if (this.$route.path !== currentPath) {
                    this.$router.push(currentPath)
                }
            }, false)
        }
    },
    watch: {
        '$route' : function(n, o){
            if(n.path == '/login'){
                this.user   = {}
                Storage.remove('login:user')
            }
        }
    },
    components: {
        'me-header' : Header,
        'me-menu'   : Menu
    }
}
</script>
