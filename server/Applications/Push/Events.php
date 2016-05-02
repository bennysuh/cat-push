<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
use \Applications\Push\Utils\Tools;
use \Applications\Push\Library\Action;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events {
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id) {
        // 向当前client_id发送数据 
    	$client_msg = "恭喜您，成功连接到服务器。您的ID为：{$client_id}";
        Gateway::sendToClient($client_id, Tools::ReturnJson(999, $client_msg));
        
        // 向所有人发送当前在线人数
        $online_num = Gateway::getAllClientCount();
        Gateway::sendToAll(Tools::ReturnJson(1001, "当前在线人数", ['num'=>$online_num]));
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message) {
   		$msg = Tools::JsonToArray($message);
   		if(!is_array($msg) || !isset($msg['action'])) {
   			Gateway::sendToClient($client_id, Tools::ReturnJson(-1, '参数错误'));
   			return false;
   		}
   		
   		//处理客户端请求
   		$msg['_client_id'] = $client_id;
   		$action = new Action();
   		$action->main($msg);
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id) {
       // 向所有人发送 
       GateWay::sendToAll("$client_id logout");
   }
}
