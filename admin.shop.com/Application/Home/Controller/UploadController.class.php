<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/5
 * Time: 23:12
 */

namespace Home\Controller;


use Think\Controller;
use Think\Upload;

class UploadController extends Controller
{
    public function upload()
    {
        //收集数据
        $config = [
            'mimes' => array('image/jpeg', 'image/png', 'image/gif'), //允许上传的文件MiMe类型
            'maxSize' => 0, //上传的文件大小限制 (0-不做限制)
            'exts' => array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
            'autoSub' => true, //自动子目录保存文件
            'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => './', //保存根路径
            'savePath' => 'Uploads/', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt' => '', //文件保存后缀，空则使用原后缀
            'replace' => false, //存在同名是否覆盖
            'hash' => false, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调，如果存在返回文件信息数组
            'driver' => '', // 文件上传驱动
        ];
        $upload = new Upload($config);


        //保存文件
        $fileinfo = $upload->upload();
//        var_dump($fileinfo);
        $fileinfo = array_pop($fileinfo);
//        var_dump($fileinfo);
//        var_dump($upload->getError(),$fileinfo);
//        exit;
        $data = [];
        if (!$fileinfo) {
            $data = [
                'status' => false,
                'msg' => $upload->getError(),
                'url' => '',
            ];
        } else {
            $url = C('PATHINFO') . $upload->rootPath . $fileinfo['savepath'] . $fileinfo['savename'];
            $data = [
                'status' => true,
                'msg' => '上传成功',
                'url' => $url,
            ];
        }
        //返回结果
        $this->ajaxReturn($data);
    }
}