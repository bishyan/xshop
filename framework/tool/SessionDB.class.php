<?php

// session操作类, session数据入库

class SessionDB {
	private $db;  	// 数据库连接资源

	public function __construct() {
		// 判断session是否启用
		if (!$this->isSessionStarted()) {
			ini_set('session.save_handler', 'user');
			session_set_save_handler(
				array($this, 'userSessionBegin'),
				array($this, 'userSessionEnd'),
				array($this, 'userSessionRead'),
				array($this, 'userSessionWrite'),
				array($this, 'userSessionDelete'),
				array($this, 'userSessionGC')
			);

			session_start();
		}
	}

	// 判断session是否启用
	private function isSessionStarted() {
	
		if (php_sapi_name() !== 'cli') {
			if (version_compare(phpversion(), '5.4.0', '>=')) {
				return session_status() === PHP_SESSION_ACTIVE? true : false;
			} else {
				return session_id() === ''? false : true;
			}
		}

		return false;
	}


	private function userSessionBegin() {
		$config = $GLOBALS['config']['db'];

		$this->db = MySQLDB::getInstance($config);
	}


	public function userSessionEnd() {
		return true;
	}

	// 读取session信息
	private function userSessionRead($session_id) {
		$sql = "SELECT session_content FROM `session` WHERE `session_id`='{$session_id}'";

		return $this->db->getOne($sql);
	}


	public function userSessionWrite($session_id, $session_content) {
		$sql = "REPLACE INTO `session` VALUES ('{$session_id}', '{$session_content}', unix_timestamp())";

		return $this->db->query($sql);
	}

	// 删除session
	public function userSessionDelete($session_id) {
		$sql = "DELETE FROM `session` WHERE session_id='{$session_id}'";

		return $this->db->query($sql);
	}


	public function userSessionGC($max_lifetime) {
		$sql = "DELETE FROM `session` WHERE last_time < unix_timestamp() - $max_lifetime";

		return $this->db->query($sql);
	}


}