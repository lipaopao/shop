<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/10
 * Time: 16:39
 */

namespace Home\Model;


use Think\Model;
use Think\Page;

class AdminModel extends Model
{


    protected $patchValidate = true;

    /**
     * 1.管理员名称不能为空  不能重复
     * 2.密码不能为空 6-16位
     * 3.确认密码和密码一致
     * 4.邮箱不能为空  满足email规则
     */
    protected $_validate = [
        ['username', 'require', '管理员名称不能为空'],
        ['username', '', '管理员名称已被占用', self::EXISTS_VALIDATE, 'unique', 'reg'],
        ['password', 'require', '密码不能为空'],
        ['password', '6,16', '密码长度不合法', self::EXISTS_VALIDATE, 'length'],
        ['repassword', 'password', '两次密码不一致', self::EXISTS_VALIDATE, 'confirm'],
//        ['email', 'require', '邮箱不能为空'],
//        ['email', 'email', '邮箱不合法'],
//        ['email', '', '邮箱已被占用', self::EXISTS_VALIDATE, 'unique', 'reg'],
//        ['captcha','require','验证码不能为空'],
//        ['captcha', 'validateCaptcha', '验证码不正确', self::EXISTS_VALIDATE, 'callback'],
    ];
    protected $_auto     = [
        ['add_time', NOW_TIME, 'reg'],
        ['salt', '\Org\Util\String::randString', 'reg', 'function'],
    ];

    /**
     * 验证验证码是否匹配.
     * @param string $captcha
     * @return boolean
     */
//    protected function validateCaptcha($captcha) {
//        $verify = new \Think\Verify();
//        return $verify->check($captcha);
//    }

    /**
     * 获取分页数据
     * @param array $cond
     * @return type
     */
    public function getPageResult(array $cond = array()) {
        $count     = $this->where($cond)->count();
        $page      = new  Page($count, C('PAGE.SIZE'));
        $page->setConfig('theme', C('PAGE.THEME'));
        $page_html = $page->show();
        $rows      = $this->where($cond)->page(I('get.p'), C('PAGE.SIZE'))->select();
        return compact('rows', 'page_html');
    }

    /**
     * 添加管理员,并且保存管理员关联的角色.
     * @return boolean
     */

    public function addAdmin() {
        $this->startTrans();
        $this->data['password'] = salt_mcrypt($this->data['password'], $this->data['salt']);

        if(($admin_id = $this->add())===false){
            $this->rollback();
            return false;
        }
        //保存角色关联
        $admin_role_model = M('AdminRole');
        $role_ids = I('post.role_id');
        if(empty($role_ids)){
            $this->commit();
            return true;
        }
        $data = [];
        foreach($role_ids as $role_id){
            $data[] = [
                'admin_id'=>$admin_id,
                'role_id'=>$role_id,
            ];
        }
        if($admin_role_model->addAll($data)===false){
            $this->error = '保存角色关系失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 获取管理员信息,包括关联的角色.
     * @param integer $id 管理员id.
     * @return array
     */
    public function getAdminInfo($id) {
        $row = $this->find($id);
        $row['role_ids'] = json_encode(M('AdminRole')->where(['admin_id'=>$id])->getField('role_id',true));
        return $row;
    }

    /**
     * 删除管理员账号
     * @param integer $id 管理员id.
     * @return boolean
     */
    public function deleteAdmin($id) {
        //删除管理员记录
        return $this->delete($id);
    }

    /**
     * 用户登录
     * 由于用户名\密码\验证必填 以及 验证码是否匹配已经自动验证完成,所以只需要检查用户名密码是否匹配即可.
     */
    public function login() {
        //1.获取用户的盐
        //1.1获取用户信息
        $username   = $this->data['username'];
        $password   = $this->data['password'];
        $admin_info = $this->getByUsername($username); //调用find获取数据，所以之后的data属性都是数据库中的数据
        if (empty($admin_info)) {
            $this->error = '用户名或密码不匹配';
            return false;
        }
        //1.2获取盐
        $salt     = $admin_info['salt'];
        //2.给用户提交的密码加盐加密
        $password = salt_mcrypt($password, $salt);

        //3.比较密码是否匹配
        if ($password == $admin_info['password']) {
            //存储登录时间和ip  保存会话信息
            $data = [
                'last_login_time' => NOW_TIME,
                'last_login_ip'   => get_client_ip(1),
                'id'              => $admin_info['id'],
            ];
            $this->save($data);
            //保存用户信息到session中
            session('ADMIN_INFO', $admin_info);
            //保存cookie信息
            $this->_saveToken($admin_info,I('post.remember'));
            $this->_permission();

            return true;
        } else {
            $this->error = '用户名或密码不匹配';
            return false;
        }
    }

    /**
     * 生成令牌，保存到cookie和db中
     * @param type $admin_info
     */
    private function _saveToken($admin_info,$is_remember=false) {
        //如果勾选了记住密码，就生成token
        if($is_remember){
            //生成随机字符串，我们习惯上称之为令牌token
            $token = \Org\Util\String::randString(32);
            //存储到cookie一份
            $data  = [
                'id'    => $admin_info['id'],
                'token' => $token,
            ];
            cookie('AUTO_LOGIN_TOKEN', $data, 604800);
            //存储到数据库一份
            $this->save($data);
        }
    }

    /**
     * 完成用户自动登录
     * @return type
     */
    public function autoLogin() {
        //获取cookie数据
        $cookie = cookie('AUTO_LOGIN_TOKEN');
        //如果没有cookie，就返回空
        if(empty($cookie)){
            return [];
        }
        //检查数据库中是否有匹配的记录
        if($admin_info = $this->where($cookie)->where(['token'=>['neq','']])->find()){
            //更新令牌
            $this->_saveToken($admin_info,true);
            //保存管理员信息到session中
            session('ADMIN_INFO', $admin_info);
            //查看用户的权限
            $this->_permission();
            return $admin_info;
        }else{
            return [];
        }
    }

    public function saveAdmin($id) {
//        dump($this->save());
//        echo 111;
//        exit;
        $this->startTrans();
        //删除历史角色
        $admin_role_model = M('AdminRole');
        if($admin_role_model->where(['admin_id'=>$id])->delete()=== false){
            $this->rollback();
            return false;
        }
        //添加新的角色关联
        $role_ids = I('post.role_id');
        if(empty($role_ids)){
            $this->commit();
            return true;
        }
        $data = [];
        foreach($role_ids as $role_id){
            $data[] = [
                'admin_id'=>$id,
                'role_id'=>$role_id,
            ];
        }
        if($admin_role_model->addAll($data)===false){
            $this->error = '保存角色关系失败';
            $this->rollback();
            return false;
        }
        //4.提交
        $this->commit();
        return true;
    }
    //用户登陆时保存用户权限
    public function _permission(){
        $admininfo = session('ADMIN_INFO');
        $permissions = D('AdminRole')->alias('ar')->field('p.id,path')->join('Role_Permission as rp using(role_id)')->join('Permission as p on rp.permission_id=p.id')->where(['ar.admin_id'=>$admininfo['id']])->select();
        $pathes = [];
        foreach($permissions as $permission){
            $pathes[] = $permission['path'];
            $permission_ids[] = $permission['id'];
        }
//        dump($pathes);
//        exit;
        session('ADMIN_PATH',$pathes);
        session('ADMIN_PIDS', $permission_ids);
    }
}