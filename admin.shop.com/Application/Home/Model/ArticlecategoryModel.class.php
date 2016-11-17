<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/6
 * Time: 15:16
 */

namespace Home\Model;


use Think\Model;
use Think\Page;

class ArticlecategoryModel extends Model
{
    //自动验证规则
    protected $_validate        =   array(
        ['name','require','名称不能为空'],
    );
    //获取分页数据
    public function getPageResult(array $cond = []){
        $cond = array_merge(['status'=>['neq',-1]],$cond);
        $pageSize = 3;
        //获取总条数
        $count = $this->where($cond)->count();
        $page = new Page($count,$pageSize);
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('theme','%FIRST% %HEADER% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $pageBar = $page->show();
        //查询数据
        $rows = $this->where($cond)->page(I('get.p'),$pageSize)->order('sort')->select();

        $cont = [
            'pageBer'=>$pageBar,
            'rows'=>$rows,
        ];
        return $cont;
    }
}