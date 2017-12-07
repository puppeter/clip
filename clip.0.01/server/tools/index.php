<?php
/**
 * index.php
 *
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio.
 * @link http://www.doitphp.com
 * @package tools
 */

define('IN_DOIT', true);

//define('DOIT_DEBUG', true);
//define('DOIT_REWRITE', true);

/**
 * DoitPHP的版本 (标准版:normal, Mysql专业版:mysql, SAE版:sae)
 */
define('DOIT_VERSION', 'mysql');

/**
 * 定义项目所在路径即:APP_ROOT
 */
define('APP_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);

/**
 * 加载配置文件:configl.ini.php
 */
require_once APP_ROOT . 'config.ini.php';

/**
 * 分析配置文件参数
 */
$admin_user_name = (!$admin_user_name) ? 'doitphp' : $admin_user_name;
$admin_password  = (!$admin_password) ? '123456' : $admin_password;

define('DOITPHP_ADMIN_USER', $admin_user_name);
define('DOITPHP_ADMIN_PASSWORD', $admin_password);


$doitphp_root	= (!$doitphp_root) ? dirname(__FILE__) . '/../doitphp' . DIRECTORY_SEPARATOR : $doitphp_root;
$webapp_root  	= (!$webapp_root) ? substr(APP_ROOT, 0, -6) : $webapp_root;

$doitphp_root 	= str_replace(array('\\', '//'), '/', $doitphp_root);
$webapp_root 	= str_replace(array('\\', '//'), '/', $webapp_root);

$doitphp_root 	= (substr($doitphp_root, -1) == '/') ? $doitphp_root : $doitphp_root . DIRECTORY_SEPARATOR;
$webapp_root  	= (substr($webapp_root, -1) == '/') ? $webapp_root : $webapp_root . DIRECTORY_SEPARATOR;

define('DOIT_ROOT', $doitphp_root);
define('WEBAPP_ROOT', $webapp_root);

/**
 * 加载DoitPHP框架的初始化文件,如果必要可以修改文件路径
 */
require_once DOIT_ROOT . 'doit.class.php';

/**
 * 启动网站进程
 */
doit::run();