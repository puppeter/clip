<?php
/**
 * file: index.php
 *
 * application index
 * @author tommy <streen003@gmail.com>
 * @copyright Copyright (C) 2009-2012 www.doitphp.com All rights reserved.
 * @version $Id: index.php 1.0 2015-01-04 11:26:05Z tommy $
 * @package application
 * @since 1.0
 */

define('IN_DOIT', true);

/**
 * 调试(debug)运行模式(开启:true, 关闭:false, 默认:false)
 */
//define('DOIT_DEBUG', true);

/**
 * 是否开启重写(rewrite)功能(开启:true, 关闭:false, 默认:false)
 */
//define('DOIT_REWRITE', true);

/**
 * 定义URL后缀,注:只有开启重写(rewrite)时,定义才有效
 */
//define('URL_SUFFIX', '.html');

/**
 * 定义URL的分割符,注:Controller和Action的命名中不能使用该分割符,以免冲突
 */
//define('URL_SEGEMENTATION', '/');

/**
 * 是否开启自定义URL路由功能(开启:true, 关闭:false, 默认:false)
 */
//define('CUSTOM_URL_ROUTER', true);

/**
 * 定义项目的视图文件格式:(false:php, true:html, 默认:false)
 */
//define('DOIT_VIEW', true);

/**
 * 定义项目所在路径(根目录):APP_ROOT
 */
define('APP_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);

/**
 * 自定义项目目录路径
 * CONTROLLER_DIR, MODEL_DIR, VIEW_DIR, CONFIG_DIR, WIDGET_DIR, EXTENSION_DIR
 * CACHE_DIR, LOG_DIR, MODULE_DIR(可选), THEME_DIR(可选), LANG_DIR(可选)
 */
//define('EXTENSION_DIR', APP_ROOT . 'application/extensions' . DIRECTORY_SEPARATOR);

/**
 * 加载DoitPHP框架的初始化文件,如果必要可以修改文件路径
 */
require_once APP_ROOT.'doitphp/doit.class.php';

/**
 * 启动应用程序(网站)进程
 */
doit::run();