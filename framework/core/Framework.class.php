<?php


class Framework {
	public static function run() {
		// 初始化目录常量
		static::_initPathConsts();

		// 初始化配置参数
		static::_initConfigParams();

		// 初始化分发参数
		static::_initDispatchParams();

		// 初始化当前平台目录常量
		static::_initCurrentPlatformPathConsts();

		// 注册自动加载
		static::_initAutoload();

		// 路由分发
		static::_router();
	}


	private static function _initPathConsts() {
		// 目录分隔符
		define('DS', DIRECTORY_SEPARATOR);
		// 根目录
		define('ROOT_PATH', getcwd() . DS);
		// 框架目录
		define('FRAMEWORK_PATH', ROOT_PATH . 'framework' . DS);
		// 应用目录
		define('APP_PATH', ROOT_PATH . 'application' . DS);
		// 公共资源目录
		define('PUBLIC_PATH', ROOT_PATH . 'public' . DS);
		// 下载文件目录
		define('UPLOAD_PATH', PUBLIC_PATH . 'uploads' . DS);
		// 框架核心类目录 
		define('CORE_PATH', FRAMEWORK_PATH . 'core' . DS);
		// 数据库 DAO 类目录
		define('DAO_PATH', FRAMEWORK_PATH . 'dao' . DS);
		// 数据库工具类目录
		define('TOOL_PATH', FRAMEWORK_PATH . 'tool' . DS);
		// 数据库辅助函数目录
		define('HELPER_PATH', FRAMEWORK_PATH . 'helpers' . DS);
		// 配置文件目录
		define('CONFIG_PATH', APP_PATH . 'config' . DS);
		// 模型类目录
		define('MODEL_PATH', APP_PATH . 'models' . DS);
	}


	// 初始化配置参数
	private static function _initConfigParams() {
		$GLOBALS['config'] = require CONFIG_PATH . 'config.php';
	}


	// 初始化分发参数
	private static function _initDispatchParams() {
		// 平台
		$default_platfrom = $GLOBALS['config']['app']['default_platform'];
		define('PLATFORM', isset($_GET['p'])? strtolower($_GET['p']) : $default_platfrom);

		// 控制器
		$default_controller = $GLOBALS['config'][PLATFORM]['default_controller'];
		define('CONTROLLER', isset($_GET['c'])? ucfirst(strtolower($_GET['c'])) : $default_controller);

		// 动作
		$default_action = $GLOBALS['config'][PLATFORM]['default_action'];
		define('ACTION', isset($_GET['a'])? strtolower($_GET['a']) : $default_action);
	}


	// 初始化当前平台目录常量
	public static function _initCurrentPlatformPathConsts() {
		// 当前平台控制器文件目录
		define('CURRENT_CONTROLLER', APP_PATH . PLATFORM . DS . 'controllers' . DS);

		// 当前平台视图文件目录
		define('CURRENT_VIEW', APP_PATH . PLATFORM . DS .'views' . DS);

		// 加载当前平台公共函数文件
		require APP_PATH . PLATFORM . DS . 'common.php';
	}

	// 注册自动加载
	private static function _initAutoload() {
		spl_autoload_register(array(__CLASS__, '_userAutoload'));
	}

	// 自定义的自动加载函数
	private static function _userAutoload($class_name) {
		// 框架的核心类
		$framework_core_class = array(
			'Controller'		=> CORE_PATH . 'Controller.class.php',
			'Model'				=> CORE_PATH . 'Model.class.php',
			'Factory'			=> CORE_PATH . 'Factory.class.php',
			'I_DAO'				=> DAO_PATH . 'I_DAO.interface.php',
			'MySQLDB'			=> DAO_PATH . 'MySQLDB.class.php',
			'PDODB'				=> DAO_PATH . 'PDODB.class.php',
			'Upload'			=> TOOL_PATH . 'Upload.class.php',
			'SessionDB'			=> TOOL_PATH . 'SessionDB.class.php',
			'Captcha'			=> TOOL_PATH . 'Captcha.class.php',
			'Page'				=> TOOL_PATH . 'Page.class.php',
		);

		// 判断是否是核心类
		if (isset($framework_core_class[$class_name])) {
			require $framework_core_class[$class_name];
		}
		// 判断是否是控制器类
		else if (substr($class_name, -10) == 'Controller') {
			require CURRENT_CONTROLLER . "{$class_name}.class.php";
		}
		// 判断是否是模型类
		else if (substr($class_name, -5) == 'Model') {
			require MODEL_PATH . "{$class_name}.class.php";
		}
	}


	// 路由分发
	public static function _router() {
		
		$controller_name = CONTROLLER . 'Controller';
		$action_name = ACTION . 'Action';

		// 实例化控制器类, 并调用动作方法
		$controller = new $controller_name;
		$controller->$action_name();
	}
}