<?php

// 管理员模型
class AdminModel extends Model {
	// 验证用户的账号和密码
	public function checkUser($username, $password) {
		$admin_name = escape_data($username);
		$password = md5($password);

		$sql = "SELECT * FROM {$this->table} WHERE admin_name = '{$admin_name}' AND password = '{$password}'";

		return $this->db->getRow($sql);
	}
}