<?php
/**
 * Upload.php
 * 文件上传类
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-09-12
 */

// 调用
// $upload = new Upload();
// $info = $upload->upload();

namespace App\Utils;

class Upload {

    private $config     = [
        'save_path' => null,
        'file_exts' => ['jpg', 'jpeg', 'png'],
        'file_size' => 10240000
    ];

    function __construct($config = []){
        $this->config['save_path']  = public_path() . '/upload';
        $this->config   = array_merge($this->config, $config);
    }

    /**
     * 批量执行上传
     * @param   $zip 是否压缩
     * @return  $info
     */
    function upload($zip = false){
        if(empty($_FILES)){
            throw new \Exception('上传文件无效', 707);
        }

        //批量处理文件
        $info   = [];
        foreach($_FILES as $key => $file){
            $info[$key] = $this->uploadOne($file, $zip);
        }

        return $info;
    }

    /**
     * 单个文件上传
     * @param   $file
     * @param   $zip    是否压缩
     * @return  $info
     */
    function uploadOne($file, $zip = false){
        if(!$file['tmp_name']){
            return ['error' => 'The file is empty!'];
        }

        $info   = $this->getFileInfo($file);
        if(!in_array(strtolower($info['ext']), $this->config['file_exts'])){
            throw new \Exception('文件类型被拒绝'.$info['ext'], 708);
        }
        if($info['size'] > $this->config['file_size']){
            throw new \Exception('文件大小超限', 709);
        }
        if(!file_exists($info['save_dir'])){
            mkdir($info['save_dir'], 0775);
        }

        //移动文件
        move_uploaded_file($info['tmp_name'], $info['path']);

        if($zip){
            //压缩文件
            $info['url']    = Image::createThumbnail($info['path']);
            //删除原图片
            unlink($info['path']);
        }

        return $info;
    }

    /**
     * 文件信息追加
     * @param   $file
     * @return  $info
     */
    private function getFileInfo($file){
        $info   = $file;
        $info['ext']    = @end(explode('.', $file['name']));
        $saveName = substr(md5($file['tmp_name']), 0, 12);
        $saveExt = isset($this->config['save_ext']) ? $this->config['save_ext'] : $info['ext'];
        $info['path']   = implode('/', [
            $this->config['save_path'], 
            date('Y-m-d'), 
            $saveName . '.' . $saveExt
        ]);
        $info['save_dir'] = dirname($info['path']);
        $info['save_name'] = $saveName;
        $info['save_ext'] = $saveExt;
        $info['url']  = config('app.url') . '/' . strstr($info['path'], 'upload');
        return $info;
    }  
}
