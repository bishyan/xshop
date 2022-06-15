<?php

// 文件上传类
class Upload {
	private $path; 			// 文件上传目录
	private $max_size;		// 上传文件大小限制
	private $errno;			// 错误代号
	private $ext;			// 允许上传的文件后缀
	private $file_prefix;	// 文件前缀
	private $type_map = array(  // 后缀后和文件类型的映射数组
		'.png'	=> array('image/png', 'image/x-png'),
		'.jpg'	=> array('image/jpeg', 'image/pjpeg'),
		'.jpeg'	=> array('image/jpeg', 'image/pjpeg'),
		'.gif'	=> array('image/gif')
	);
	private $mime = array(); // 允许上传的文件类型

	public function __construct($config = array()) {

		if (isset($GLOBALS['config']['upload'])) {
			$config = array_merge($GLOBALS['config']['upload'], $config);
		}

		$this->path     	= isset($config['path'])? $config['path'] : './';
		$this->max_size 	= isset($config['max_size'])? $config['max_size'] * 1024 * 1024 : 5 * 1024 * 1024;
		$this->ext      	= isset($config['ext'])? $config['ext'] : array('.png', '.jpeg');
		$this->file_prefix  = isset($config['file_prefix'])? $config['file_prefix'] : '';
	}

	/**
	 *	文件上传方法, 分目录存放文件
	 *	@param $file   array 包含上传文件信息的数组
	 *  @return  mixed  成功返回上传的文件名, 失败返回false
	 */
	public function uploadOne($file) {

		if (!is_uploaded_file($file['tmp_name'])) {
			$this->errno = 8;  
			return false;
		}


		if ($file['error'] == 0) {
			// 文件成功上传到临时文件夹
			// 判断文件大小
			if ($file['size'] > $this->max_size) {
				// 大小超过配置文件中的上传限制
				$this->errno = 9;
				return false;
			}

			// 判断文件类型
			$this->getAllowMime();
			if (!in_array($file['type'], $this->mime)) {
				$this->errno = 10;
				return false;
			}

			// 判断真实的mime类型
			if (!in_array($this->getTrueMimeType($file['tmp_name']), $this->mime)) {
				$this->errno = 11;
				return false;
			}

			// 分目录存储
			$sub_dir = date('Ymd') . '/';
			if (!is_dir($this->path . $sub_dir)) {
				mkdir($this->path . $sub_dir, 0777, true);
			}

			// 重命名文件
			$file_name = uniqid($this->file_prefix) . date('YmdHis') . strrchr($file['name'], '.');

			// 上传
			if (move_uploaded_file($file['tmp_name'], $this->path . $sub_dir . $file_name)) {
				// 移动成功
				return $sub_dir . $file_name;
			} else {
				// 移动失败
				$this->errno = 12;
				return false;
			}

		} else {
			$this->errno = $file['error'];
			return false;
		}
	}

	// 获取允许的MIE文件类型
	private function getAllowMime() {
		$mime = array();
		foreach ($this->ext as $v) {
			$mime = array_merge($mime, $this->type_map[$v]);
		}

		$this->mime = array_unique($mime);
	}

	// 利用php自带的finfo扩展, 获取真实的mime类型
	private function getTrueMimeType($file) {
		$finfo = new Finfo(FILEINFO_MIME_TYPE);

		return $finfo->file($file);
	}

	// 获取错误信息
	public function getError() {
		switch ($this->errno) {
			case 1:
				return '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值, 其大小为: ' . ini_get('upload_max_filesize');
				break;
			case 2:
				return '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值,其大小为' . $_POST['MAX_FILE_SIZE'];;
				break;
			case 3:
				return '文件只有部分被上传';
				break;
			case 4:
				return '没有文件被上传';
				break;
			case 5:
				return '上传的是空文件';
				break;
			case 6:
				return '找不到临时文件夹';
				break;
			case 7:
				return '文件写入临时文件夹失败';
				break;
			case 8:
				return '非法上传';
				break;
			case 9:
				return '文件大小超出系统规定的大小, 最大不能超过' . $this->max_size;
				break;
			case 10:
				return '请检查你的文件类型, 系统支持后缀为:' . implode(', ', $this->ext) . '的文件';
				break;
			case 11:
				return '不允许的MIME类型文件';
				break;
			case 12:
				return '文件移动失败';
				break;
			default:
				return '未知错误';
				break;
		}
	}
}