# -*- coding: utf-8 -*-
# api.py
# api工具
# 
# @author  王中艺 <wangzy_smile@qq.com>
# @date    2018-06-16 14:13:47

import requests
import json

class Api:
    # 接口前缀
    prefixUrl   = 'http://api.yingda.local.com/'
    token   = 'D9BC40BEC6080388B7A0AC9B1FBFE933'
    # 请求头信息
    headers     = {
        'Content-type'  : 'application/json',
        'Authorization'   : 'D9BC40BEC6080388B7A0AC9B1FBFE933'
    }

    @classmethod
    def get(self, url):
        url     = self.prefixUrl + url
        if url.find('?') > 0:
            url     += '&token='+self.token
        else:
            url     += '?token='+self.token

        return requests.get(url, headers = self.headers).text

    @classmethod
    def post(self, url, params):
        url     = self.prefixUrl + url
        if url.find('?') > 0:
            url     += '&token='+self.token
        else:
            url     += '?token='+self.token

        return requests.post(url, json.dumps(params), headers = self.headers).text
     