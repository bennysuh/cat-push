<?php
namespace Applications\Push\Library;

use \GatewayWorker\Lib\Gateway;
use \Applications\Push\Utils\Tools;
use \Applications\Push\Model\User;

/**
 * 客户端请求处理
 * @author Evan
 * @since 2016年5月2日
 */
class Action {
	/**
	 * 入口方法
	 * @param unknown $params
	 */
	public function main($params) {
		//判断请求是否存在
		$action = $params['action'];
		if(!method_exists($this,$action)) {
			return Gateway::sendToClient($params['_client_id'], Tools::ReturnJson(-2, '请求不合法'));;
		}
		
		//调用接口
		$this->$action($params);
	}
	
	/**
	 * client_id绑定平台uid
	 * @param array $params
	 */
	private function bindUid($params) {
		if(!isset($params['uid'])) {
			return Gateway::sendToClient($params['_client_id'], Tools::ReturnJson(-1, '参数错误'));
		}
		
		$uid = $params['uid'];
		$client_id = $params['_client_id'];
		
		//判断uid是否为有效用户id
		$userModel = new User();
		$userInfo = $userModel->getUserInfoById($uid);
		if(!$userInfo) {
			return Gateway::sendToClient($params['_client_id'], Tools::ReturnJson(-3, '参数不合法'));
		}
		
		//用户数据
		$userData = [];
		$userData['userInfo'] = $userInfo;
		
		//将客户端id与平台id进行绑定
		Gateway::bindUid($client_id, $uid);
		
		//判断用户是否关联用户组，如果有关联用户组，则将客户端id绑定分组
		$userGroupList = $userModel->getUserGroupList($uid);
		if(is_array($userGroupList) && count($userGroupList) > 0) {
			foreach($userGroupList as $group) {
				Gateway::joinGroup($client_id, $group['id']);
			}
			$userData['userGroupList'] = $userGroupList;
		}
		
		//将用户数据存在session中，以便通过getALLClientInfo方法获取客户端id对应的平台用户信息
		Gateway::setSession($client_id, $userData);
		
		Gateway::sendToClient($client_id, Tools::ReturnJson(999, "您已成功绑定UID：{$uid}"));;
	}
}