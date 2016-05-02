<?php
namespace Applications\Push\Utils;

use \PDO;

/**
 * 数据库工具类
 * @author Evan
 * @since 2016年5月2日
 */
class DB {
	
	//PDO实例数组
	protected static $instances;
	
	//当前PDO实例数组的key
	protected static $pdo_key;
	
	//PDOStatement对象
	protected static $PDOStatement;

	//数据库配置
	protected static $config = [];
	
	//当前要执行的sql
	protected static $sql;
	
	//执行sql返回的错误信息
	protected static $error = [];
	
	//被尝试执行sql的信息（执行失败）
	protected static $executed_sql = [];
	
	//执行sql回调
	protected static $query_callback = null;
	
	//默认加载的数据库配置文件
	protected static $db_config = 'online';
	
	/**
	 * 设置回调
	 * @param string $callback_function
	 */
	public static function setQueryCallback($callback_function) {
		self::$query_callback = $callback_function;
	}
	
	/**
	 * 查询一行数据
	 * @param unknown $sql
	 * @param array $params
	 */
	public static function selectOne($sql, $params = array()) {
		$res = self::select($sql, $params, false);
		if ($res) {
			return $res[0];
		}
		return $res;
	}
	
	/**
	 * 查询多行数据
	 * @param string $sql
	 * @param array $params
	 * @param string $with_column_key
	 */
	public static function select($sql, $params=[], $with_column_key=false) {
		if (self::Query($sql, $params)) {
			if ($with_column_key) {
				$rows = array();
				while ($row = self::$PDOStatement->fetch(PDO::FETCH_ASSOC)) {
					$rows[$row[$with_column_key]] = $row;
				}
				return $rows;
			} else {
				return self::$PDOStatement->fetchAll(PDO::FETCH_ASSOC);
			}
		} else {
			return false;
		}
	}
	
	/**
	 * 插入数据
	 * @param string $sql
	 * @param array $params
	 * @return 返回新插入数据的id，失败返回false
	 */
	public static function insert($sql, $params=[]) {
		$ext_info = 'last_id';
		$res = self::Query($sql, $params, $ext_info);
		if($res) {
			return $ext_info;
		}
		return $res;
	}
	
	/**
	 * 删除数据
	 * @param string $sql
	 * @param array $params
	 * @return 返回受影响行数，失败返回false
	 */
	public static function delete($sql, $params=[]) {
		$ext_info = 'rows';
		$res = self::Query($sql, $params, $ext_info);
		if ($res) {
			return $ext_info;
		}
		return $res;
	}
	
	/**
	 * 更新数据
	 * @param string $sql
	 * @param array $params
	 * @return 返回受影响行数，失败返回false
	 */
	public static function update($sql, $params=[]) {
		$ext_info = 'rows';
		$res = self::Query($sql, $params, $ext_info);
		if ($res) {
			return $ext_info;
		}
		return $res;
	}
	
	/**
	 * 获取受影响行数
	 */
	public static function affectedRows() {
		return self::$PDOStatement->rowCount();
	}
	
	/**
	 * 执行sql
	 * @param string $sql 要执行的sql
	 * @param array $params 绑定的参数
	 * @param string $ext_info 扩展信息
	 */
	public static function query($sql, $params = [], &$ext_info = null) {
		self::$sql = $sql;
		$time = microtime(true);
		
		//实例化PDO
		self::$pdo_key = self::getInstance();
		//预处理sql
		self::$PDOStatement = self::$instances[self::$pdo_key]->prepare($sql);
		//绑定参数
		if(is_array($params) && count($params) > 0) {
			foreach ($params as $key => $value) {
				if(is_int($value)) {
					$PARAM_TYPE = PDO::PARAM_INT;
				} else {
					$PARAM_TYPE = PDO::PARAM_STR;
				}
				if(is_numeric($key)) {
					self::$PDOStatement->bindValue($key + 1, $value, $PARAM_TYPE);
				} else {
					self::$PDOStatement->bindValue(':' . $key, $value, $PARAM_TYPE);
				}
			}
		}
		
		//执行sql
		$res = self::$PDOStatement->execute();
		
		$time = microtime(true) - $time;
		
		if($res) {
			switch ($ext_info) {
				case 'last_id':
					$ext_info = self::$instances[self::$pdo_key]->lastInsertId();
					break;
				case 'rows':
					$ext_info = self::$PDOStatement->rowCount();
					break;
				default:
					// TODO...
					break;
			}
			
			//如果设置了回调，则执行回调
			self::$query_callback && call_user_func_array(self::$query_callback, ['sql'=>$sql, 'params'=>$params]);
		} else {
			self::$error[] = self::$PDOStatement->errorInfo();
			self::$executed_sql[] = ['sql'=>$sql, 'params'=>$params, 'time'=>$time, 'error'=>self::$PDOStatement->errorInfo()];
		}
		return $res;
	}
	
	/**
	 * 实例化PDO对象
	 * @param array $config
	 */
	protected static function getInstance($config = null) {
		!$config && $config = self::$config;
		!$config && $config = self::initConfig();
		
		switch ($config['driver']) {
			case 'mysql' :
				$dsn = "{$config['driver']}:dbname={$config['database']};host={$config['host']};charset={$config['charset']}";
				break;
			default:
				// TODO...
				break;
		}
		
		$key = "{$config['driver']}:host={$config['host']};username={$config['username']}";
		if(empty(self::$instances[$key]) || !(self::$instances[$key] instanceof PDO)) {
			//实例化PDO对象
			self::$instances[$key] = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_TIMEOUT => 2]);
		}
		
		return $key;
	}
	
	/**
	 * 初始化配置文件
	 * @param string $config
	 */
	public static function initConfig($config = null) {
		empty(self::$config) && self::$config = self::getConfig('database/', self::$db_config);
		
		if($config) {
			$config_arr = self::getConfig('database/', $config);
			if (is_array($config_arr) && count($config_arr) > 0) {
				foreach ($config_arr as $key => $value) {
					self::$config[$key] = $value;
				}
			}
		}
		
		return self::$config;
	}
	
	/**
	 * 加载配置文件
	 * @param string $dir_name
	 * @param string $config
	 */
	protected static function getConfig($dir_name, $config) {
		return include_once __DIR__ . '/../Config/' . $dir_name . $config . '.php';
	}
}