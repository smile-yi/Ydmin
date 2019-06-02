<template>
    <div class='bodyer'>
        <div class='title'>游戏列表</div>
        <div class='content' v-loading='loading'>
            <div class='box-ops'>
                <el-button-group>
                    <el-button type='primary' @click="dialog.add = true">添加</el-button>
                </el-button-group>
            </div>
            <div class='box-cont'>
                <el-table :data='list' border>
                    <el-table-column prop='id' label='#ID' width='50px'></el-table-column>
                    <el-table-column prop='name' label='#昵称'></el-table-column>
                    <el-table-column label='#模板'>
                        <template slot-scope='scope'>{{scope.row.faq_tpl}}</template>
                    </el-table-column>
                    <el-table-column label='#问答首页'>
			<template slot-scope='scope'><a :href='scope.row.faq_link'>点击查看</a></template>
		    </el-table-column>
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
                                    @click='getItem(scope.row)'>
                                    详情
                                </el-button>
                                <el-button size='micro' type='warning' 
                                    v-if='scope.row.status == 1' 
                                    @click='updateItem(scope.row.id, {status : 0})'>
                                    禁用
                                </el-button>
                                <el-button size='micro' type='success' 
                                    v-if='scope.row.status == 0' 
                                    @click='updateItem(scope.row.id, {status : 1})'>
                                    启用
                                </el-button>
                                <el-button size='micro' type='danger' 
                                    @click='updateItem(scope.row.id, {status : -1})'>
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
                    @current-change='getList'
                ></el-pagination>
            </div>
        </div>

        <!-- 编辑 -->
        <el-dialog title='游戏编辑' :visible.sync='dialog.update' width='800px'>
            <el-form :model='nItem' label-width='100px' label-position='right'>
                <el-form-item label='游戏名称:'>
                    <el-input v-model='nItem.name'></el-input>
                </el-form-item>
                <el-form-item label='问答模板:'>
                    <span>{{nItem.faq_tpl}}</span>
                    <el-button type='default' circle @click='downloadFaqTpl(nItem)'>
                        <i class='el-icon-download'></i>
                    </el-button>
                    <el-upload style='display:inline-block'
                      :action="uploadFaqTplUrl"
                      :show-file-list="false"
                      :on-success="uploadSuccess">
                      <el-button type='primary' circle>
                        <i class='el-icon-upload2'></i>
                      </el-button>
                    </el-upload>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.update = false'>取消</el-button>
                <el-button type='primary' @click='updateItem(nItem.id, nItem)'>确定</el-button>
            </div>
        </el-dialog>

        <!-- 添加 -->
        <el-dialog title='游戏添加' :visible.sync='dialog.add' width='800px'>
            <el-form :model='aItem' label-width='100px' label-position='right'>
                <el-form-item label='游戏名称:'>
                    <el-input v-model='aItem.name'></el-input>
                </el-form-item>
                <el-form-item label='问答模板:'>
                    <span>{{aItem.faq_tpl}}</span>
                    <el-button type='default' circle @click='downloadFaqTpl()'>
                        <i class='el-icon-download'></i>
                    </el-button>
                    <el-upload style='display:inline-block'
                      :action="uploadFaqTplUrl"
                      :show-file-list="false"
                      :on-success="uploadFaqTpl">
                      <el-button type='primary' circle>
                        <i class='el-icon-upload2'></i>
                      </el-button>
                    </el-upload>
                </el-form-item>
            </el-form>

            <div slot='footer' class='dialog-footer'>
                <el-button @click='dialog.add = false'>取消</el-button>
                <el-button type='primary' @click='addItem(aItem)'>确定</el-button>
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
            list: [],
            count: 100,
            page: 1,
            dialog: {
                add: false,
                update: false
            },
            loading: false,
            nItem: {},
            aItem: {'faq_tpl' : 'default.faq'},
            uploadFaqTplUrl: Request.createApi('/admin/game/upload-faq-tpl'),
        }
    },
    created(){
        this.getList()
    },
    methods: {
        //列表获取
        getList(page){
            var page = page || 1
            var url = Request.createApi('/admin/game/list?page='+page)
            Request.get(url, res => {
                this.list = res.list
                this.page = page
                this.count = res.page_info.count
                this.loading = false
            })
            this.loading = true
        },

        //详情
        getItem(item){
            this.nItem = item
            this.dialog.update = true
        },

        //添加
        addItem(item){
            var url = Request.createApi('/admin/game/add')
            var param = item

            Request.post(url, param, res => {
                this.aItem = {'faq_tpl' : 'default.faq'}
                this.getList(1)
                this.loading = false
            })

            this.loading = true
        },

        //上传成功
        uploadSuccess(res, file) {
            this.nItem.faq_tpl = res.data.info.tpl_name
        },

        //上传模版
        uploadFaqTpl(res, file){
            this.aItem.faq_tpl = res.data.info.tpl_name
        },

        //下载问答模板
        downloadFaqTpl(item){
            var id = item ? item.id : ''
            var url = Request.createApi('/admin/game/download-faq-tpl?id='+id)
            window.open(url, '_blank')
        },

        //更新
        updateItem(id, info) {
            var url = Request.createApi('/admin/game/update')
            var param = {
                id: id,
                info: info
            }

            Request.post(url, param, res => {
                this.dialog.update = false
                this.getList(this.page)
                this.loading = false
            })

            this.loading = true
        }
    }
}
</script>
