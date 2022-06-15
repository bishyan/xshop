<?php 

// 后台公共权限类, 需要验证权限的类统一继承此类
class PrivilegeController extends Controller {
	public function __construct() {
		parent::__construct();
		$this->checkLogin();
	}


	private function checkLogin() {
	
		if (!isset($_SESSION['admin']['admin_id'])) {
			$this->jump('index.php?p=admin&c=Login&a=login', '请先登录!');
		}
	}


}