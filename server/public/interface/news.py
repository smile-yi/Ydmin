# -*- coding: utf-8 -*-
# news.py
# 咨询借口
# 
# @author  王中艺 <wangzy_smile@qq.com>
# @date    2018-06-17 21:13:15

from api import Api

# # 新闻添加
# url = 'admin/news/add'
# params   = {
#     'relate_type' : 'game',
#     'relate_id' : 1,
#     'title' : '私奔的女人',
#     'banner' : 'https://upload-images.jianshu.io/upload_images/1210979-72aea2c42f6ce3e9.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/599',
#     'brief' : '本文参加简书七大主题征文活动',
#     'content' : '这条回家的路，已不是记忆中的坑洼不平，拉着两个孩子，远远地，看着山坡上的二层小楼。她踌躇着，不敢往家的方向走，爸妈还好吗？哥哥嫂子还好吗？侄儿小虎该长成大小伙儿了吧？'
# }
# print(Api.post(url, params))

# # 新闻列表
# url = 'admin/news/list?where[relate_type]=game&where[relate_id]=1&page=1'
# print(Api.get(url))

# # 新闻修改
# url = 'admin/news/update'
# params  = {
#     'id' : 2,
#     'info' : {
#         'status' : 'delete',
#         'title' : '修改的标题',
#     }
# }
# print(Api.post(url, params))