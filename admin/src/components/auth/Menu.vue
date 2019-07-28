<template>
    <div class='page-container'>
        <div class='title'>菜单列表</div>
        <div class='body' v-loading='loading'>
            <div class='execute'>
                <el-button-group>
                    <el-button type='primary' @click="dialog.add = true">添加</el-button>
                </el-button-group>
            </div>
            <div class='content list-menu-tree '>
                <el-collapse>
                    <el-collapse-item v-for='menu in list' :key='menu.id' label='ok'>
                        <!-- 一级菜单 -->
                        <template slot='title'>
                            <el-row style='width:100%'>
                                <el-col :span='6'>{{menu.name}}</el-col>
                                <el-col :span='6'>{{menu.url}}</el-col>
                                <el-col :span='6' class='align-center'>
                                    <el-tag :type='menu.show == 1 ? "success" : "warning"' size='mini'>
                                        {{['隐藏', '显示'][menu.is_menu]}}
                                    </el-tag>
                                </el-col>
                                <el-col :span='5' class='float-right'>
                                    <el-button-group class='float-right'>
                                        <el-button size='mini' type='primary' 
                                            @click.capture.stop='getItem(menu)'>
                                            详情
                                        </el-button>
                                        <el-button size='mini' type='danger'
                                            @click.capture.stop='updateItem(menu.id, {"status" : -1})'>
                                            删除
                                        </el-button>
                                    </el-button-group>
                                </el-col>
                            </el-row>
                        </template>
                        <el-collapse>
                            <div v-for='cmenu in menu.child' :key='cmenu.id'>
                                <!-- 二级菜单 -->
                                <el-collapse-item>
                                    <template slot='title'>
                                        <el-row>
                                            <el-col :span='6'>|-----{{cmenu.name}}</el-col>
                                            <el-col :span='6'>{{cmenu.url}}</el-col>
                                            <el-col :span='6' class='align-center'>
                                                <el-tag :type='cmenu.show == 1 ? "success" : "warning"' size='mini'>
                                                    {{['隐藏', '显示'][cmenu.show]}}
                                                </el-tag>
                                            </el-col>
                                            <el-col :span='5' class='float-right'>
                                                <el-button-group class='float-right'>
                                                    <el-button size='mini' type='primary' 
                                                        @click.capture.stop='getItem(cmenu)'>
                                                        详情
                                                    </el-button>
                                                    <el-button size='mini' type='danger'
                                                        @click.capture.stop='updateItem(cmenu.id, {"status" : -1})'>
                                                        删除
                                                    </el-button>
                                                </el-button-group>
                                            </el-col>
                                        </el-row>
                                    </template>

                                    <!-- 三级菜单 -->
                                    <el-row class='menu-item' v-for='ccmenu in cmenu.child' 
                                        :key='ccmenu.id'>
                                        <i class='fa fa-circle-o'></i>
                                        <el-col :span='6'>|-----|-----{{ccmenu.name}}</el-col>
                                        <el-col :span='6'>{{ccmenu.url}}</el-col>
                                        <el-col :span='6' class='align-center'>
                                            <el-tag :type='ccmenu.show == 1 ? "success" : "warning"' 
                                                size='mini'>
                                                {{['隐藏', '显示'][ccmenu.show]}}
                                            </el-tag>
                                        </el-col>
                                        <el-col :span='5' class='float-right'>
                                            <el-button-group class='float-right'>
                                                <el-button size='mini' type='primary' 
                                                    @click.capture.stop='getItem(ccmenu)'>
                                                    详情
                                                </el-button>
                                                <el-button size='mini' type='danger'
                                                    @click.capture.stop='updateItem(ccmenu.id, {"status" : -1})'>
                                                    删除
                                                </el-button>
                                            </el-button-group>
                                        </el-col>
                                    </el-row>
                                </el-collapse-item>
                            </div>
                        </el-collapse>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </div>
        
        <!-- 添加 -->
        <el-dialog title='菜单添加' :visible.sync='dialog.add' width='800px'>
            <el-form :model='aItem' label-width='100px' label-position='right'>
                <el-form-item label='父级:'>
                    <el-select v-model='aItem.pid'>
                        <el-option v-for='menu in pid_options' 
                            :key='menu.id' 
                            :value='menu.id' 
                            :label='menu.name'>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label='名称:'>
                    <el-input v-model='aItem.name'></el-input>
                </el-form-item>
                <el-form-item label='路由:'>
                    <el-input v-model='aItem.url'></el-input>
                </el-form-item>
                <el-form-item label='图标:'>
                    <el-input v-model='aItem.icon'></el-input>
                </el-form-item>
                <el-form-item label='显示:'>
                    <el-radio v-model='aItem.show' label='1'>是</el-radio>
                    <el-radio v-model='aItem.show' label='0'>否</el-radio>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.add = false'>取消</el-button>
                <el-button type='primary' @click='addItem(aItem)'>确定</el-button>
            </div>
        </el-dialog>

        <!-- 编辑 -->
        <el-dialog title='菜单编辑' :visible.sync='dialog.update' width='800px'>
            <el-form :model='nItem' label-width='100px' label-position='right'>
                <el-form-item label='父级:'>
                    <el-select v-model='nItem.pid'>
                        <el-option v-for='menu in pid_options' 
                            :key='menu.id' 
                            :value='menu.id' 
                            :label='menu.name'>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label='名称:'>
                    <el-input v-model='nItem.name'></el-input>
                </el-form-item>
                <el-form-item label='路由:'>
                    <el-input v-model='nItem.url'></el-input>
                </el-form-item>
                <el-form-item label='图标:'>
                    <el-input v-model='nItem.icon'></el-input>
                </el-form-item>
                <el-form-item label='显示:'>
                    <el-radio v-model='nItem.show' label='1'>是</el-radio>
                    <el-radio v-model='nItem.show' label='0'>否</el-radio>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.update = false'>取消</el-button>
                <el-button type='primary' @click='updateItem(nItem.id, nItem)'>确定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script type="text/javascript">
