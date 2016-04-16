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
    	$serverList = C('SERVER_LIST');
    	foreach($serverList as $key => $server) {
    		//判断服务是否正在运行
    		if(!isset($status)) {
    			$status = true;
    		}
    		if($server['ip'] && $server['port']) {
    			$status = check_port($server['ip'], $server['port'], 0.1);
    		}
    		
    		if($status) {
    			$serverList[$key]['status'] = 1;
    			$serverList[$key]['status_desc'] = '运行中';
    		} else {
    			$serverList[$key]['status'] = 0;
    			$serverList[$key]['status_desc'] = '未运行';
    		}
    	}
    	
        $this->assign('serverList', $serverList);
        $this->loadFrame('index');
    }
}