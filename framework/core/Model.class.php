<?php

// 基础模型类
class Model {
	protected $db;
	protected $table;				// 真实表名
	protected $fields = array();	// 字段列表


	public function __construct($table = '') {
		$config = $GLOBALS['config']['db'];
		switch ($GLOBALS['config']['app']['dao']) {
			case 'mysql':
				$dao_class = 'MySQLDB';
				break;
			default:
				$dao_class = 'PDODB';
				break;
		}

		$this->db = $dao_class::getInstance($config);

		if (!empty($table)) {
			$this->table = "`" . $GLOBALS['config']['app']['table_prefix'] . $table  . "`";

			// 获取Fields字段
			$this->getFields();
		}
	}


	// 获取表字段列表
	private function getFields() {
		$sql = "DESC $this->table";
		$records = $this->db->getAll($sql);

		foreach ($records as $v) {
			$this->fields[] = $v['Field'];
			// 判断是否是主键
			if ($v['Key'] == 'PRI') {
				$this->fields['pk'] = $v['Field'];
			}
		}
	}


	/**
	 *	自动插入记录
	 *	@param $list  array   关联数组
	 *	@return mixed 	成功返回记录的id, 失败则返回false
	 */
	public function insert($list) {
		$field_string = '';
		$value_string = '';

		foreach ($list as $k => $v) {
			if (in_array($k, $this->fields)) {
				$field_string .= "`" . $k . "`" . ',';
				$value_string .= "'" . $v . "'" . ',';
			}
		}

		// 去掉多余的逗号
		$field_string = rtrim($field_string, ',');
		$value_string = rtrim($value_string, ',');

		$sql = "INSERT INTO $this->table ({$field_string}) VALUES ({$value_string})";

		
		if ($this->db->query($sql)) {
			// 成功, 返回新记录的id
			return $this->db->getInsertId();
		} else {
			return false;
		}
	}


	public function update($list) {
		$uplist = '';		// 更新字段字符串
		$where = '';		// 更新的条件
		foreach ($list as $k => $v) {
			if (in_array($k, $this->fields)) {
				// 判断是否主键
				if ($k == $this->fields['pk']) {
					$where .= "`$k`=$v";
				} else {
					$uplist .= "`$k`='$v',";
				}
			}
		}

		// 去除多余逗号
		$uplist = rtrim($uplist, ',');

		$sql = "UPDATE {$this->table} SET {$uplist} WHERE {$where}";

		if ($this->db->query($sql)) {
			return $this->db->getAffectedRows();
		} else {
			return false;
		}
	}

	// 根据主键id删除分类
	public function delete($pk) {
		$where = 0;  // 条件字符串
		if (is_array($pk)) {
			// 数组
			$where = "`{$this->fields['pk']}` in (" . implode(',', $pk) . ")";
		} else {
			$where = "`{$this->fields['pk']}` = $pk";
		}

		$sql = "DELETE FROM {$this->table} WHERE {$where}";

		if ($this->db->query($sql)) {
			// 成功
			if ($rows = $this->db->getAffectedRows()) {
				// 有受影响的记录数
				return $rows;
			} else {
				return false;
			}
		} else {
			// 失败, 返回false
			return false;
		}
	}


	public function selectByPk($id) {
		$sql = "SELECT * FROM {$this->table} WHERE `{$this->fields['pk']}`={$id}";
		return $this->db->getRow($sql);
	}


	public function total($where) {

		if (empty($where)) {
			$sql = "SELECT count(*) FROM {$this->table}";
		} else {
			$sql = "SELECT count(*) FROM {$this->table} WHERE $where";
		}

		return $this->db->getOne($sql);
	}
}