<?php
namespace Applications\Push\Model;

use \Applications\Push\Utils\DB;

/**
 * 用户模型
 * @author Evan
 * @since 2016年5月2日
 */
class User {
	/**
	 * 根据用户id查询用户信息
	 * @param int $uid 用户id
	 */
	public function getUserInfoById($uid) {
		$sql = 'SELECT * FROM `tb_user` WHERE `id`=:id AND `status`=0 LIMIT 1';
		$userInfo = DB::selectOne($sql, ['id'=>$uid]);
		return $userInfo;
	}
	
	/**
	 * 查询用户关联的分组
	 * @param int $uid
	 */
	public function getUserGroupList($uid) {
		$sql = 'SELECT g.`id`,g.`name` 
				FROM `tb_user_group` ug 
				LEFT JOIN `tb_group` g 
				ON ug.`group_id`=g.`id` 
				WHERE ug.`uid`=:uid AND ug.`status`=0 AND g.`status`=0 AND g.`id` IS NOT NULL';
		$groupList = DB::select($sql, ['uid'=>$uid]);
		return $groupList;
	}
}