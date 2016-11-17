<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/6
 * Time: 11:26
 */

namespace Home\Controller;


use Think\Controller;

class ArticleController extends Controller
{
    //文章模型
    public function index(){
        $keyWord = trim(I('get.name'))?trim(I('get.name')) : '';
        $cond = [ ];
        if($keyWord){
            $cond[] = [
                'articlename'=> ['like','%' . $keyWord . '%'],
                'name' => ['like','%' . $keyWord . '%'],
                '_logic'=>'or',
            ];
//            $cond['articlename'] = ['like','%' . $keyWord . '%'];
//            $cond['name'] = ['like','%' . $keyWord . '%'];
        }
        //重数据库获取数据
        $articleModel = D('Article');
        $rows = $articleModel->getPageResult($cond);
        $this->assign('rows',$rows);
        $this->display();
    }
    //添加文章
    public function add(){
        $articleModel = D('Article');
        $articlecategoryModel = D('Articlecategory');
        if(IS_POST){
            $content = I('post.content');
            if($articlecategoryModel->create() === false){
                $this->error($articlecategoryModel->getError());
            }
            if($articleModel->create() === false){
                $this->error($articleModel->getError());
            }
            $id=$articleModel->add();
            if($id === false){
                $this->error($articleModel->getError());
            }else{
                $cond = [
                  'id'=>$id,
                  'content'=>$content,
                ];
//                dump($cond);
//                exit;
                $articledetailModel = D('Articledetail');
                if($articledetailModel->add($cond) === false){
                    $this->error($articledetailModel->getError());
                }else{
                        $this->success('添加成功', U('index'));
                }
            }
        }else{
            $brandModel = D('Articlecategory');
            $rows = $brandModel->field('id,name')->select();
            $this->assign('rows',$rows);
            $this->display();
        }
    }
    //修改文章
    public function edit($id)
    {
        $articleModel = D('Article');
        $articlecategoryModel = D('articlecategory');
        if (IS_POST) {
            //获取数据
            if ($articlecategoryModel->create() === false) {
                $this->error($articleModel->getError());
            }
            if ($articleModel->create() === false) {
                $this->error($articleModel->getError());
            }
            //保存
            if ($articleModel->save() === false) {
                $this->error($articleModel->getError());
            } else {
                $this->success('修改成功', U('index'));
            }
        } else {
            //实例化对象
            $articlecategoryModel = D('Articlecategory');
            //查询
            $names = $articlecategoryModel->select();
            $row = $articleModel->find($id);
            $this->assign('row', $row);
            $this->assign('names',$names);
            $this->display('edit');
        }
    }
    //删除文章
    public function remove($id) {
        $articleModel = D('Article');
        $articledetailModel = D('Articledetail');
        if(!$articleModel->where(['id'=>$id])->setField('articlestatus', -1)){
            echo $articleModel->getError();
        }else{
            $this->success('删除成功',U('index'));
        }
    }

    //编辑文章详情
    public function content($id){
        $articledetailModel = D('Articledetail');
        $articleModel = D('Article');
        if(IS_POST){
            if ($articledetailModel->create() === false) {
                $this->error($articledetailModel->getError());
            }
            //保存
            if ($articledetailModel->save() === false) {
                $this->error($articledetailModel->getError());
            } else {
                $this->success('修改成功', U('index'));
            }
        }else{
            //实例化对象
            if(!$row1 = $articledetailModel->where(['id'=>$id])->select()){
                $row1 = ['content'=>'','id'=>$id];
                $articledetailModel->add($row1);
            }else{
                $row1 = $articledetailModel->where(['id'=>$id])->find();
            }
//            dump($row1);
//            exit;
//            dump($articledetailModel->getLastSql());
            $row2 = $articleModel->find($id);
//            dump($articleModel->getLastSql());
            $this->assign('row1',$row1);
            $this->assign('row2',$row2);
            $this->display('content');
        }
    }
}