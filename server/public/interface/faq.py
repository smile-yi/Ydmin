# -*- coding: utf-8 -*-
# faq.py
# faq接口测试
# 
# @author  王中艺 <wangzy_smile@qq.com>
# @date    2018-06-17 21:45:36

from api import Api

# url = 'admin/faq/add'
# params  = {
#     'relate_type'   : 'game',
#     'relate_id' : 0,
#     'question'  : '杜甫属于哪个朝代？',
#     'answer'    : '唐朝'
# }
# print(Api.post(url, params))

# 列表
url = 'admin/faq/list?where[relate_type]=game&where[keyword]=静夜思'
print(Api.get(url))

# # 修改
# url = 'admin/faq/update'
# params  = {
#     'id'    : 2,
#     'info'  : {
#         'question'  : '将进酒的作者是谁?',
#         'answer'    : '李白'
#     }
# }
# print(Api.post(url, params))

