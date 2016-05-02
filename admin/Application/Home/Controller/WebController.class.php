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
    	//获取用户列表
    	$userList = \Home\ORG\Utils\Gateway::getALLClientInfo();
    	
    	$this->assign('userList', $userList);
        $this->loadFrame('user_list');
    }
    
    /**
     * 推送消息
     */
    public function pushMsg() {
        $this->loadFrame('push_msg');
    }
    
    /**
     * 提交推送
     */
    public function pushCommit() {
    	$type = I('post.type');
    	$content = I('post.content');
    	
    	!in_array($type, ['all','group','part']) && $type = 'all';
    	
    	if(!$content) {
    		return Response(1001,'推送内容不能为空');
    	}
    	
    	//初始化GatewayClient
    	$serverList = C('SERVER_LIST');
    	\Home\ORG\Utils\Gateway::$registerAddress = $serverList['registerServer']['ip'].':'.$serverList['registerServer']['port'];
    	
    	switch ($type) {
    		case 'all':
    			//推送给所有用户
    			\Home\ORG\Utils\Gateway::sendToAll(ReturnJson(999,$content));
    			break;
    		case 'group':
    			//推送给指定用户组用户
    			$groups = I('post.groupList');
    			$groupList = explode(',', $groups);
    			if(!$groupList) {
    				return Response(1002,'请添加要推送的用户组');
    			}
    			foreach ($groupList as $group) {
    				\Home\ORG\Utils\Gateway::sendToGroup($group, ReturnJson(999,$content));
    			}
    			break;
    		case 'part':
    			//推送给指定用户
    			$uids = I('post.uidList');
    			$uidList = explode(',', $uids);
    			if(!$uidList) {
    				return Response(1003,'请添加要推送的用户');
    			}
    			foreach ($uidList as $uid) {
    				\Home\ORG\Utils\Gateway::sendToClient($uid, ReturnJson(999,$content));
    			}    			
    			break;
    	}
    	
    	return Response(999,'推送成功');
    }
}