# 创建数据库
create database if not exists xshop02 charset = utf8;


# 创建商品分类表
create table if not exists xshop02_category (
	cat_id smallint unsigned not null auto_increment primary key,
	cat_name varchar(32) not null comment '分类名称',
	parent_id smallint unsigned not null default 0 comment '上级分类id',
	cat_desc varchar(255) not null default '' comment '分类描述',
	sort_order tinyint not null default 50 comment '排序依据',
	unit varchar(15) not null default '' comment '分类数量单位',
	is_show tinyint not null default 1 comment '是否显示',
	fieter varchar(120) not null default '' comment ''
) engine Myisam charset utf8 comment '商品分类表';


# 创建商品品牌表
create table if not exists xshop02_brand (
	brand_id mediumint unsigned not null auto_increment primary key,
	brand_name varchar(32) not null comment '品牌名称',
	site_url varchar(120) not null default '' comment '品牌网址',
	logo varchar(255) not null default '' comment '品牌logo',
	brand_desc varchar(512) not null default '' comment '品牌描述',
	sort_order tinyint not null default 50 comment '排序依据',
	is_show tinyint not null default 1 comment '是否显示'
) engine Myisam charset utf8 comment '商品品牌表';



#创建管理员表
create table if not exists xshop02_admin (
	admin_id tinyint unsigned not null auto_increment primary key,
	admin_name varchar(15) not null comment '管理员账号',
	password char(32) not null comment '密码',
	email varchar(150) not null default '' comment '管理员email',
	role_id tinyint unsigned not null default 0 comment '所属角色id',
	is_use tinyint unsigned not null default 1 comment '是否启用',
	last_ip int unsigned not null default 0 comment '最后登录ip',
	last_time int unsigned not null default 0 comment '最后登录时间'
) engine=Myisam charset=utf8 comment '管理员表';


# 创建session表
create table if not exists `session` (
	`session_id` varchar(64) not null primary key comment 'session_id',
	`session_content` varchar(255) not null default '' comment 'session内容',
	`last_time` int unsigned not null default 0 comment '最后修改时间'
) engine=Myisam charset=utf8 comment 'session表';