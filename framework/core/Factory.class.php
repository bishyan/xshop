<?php

// 单例工厂类, 作用是: 生产模型类的实例
class Factory {
	static $model_list = array();

	public static function M($model_name) {
		if (!isset($model_list[$model_name])) {
			$table_name = strtolower(substr($model_name, 0, -5));
			$model_list[$model_name] = new $model_name($table_name);
		}

		return $model_list[$model_name];
	}
}