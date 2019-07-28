<template>
    <div class='wrap-login'>
        <div class='header'>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='box-logo'>
                            <img src='../../../static/image/logo.png' class='logo'>
                        </div>
                        <div class='box-title'>
                            趣点管理后台
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class='content'>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-4'></div>
                    <div class='col-md-4'>
                        <div class='box-form'>
                            <div class='title'>用户登录</div>
                            <div class='box-input'>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-user'></i></span>
                                    <input type='text' class='form-control' v-model='username' placeholder='请输入用户名'>
                                </div>
                            </div>
                            <div class='box-input'>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='fa fa-lock'></i></span>
                                    <input type='password' class='form-control' v-model='password' placeholder='请输入密码'>
                                </div>
                            </div>
                            <div class='box-checkbox'>
                                <input type='checkbox' v-model='remeberMe' value='1'>
                                <span>记住我的登录信息</span>
                            </div>
                            <div class='box-button'>
                                <button class='btn btn-success btn-block' @click='login()'>立即登录</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class='footer'>
            Copyright © 2013-2017 亮导科技  liangdaovip.com All Rights Reserved. 备案号：闽ICP备15012807号-1
        </div>
    </div>
</template>

<script type="text/javascript">
import Request from '@/common/Request'
import Common from '@/common/Common'

export default {
    data : function() {
        return {
            username : '',
            password : '',
            remeberMe : false
        }
    },
    methods : {
        login : function(){
            var url = Request.getApiUrl('/admin/login');
            var param = {
                username : this.username,
                password : this.password 
            }

            Request.post(url, param, res => {
                this.$emit('login-success', res.info);
            })
        }
    }
}
</script>