# -*- coding: utf-8 -*-
# api.py
# api测试
# 
# @author  王中艺 <wangzy_smile@qq.com>
# @date    2019-05-22 16:03:38

from call import api

# 创建用户组
result = api('/admin/auth/group/add', {
    'name': '测试组4',
    'desc': '测试组5'
})
group = result['info']
print('group: ', group)

# 修改用户组权限
result = api('/admin/auth/group/update', {
    'id': group['id'],
    'info' : {
        'name': group['name'],
        'desc': group['desc'],
        'status': group['status'],
        'rule_ids': [3, 4]
    }
})

# 获取用户组列表
result = api('/admin/auth/group/list', {})
print('group list: ', result['list'])


