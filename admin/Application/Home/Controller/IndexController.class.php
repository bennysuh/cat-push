<?php
namespace Home\Controller;
use Home\Controller\BaseController;

/**
 * 系统首页
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-10
 */

class IndexController extends BaseController {
    /**
     * 首页
     */
    public function index(){
        $this->assign('tcp', C('TCP_SERVER'));
        $this->assign('websocket', C('WEBSOCKET_SERVER'));
        $this->assign('http', C('HTTP_SERVER'));
        $this->assign('channel', C('CHANNEL_SERVER'));
        $this->loadFrame('index');
    }
}