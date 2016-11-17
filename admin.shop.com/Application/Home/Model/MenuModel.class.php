<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/16
 * Time: 9:58
 */

namespace Home\Model;


use Home\Logic\MySQLORM;
use Home\Logic\NestedSets;
use Think\Model;

class MenuModel extends Model
{
    //获取所有的权限
    public function getList() {
        return $this->order('lft')->select();
    }

    public function addMenu() {
        $this->startTrans();
        //创建orm
        $orm        = new MySQLORM();
        //创建nestedsets
        $nestedsets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
        //执行添加
        if (($menu_id    = $nestedsets->insert($this->data['parent_id'], $this->data, 'bottom')) === false) {
            $this->error = '添加失败';
            $this->rollback();
            return false;
        }
        //保存菜单和权限关联关系
        $menu_permission_model = M('MenuPermission');
        //收集数据
        $permission_ids        = I('post.permission_id');
        if (empty($permission_ids)) {
            $this->commit();
            return true;
        }

        $data = [];
        foreach ($permission_ids as $permission_id) {
            $data[] = [
                'menu_id'       => $menu_id,
                'permission_id' => $permission_id,
            ];
        }
        if ($menu_permission_model->addAll($data) === false) {
            $this->error = '保存菜单和权限关联关系失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    public function saveMenu($id) {
        $this->startTrans();
        //获取数据库中父级菜单
        $parent_id = $this->where(['id' => $id])->getField('parent_id');
        if ($parent_id != $this->data['parent_id']) {
            //创建orm
            $orm        = new MySQLORM();
            //创建nestedsets
            $nestedsets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
            if ($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom') === false) {
                $this->error = '不能移动到自己或后代菜单中';
                $this->rollback();
                return false;
            }
        }
        //保存菜单和权限关联关系
        $menu_permission_model = M('MenuPermission');
        //删除历史关联
        if ($menu_permission_model->where(['menu_id' => $id])->delete() === false) {
            $this->rollback();
            return false;
        }
        //收集数据
        $permission_ids = I('post.permission_id');
        if (empty($permission_ids)) {
            $this->commit();
            return true;
        }

        $data = [];
        foreach ($permission_ids as $permission_id) {
            $data[] = [
                'menu_id'       => $id,
                'permission_id' => $permission_id,
            ];
        }
        if ($menu_permission_model->addAll($data) === false) {
            $this->error = '保存菜单和权限关联关系失败';
            $this->rollback();
            return false;
        }
        if($this->save() === false){
            return '修改失败';
        }
        $this->commit();
        return true;
    }

    /**
     * 删除菜单及其后代菜单
     * @param type $id
     * @return boolean
     */
    public function deleteMenu($id) {
        //创建orm
        $orm        = new MySQLORM();
        //创建nestedsets
        $nestedsets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
        if ($nestedsets->delete($id) === false) {
            $this->error = '删除失败';
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取菜单信息和关联权限.
     * @param integer $id
     * @return type
     */
    public function getMenuInfo($id) {
        $row                   = $this->find($id);
        $row['permission_ids'] = json_encode(M('MenuPermission')->where(['menu_id' => $id])->getField('permission_id',true));
        return $row;
    }

    /**
     * 获取可见的菜单.
     */
    public function getVisableMenu() {
        //SELECT DISTINCT m.id,m.`name`,m.`path`,m.`parent_id` FROM shop_menu_permission AS mp JOIN shop_menu AS m ON m.`id`=mp.`menu_id` WHERE permission_id IN(1,2,3,4,5,6)
        $admin_info = session('ADMIN_INFO');
        if($admin_info['username'] != '1234'){
            //获取权限列表
            $pids = session('ADMIN_PIDS');
//            dump($pids);
//            exit;
            if (empty($pids)) {
                return ;
            }
            return $this->distinct(true)->field('m.id,m.name,m.path,m.parent_id')->alias('m')->join('__MENU_PERMISSION__ as mp ON m.`id`=mp.`menu_id`')->where(['permission_id'=>['in',$pids]])->select();
        }else{
            return $this->distinct(true)->field('m.id,m.name,m.path,m.parent_id')->alias('m')->select();
        }

    }

}
