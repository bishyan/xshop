<?php

class CategoryController extends PrivilegeController {
	private $cats;		// 所有的分类信息
	private $model;  	// 模型实例

	public function __construct() {
		parent::__construct();
		$this->model = Factory::M('CategoryModel');
		$this->cats = $this->model->getCats();
	}

	// 显示分类列表
	public function listAction() {
		// 获取全部的分类记录
		$cats = $this->cats;

		include CURRENT_VIEW . 'cat_list.html';
	}

	// 添加商品分类
	public function addAction() {

		if ($_POST) {
			// 接收参数
			$data['cat_name'] = $_POST['cat_name'];
			$data['parent_id'] = $_POST['parent_id'];
			$data['unit'] = $_POST['unit'];
			$data['sort_order'] = $_POST['sort_order'];
			$data['is_show'] = $_POST['is_show'];
			$data['cat_desc'] = $_POST['cat_desc'];

			$data = $this->checkData($data);
			//var_dump($data); exit;
			if ($data['cat_name'] == '') {
				$this->jump('index.php?p=admin&c=Category&a=add', '商品分类名称不能为空');
			}

			
			if ($this->model->insert($data)) {
				$this->jump('index.php?p=admin&c=Category&a=list', '商品分类添加成功');
			} else {
				$this->jump('index.php?p=admin&c=Category&a=add', '商品分类添加失败');
			}
			exit;
		}

		// 获取全部的分类记录
		$cats = $this->cats;
		include CURRENT_VIEW . 'cat_add.html';
	}

	// 编辑分类信息
	public function editAction() {

		if ($_POST) {
			$data = $this->checkData($_POST);

			if ($data['cat_name'] == '') {
				$this->jump('index.php?p=admin&c=Category&a=add', '商品分类名称不能为空');
			}

			// 获取当前分类的子孙分类
			$subIds = $this->model->getSubIds($data['cat_id']);
			// 合并当前分类id
			$subIds[] = $data['cat_id'];

			// 判断数据中的上级分类是否在其中
			if (in_array($data['parent_id'], $subIds)) {
				$this->jump('index.php?p=admin&c=Category&a=edit&cat_id=' . $data['cat_id'], '上级分类选择错误,不能选择自身和子孙分类作为上级分类');
			}

			// 
			if ($this->model->update($data)) {
				$this->jump('index.php?p=admin&c=Category&a=list', '商品分类编辑成功');
			} else {
				$this->jump('index.php?p=admin&c=Category&a=edit&cat_id=' . $data['cat_id'], '商品分类编辑失败');
			}

			exit;
		}


		$model = Factory::M('CategoryModel');

		// 获取当前分类信息
		$cat_id = $_GET['cat_id'] + 0;
		$cat = $this->model->selectByPk($cat_id);

		// 获取当前分类的子孙分类
		$subIds = $this->model->getSubIds($cat_id);

		// 获取所有的分类
		$cats = $this->cats;


		include CURRENT_VIEW . 'cat_edit.html';
	}

	// 删除商品分类
	public function deleteAction() {
		$cat_id = $_GET['cat_id'] + 0;

		// 获取当前分类的子孙id
		$subIds = $this->model->getSubIds($cat_id);
		if (!empty($subIds)) {
			$this->jump('index.php?p=admin&c=Category&a=list', '当前分类下还有子孙分类, <br />请先删除子孙分类');
		}

		if ($this->model->delete($cat_id)) {
			$this->jump('index.php?p=admin&c=Category&a=list', '操作成功');
		} else {
			$this->jump('index.php?p=admin&c=Category&a=list', '操作失败');
		}
	}
}