<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/8
 * Time: 12:40
 */

namespace Home\Model;


use Home\Logic\MySQLORM;
use Home\Logic\NestedSets;
use Think\Model;

class GoodsCategoryModel extends Model
{
    /*
     * 获取数据*/
    public function getList(array $cond = []){
        return $this->order('lft')->where($cond)->select();
    }
    //添加方法
    public function addCategory(){
        $orm        = new MySQLORM();
        $NestedSets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');

        return $NestedSets->insert($this->data['parent_id'], $this->data, 'bottom');
    }
//修改数据
    public function editCategory() {
        //判断是否修改了父级分类
        //获取原来的父级分类
        $old_parent_id = $this->where(['id' => $this->data['id']])->getField('parent_id');
        if ($old_parent_id != $this->data['parent_id']) {
            //需要计算左右节点和层级，那么我们还是要使用nestedsets
            $orm        = new MySQLORM();
            $nestedSets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
            if ($nestedSets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom') === false) {
                $this->error = '不能将分类移动到后代分类中';
                return false;
            }
        }
        return $this->save();
    }
    public function deleteCategory($id) {
        //需要计算左右节点和层级，那么我们还是要使用nestedsets
        $orm        = new MySQLORM();
        $nestedSets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
        if ($nestedSets->delete($id) === false) {
            $this->error = '删除失败';
            return false;
        }
        return true;
    }
}