import Request from '@/common/Request'
import Common from '@/common/Common'
import Notify from '@/common/Notify'
export default {
    data    : function(){
        return {
            'list'  : [],
            'dialog': {
                'add'  : false,
                'update'   : false,
                'rules' : false
            },
            'nItem' : {},
            'aItem' : {'pid' : '0', 'show' : '1'},
            'rules' : [],
            'loading'   : false,
            'pid_options' : [{
                'name'  : '顶级菜单',
                'id'    : '0'
            }]
        }
    },
    created : function(){
        this.getList()
    },
    methods : {
        getList: function(){
            var url = Request.createApi('/admin/auth/rule/list')
            Request.get(url, res => {
                this.list = res.list
                this.loadPidOptions()
                this.loading = false
            })

            this.loading = true
        },

        getItem: function(item){
            this.nItem = item
            this.nItem.show = String(item.show)
            this.nItem.pid = String(item.pid)
            this.dialog.update = true;
        },

        addItem: function(item){
            var url = Request.createApi('/admin/auth/rule/add')
            Request.post(url, item, res => {
                this.dialog.add = false
                this.getList()

                Notify.success('添加成功')
            })
        },

        updateItem: function(id, info){
            var url = Request.createApi('/admin/auth/rule/update')
            var param = {
                'id': id,
                'info': info
            }

            Request.post(url, param, res => {
                this.dialog.update  = false
                this.getList()

                Notify.success('修改成功')
            })
        },

        //加载父类菜单选项
        loadPidOptions    : function(){
            this.pid_options    = this.getPidOptions(this.list, 1)

            this.pid_options.splice(0, 0, {
                'name'  : '顶级菜单',
                'id'    : '0'
            })
        },

        getPidOptions: function(list, level){
            var pid_options = []
            var level = level || 1

            for(let k in list){
                let menu = list[k]
                pid_options.push({
                    'name' : Common.getBatchString('|--', level) + menu.name,
                    'id' : String(menu.id)
                })

                if(menu.child){
                    let childs  = this.getPidOptions(menu.child, level + 1)
                    for(let k in childs){
                        pid_options.push(childs[k])
                    }
                }
            }

            return pid_options
        }
    }
}
</script>