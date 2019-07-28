<template>
    <div class='util-header'>
        <div class='header'>
            <div class='logo'>
                <img src='/static/image/logo.png'>YesAdmin
            </div>
            <div class='user'>
                <div class='info'>
                    <img class='avatar' src='/static/image/avatar.png'> {{user.nickname_show}}
                </div>
                <el-dropdown trigger="click" @command='userDropdown'>
                    <div class='btn-extend el-dropdown-link'>
                        <i class='btn-icon el-icon-arrow-down'></i>
                    </div>
                    <el-dropdown-menu slot="dropdown" class='dropdown-list'>
                        <el-dropdown-item command='user_update'>信息修改</el-dropdown-item>
                        <el-dropdown-item command='user_repass'>密码修改</el-dropdown-item>
                        <el-dropdown-item divided class='button-item' command='logout'>
                            <el-button type='danger' size='mini'>退出登录</el-button>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
        </div>

        <el-dialog title='信息修改' :visible.sync='dialog.user_update' width='700px'>
            <el-form :model='nUser' label-width='100px' label-position='right'>
                <el-form-item label='账号:'>
                    <el-input v-model='nUser.username' disabled></el-input>
                </el-form-item>
                <el-form-item label='姓名:'>
                    <el-input v-model='nUser.truename'></el-input>
                </el-form-item>
                <el-form-item label='昵称:'>
                    <el-input v-model='nUser.nickname'></el-input>
                </el-form-item>
                <el-form-item label='手机:'>
                    <el-input v-model='nUser.mobile'></el-input>
                </el-form-item>
                <el-form-item label='邮箱:'>
                    <el-input v-model='nUser.email'></el-input>
                </el-form-item>
            </el-form>
            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.user_update = false'>取消</el-button>
                <el-button type='primary' @click='userUpdate(nUser)'>确定</el-button>
            </div>
        </el-dialog>
        <el-dialog title='密码修改' :visible.sync='dialog.user_repass' width='700px'>
            <el-form :model='repass' label-width='100px' label-position='right'>
                <el-form-item label='原密码:'>
                    <el-input v-model='repass.pwd_old' type='password'></el-input>
                </el-form-item>
                <el-form-item label='新密码:'>
                    <el-input v-model='repass.pwd_new' type='password'></el-input>
                </el-form-item>
                <el-form-item label='新密码:'>
                    <el-input v-model='repass.pwd_new_2' type='password'></el-input>
                </el-form-item>
            </el-form>
            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.user_repass = false'>取消</el-button>
                <el-button type='primary' @click='userRepwd(repass)'>确定</el-button>
            </div>
        </el-dialog>
     </div>
</template>

<script>
import Request from '@/common/Request'
import Common from '@/common/Common'
import Cache from '@/common/Cache'
import Storage from '@/common/Storage'
import Notify from '@/common/Notify'

export default {
    data : function(){
        return {
            'user' : this._user,
            'nUser' : {},
            'loading' : false,
            'repass' : {
                'password'  : '',
                'new_pass'  : '',
                're_new_pass'   : ''
            },
            'dialog'    : {
                'user_update'   : false,
                'user_repass'   : false,
            }
        }
    },
    props : ['_user'],
    methods : {
        //更新用户信息
        userUpdate(info) {
            var url = Request.createApi('/admin/update');
            var param = {
                info : info
            }

            Request.post(url, param, res => {
                this.$emit('user:update', res.info)
                this.dialog.user_update     = false
                Notify.success('信息更改成功')
            })
        },

        //密码修改
        userRepwd : function(info){
            if(info.pwd_new !== info.pwd_new_2){
                Notify.error('两次输入的密码不一致，请检查！')
                return false
            }
            var url = Request.createApi('/admin/repwd');
            Request.post(url, info, res => {
                Notify.success('密码修改成功')
                this.dialog.user_repass     = false

                //退出登录
                setTimeout(() => {
                    this.logout()
                }, 500)
            })
        },

        //用户下拉菜单指令
        userDropdown(command) {
            switch(command){
                case 'user_update':
                    this.nUser  = Common.clone(this.user)
                    this.dialog.user_update     = true
                break;
                case 'user_repass':
                    this.dialog.user_repass     = true
                break;
                case 'logout':
                    this.logout()
                break;
            }
        },

        //退出登录
        logout() {
            this.$router.push({
                path: '/login'
            })
        }
    },
    watch : {
        _user : function(n, o){
            this.user   = n;
        }
    }
}
</script>
<style type="text/css">
    .el-dropdown-menu > .el-dropdown-menu__item {
        text-align: center;
    }
</style>
