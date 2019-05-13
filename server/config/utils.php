<?php
/**
 * util.php
 * 工具包配置文件
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2019-05-10 10:43:47
 */


return [
    'log' => [
        'dir' => dirname(__FILE__) . '/../storage/logs/',
    ],
    'common' => [
        'salt' => 'H3wsn0EJzqWkwqPKAr4e',
    ],
    'base64' => [
        'map' => 'OBrsYZajklWApqKLM6Evwz012FbgQRSTUtu3Jn7Ncde45mxGHIfXyoPDChiV89+/'
    ],
    'upload' => [
        'dir' => dirname(__FILE__) . '/../public/upload/',
        'ext' => ['*'],
        'size' => 1024 * 1024 * 100
    ]
];
     