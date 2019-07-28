<template>
    <div class='page-container'>
        <div class='title'>角色列表</div>
        <div class='body' v-loading='loading'>
            <div class='execute'>
                <el-button-group>
                    <el-button type='primary' @click="dialog.add = true">添加</el-button>
                </el-button-group>
            </div>
            <div class='content'>
                <el-table :data='list' border>
                    <el-table-column prop='id' label='#ID' width='50px'></el-table-column>
                    <el-table-column prop='name' label='#名称'></el-table-column>
                    <el-table-column prop='desc' label='#描述'></el-table-column>
                    <!-- <el-table-column prop='created_at' label='#创建'></el-table-column> -->
                    <el-table-column label='#状态' width='100px'>
                        <template slot-scope='scope'>
                            <el-tag :type='scope.row.status == 1 ? "success" : "warning"' size='mini'>
                                {{{2: '禁用', 1: '正常'}[scope.row.status]}}
                            </el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label='#操作'>
                        <template slot-scope='scope'>
                            <el-button-group>
                                <el-button size='mini' type='primary' 
                                    @click='getItem(scope.row)'>
                                    详情
                                </el-button>
                                <el-button size='mini' type='primary' 
                                    @click='getRules(scope.row)'>
                                    授权
                                </el-button>
                                <el-button size='mini' type='warning' 
                                    v-if='scope.row.status == 1' 
                                    @click='updateItem(scope.row.id, {status : 0})'>
                                    禁用
                                </el-button>
                                <el-button size='mini' type='success' 
                                    v-if='scope.row.status == 0' 
                                    @click='updateItem(scope.row.id, {status : 1})'>
                                    启用
                                </el-button>
                                <el-button size='mini' type='danger' 
                                    @click='updateItem(scope.row.id, {status : -1})'>
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
        <el-dialog title='角色添加' :visible.sync='dialog.add' width='800px'>
            <el-form :model='aItem' label-width='100px' label-position='right' :rules='rules'>
                <el-form-item label='名称:' prop='name'>
                    <el-input v-model='aItem.name'></el-input>
                </el-form-item>
                <el-form-item label='描述:' prop='depict'>
                    <el-input v-model='aItem.depict' type='textarea' rows='5'></el-input>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.add = false'>取消</el-button>
                <el-button type='primary' @click='addItem(aItem)'>确定</el-button>
            </div>
        </el-dialog>

        <!-- 编辑 -->
        <el-dialog title='角色编辑' :visible.sync='dialog.update' width='800px'>
            <el-form :model='nItem' label-width='100px' label-position='right' :rules='rules'>
                <el-form-item label='名称:' prop='name'>
                    <el-input v-model='nItem.name'></el-input>
                </el-form-item>
                <el-form-item label='描述:' prop='depict'>
                    <el-input v-model='nItem.desc' type='textarea' rows='5'></el-input>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.update = false'>取消</el-button>
                <el-button type='primary' @click='updateItem(nItem.id, nItem)'>确定</el-button>
            </div>
        </el-dialog>

        <!-- 授权 -->
        <el-dialog title='角色授权' :visible.sync='dialog.rules' width='800px'>
            <el-scrollbar>
                <el-checkbox-group v-model='rules_selected' style='height: 40vh;'>
                    <div class='box-rules first'>
                        <div class='rule' v-for='rule in auth_rules' :key='rule.id'>
                            <el-checkbox :label='rule.id'>{{rule.name}}</el-checkbox>
                            <div class='box-rules second'>
                                <div class='rule' v-for='crule in rule.childs' :key='crule.id'>
                                    <el-checkbox :label='crule.id'>{{crule.name}}</el-checkbox>
                                    <el-row class='box-rules third'>
                                        <el-col :span='6' v-for='ccrule in crule.childs' :key='ccrule.id' class='rule'>
                                            <el-checkbox :label='ccrule.id'>{{ccrule.name}}</el-checkbox>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>
                        </div>
                    </div>
                </el-checkbox-group>
            </el-scrollbar>
            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.rules = false'>取消</el-button>
                <el-button type='primary' @click='updateItem(nItem.id, {rule_ids: rules_selected})'>确定</el-button>
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
            'dialog': {
                'add'  : false,
                'update'   : false,
                'rules' : false
            },
            'aItem' : {},
            'nItem' : {},
            'rules' : {
                
            },
            'loading'   : true,
            'auth_rules' : false,
            'rules_selected' : []
        }
    },
    created() {
        this.getList()
    },
    methods : {
        getList(page) {
            var page = page || 1
            var url = Request.createApi('/admin/auth/group/list?page='+page)
            Request.get(url, res => {
                this.loading = false
                this.list = res.list
                this.count = res.page_info.count;
                this.page = page
            })

            this.loading = true
        },

        addItem(info) {
            var url = Request.createApi('/admin/auth/group/add')
            Request.post(url, info, res => {
                this.dialog.add    = false
                this.getList()

                Notify.success('添加成功')
            })
        },

        getItem(item) {
            this.nItem  = item
            this.dialog.update  = true
        },

        updateItem(id, info) {
            var url = Request.createApi('/admin/auth/group/update');
            var param = {
                'id' : id,
                'info' : info
            }

            Request.post(url, param, res => {
                this.dialog.update = false;
                this.dialog.rules = false
                this.getList(this.page)

                Notify.success('修改成功')
            })
        },

        getRules(item) {
            this.nItem = item
            this.rules_selected = item.rule_ids ? item.rule_ids : []
            var url = Request.createApi('/admin/auth/rule/list?group_id='+item.id+'&format=tree')
            Request.get(url, res => {
                this.dialog.rules = true;
                this.auth_rules = res.list
            })
            
        },

        updateRules(item, rule_ids) {
            var url     = Request.createApi('/admin/auth/group/update-rules')
            var param   = {
                'id'    : item.id,
                'rule_ids'  : rule_ids
            }

            Request.post(url, param, res => {
                this.dialog.rules    = false
                this.getList(this.page)

                Notify.success('修改成功')
            })
        }
    }
}
</script>