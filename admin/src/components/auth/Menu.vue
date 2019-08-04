<template>
    <div class='page-container'>
        <div class='title'>菜单列表</div>
        <div class='body' v-loading='loading'>
            <div class='execute'>
                <el-button-group>
                    <el-button type='primary' @click="dialog.add = true">添加</el-button>
                </el-button-group>
            </div>
            <div class='content tree-rule'>
                <el-tree :data='list' :props='{children: "childs", label: "name", is_menu: "is_menu"}'>
                    <el-row slot-scope="{node, data}" style='width: 100%' class='rule-item'>
                        <el-col :span=6>{{data.name}}</el-col>
                        <el-col :span=6>{{data.url}}</el-col>
                        <el-col :span=6>
                            <el-tag :type='data.is_menu == 1 ? "success" : "warning"' size='mini'>
                                {{["隐藏", "展示"][data.is_menu]}}
                            </el-tag>
                        </el-col>
                        <el-col :span=6>
                            <el-button-group>
                                <el-button size='mini' type='primary' 
                                    @click.capture.stop='getItem(data)'>
                                    详情
                                </el-button>
                                <el-button size='mini' type='success' v-if="data.status == 2"
                                    @click.capture.stop='updateItem(data.id, {"status" : 1})'>
                                    启用
                                </el-button>
                                <el-button size='mini' type='warning' v-if="data.status == 1"
                                    @click.capture.stop='updateItem(data.id, {"status" : 2})'>
                                    禁用
                                </el-button>
                                <el-button size='mini' type='danger'
                                    @click.capture.stop='updateItem(data.id, {"status" : 9})'>
                                    删除
                                </el-button>
                            </el-button-group>
                        </el-col>
                    </el-row>
                </el-tree>
            </div>
        </div>
        
        <!-- 添加 -->
        <el-dialog title='菜单添加' :visible.sync='dialog.add' width='700px'>
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
                    <el-radio v-model='aItem.is_menu' label='1'>是</el-radio>
                    <el-radio v-model='aItem.is_menu' label='0'>否</el-radio>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.add = false'>取消</el-button>
                <el-button type='primary' @click='addItem(aItem)'>确定</el-button>
            </div>
        </el-dialog>

        <!-- 编辑 -->
        <el-dialog title='菜单编辑' :visible.sync='dialog.update' width='700px'>
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
                    <el-radio v-model='nItem.is_menu' label='1'>是</el-radio>
                    <el-radio v-model='nItem.is_menu' label='0'>否</el-radio>
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
    data() {
        return {
            'list'  : [],
            'dialog': {
                'add'  : false,
                'update'   : false,
                'rules' : false
            },
            'nItem' : {},
            'aItem' : {'pid' : '0', 'is_menu' : '1'},
            'rules' : [],
            'loading'   : false,
            'pid_options' : [{
                'name'  : '顶级菜单',
                'id'    : '0'
            }]
        }
    },
    created() {
        this.getList()
    },
    methods: {
        getList: function(){
            var url = Request.createApi('/admin/auth/rule/list?format=tree')
            Request.get(url, res => {
                this.list = res.list
                console.log(this.list)
                this.loadPidOptions()
                this.loading = false
            })

            this.loading = true
        },

        getItem: function(item){
            this.nItem = item
            this.nItem.is_menu = String(item.is_menu)
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

        append(data, node) {
            console.log(data, node)
        },

        //加载父类菜单选项
        loadPidOptions: function(){
            this.pid_options    = this.getPidOptions(this.list, 1)

            this.pid_options.splice(0, 0, {
                'name'  : '顶级菜单',
                'id'    : '0'
            })
        },

        getPidOptions(list, level) {
            var pid_options = []
            var level = level || 1

            for(let k in list){
                let menu = list[k]
                pid_options.push({
                    'name' : Common.getBatchString('|--', level) + menu.name,
                    'id' : String(menu.id)
                })

                if(menu.childs){
                    let childs  = this.getPidOptions(menu.childs, level + 1)
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