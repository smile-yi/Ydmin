<template>
    <el-container class='page-login'>
        <el-header height="80px">
            <div class='logo'><img src='/static/image/logo.png'>YesAdmin</div>
        </el-header>
        <el-main>
            <el-row>
                <el-col :span='8' :offset='8'>
                    <div class='box-form'>
                        <div class='form-title'>用户登录</div>
                        <el-form>
                            <el-form-item>
                                <el-input placeholder="用户名" v-model="login.username">
                                    <template slot="prepend"><i class='el-icon-user'></i></template>
                                </el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-input placeholder="密码" v-model="login.password" type="password">
                                    <template slot="prepend"><i class='el-icon-lock'></i></template>
                                </el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-checkbox v-model="login.remeber_me">记住我</el-checkbox>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" style="width:100%;" @click='toLogin()'>登&nbsp;&nbsp;录</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
        </el-main>
    </el-container>
</template>

<script type="text/javascript">
import Request from '@/common/Request'
import Cache from '@/common/Cache'
import Storage from '@/common/Storage'
export default {
    data() {
        return {
            login: {
                username: '',
                password: '',
                remeber_me: true
            }
        }
    },
    methods: {
        toLogin() {
            var url = Request.createApi('/admin/login')
            var that = this
            Request.post(url, this.login, res => {
                Storage.set('login:user', res.info)
                //路由跳转
                this.$router.push({path:'/'})
            })
        }
    }
}
</script>