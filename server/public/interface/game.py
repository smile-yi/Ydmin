# -*- coding: utf-8 -*-
# game.py
# 游戏类接口测试
# 
# @author  王中艺 <wangzy_smile@qq.com>
# @date    2018-06-16 12:51:42

from api import Api

# # 游戏添加
# url     = 'admin/game/add'
# params  = {
#     'name'  : '机甲神兵',
#     'icon'  : 'http://ossweb-img.qq.com/images/lol/img/champion/Aatrox.png',
#     'depict': '远古的暗裔族群中，曾有一位举世无双的剑术大师亚托克斯，他将浴血的战场看作狂欢的乐园。后来，他被敌人用魔法禁锢在了自己的剑中。他蛰伏了千年，只为等待一个合适的宿主到来—— 一位凡人武士持起亚托克斯的同时，也被这把活体武器侵蚀腐化，并改变了形态，至此，亚托克斯获得了重生。',
#     'redeem_rate'   : 10,
#     'link_ios'  : '',
#     'link_android'  : ''
# }
# print(Api.post(url, params))

# # 游戏列表
# url     = 'admin/game/list?page=1'
# print(Api.get(url))


# # 游戏更改
# url     = 'admin/game/update'
# params  = {
#     'id'    : 1,
#     'info'  : {
#         'name'  : '机甲神兵2',
#         'redeem_rate'   : 20,
#         'status'    : 'delete'
#     }
# }
# print(Api.post(url, params))


# # 区服添加
# url     = 'admin/game-server/add'
# params  = {
#     'game_id'   : 1,
#     'number'    : 1,
#     'name'  : '第一服',
#     'redeem_url'    : 'http://sina.com'
# }
# print(Api.post(url, params))


# # 区服列表
# url     = 'admin/game-server/list?where[game_id]=1&page=1'
# print(Api.get(url))


# 区服更改
# url     = 'admin/game-server/update'
# params  = {
#     'id'    : 2,
#     'name'  : '风雨同舟',
#     'number'    : 2,
#     'redeem_url'    : 'http://game.com/fengyutngzhou',
#     'status'    : 'delete'
# }
# print(Api.post(url, params))