# -*- coding: utf-8 -*-
# admin_auth_rule.py
# 规则控制器测试
# 
# @author  王中艺 <wangzy_smile@qq.com>
# @date    2019-05-22 16:50:59

from call import api
import json

# 菜单列表
result = api('/admin/auth/rule/list', {
    'user_id': 6,
    'format': 'tree'
})

print('rule list is: ', json.dumps(result['list']))

# 添加菜单
result = api('/admin/auth/rule/add', {
    'name': '游戏管理',
    'pid': 0,
    'url': '/admin/game/index',
    'is_menu': 1,
    'icon': '',
})
menu = result['info']
print('new menu is: ', menu)

result = api('/admin/auth/rule/add', {
    'name': '游戏列表',
    'pid': menu['id'],
    'url': '/admin/game/list',
    'is_menu': 1,
    'icon': ''
})
print('new menu is: ', result['info'])