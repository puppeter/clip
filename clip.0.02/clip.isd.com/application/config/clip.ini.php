<?php
/**
 * file: config.ini.php
 *
 * 设置数据库连接参数
 * @author anyone
 * @copyright Copyright (C) 2009-2012 www.doitphp.com All rights reserved.
 * @version $Id: config.ini.php 1.0 2015-01-04 11:26:05Z anyone $
 * @package config
 * @since 1.0
 */

#mysql -h10.59.216.87 -P5047 -pePcB8a89K# --default-character-set=utf8
if (!defined('IN_DOIT')) {
	exit('403:Access Forbidden!');
}
return  array(
    'master' => array(
        'host' => '10.59.216.87',
        'username' => 'root',
        'password' => 'ePcB8a89K#',
        'dbname' => 'cos_devops',
        'port' => '5047'
    ),
    'slave' => array(
        0 => array(
            'host' => '10.59.216.87',
            'username' => 'root',
            'password' => 'ePcB8a89K#',
            'dbname' => 'cos_devops',
            'port' => '5047'
        )
    ),
    'driver' => 'mysqli',
    'charset' => 'utf8',
    'prefix' => ''
);
