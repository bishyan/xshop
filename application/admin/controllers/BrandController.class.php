<?php

// 商品品牌控制器
class BrandController extends PrivilegeController {

	// 显示商品品牌列表
	public function listAction() {
		$model = Factory::M('BrandModel');

		$info = $model->getBrands();

		$brands = $info['data'];
		$page = $info['page'];

		include CURRENT_VIEW . 'brand_list.html';
	}

	// 添加商品品牌
	public function addAction() {

		if ($_POST) {
			// 接收并转换特殊字符
			$data = $this->checkData($_POST);

			// 处理上传文件
			if ($_FILES['logo']['error'] == 0) {

				$upload = new Upload();

				if ($info = $upload->uploadOne($_FILES['logo'])) {
					// 上传成功
					$data['logo'] = $info;
				} else {

					$this->jump('index.php?p=admin&c=Brand&a=add', '品牌logo上传失败: ' . $upload->getError(), 3);
				}
			}


			// 验证数据
			if ($data['brand_name'] == '') {
				$this->jump('index.php?p=admin&c=Brand&a=add', '品牌名称不能为空', 2);
			}

			$model = Factory::M('BrandModel');
			// 存储数据到数据库
			if ($model->insert($data)) {
				$this->jump('index.php?p=admin&c=Brand&a=list', '品牌添加成功', 2);
			} else {
				$this->jump('index.php?p=admin&c=Brand&a=add', '品牌添加失败', 2);
			}
			exit;
		}

		include CURRENT_VIEW . 'brand_add.html';
	}

	// 编辑商品品牌信息
	public function editAction() {
		// 获取品牌模型
		$model = Factory::M('BrandModel');
		if ($_POST) {
			// 接收并验证信息
			$data = $this->checkData($_POST);

			if ($data['brand_name'] == '') {
				$this->jump('index.php?p=admin&c=Brand&a=add', '品牌名称不能为空', 2);
			}

			// 处理上传文件
			if ($_FILES['logo']['error'] == 0) {
				$upload = new Upload();

				if ($info = $upload->uploadOne($_FILES['logo'])) {
					// 上传成功
					$data['logo'] = $info;
					// 删除旧的图片
					del_file($data['old_logo']);
				} else {
					$this->jump('index.php?p=admin&c=Brand&a=add', '品牌logo上传失败: ' . $upload->getError(), 3);
				}
			}

			// 更新数据
			if ($model->update($data)) {
				$this->jump('index.php?p=admin&c=Brand&a=list', '品牌编辑成功', 2);
			} else {
				$this->jump('index.php?p=admin&c=Brand&a=edit&brand_id='.$data['brand_id'], '品牌编辑失败', 2);
			}
			exit;
		}

		$brand_id = $_GET['brand_id'] + 0;
		$brand = $model->selectByPk($brand_id);


		include CURRENT_VIEW . 'brand_edit.html';
	}


	// 删除商品品牌
	public function deleteAction() {
		// 获取品牌模型
		$model = Factory::M('BrandModel');

		$brand_id = $_GET['brand_id'] + 0;

		if ($model->delete($brand_id)) {
			$this->jump('index.php?p=admin&c=Brand&a=list', '品牌删除成功', 2);
		} else {
			$this->jump('index.php?p=admin&c=Brand&a=list', '品牌删除失败', 2);
		}
	}
} 