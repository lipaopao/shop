<?php
/**
 * Created by PhpStorm.
 * User: liyuhang
 * Date: 2016/11/13
 * Time: 10:21
 */

namespace Home\Behaviors;


use Think\Behavior;

class CheckBehavior extends Behavior
{
    public function run(&$params)
    {
        //执行逻辑
        //增加排除列表 login captcha
        $ignores = C('RABC.IGONRE');
        $url = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;

        if(in_array($url,$ignores)){
            return;
        }
        //检查用户是否登陆
        if(!$admininfo = session('ADMIN_INFO')){
            //尝试自动登录
            if (!$admininfo = D('Admin')->autoLogin()) {
                $url = U('Home/Admin/login');
                redirect($url);
            }
        }else{

        }
        //一登陆用户忽略列表
        $user_ignores = C('RABC.USER_IGONRE');
        if(in_array($url,$user_ignores)){

            return true;
        }

        //超级管理员
        if($admininfo['username']=='1234'){
            return true;
        }
        //检查rbac权限路径
        $permission = session('ADMIN_PATH');
        if (in_array($url, $permission)) {
            return true;
        } else {
            echo '<script type="text/javascript">alert("无权访问");history.back();</script>';
            exit;
        }
        //没有登陆跳转到登陆界面
    }

}