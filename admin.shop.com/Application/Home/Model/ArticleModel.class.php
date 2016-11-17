<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/6
 * Time: 11:27
 */

namespace Home\Model;


use Think\Model;
use Think\Page;

class ArticleModel extends Model
{
    //自动验证规则
    protected $_validate        =   array(
        ['name','require','名称不能为空'],
    );
    protected $_auto        =   array(
        ['inputtime',NOW_TIME,'','string'],
    );

    //获取分页数据
    public function getPageResult(array $cond = []){

        $cond = array_merge(['articlestatus'=>['neq',-1]],$cond);
        $pageSize = 3;
        //获取总条数
        $count = $this->where($cond)->join("articlecategory as ac on article.article_category_id=ac.id")->count();
//        dump($this->getLastSql());
        $page = new Page($count,$pageSize);
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('theme','%FIRST% %HEADER% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $pageBar = $page->show();
        //查询数据
        $rows = $this->field('article.*,ac.name')->order('articlesort')->where($cond)->page(I('get.p'),$pageSize)->join("articlecategory as ac on article.article_category_id=ac.id")->select();
//        $rows = $this->execute("select * from article as ac JOIN articlecategory as act ON ac.");
        $cont = [
            'pageBer'=>$pageBar,
            'rows'=>$rows,
        ];
        return $cont;
    }
}