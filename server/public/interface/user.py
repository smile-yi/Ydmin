# -*- coding: utf-8 -*-
# user.py
# 用户接口测试
# 
# @author  王中艺 <wangzy_smile@qq.com>
# @date    2018-06-22 12:33:25

from api import Api

# # 用户列表
# url = '/admin/user/list?page=1&where[keyword]=user'
# print(Api.get(url))

# # 用户信息更改
# url = 'admin/user/update'
# param   = {
#     'id'    : 1,
#     'info'  : {
#         'status'    : 'forbid'
#     }
# }
# print(Api.post(url, param))

# # 充值列表
# url     = 'admin/recharge/list?page=1&where_user[keyword]=user'
# print(Api.get(url))

# # 兑换列表
# url     = 'admin/redeem-game/list?page=1'
# print(Api.get(url))