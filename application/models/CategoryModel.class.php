<?php

/**
 *	商品分类表操作模型
 */
class CategoryModel extends Model {

	public function getAllCats() {
		$sql = "SELECT * FROM {$this->table}";
		return $this->db->getAll($sql);
	}
	
	public function getCats() {

		$cats = $this->getAllCats();

		return $this->tree($cats);
	}

	// 将一个数组转化为树状结构
	public function tree($arr, $pid = 0, $level = 0, $is_begin = true) {
		static $tree = array();
		if ($is_begin) {
			$tree = array();
		}

		foreach ($arr as $v) {
			if ($v['parent_id'] == $pid) {
				$v['level'] = $level;
				$tree[] = $v;
				$this->tree($arr, $v['cat_id'], $level+1, false);
			}
		}

		return $tree;
	}

	// 获取指定分类的子孙分类的ID
	public function getSubIds($cat_id = 0) {
		$cats = $this->getAllCats();
		
		$subCats = $this->tree($cats, $cat_id);
		$subIds = array();
		
		foreach ($subCats as $v) {
			$subIds[] = $v['cat_id'];
		}

		return $subIds;
	}
}