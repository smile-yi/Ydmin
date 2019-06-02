<template>
    <div>
        <div class='header'>
            <div class='box-logo'>
                <img src='/static/image/logo.png'>U9应答系统
            </div>
            <div class='box-user'>
                <div class='headimg'>
                    <img src='/static/image/user-green.png'>
                </div>
                <div class='username'>{{user.nickname_show}}</div>
                <el-dropdown trigger="click" @command='user_dropdown'>
                    <div class='btn-extend el-dropdown-link'>
                        <i class='btn-icon el-icon-arrow-down'></i>
                    </div>
                    <el-dropdown-menu slot="dropdown" class='header-dropdown'>
                        <el-dropdown-item command='user_update'>信息修改</el-dropdown-item>
                        <el-dropdown-item command='user_repass'>密码修改</el-dropdown-item>
                        <el-dropdown-item divided class='button-item' command='logout'>
                            <el-button type='danger' size='mini' @click='logout()'>退出登录</el-button>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
        </div>

        <el-dialog title='信息修改' :visible.sync='dialog.user_update' width='800px'>
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
                <el-button type='primary' @click='update_user(nUser)'>确定</el-button>
            </div>
        </el-dialog>
        <el-dialog title='密码修改' :visible.sync='dialog.user_repass' width='800px'>
            <el-form :model='repass' label-width='100px' label-position='right'>
                <el-form-item label='原密码:'>
                    <el-input v-model='repass.password' type='password'></el-input>
                </el-form-item>
                <el-form-item label='新密码:'>
                    <el-input v-model='repass.new_pass' type='password'></el-input>
                </el-form-item>
                <el-form-item label='新密码:'>
                    <el-input v-model='repass.re_new_pass' type='password'></el-input>
                </el-form-item>
            </el-form>
            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.user_repass = false'>取消</el-button>
                <el-button type='primary' @click='toRepass(repass)'>确定</el-button>
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
    // computed : {
    //     nUser : function(){
    //         return Common.clone(this.user);
    //     }
    // },
    methods : {
        
        //更新用户信息
        update_user : function(info){
            var url = Request.createApi('/admin/update');
            var param = {
                info : info
            }

            Request.post(url, param, res => {
                this.$emit('update-user', res.info)
                this.dialog.user_update     = false
                Notify.success('信息更改成功')
            })
        },

        //密码修改
        toRepass : function(info){
            if(info.new_pass !== info.re_new_pass){
                Notify.error('两次输入的密码不一致，请检查！')
                return false
            }
            var url = Request.createApi('/admin/repass');
            Request.post(url, info, res => {
                Notify.success('密码修改成功')
                this.dialog.user_repass     = false
            })
        },

        //用户下拉菜单指令
        user_dropdown   : function(command){
            switch(command){
                case 'user_update':
                    this.nUser  = Common.clone(this.user)
                    this.dialog.user_update     = true
                break;
                case 'user_repass':
                    this.dialog.user_repass     = true
                break;
                case 'logout':
                break;
            }
        },

        //退出登录
        logout  : function(){
            Common.route('/login')
        }

    },
    watch : {
        _user : function(n, o){
            this.user   = n;
        }
    },
    created : function(){
        
    }
}
</script>
