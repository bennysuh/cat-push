<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 登录
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-10
 */

class LoginController extends Controller {
    /**
     * 登录
     */
    public function index(){
        if(session('_USER_')) {
            header("Location:/");
            return false;
        }
        $this->display('login');
    }
    
    /**
     * 登录验证
     */
    public function loginCommit() {
        $username = I('post.username');
        $password = I('post.password');
        
        if(!$username || !$password) {
            return Response(2001, '用户名或密码不能为空');
        }
        
        if($username == 'admin' && $password == 'admin') {
            session('_USER_', $username);
            return Response(999, '登录成功', array('url'=>'/'));
        } else {
            return Response(2002, '用户名或密码错误');
        }
    }
    
    /**
     * 退出登录
     */
    public function logout() {
        session('_USER_', null);
        header("Location:/login");
    }
}