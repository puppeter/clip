<?php
/**
 * confing.ini.php
 * 
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio.
 * @link http://www.doitphp.com
 * @package tools
 * 
 * 注：本配文件是用来设置所要创建项目目录及管理员登陆信息的配置文件，非手册中所提及的数据库连接配置文件
 */

if (!defined('IN_DOIT')) {
	exit();
}

/**
 * 管理登陆信息设置
 */

//登陆用户名设置
$admin_user_name	= 'doitphp';

//登陆密码设置
$admin_password		= '123456';	


/**
 * 设置所要创建项目(WebApp)目录及Doitphp框架文件目录
 */

//设置DoitPHP框架文件目录
$doitphp_root	= dirname(__FILE__) . '/../doitphp' . DIRECTORY_SEPARATOR;

//设置WebApp目录
$webapp_root	= substr(APP_ROOT, 0, -6);