# -*- coding: utf-8 -*-
# admin_auth_user_api.py
# 接口测试
# 
# @author  王中艺 <wangzy_smile@qq.com>
# @date    2019-05-22 16:22:30

from call import api;

# 添加用户
result = api('/admin/auth/user/add', {
    'username': 'user06',
    'password': 'verylucky',
    'nickname': 'nickuser06',
    'email': 'user06@yi.dev'
})
user = result['info']
print('new user is: ', user)

# 用户详情
result = api('/admin/auth/user/detail', {
    'id': user['id']
})
user = result['info']
print('user detail is: ', user)

# 编辑用户
user['status'] = 0;
user['mobile'] = '18844677862';
result = api('/admin/auth/user/detail', {
    'id': user['id'],
    'info': user
})
print('detail result is: ', result)

# 修改用户组
result = api('/admin/auth/user/update-groups', {
    'id': user['id'],
    'group_ids': [4, 5]
})
print('update-groups result is: ', result)
