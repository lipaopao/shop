<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/8
 * Time: 12:39
 */

namespace Home\Controller;


use Think\Controller;

class GoodsCategoryController extends Controller
{
    private $_model;
    public function _initialize(){
        $this->_model= D('GoodsCategory');
    }
    //չʾ��ҳ����
    public function index(){
        $rows = $this->_model->getList();
        $this->assign('rows',$rows);
        $this->display();
    }
    //�������
    public function add(){
        if(IS_POST){
            //��ȡ����
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            if($this->_model->addCategory() === false){
                $this->error($this->_model->getError());
            }else{
                $this->success('��ӳɹ�',U('index'));
            }
        }else{
            //��ȡ���еķ���
            $rows = $this->_model->getList();
            $this->assign('rows',$rows);
            $this->display();
        }
    }
    //�޸�����
    public function edit($id){
        if(IS_POST){
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            if($this->_model->editCategory() === false){
                $this->error($this->_model->getError());
            }else{
                $this->success('�޸ĳɹ�',U('index'));
            }
        }else{
//            echo 1;
            $row = $this->_model->find($id);
            $this->assign("row",$row);
            //��ȡ���еķ���
            $cond = [
                'id'=>['neq',$id],
//                'parent_id'=>['neq',$id],
                'lft'=>['lt',$row['lft']],
                'rght'=>['gt',$row['rght']],
                '_logic'=>'or',

            ];
            $rows = $this->_model->getList($cond);
            $this->assign('rows',$rows);
            $this->display('edit');
        }

    }
    //ɾ������
    public function remove($id) {
        if ($this->_model->deleteCategory($id) === false) {
            $this->error($this->_model->getError());
        }
        $this->success('ɾ���ɹ�', U('index'));
    }
}