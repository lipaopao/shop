<?php

/**
 * @link http://blog.kunx.org/.
 * @copyright Copyright (c) 2016-11-13 
 * @license kunx-edu@qq.com.
 */

namespace Home\Model;
use Home\Logic\MySQLORM;
use Home\Logic\NestedSets;
use Think\Model;
class PermissionModel extends Model {

    //put your code here
    protected $patchValidate = true;
    protected $_validate = [
        ['name', 'require', '权限名称不能为空'],
        ['parent_id', 'require', '父级不能为空'],
    ];

    /**
     * 获取权限列表。
     *
     */
    public function getList() {
        return $this->order('lft')->select();
    }
    /**
     * 添加权限。
     *
     */
    public function addPermission() {
        //使用nestedsets完成左右节点和层级的计算。
        $orm        = new MySQLORM();
        $nestedsets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
        if ($nestedsets->insert($this->data['parent_id'], $this->data, 'bottom') === false) {
            $this->error = '添加失败';
            return false;
        }
        return true;
    }

    /**
     * 保存权限。
     * @return boolean
     */
    public function savePermission() {
        //修改左右节点和层级
        //判断是否需要移动
        //获取db中的父级分类
        $parent_id  = $this->where(['id' => $this->data['id']])->getField('parent_id');
        if($parent_id != $this->data['parent_id']){
            //使用nestedsets完成左右节点和层级的计算。
            $orm        = new MySQLORM;
            $nestedsets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
            if ($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom') === false) {
                $this->error = '不能将分类移动到自身或后代分类中';
                return false;
            }
        }

        //保存基本信息
        return $this->save();
    }
    //移出数据
    public function delPermissssion($id){
        $this->startTrans();
        $getone = $this->find($id);
            $data = [
                'lft' =>['egt',$getone['lft']],
                'rght' =>['elt',$getone['rght']],
                '_logic'=>'and',
            ];
        $rows = $this->field(['id'])->where($data)->select();
            $rolePermission = D('rolePermission');
            foreach($rows as $v) {
                if($rolePermission->where(['permission_id'=>$v])->delete()===false){
                    $this->rollback();
                    $this->getError();
                }
            }
        //需要计算左右节点和层级，那么我们还是要使用nestedsets
        $orm        = new MySQLORM();
        $nestedSets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
        if ($nestedSets->delete($id) === false) {
            $this->error = '删除失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }
}
