<?php


// 数据库操作类

class MySQLDB implements I_DAO {
	// 属性
	private $_host;
	private $_port;
	private $_username;
	private $_password;
	private $_charset;
	private $_dbname;

	protected $link;  				// 数据库连接资源
	private static $instance;		// 数据库操作实例

	// 初始化操作, 连接数据库服务器,设置字符集,选择数据库
	private function __construct($config) {
		// 初始化参数
		$this->_initConfigParams($config);
		// 连接数据库
		$this->_connect();
		// 设置字符集
		$this->_set_charset();
		// 选择数据库
		//$this->_select_db();
	}


	// 获取操作实例
	public static function getInstance($config) {
		if (!static::$instance instanceof static) {
			static::$instance = new static($config);
		}

		return static::$instance;
	}

	// 禁止克隆
	private function __clone() {}


	private function _initConfigParams($config) {
		$this->_host = isset($config['host'])? $config['host'] : 'localhost';
		$this->_port = isset($config['port'])? $config['port'] : '3306';
		$this->_username = isset($config['username'])? $config['username'] : 'root';
		$this->_password = isset($config['password'])? $config['password'] : '';
		$this->_charset = isset($config['charset'])? $config['charset'] : 'utf8';
		$this->_dbname = isset($config['dbname'])? $config['dbname'] : '';
	}

	private function _connect() {
		$this->link = mysqli_connect("$this->_host:$this->_port", $this->_username, $this->_password, $this->_dbname) or die('连接数据库失败');
	}

	private function _set_charset() {
		mysqli_set_charset($this->link, $this->_charset);
	}


	private function _select_db() {
		mysqli_select_db($this->link, $this->_dbname) or die('数据库选择失败');
	}


	// 执行sql语句
	public function query($sql) {
		$result = mysqli_query($this->link, $sql);

		if ($result === false) {
			echo '执行失败: ';
			echo "<br />失败的sql语句为: " . $sql;
			echo "<br />错误代号为: " . mysqli_connect_errno();
			echo "<br />错误信息为: " . mysqli_connect_error();
			die();
		}

		return $result;
	}

	// 获取第一条记录的第一个字段的值
	public function getOne($sql) {
		$result = $this->query($sql);
		$row = mysqli_fetch_row($result);
		if ($row) {
			return $row[0];
		} else {
			return false;
		}
	}

	// 获取一条记录
	public function getRow($sql) {
		$result = $this->query($sql);
		
		return mysqli_fetch_array($result, MYSQL_ASSOC);
	}

	// 获取所有的数据
	public function getAll($sql) {
		$result = $this->query($sql);
		
		$list = array();
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
			$list[] = $row;
		}

		return $list;
	}

	// 取得上一步 INSERT 操作产生的 ID
	public function getInsertId() {
		return mysqli_insert_id($this->link);
	}

	// 返回受影响的记录数
	public function getAffectedRows() {
		return mysqli_affected_rows($this->link);
	}
}