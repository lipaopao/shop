<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/16
 * Time: 9:55
 */

namespace Home\Controller;


use Think\Controller;

class MenuController extends Controller
{
    private $_model = null;
    //实例化对象
    protected function _initialize(){
        $this->_model = D('Menu');
    }
    //展示界面
    public function index(){
        $rows = $this->_model->getList();
        $this->assign('rows',$rows);
        $this->display();
    }
    //添加数据
    public function add(){
        if(IS_POST){
            unset($_POST['id']);
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            if($this->_model->addMenu() === false){
                $this->error($this->_model->getError());
            }else{
                $this->success('添加成功',U('index'));
            }
        }else{
            $this->_before_view();
            $this->display();
        }
    }

    //修改数据
    public function edit($id){
        if(IS_POST){
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->saveMenu($id) === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('修改成功', U('index'));
        }else{
            $this->_before_view();
            //获取数据
            $row = $this->_model->getMenuInfo($id);
            $this->assign('row', $row);
            $this->display('add');
        }
    }
    //删除数据
    public function remove($id){
        if ($this->_model->deleteMenu($id) === false) {
            $this->error(get_error($this->_model));
        } else {
            $this->success('删除成功', U('index'));
        }
    }
//获取所有的权限
    private function _before_view() {
        //获取已有菜单列表,以便设置父级
        $rows = $this->_model->getList();
        array_unshift($rows, ['id' => 0, 'name' => '顶级菜单']);
        $this->assign('menus', json_encode($rows));
        //获取所有的权限列表
        $permissions = D('Permission')->getList();
        $this->assign('permissions', json_encode($permissions));
    }
}