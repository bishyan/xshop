<?php


// 配置文件
return array(
	// 连接数据库参数
	'db'		=> array(
		'host'		=> 'localhost',
		'port'		=> '3306',
		'username'	=> 'root',
		'password'	=> '1234abcd',
		'charset'	=> 'utf8',
		'dbname'	=> 'xshop02',
	),
	// 应用组
	'app'		=> array(
		'default_platform'	=> 'admin',  		// 默认为后台
		'table_prefix'		=> 'xshop02_', 		// 表前缀
		'dao'				=> 'pdo',			// 支持两种方式操作数据库 mysql, pdo
	),

	// 后台组
	'admin'		=> array(
		'default_controller' => 'Index', 		// 默认控制器
		'default_action'	 => 'index',		// 默认动作
	),

	// 前台组
	'home'		=> array(
		'default_controller' => 'Home', 		// 默认控制器
		'default_action'	 => 'index',		// 默认动作
	),

	// 上传文件配置
	'upload'	=> array(
		'path'		  => UPLOAD_PATH,			// 文件上传的目录
		'ext'		  => array('.png', '.jpg', '.gif'), // 允许上传的文件后缀类型
		'max_size'	  => 5,						// 上传文件大小限制, 单位是M
		'file_prefix' => 'xshop_',   			// 重命名时的文件前缀
	),
);