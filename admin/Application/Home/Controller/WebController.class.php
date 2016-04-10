<?php
namespace Home\Controller;
use Home\Controller\BaseController;

/**
 * WEB消息推送
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-10
 */

class WebController extends BaseController {
    /**
     * 在线用户列表
     */
    public function userList() {
        $this->loadFrame('user_list');
    }
    
    /**
     * 推送消息
     */
    public function pushMsg() {
        $this->loadFrame('push_msg');
    }
}