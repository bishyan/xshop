<?php

/**
 * 基础控制器
 */
class Controller {
	// 构造函数
	public function __construct() {
		$this->_initSession();
	}


	private function _initSession() {
		// session_start();
		new SessionDB();
	}


	// 页面跳转
	public function jump($url, $message = null, $wait = 3) {
		if (is_null($message)) {
			header('Location:' . $url);
		} else {
			include CURRENT_VIEW . 'message.html';
		}
		exit();
	}

	// 载入辅助函数
	public function helper($helper) {
		require HELPER_PATH . "{$helper}_helper.php";
	}


	// 转义特殊字符, 预防sql注入
	public function checkData($data) {
		$data = escape_data($data);

		return transfer_chars($data);
	}
}