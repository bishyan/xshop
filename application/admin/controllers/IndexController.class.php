<?php

/**
 * 后台首页控制器
 */
class IndexController extends PrivilegeController {
	public function indexAction() {
		include CURRENT_VIEW . 'index.html';
	}

	public function topAction() {
		include CURRENT_VIEW . 'top.html';
	}


	public function dragAction() {
		include CURRENT_VIEW . 'drag.html';
	}


	public function menuAction() {
		include CURRENT_VIEW . 'menu.html';
	}


	public function mainAction() {
		include CURRENT_VIEW . 'main.html';
	}
}