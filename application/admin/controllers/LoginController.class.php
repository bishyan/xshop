<?php

// 登录控制器
class LoginController extends Controller {

	// 显示登录页面
	public function loginAction() {

		include CURRENT_VIEW . 'login.html';
	}

	// 验证登录
	public function checkAction() {
		$admin_name = $_POST['username'];
		$password = $_POST['password'];
		$captcha = $_POST['captcha'];


		// 先验证验证码
		if (!strcasecmp($captcha, $_SESSION['captcha_code']) == 0) {
			$this->jump('index.php?p=admin&c=Login&a=login', '验证码错误!');
		}

		// 验证登录的用户名和密码
		$admin_model = Factory::M('AdminModel');
		if ($admin_info = $admin_model->checkUser($admin_name, $password)) {
			$_SESSION['admin'] = $admin_info;

			$this->jump('index.php?p=admin&c=Index&a=index');
		} else {
			$this->jump('index.php?p=admin&c=Login&a=login', '用户名或密码错误!');
		}
	}


	// 登出系统
	public function logoutAction() {

		$_SESSION['admin'] = array();
		session_destroy();


		$this->jump('index.php?p=admin&c=Login&a=login');
	}


	public function captchaAction() {
		$captcha = new Captcha();

		$captcha->generateCode();

		$_SESSION['captcha_code'] = $captcha->getCode();
	}
}