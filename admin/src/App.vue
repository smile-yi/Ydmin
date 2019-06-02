<template>
    <div id="app">
        <el-container class='app' v-if='user.id'>
            <el-header>
                <me-header :_user='user' @update-user='setUser'></me-header>
            </el-header>
            <el-container>
                <el-aside width='225px'>
                    <me-side></me-side>
                </el-aside>
                <el-main>
                    <router-view/>
                </el-main>
            </el-container>
        </el-container>

        <el-container class='login' v-if='!user.id'>
            <el-header height='auto'>
                <div class='logo'>
                    <img src='/static/image/logo.png'>U9应答系统
                </div>
            </el-header>
            <el-main>
                <el-row>
                    <el-col :span='8' :offset='8'>
                        <div class='box-form'>
                            <div class='title'>用户登录</div>
                            <div class='content'>
                                <el-form :model='login'>
                                    <el-form-item>
                                        <el-input v-model='login.username'>
                                            <template slot='prepend'><i class='fa fa-user'></i></template>
                                        </el-input>
                                    </el-form-item>
                                    <el-form-item>
                                        <el-input v-model='login.password' type='password'>
                                            <template slot='prepend'><i class='fa fa-lock'></i></template>
                                        </el-input>
                                    </el-form-item>
                                    <el-form-item>
                                        <el-checkbox v-model='login.remeber'>记住我</el-checkbox>
                                    </el-form-item>
                                    <el-form-item>
                                        <el-button type='primary' style='width: 100%'
                                            @click='toLogin'>
                                            登录
                                        </el-button>
                                    </el-form-item>
                                </el-form>
                            </div>
                        </div>
                    </el-col>
                </el-row>
            </el-main>
            <el-footer>
            </el-footer>
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
            'user'  : {},
            'login' : {
                'username'  : '',
                'password'  : '',
                'remeber'   : true
            }
        }
    },
    methods : {
        toLogin   : function(){
            var url     = Request.createApi('/admin/login')
            Request.post(url, this.login, res => {
                Notify.success('登录成功')
                Common.route('/admin/auth/user/list')
                this.setUser(res.info)
            })
        },

        //设置用户信息
        setUser   : function(user){
            if(user){
                this.user   = user;
                this.user.nickname_show     = Common.stringCut(this.user.nickname, 5)
                Request.token   = this.user.token

                //存储用户数据
                if(this.login.remeber){
                    Storage.set('login-user', this.user, 7*24*3600)
                }
            }
        }
    },
    created : function(){
        //加载用户信息
        var user    = Storage.get('login-user');
        if(user){
            this.setUser(user)
        }
    },
    mounted : function(){
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
    watch : {
        '$route' : function(n, o){
            if(n.path == '/login'){
                this.user   = {}
                Storage.remove('login-user')
            }
        }
    },
    components : {
        'me-header' : Header,
        'me-side'   : Menu
    }
}
</script>
