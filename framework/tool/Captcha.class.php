<?php

// 验证码工具类
class Captcha {
	private $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	private $code;						// 验证码字符串
	private $code_len;					// 验证码的长度
	private $width;						// 验证码的宽度
	private $height;					// 验证码的高度
	private $fonefile;					// 验证码字体路径
	private $image;						// 图形资源句柄
	
	public function __construct($config = array()) {
		$this->chars 	= isset($config['chars'])? $config['chars'] : 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$this->code_len = isset($config['code_len'])? $config['code_len'] : 4;
		$this->width 	= isset($config['width'])? $config['width'] : 150;
		$this->height 	= isset($config['height'])? $config['height'] : 58;
		$this->fontfile = isset($config['fontfile'])? $config['fontfile'] : TOOL_PATH . 'elephant.ttf';
	}


	// 生成随机码
	private function createCode() {
		$len = strlen($this->chars);
		for ($i = 0; $i < $this->code_len; ++$i) {
			$rand_index = mt_rand(0, $len - 1);
			$this->code .= $this->chars[$rand_index];
		}
	}


	// 生成背景
	private function createBg() {
		$this->image = imagecreatetruecolor($this->width, $this->height);
		$color = imagecolorallocate($this->image, mt_rand(155, 255), mt_rand(155, 255), mt_rand(155, 255));
		// imagefill($this->image, 0, 0, $color);
		imagefilledrectangle($this->image, 0, $this->height, $this->width, 0, $color);
	}

	// 生成干扰元素(线条, 雪花)
	private function createNoise() {
		for ($i = 0; $i < 10; $i++) {
			$rand_color = imagecolorallocate($this->image, mt_rand(155, 255), mt_rand(155, 255), mt_rand(155, 255));
		
			imageline($this->image, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $rand_color);
		}

		for ($i = 0; $i < 80; $i++) {
			$rand_color = imagecolorallocate($this->image, mt_rand(155, 255), mt_rand(155, 255), mt_rand(155, 255));
					
			imagestring($this->image, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), 'G', $rand_color);
		}
	}


	// 生成码值
	private function createFont() {
		$avg_w = $this->width / $this->code_len;

		for ($i = 0; $i < $this->code_len; ++$i) {
			$color = imagecolorallocate($this->image, mt_rand(0, 125), mt_rand(0, 125), mt_rand(0, 125));
			$x = $avg_w * $i + 10;
			imagettftext($this->image, mt_rand(18, 36), mt_rand(-15, 30), $x, $this->height - 10, $color, $this->fontfile, $this->code[$i]);
		}
	}


	// 输出验证码
	private function output() {
		header('Content-Type: image/png');
		imagepng($this->image);
	}


	// 对外生成验证码
	public function generateCode() {

		$this->createBg();
		$this->createCode();
		$this->createNoise();
		$this->createFont();
		$this->output();
	}


	// 获取验证码
	public function getCode() {
		return $this->code;
	}
	
}