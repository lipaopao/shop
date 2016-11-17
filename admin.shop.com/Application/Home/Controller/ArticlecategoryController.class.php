<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/6
 * Time: 12:33
 */

namespace Home\Controller;

use Home\Model;
use Think\Controller;

class ArticlecategoryController extends Controller
{
    public function index(){
        $cond = [];
        //实例化对象
        $articlecategoryModel = D('Articlecategory');

        $rows = $articlecategoryModel->getPageResult($cond);
        $this->assign("rows",$rows);
        $this->display();
    }
    //添加分类
    public function add(){
        $articlecategoryModel = D('Articlecategory');
        if(IS_POST){
            if($articlecategoryModel->create() === false){
                $this->error($articlecategoryModel->getError());
            }
            if($articlecategoryModel->add() === false){
                $this->error($articlecategoryModel->getError());
            }else{
                $this->success('添加成功',U('index'));
            }
        }else{
            $this->display();
        }
    }
    //修改分类
    public function edit($id)
    {
        $articlecategoryModel = D('Articlecategory');
        if (IS_POST) {
            //获取数据
            if ($articlecategoryModel->create() === false) {
                $this->error($articlecategoryModel->getError());
            }
            //保存
            if ($articlecategoryModel->save() === false) {
                $this->error($articlecategoryModel->getError());
            } else {
                $this->success('修改成功', U('index'));
            }
        } else {
            $row = $articlecategoryModel->find($id);
            $this->assign('row', $row);
            $this->display('add');
        }
    }
        //删除分类
        public function remove($id) {
            $articlecategoryModel = D('Articlecategory');
            if(!$articlecategoryModel->where(['id'=>$id])->setField('status', -1)){
                echo $articlecategoryModel->getError();
            }else{
                $this->success('删除成功',U('index'));
            }
        }
}