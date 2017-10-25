<?php
class IndexController extends CommonController {

	public function indexAction() {

	    //parse login status
	    $this->parse_login();

		//检查$_SERVER变量
		$server_vars = array('SCRIPT_NAME', 'REQUEST_URI', 'HTTP_HOST', 'SERVER_PORT', 'HTTP_USER_AGENT', 'REQUEST_TIME', 'HTTP_ACCEPT_LANGUAGE', 'REMOTE_ADDR', 'HTTP_REFERER');

		$miss_array = array();
		foreach ($server_vars as $value) {
			if (!isset($_SERVER[$value])) {
				$miss_array[] = $value;
			}
		}

		//支持的数据库
		$database_array = array();
		if (function_exists('mysql_get_client_info') || extension_loaded('pdo_mysql')) {
			$database_array[] = 'MySql';
		}
		if (function_exists('mssql_connect') || extension_loaded('pdo_mssql')) {
			$database_array[] = 'MSSQL';
		}
		if (function_exists('pg_connect') || extension_loaded('pdo_pgsql')) {
			$database_array[] = 'PostgreSQL';
		}
		if (function_exists('oci_connect') || extension_loaded('pdo_oci8') || extension_loaded('pdo_oci')) {
			$database_array[] = 'Oracle';
		}
		if (extension_loaded('sqlite') || extension_loaded('pdo_sqlite')) {
			$database_array[] = 'Sqlite';
		}
		if (extension_loaded('mongo')) {
			$database_array[] = 'MongoDB';
		}

		//检查GD库
		if (extension_loaded('gd')) {
			$gdinfo=gd_info();
			$gd_result = (!$gdinfo['FreeType Support']) ? '<span class="red">Not Support FreeType</span>' : 'Yes';
		} else {
			$gd_result = '<span class="red">No</span>';
		}

		//assign params
		$this->assign(array(
		'server_result' => (!empty($miss_array)) ? '<span class="red">$_SERVER不支持的变量为: ' . implode(',', $miss_array) . '</span>' : 'Yes',
		'database_info' => implode(',', $database_array),
		'gd_result'		=> $gd_result,
		));

		//display page
		$this->display();
	}

	public function phpinfoAction() {

	    //parse login status
	    $this->parse_login();

		phpinfo();
	}
}