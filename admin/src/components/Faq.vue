<template>
    <div class='bodyer'>
        <div class='title'>问答列表</div>
        <div class='content' v-loading='loading'>
            <div class='box-ops'>
                <el-button-group>
                    <el-button type='primary' @click="addItem()">添加</el-button>
                </el-button-group>

                <el-input v-model='where.keyword' style='width: 400px;' 
                    class='float-right input-with-select' 
                    placeholder='标题'>
                    <el-select v-model='where.game_id' slot='prepend' placeholder='游戏'>
                        <el-option v-for='item in games' :value='item.id' :key='item.id' 
                            :label='item.name'>
                        </el-option>
                    </el-select>
                    <el-button type="primary" slot='append' icon='el-icon-search'
                        @click='getList(1)'></el-button>
                </el-input>
            </div>
            <div class='box-cont'>
                <el-table :data='list' border>
                    <el-table-column prop='id' label='#ID' width='50px'></el-table-column>
                    <el-table-column prop='game.name' label='#游戏'></el-table-column>
                    <el-table-column prop='title_sim' label='#标题'></el-table-column>
                    <el-table-column prop='description_sim' label='#描述'></el-table-column>
                    <el-table-column prop='author' label='#作者'></el-table-column>
                    <el-table-column label='#链接'>
                        <template slot-scope='scope'>
                            <a :href='scope.row.link' target='_blank'>查看</a>
                        </template>
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
                                <el-button size='micro' type='primary' 
                                    @click='push(scope.row)'>
                                    推送
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
    </div>
</template>

<style>
  .el-select .el-input {
    width: 120px;
  }
  .input-with-select .el-input-group__prepend {
    background-color: #fff;
  }
</style>

<script type="text/javascript">
import Request from '@/common/Request'
import Notify from '@/common/Notify'
import Common from '@/common/Common'
import Storage from '@/common/Storage'
export default {
    data() {
        return {
            list: [],
            count: 100,
            page: 1,
            loading: false,
            nItem: {},
            aItem: {},
            uploadFaqTplUrl: Request.createApi('/admin/game/upload-faq-tpl'),
            games: [],
            where: {
                keyword: '',
                game_id: ''
            }
        }
    },
    created(){
        this.getList()
        var url = Request.createApi('/admin/game/list?where[status]=1')
        Request.get(url, res => {
            this.games = res.list
            if(this.games.length > 0){
                this.nItem.game_id = this.games[0].id
            }
        })
    },
    methods: {
        //列表获取
        getList(page){
            var page = page || 1
            var url = Request.createApi('/admin/faq/list?page='+page)
            url += Request.httpBuildQuery({ 'where': this.where })
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
            Storage.set('faq', item)
            Common.toUrl('/admin/faq/update')
            return true
        },

        //添加
        addItem(item){
            Common.toUrl('/admin/faq/add')
            return true
        },

        //推送
        push(item){
            var url = Request.createApi('/admin/faq/push?id='+item.id)
            Request.get(url, res => {
                Notify.success('推送成功')
            })
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
            var url = Request.createApi('/admin/faq/update')
            var param = {
                id: id,
                info: info
            }

            Request.post(url, param, res => {
                this.getList(this.page)
                this.loading = false
            })

            this.loading = true
        }
    }
}
</script>
