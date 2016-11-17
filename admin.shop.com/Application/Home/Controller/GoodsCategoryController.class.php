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
    //展示首页数据
    public function index(){
        $rows = $this->_model->getList();
        $this->assign('rows',$rows);
        $this->display();
    }
    //添加数据
    public function add(){
        if(IS_POST){
            //获取数据
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            if($this->_model->addCategory() === false){
                $this->error($this->_model->getError());
            }else{
                $this->success('添加成功',U('index'));
            }
        }else{
            //获取已有的分类
            $rows = $this->_model->getList();
            $this->assign('rows',$rows);
            $this->display();
        }
    }
    //修改数据
    public function edit($id){
        if(IS_POST){
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            if($this->_model->editCategory() === false){
                $this->error($this->_model->getError());
            }else{
                $this->success('修改成功',U('index'));
            }
        }else{
//            echo 1;
            $row = $this->_model->find($id);
            $this->assign("row",$row);
            //获取已有的分类
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
    //删除数据
    public function remove($id) {
        if ($this->_model->deleteCategory($id) === false) {
            $this->error($this->_model->getError());
        }
        $this->success('删除成功', U('index'));
    }
}