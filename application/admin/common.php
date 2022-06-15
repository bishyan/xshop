<?php

// 公共函数文件


function get_img($path, $width = 88) {
	echo "<img src='public/uploads/{$path}' width='{$width}' />";
}


// 加载页脚
function footer() {
	include CURRENT_VIEW . 'layout/footer.html';
}


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



// 删除文件
function del_file($file) {
	$path = UPLOAD_PATH . $file;
	if (file_exists($path) && is_file($path)) {
		unlink($path);
	}
}