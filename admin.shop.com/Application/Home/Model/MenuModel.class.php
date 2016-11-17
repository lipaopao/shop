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
    //��ȡ���е�Ȩ��
    public function getList() {
        return $this->order('lft')->select();
    }

    public function addMenu() {
        $this->startTrans();
        //����orm
        $orm        = new MySQLORM();
        //����nestedsets
        $nestedsets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
        //ִ�����
        if (($menu_id    = $nestedsets->insert($this->data['parent_id'], $this->data, 'bottom')) === false) {
            $this->error = '���ʧ��';
            $this->rollback();
            return false;
        }
        //����˵���Ȩ�޹�����ϵ
        $menu_permission_model = M('MenuPermission');
        //�ռ�����
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
            $this->error = '����˵���Ȩ�޹�����ϵʧ��';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    public function saveMenu($id) {
        $this->startTrans();
        //��ȡ���ݿ��и����˵�
        $parent_id = $this->where(['id' => $id])->getField('parent_id');
        if ($parent_id != $this->data['parent_id']) {
            //����orm
            $orm        = new MySQLORM();
            //����nestedsets
            $nestedsets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
            if ($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom') === false) {
                $this->error = '�����ƶ����Լ������˵���';
                $this->rollback();
                return false;
            }
        }
        //����˵���Ȩ�޹�����ϵ
        $menu_permission_model = M('MenuPermission');
        //ɾ����ʷ����
        if ($menu_permission_model->where(['menu_id' => $id])->delete() === false) {
            $this->rollback();
            return false;
        }
        //�ռ�����
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
            $this->error = '����˵���Ȩ�޹�����ϵʧ��';
            $this->rollback();
            return false;
        }
        if($this->save() === false){
            return '�޸�ʧ��';
        }
        $this->commit();
        return true;
    }

    /**
     * ɾ���˵��������˵�
     * @param type $id
     * @return boolean
     */
    public function deleteMenu($id) {
        //����orm
        $orm        = new MySQLORM();
        //����nestedsets
        $nestedsets = new NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
        if ($nestedsets->delete($id) === false) {
            $this->error = 'ɾ��ʧ��';
            return false;
        } else {
            return true;
        }
    }

    /**
     * ��ȡ�˵���Ϣ�͹���Ȩ��.
     * @param integer $id
     * @return type
     */
    public function getMenuInfo($id) {
        $row                   = $this->find($id);
        $row['permission_ids'] = json_encode(M('MenuPermission')->where(['menu_id' => $id])->getField('permission_id',true));
        return $row;
    }

    /**
     * ��ȡ�ɼ��Ĳ˵�.
     */
    public function getVisableMenu() {
        //SELECT DISTINCT m.id,m.`name`,m.`path`,m.`parent_id` FROM shop_menu_permission AS mp JOIN shop_menu AS m ON m.`id`=mp.`menu_id` WHERE permission_id IN(1,2,3,4,5,6)
        $admin_info = session('ADMIN_INFO');
        if($admin_info['username'] != '1234'){
            //��ȡȨ���б�
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
