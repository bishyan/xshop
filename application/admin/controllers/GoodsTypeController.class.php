<?php 


class GoodsTypeController {
	// 显示商品类型列表
	public function listAction() {

		include CURRENT_VIEW . 'goods_type_list.html';
	}


	// 添加商品类型
	public function addAction() {

		include CURRENT_VIEW . 'goods_type_add.html';
	}
}