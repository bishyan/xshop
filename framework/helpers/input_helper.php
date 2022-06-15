<?php


// 转换数据中的特殊字符
function escape_data($data) {
	if (empty($data)) {
		return $data;
	}

	return is_array($data)? array_map(__FUNCTION__, $data) : addslashes($data);
}


// 将特殊字符转换为 HTML 实体
function transfer_chars($data) {
	if (empty($data)) {
		return $data;
	}

	return is_array($data)? array_map(__FUNCTION__, $data) : htmlspecialchars($data);
}