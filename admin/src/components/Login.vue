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
                                <el-input placeholder="密码" v-model="login.password">
                                    <template slot="prepend"><i class='el-icon-lock'></i></template>
                                </el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-checkbox v-model="login.remeber_me">记住我</el-checkbox>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" style="width:100%;" @click='__login()'>登&nbsp;&nbsp;录</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
        </el-main>
    </el-container>
</template>

<script type="text/javascript">
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
        __login() {
            console.log(this.login)
        }
    }
}
</script>