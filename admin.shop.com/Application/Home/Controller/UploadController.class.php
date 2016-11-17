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
        //�ռ�����
        $config = [
            'mimes' => array('image/jpeg', 'image/png', 'image/gif'), //�����ϴ����ļ�MiMe����
            'maxSize' => 0, //�ϴ����ļ���С���� (0-��������)
            'exts' => array('jpg', 'gif', 'png', 'jpeg'), //�����ϴ����ļ���׺
            'autoSub' => true, //�Զ���Ŀ¼�����ļ�
            'subName' => array('date', 'Y-m-d'), //��Ŀ¼������ʽ��[0]-��������[1]-�������������ʹ������
            'rootPath' => './', //�����·��
            'savePath' => 'Uploads/', //����·��
            'saveName' => array('uniqid', ''), //�ϴ��ļ���������[0]-��������[1]-�������������ʹ������
            'saveExt' => '', //�ļ������׺������ʹ��ԭ��׺
            'replace' => false, //����ͬ���Ƿ񸲸�
            'hash' => false, //�Ƿ�����hash����
            'callback' => false, //����ļ��Ƿ���ڻص���������ڷ����ļ���Ϣ����
            'driver' => '', // �ļ��ϴ�����
        ];
        $upload = new Upload($config);


        //�����ļ�
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
                'msg' => '�ϴ��ɹ�',
                'url' => $url,
            ];
        }
        //���ؽ��
        $this->ajaxReturn($data);
    }
}