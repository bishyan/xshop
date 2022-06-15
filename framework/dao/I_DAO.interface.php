<?php

// 数据库操作类接口

interface I_DAO {
	// 获取操作实例
	public static function getInstance($config);

	// 执行sql语句
	public function query($sql);

	// 获取第一条记录的第一个字段的值
	public function getOne($sql);

	// 获取一条记录
	public function getRow($sql);

	// 获取所有的数据
	public function getAll($sql);

	// 取得上一步 INSERT 操作产生的 ID
	public function getInsertId();

	// 返回受影响的记录数
	public function getAffectedRows();
}