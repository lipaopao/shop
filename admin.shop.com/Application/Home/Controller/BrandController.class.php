<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/5
 * Time: 11:20
 */

namespace Home\Controller;


use Think\Controller;

class BrandController extends Controller
{
    //展示商品视图
    public function index(){
        //搜索
        $keyWord = trim(I('get.brandName'))?trim(I('get.brandName')) : '';
        $config = [];
        if($keyWord){
            $config['brandName'] = ['like','%' . $keyWord . '%'];

        }
        //实例化模型
        $brandModel = D('Brand');
        //查询所有的语句
        $cont = $brandModel->getPageResult($config);
        //写入数据
        $this->assign("rows",$cont);
        $this->display();
    }
    //添加商品
    public function add(){
        if(IS_POST){
            //创建模型
            $brandModel = D('Brand');
            //获取数据,进行自动验证
            if($brandModel->create() === false){
                echo $brandModel->getError();
            }
            if($brandModel->add() === false){
                echo $brandModel->getError();
            }else{
                $this->success('添加成功',U('index'));
            }
        }else{
            $this->display();
        }
    }
    //编辑商品
    public function edit($id){
        $brandModel = D('Brand');
        if(IS_POST){
            //获取数据
            if($brandModel->create() === false){
                $this->error($brandModel->getError());
            }
            //保存
            if($brandModel->save() === false){
                $this->error($brandModel->getError());
            }else{
                $this->success('修改成功',U('index'));
            }
        }else{
            $row = $brandModel->find($id);
//            dump($row);
//            exit;
            $this->assign('row',$row);
            $this->display('add');
        }
    }

    public function remove($id) {
        $brandModel = D('Brand');
        if(!$brandModel->where(['id'=>$id])->setField('brandShow', -1)){
           echo $brandModel->getError();
        }else{
            $this->success('删除成功',U('index'));
        }
    }
}