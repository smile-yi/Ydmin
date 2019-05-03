<?php
/**
 * Image.php
 * 图像类工具
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-11-21
 */

namespace App\Utils;
use Intervention\Image\ImageManagerStatic as ImageTools;

class Image {

    /**
     * 缩略图生成
     * @param   $url
     * @param   $savePath
     * @return  $url
     */
    static function createThumbnail($url, $savePath = null){
        $savePath || $savePath = public_path() . '/thumbnail/' . date('Y-m-d');
        file_exists($savePath) || mkdir($savePath,  0775);
        $filePath   = $savePath . '/' . basename($url);

        ImageTools::make($url)->resize(200, null, function($con){
            $con->aspectRatio();
        })->save($filePath);

        $filePath   = strstr($filePath, 'thumbnail');
        return config('app.url') . '/' . $filePath;
    }
}