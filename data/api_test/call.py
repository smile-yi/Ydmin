# -*- coding: utf-8 -*-
# call.py
# 请求
# 
# @author  王中艺 <wangzy_smile@qq.com>
# @date    2019-05-22 16:24:40

import requests
import json

def api(api, params):
    url = 'http://yesadmin.local.com' + api
    header = {
        'Content-type': 'application/json'
    }

    req = requests.post(url, json.dumps(params), headers = header)
    res = json.loads(req.text)
    if res['errno'] > 0:
        print(api, json.dumps(res))
        exit(res['errno'])
    else:
        return res['data']
     