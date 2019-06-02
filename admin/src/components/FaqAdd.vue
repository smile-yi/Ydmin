<template>
    <div class='bodyer'>
        <div class='title'>问答添加</div>
        <div class='content'>
            <div class='box-ops clearfix'>
                <el-button-group>
                    <el-button type='primary' @click='addItem()'>
                        保存
                    </el-button>
                    <el-button type='default' @click='goBack()'>
                        返回
                    </el-button>
                </el-button-group>
            </div>

            <div class='box-cont'>
                <table class='vertical-post'>
                    <tr>
                        <th>短标:</th>
                        <td><el-input v-model='nItem.headline'></el-input></td>
                    </tr>
                    <tr>
                        <th>问题:</th>
                        <td><el-input v-model='nItem.title'></el-input></td>
                    </tr>
                    <tr>
                        <th>作者:</th>
                        <td><el-input v-model='nItem.author' style='width:216px'></el-input></td>
                    </tr>
                    <tr><th>游戏:</th><td>
                        <el-select v-model='nItem.game_id'>
                            <el-option v-for='item in games' :value='item.id' :key='item.id' 
                                :label='item.name'>
                            </el-option>
                        </el-select></td>
                    </tr>
                    <tr><th>图片:</th>
                        <td class='upload-img'>
                            <el-upload
                              :action="uploadUrl"
                              :show-file-list="false"
                              :on-success="uploadImage">
                              <el-button type='primary'>
                                <i class='el-icon-upload'></i>
                              </el-button>
                              <img :src="nItem.image" class="img">
                            </el-upload>
                        </td>
                    </tr>
                    <tr>
                        <th style='vertical-align:top; padding-top:10px;'>描述:</th>
                        <td>
                            <el-input type="textarea" v-model="nItem.description" rows='5'>
                            </el-input>
                        </td>
                    </tr>
                    <tr>
                        <th style='vertical-align:top; padding-top:10px;'>答案:</th>
                        <td style='width:700px'><wangeditor :text.sync='nItem.answer'></wangeditor></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import Request from '@/common/Request'
import Notify from '@/common/Notify'
import Common from '@/common/Common'
import WangEditor from '@/components/utils/WangEditor'
export default {
    data() {
        return {
            nItem: { image: '', game_id: '' },
            uploadUrl: Request.createApi('/public/upload'),
            games: []
        }
    },
    methods: {
        addItem() {
            var url = Request.createApi('/admin/faq/add')
            var param = this.nItem
            console.log(param)
            Request.post(url, param, res => {
                Notify.success('添加成功')
                Common.route('/admin/faq/list')
            })
        },

        goBack() {
            Common.route('/admin/faq/list')
            return true
        },

        uploadImage(res, file) {
            this.nItem.image = res.data.list.file.url
        }
    },
    components: {
        'wangeditor' : WangEditor
    },
    created() {
        var url = Request.createApi('/admin/game/list?where[status]=1')
        Request.get(url, res => {
            this.games = res.list
            if(this.games.length > 0){
                this.nItem.game_id = this.games[0].id
            }
        })
    }
}
</script>
