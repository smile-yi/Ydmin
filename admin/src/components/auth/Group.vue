<template>
    <div class='bodyer'>
        <div class='title'>角色列表</div>
        <div class='content' v-loading='loading'>
            <div class='box-ops'>
                <el-button-group>
                    <el-button type='primary' @click="dialog.add = true">添加</el-button>
                </el-button-group>
            </div>
            <div class='box-cont'>
                <el-table :data='list' border>
                    <el-table-column prop='id' label='#ID' width='50px'></el-table-column>
                    <el-table-column prop='name' label='#名称'></el-table-column>
                    <el-table-column prop='depict' label='#描述'></el-table-column>
                    <!-- <el-table-column prop='created_at' label='#创建'></el-table-column> -->
                    <el-table-column label='#状态' width='100px'>
                        <template slot-scope='scope'>
                            <el-tag :type='scope.row.status == 1 ? "success" : "warning"' size='mini'>
                                {{['禁用', '正常'][scope.row.status]}}
                            </el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label='#操作'>
                        <template slot-scope='scope'>
                            <el-button-group>
                                <el-button size='micro' type='primary' 
                                    @click='get_item(scope.row)'>
                                    详情
                                </el-button>
                                <el-button size='micro' type='primary' 
                                    @click='get_rules(scope.row)'>
                                    授权
                                </el-button>
                                <el-button size='micro' type='warning' 
                                    v-if='scope.row.status == 1' 
                                    @click='update_item(scope.row.id, {status : 0})'>
                                    禁用
                                </el-button>
                                <el-button size='micro' type='success' 
                                    v-if='scope.row.status == 0' 
                                    @click='update_item(scope.row.id, {status : 1})'>
                                    启用
                                </el-button>
                                <el-button size='micro' type='danger' 
                                    @click='update_item(scope.row.id, {status : -1})'>
                                    删除
                                </el-button>
                            </el-button-group>
                        </template>
                    </el-table-column>
                </el-table>
                <br>
                <el-pagination class='float-right'
                    layout='prev, pager, next' 
                    :total='count' 
                    @current-change='get_list'
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
                <el-button type='primary' @click='add_item(aItem)'>确定</el-button>
            </div>
        </el-dialog>

        <!-- 编辑 -->
        <el-dialog title='角色编辑' :visible.sync='dialog.update' width='800px'>
            <el-form :model='nItem' label-width='100px' label-position='right' :rules='rules'>
                <el-form-item label='名称:' prop='name'>
                    <el-input v-model='nItem.name'></el-input>
                </el-form-item>
                <el-form-item label='描述:' prop='depict'>
                    <el-input v-model='nItem.depict' type='textarea' rows='5'></el-input>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.update = false'>取消</el-button>
                <el-button type='primary' @click='update_item(nItem.id, nItem)'>确定</el-button>
            </div>
        </el-dialog>

        <!-- 授权 -->
        <el-dialog title='角色授权' :visible.sync='dialog.rules' width='800px'>
            <el-scrollbar>
                <el-checkbox-group v-model='rules_selected' style='height: 40vh;'>
                    <div class='box-rules first'>
                        <div class='rule' v-for='rule in auth_rules'>
                            <el-checkbox :label='rule.id'>{{rule.name}}</el-checkbox>
                            <div class='box-rules second'>
                                <div class='rule' v-for='crule in rule.child'>
                                    <el-checkbox :label='crule.id'>{{crule.name}}</el-checkbox>
                                    <el-row class='box-rules third'>
                                        <el-col :span='6' v-for='ccrule in crule.child' :key='ccrule.id'>
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
                <el-button type='primary' @click='update_rules(nItem, rules_selected)'>确定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script type="text/javascript">
import Request from '@/common/Request'
import Notify from '@/common/Notify'
export default {
    data    : function(){
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
    created : function(){
        this.get_list()
    },
    methods : {
        get_list    : function(page){
            var page    = page || 1
            var url     = Request.createApi('/admin/auth/group/list?page='+page)
            Request.get(url, res => {
                this.loading    = false
                this.list   = res.list
                this.count  = res.page_info.count;
                this.page   = page
            })

            this.loading    = true
        },

        add_item    : function(info){
            var url     = Request.createApi('/admin/auth/group/add')
            Request.post(url, info, res => {
                this.dialog.add    = false
                this.get_list()

                Notify.success('添加成功')
            })
        },

        get_item    : function(item){
            this.nItem  = item
            this.dialog.update  = true
        },

        update_item : function(id, info){
            var url     = Request.createApi('/admin/auth/group/update');
            var param   = {
                'id'    : id,
                'info'  : info
            }
            Request.post(url, param, res => {
                this.dialog.update     = false;
                this.get_list(this.page)

                Notify.success('修改成功')
            })
        },

        get_rules   : function(item){
            this.nItem  = item
            this.rules_selected     = item.rule_ids ? item.rule_ids : []
            if(this.auth_rules === false){
                var url     = Request.createApi('/admin/auth/menu/list')
                Request.get(url, res => {
                    this.auth_rules     = res.list
                })
            }
            this.dialog.rules    = true;
        },

        update_rules    : function(item, rule_ids){
            var url     = Request.createApi('/admin/auth/group/update-rules')
            var param   = {
                'id'    : item.id,
                'rule_ids'  : rule_ids
            }

            Request.post(url, param, res => {
                this.dialog.rules    = false
                this.get_list(this.page)

                Notify.success('修改成功')
            })
        }
    }
}
</script>