<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/9
 * Time: 9:32
 */

namespace Home\Controller;


use Think\Controller;

class GoodsController extends Controller
{
    //展示商品数据
    public function index(){
        $goodsModel = D('Goods');

        /*
         * 获取关键字
         * */
        $keyWord = trim(I('get.keyword'))?trim(I('get.keyword')) : '';
        /*
         * 获取商品下拉框的值
         * */
        $category = trim(I('get.category'))?trim(I('get.category')) : '';
        /*
         * 获取品牌的值
         * */
        $barand = trim(I('get.brand'))?trim(I('get.brand')) : '';
        //
        $config = [];
        if($keyWord){
            $config['goods.name'] = ['like','%' . $keyWord . '%'];
        }
        if($category){

            $config['goods_category_id']=$category;
        }
        if($barand){
            $config['brand_id']=$barand;

        }
        //查询所有语句并惊醒分页
        $cont = $goodsModel->getList($config);
        //获取商品分类
        $rows = $goodsModel->selectList();
        //写入数据
        $this->assign('rows',$rows);
        //写入数据
        $this->assign('cont',$cont);
        //获取商品分类
        $this->display();
    }
    //添加数据
    public function add(){
        $goodsModel = D('Goods');
        if(IS_POST){
            if($goodsModel->goodsadd() === false){
                $this->error('添加失败');
            }else {
                $this->success('添加成功', U('index'));
            }
        }else{
            //获取品牌
            $sn = $goodsModel->createSn();
            //获取商品分类
            $rows = $goodsModel->selectList();
            //写入数据
            $this->assign('rows',$rows);
            $this->assign('sn',$sn);
            $this->display();
        }
    }
    //添加一个方法编辑数据
    public function edit($id){
        $goodsModel = D('Goods');
        $goodsIntroOneModel = D('GoodsIntro');
        $goodsGalleryModel = D('GoodsGallery');
        if(IS_POST){
            //开启事物
            $goodsModel->startTrans();
            $goods_status = I('post.goods_status');
            $content = I('post.content');
            //获取图片的路径
            $url = I('post.logo');
            //遍历多选框值的方法
            $_POST['goods_status'] = $goodsModel->goodsStatus($goods_status);
            //获取所有所有数据
            if($goodsModel->create() === false){
                $this->error($goodsModel->getError());
            }
            //修改
            if($goodsModel->save() === false){
                $this->error($goodsModel->getError());
                //实现回滚
                $goodsModel->rollback();
            }else{
                $data = [
                    'goods_id' => $id,
                    'content' => $content,
                ];
                //实例化对象
                $goodsIntroModel = D('GoodsIntro');
                //修改文章表的数据
                if($goodsIntroModel->save($data) === false){
                    $this->error($goodsIntroModel->getError());
                    //实现回滚
                    $goodsModel->rollback();
                }else {
                    $urlData = [
                        'goods_id' => $id,
                        'path' =>$url,
                    ];
                    $goodsGalleryModel = D('GoodsGallery');

                    if($goodsGalleryModel->where(['goods_id'=>$id])->save($urlData)===false){
                        return $goodsGalleryModel->getError();
                    }
                }
            }
            //提交
            $goodsModel->commit();
            $this->success('修改成功', U('index'));
        }else{
            $goods_stts = array(
                1 => '精品',
                2 => '热销',
                4 => '新品',
            );
            //搜索一条数据
            $goodsOne = $goodsModel->find($id);
            $goods_sts = $goodsOne['goods_status'];
            $goods_sts = explode(',',$goods_sts);
            $num = array();
                foreach($goods_stts as $key=>$values) {
                    foreach($goods_sts as $value) {
                        if ($values == $value) {
                            $num[] = $key;
                        }
                    }
                }
            $goodsOne['goods_status'] = json_encode($num);
//            dump($goodsOne);
//            exit;
            $goodsIntroOne = $goodsIntroOneModel->where(['id' =>$id])->find();
            //获取商品分类
            $rows = $goodsModel->selectList();
            $this->assign('goodsOne',$goodsOne);
            $this->assign('goodsIntroOne',$goodsIntroOne);
            //写入数据
            $this->assign('rows',$rows);
            $this->display('edit');
        }
    }

    //添加一个方法进行逻辑删除
    public function remove($id){
        //实例化对象
        $goodsModel = D('Goods');
        if(!$goodsModel->where(['id'=>$id])->setField('status', -1)){
            $this->error($goodsModel->getError());
        }else{
            $this->success('删除成功',U('index'));
        }
    }

    //查看文章数据
    public function content($id){
        $data = [
            'goods_id' =>$id,
            'content' =>'',
        ];
        $goodsIntroModel = D('GoodsIntro');
        if(!$goodsIntroModel->where(['goods_id'=>$id])->find()){
            $goodsIntroModel->add($data);
        }else {
            $row = $goodsIntroModel->where(['goods_id' => $id])->find();
        }

        $this->assign('row',$row);
        $this->display('content');

    }
}