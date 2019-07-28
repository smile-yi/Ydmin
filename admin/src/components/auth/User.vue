<template>
    <div class='page-container'>
        <div class='title'>用户列表</div>
        <div class='body' v-loading='loading'>
            <div class='execute'>
                <el-button-group>
                    <el-button type='primary' @click="dialog.add = true">添加</el-button>
                </el-button-group>
            </div>
            <div class='content'>
                <el-table :data='list' border>
                    <el-table-column prop='id' label='#ID' width='50px'></el-table-column>
                    <el-table-column prop='nickname' label='#昵称'></el-table-column>
                    <el-table-column prop='username' label='#用户名'></el-table-column>
                    <el-table-column prop='login_count' label='#登录次数'></el-table-column>
                    <el-table-column prop='last_login_ip' label='#登录IP'></el-table-column>
                    <el-table-column label='#状态' width='100px'>
                        <template slot-scope='scope'>
                            <el-tag :type='scope.row.status == 1 ? "success" : "warning"' size='mini'>
                                {{{2:'禁用', 1:'正常'}[scope.row.status]}}
                            </el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label='#操作' width='260px'>
                        <template slot-scope='scope'>
                            <el-button-group>
                                <el-button size='mini' type='primary' 
                                    @click='getItem(scope.row.id)'>
                                    详情
                                </el-button>
                                <el-button size='mini' type='primary' 
                                    @click='getGroups(scope.row)'>
                                    授权
                                </el-button>
                                <el-button size='mini' type='warning' 
                                    v-if='scope.row.status == 1' 
                                    @click='updateItem(scope.row.id, {status : 2})'>
                                    禁用
                                </el-button>
                                <el-button size='mini' type='success' 
                                    v-if='scope.row.status == 2' 
                                    @click='updateItem(scope.row.id, {status : 1})'>
                                    启用
                                </el-button>
                                <el-button size='mini' type='danger' 
                                    @click='updateItem(scope.row.id, {status : 9})'>
                                    删除
                                </el-button>
                            </el-button-group>
                        </template>
                    </el-table-column>
                </el-table>
                <br>
                <el-pagination
                    layout='prev, pager, next' 
                    :total='count' 
                    @current-change='getList'
                ></el-pagination>
            </div>
        </div>

        <!-- 添加 -->
        <el-dialog title='用户添加' :visible.sync='dialog.add' width='800px'>
            <el-form :model='aItem' label-width='100px' label-position='right' :rules='rules.user_add'>
                <el-form-item label='用户名:' prop='username'>
                    <el-input v-model='aItem.username'></el-input>
                </el-form-item>
                <el-form-item label='密码:' prop='password'>
                    <el-input v-model='aItem.password' type='password'></el-input>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.add = false'>取消</el-button>
                <el-button type='primary' @click='addItem(aItem)'>确定</el-button>
            </div>
        </el-dialog>

        <!-- 编辑 -->
        <el-dialog title='用户编辑' :visible.sync='dialog.update' width='800px'>
            <el-form :model='nItem' label-width='100px' label-position='right' :rules='rules.user_update'>
                <el-form-item label='用户名:'>
                    <el-input v-model='nItem.username' disabled></el-input>
                </el-form-item>
                <el-form-item label='昵称:'>
                    <el-input v-model='nItem.nickname'></el-input>
                </el-form-item>
                <el-form-item label='真实姓名:'>
                    <el-input v-model='nItem.truename'></el-input>
                </el-form-item>
                <el-form-item label='手机号:' prop='mobile'>
                    <el-input v-model='nItem.mobile'></el-input>
                </el-form-item>
                <el-form-item label='邮箱:' prop='email'>
                    <el-input v-model='nItem.email'></el-input>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.update = false'>取消</el-button>
                <el-button type='primary' @click='updateItem(nItem.id, nItem)'>确定</el-button>
            </div>
        </el-dialog>

        <!-- 授权 -->
        <el-dialog title='用户授权' :visible.sync='dialog.update_groups' width='800px'>
            <el-checkbox-group v-model='nUserGroups'>
                <el-row>
                    <el-col :span='6' v-for='group in nGroups' :key='group.id'>
                        <el-checkbox :label='group.id'>{{group.name}}</el-checkbox>
                    </el-col>
                </el-row>
            </el-checkbox-group>
            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.update_groups = false'>取消</el-button>
                <el-button type='primary' @click='updateGroups(nItem.id, nUserGroups)'>确定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script type="text/javascript">
import Request from '@/common/Request'
import Notify from '@/common/Notify'
export default {
    data() {
        return {
            'list'  : [],
            'count' : 100,
            'page'  : 1,
            'msg'   : 'admin/auth/user',
            'dialog': {
                'add'   : false,
                'update': false,
                'update_groups' : false
            },
            'aItem' : {},
            'nItem' : {},
            'nGroups' : [],
            'nUserGroups' : [],
            'loading' : false,
            'rules' : {
                'user_add'  : {
                    'username'  : [{'required' : true, 'message' : '请输入用户名'}],
                    'password'  : [{'required' : true, 'message' : '请输入密码'}]
                },
                'user_update'  : {
                    'mobile'    : [{
                        'pattern'   : /^1\d{10}$/,
                        'message'   : '手机号格式错误',
                        'trigger'   : 'blur'
                    }],
                    'email' : [{
                        'pattern'   : /^\w+@\w+\.com$/,
                        'message'   : '邮箱格式错误',
                        'trigger'   : 'blur'
                    }]
                }
            }
        }
    },
    created() {
        this.getList()
    },
    methods : {
        getList(page) {
            var page    = page || 1
            var url     = Request.createApi('/admin/auth/user/list?page='+page)
            Request.get(url, res => {
                this.list   = res.list
                this.count  = res.page_info.count;
                this.page   = page
                this.loading    = false
            })

            this.loading    = true
        },

        addItem(info) {
            var url     = Request.createApi('/admin/auth/user/add')
            Request.post(url, info, res => {
                this.dialog.add    = false
                this.getList()

                Notify.success('添加成功')
            })
        },

        getItem(id) {
            var url     = Request.createApi('/admin/auth/user/detail?id='+id)
            Request.get(url, res => {
                this.nItem  = res.info
                this.dialog.update     = true
            })
        },

        updateItem(id, info) {
            var url     = Request.createApi('/admin/auth/user/update');
            var param   = {
                'id'    : id,
                'info'  : info
            }
            Request.post(url, param, res => {
                this.dialog.update     = false;
                this.getList(this.page)

                Notify.success('修改成功')
            })
        },

        getGroups(user) {
            var url = Request.createApi('/admin/auth/group/list?user_id='+user.id)
            Request.get(url, res => {
                this.nUserGroups = []
                for(var k in res.list) {
                    var group   = res.list[k]
                    if(group.user_in){
                        this.nUserGroups.push(group.id)
                    }
                }
                this.nGroups = res.list
                this.dialog.update_groups   = true
            })
            this.nItem  = user
        },

        updateGroups(id, groupIds) {
            var url = Request.createApi('/admin/auth/user/update-groups')
            var param   = {
                'id'    : id,
                'group_ids' : groupIds 
            }

            Request.post(url, param, res => {
                this.dialog.update_groups   = false
                Notify.success('修改成功')
            })
        }
    }
}
</script>