<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/9
 * Time: 9:56
 */

namespace Home\Model;


use Think\Model;
use Think\Page;

class GoodsModel extends Model
{
    //自动验证规则
    protected $_validate        =   array(
        ['name','require','名称不能为空'],
    );
    protected $_auto        =   array(
        ['inputtime',NOW_TIME,'','string'],
    );
    //展示分页
    public function getList(array $cond = []){
        //查询所有数据
        $cond = array_merge(['goods.status'=>['neq',-1]],$cond);
        $pageSize = 5;
        //获取数据的总条数
        $count = $this->field('goods.*,gc.name as categoryname,b.brandName')->where($cond)->join("goods_category as gc on goods.goods_category_id=gc.id")->join("brand as b on goods.brand_id=b.id")->count();
        //实例化分页
        $page = new Page($count,$pageSize);
        //设置样式
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('header','个条数据');
        $page->setConfig('theme','%TOTAL_ROW% %FIRST% %HEADER% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        //生成分页
        $pageBar = $page->show();
        //查询数据
        $rows = $this->field('goods.*,gc.name as categoryname,b.brandName')->where($cond)->page(I('get.p'),$pageSize)->join("goods_category as gc on goods.goods_category_id=gc.id")->join("brand as b on goods.brand_id=b.id")->select();

        $cont = [
            'pageBer'=>$pageBar,
            'rows'=>$rows,
        ];
        return $cont;
    }
    //添加一个方法获取所有的下拉框数据
    public function selectList(array $cond = []){
        //获取商品分类
        $goodsCategoryModel = D('GoodsCategory');
        $categorys = $goodsCategoryModel->where($cond)->order('lft')->field('id,name,level')->select();
        //获取商品品牌
        $brandModel = D('Brand');
        $brands = $brandModel->where($cond)->field('id,brandname')->select();
        $rows = [
            'categorys' => $categorys,
            'brands' => $brands,
        ];
        //写入数据
        return $rows;
    }

    //添加一个方法生成货号
    public function createSn(){
        $time = date('Ymd',time());
        //一共有多少个商品
        $goods_count_model = M('GoodsDayCount');
        //如果是今天第一个商品，就插一条数据
        if(!($count = $goods_count_model->getFieldByDay($time,'count'))){
            $count =1;
            $data = array(
                'day' => $time,
                'count' => $count,
            );
            $goods_count_model->add($data);
        }else{
            $count++;
            $goods_count_model->where(array('day'=>$time))->setInc('count',1);
        }
        $goods_sn = 'SN'.$time.str_pad($count,5,'0',STR_PAD_LEFT);
        return $goods_sn;
    }

    //创建一个方法遍历多选框
    public function goodsStatus(array $goods_status = []){
        $num = '';
        foreach($goods_status as $val){
            $num += $val;
        }
        return $num;
    }

    //回显对选款的值
    //封装添加数据的方法
    public function goodsadd(){
        $this->startTrans();
        $goods_status = I('post.goods_status');
        $content = I('post.content');
        //获取图片的路径
        $url = I('post.path');
//        dump($url);
//        exit;
        //遍历多选框值的方法
        $_POST['goods_status'] = $this->goodsStatus($goods_status);
        //获取所有所有数据
        if($this->create() === false){
            $this->getError();
        }
        //添加成功时返回的id值
        $id = $this->add();
        if($id === false){
            $this->getError();
            $this->rollback();
        }else{
            $data = [
                'goods_id' => $id,
                'content' => $content,
            ];
            //实例化对象
            $goodsIntroModel = D('GoodsIntro');
            //添加文章表的数据
            if($goodsIntroModel->add($data) === false){
                $goodsIntroModel->getError();
                $this->rollback();
            }else {
                $data = [];
                foreach($url as $v){
                    $data[] = [
                        'goods_id' => $id,
                        'path' =>$v,
                    ];
                }
                $goodsGalleryModel = D('GoodsGallery');
                if ($goodsGalleryModel->addAll($data) === false) {
                    $goodsIntroModel->getError();
                    $this->rollback();
                    return false;
                }
            }
        }
        $this->commit();
        return;
    }

}