<?php

// 品牌模型类
class BrandModel extends Model {
	public function getBrands() {
		// 接收参数
		$where = '';
		$brand_name = isset($_REQUEST['brand_name'])? $_REQUEST['brand_name'] : '';
		if (!empty($brand_name)) {
			$where = "brand_name like '%{$brand_name}%'";
		}

		// 分页获取信息
		$page_size = 2;
		$current = isset($_GET['page'])? $_GET['page'] : 1;
		$offset = ($current - 1) * $page_size;

		if ($where == '') {
			$sql = "SELECT * from {$this->table} ORDER BY brand_id LIMIT {$offset}, {$page_size}";
		} else {
			$sql = "SELECT * from {$this->table} WHERE {$where} ORDER BY brand_id LIMIT {$offset}, {$page_size}";			
		}
		
		$info['data'] = $this->db->getAll($sql);

		// 获取分页信息
		$total = $this->total($where);	// 总的记录数
		$page = new Page($total, $page_size, $current, $script = 'index.php', $params = array('p'=>'admin', 'c'=>'Brand', 'a'=>'list', 'brand_name'=>$brand_name));
		$info['page'] = $page->showPage();
		
		return $info;
	}
}