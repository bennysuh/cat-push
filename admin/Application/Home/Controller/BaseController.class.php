<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 基础控制器
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-10
 */

class BaseController extends Controller {
    public function __construct() {
        parent::__construct();
        
        $this->checkLogin();
    }
    
    /**
     * 渲染页面
     * @param type $template
     */
    protected function loadFrame($template = '') {   
        $this->assign('menuList',C('MENU_LIST'));
        $this->assign('currMenu',curr_uri());
        $this->display('Public:header');
        $this->display('Public:left');
        $this->display($template);
        $this->display('Public:footer');
    }
    
    /**
     * 检测是否登录
     */
    private function checkLogin() {
        $user = session('_USER_');
        
        if(!$user) {
            header('Location:/login');
            return false;
        }
        
        return $user;
    }
}