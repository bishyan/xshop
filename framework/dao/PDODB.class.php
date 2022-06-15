<?php


class PDODB implements I_DAO {
	// 属性
	private $_host;
	private $_port;
	private $_username;
	private $_password;
	private $_charset;
	private $_dbname;

	private $dsn;					// 数据源名称
	private $driver_options;		// 驱动选项
	protected $pdo;  				// pdo对象
	private static $instance;		// 数据库操作实例


	private function __construct($config) {
		// 初始化参数
		$this->_initConfigParams($config);
		// 初始化DSN(数据源名称)
		$this->_initDSN();
		// 初始化driver_options(驱动选项)
		$this->_initDriverOptions();

		// 初始化pdo
		$this->_initPDO();
	}

	private function _initConfigParams($config) {
		$this->_host = isset($config['host'])? $config['host'] : 'localhost';
		$this->_port = isset($config['port'])? $config['port'] : '3306';
		$this->_username = isset($config['username'])? $config['username'] : 'root';
		$this->_password = isset($config['password'])? $config['password'] : '';
		$this->_charset = isset($config['charset'])? $config['charset'] : 'utf8';
		$this->_dbname = isset($config['dbname'])? $config['dbname'] : '';
	}


	private function _initDSN() {
		$this->dsn = "mysql:host={$this->_host};port={$this->_port};dbname={$this->_dbname}";
	}

	private function _initDriverOptions() {
		$this->driver_options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND	=> "SET NAMES {$this->_charset}"
		);
	}


	private function _initPDO() {
		$this->pdo = new PDO($this->dsn, $this->_username, $this->_password, $this->driver_options);
	}

	// 禁用克隆
	private function __clone() {}


	// 获取操作实例
	public static function getInstance($config) {
		if (!static::$instance instanceof static) {
			static::$instance = new static($config);
		}

		return static::$instance;
	}

	// 执行sql语句
	public function query($sql) {
		$action = strtolower(strstr($sql, ' ', true));
		if (in_array($action, array('desc', 'select', 'show'))) {
			$result = $this->pdo->query($sql);
		} else {
			$this->num = $result = $this->pdo->exec($sql);
		}

		if ($result === false) {
			$error_info = $this->pdo->errorInfo();
			echo "执行失败: ";
			echo "<br />失败的sql语句为: " . $sql;
			echo "<br />错误代码为: " . $error_info[1];
			echo "<br />错误信息为: " . $error_info[2];
			die();
		}

		return $result;
	}

	// 获取第一条记录的第一个字段的值
	public function getOne($sql) {
		$result = $this->query($sql);

		$string = $result->fetchColumn();

		$result->closeCursor();

		return $string;
	}

	// 获取一条记录
	public function getRow($sql) {
		$result = $this->query($sql);

		$row = $result->fetch(PDO::FETCH_ASSOC);

		$result->closeCursor();

		return $row;
	}

	// 获取所有的数据
	public function getAll($sql) {
		$result = $this->query($sql);

		$list = $result->fetchAll(PDO::FETCH_ASSOC);

		$result->closeCursor();

		return $list;
	}


	// 取得上一步 INSERT 操作产生的 ID
	public function getInsertId() {
		return $this->pdo->lastInsertID();
	}

	// 返回受影响的记录数
	public function getAffectedRows() {
		return $this->num;
	}
}