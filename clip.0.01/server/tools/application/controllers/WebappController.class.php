<?php
class WebappController extends CommonController {

	public function indexAction() {

	    //parse login status
	    $this->parse_login();

	    //get software name
	    $is_apache = (strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false && DOIT_VERSION !== 'sae') ? true : false;
		$appDirStatus = is_dir(WEBAPP_ROOT . 'application') ? true : false;

		//assign params
		$this->assign(array(
		'is_apache' 		=> $is_apache,
		'app_dir_status'	=> $appDirStatus,
		));

		//display page
		$this->display();
	}

	/**
	 * AJAX创建WebApp项目目录
	 */
	public function ajax_create_webappAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
		$module_state	= $this->post('module_state');
		$rewrite_state	= $this->post('rewrite_state');
		$theme_state	= $this->post('theme_state');
		$webserver_name	= $this->post('webserver_name');
		$lang_state     = $this->post('lang_state');

		$app_root		= substr(WEBAPP_ROOT, 0, -1);
		if (!is_dir($app_root)) {
			echo '对不起,项目(WebApp)根目录:', $app_root, '不存在!请创建项目根目录';
			exit();
		}

		$app_dir_array	= array(
		'application/config',
		'application/controllers',
		'application/models',
		'application/widgets',
		'application/extensions',
		'application/views/error',
		'application/views/layout',
		'application/widgets/views',

		'assets/images',
		'assets/js',
		'assets/doit/images',
		'assets/doit/js',

		'cache/data',
		'cache/models',
		'cache/temp',
		'cache/views/widgets',
		'cache/html',
		'cache/html/widgets',
		'logs',
		);

		if ($module_state == 'checked') {
			$app_dir_array[] = 'modules';
		}
		if ($theme_state == 'checked') {
			$app_dir_array[] = 'themes/default';
		}
		if ($lang_state == 'checked') {
            $app_dir_array[] = 'application/language';
		}

		//生成目录
		foreach ($app_dir_array as $lines) {
			$app_dir_name	= $app_root . '/' . $lines;
			if (!is_dir($app_dir_name)) {
				mkdir($app_dir_name, 0777, true);
			}
		}

		//doit仓库中js文件及图片的迁移
		$file_list = $this->instance('file');

		$file_list->copyDir(DOIT_ROOT . 'vendors', $app_root . '/assets/doit/js');
		$file_list->copyDir(DOIT_ROOT . 'views/images', $app_root . '/assets/doit/images');

		//生成目录权限控制文件
		if ($webserver_name == 'apache') {
			$deny_dir_array = array(
			'application',
			'cache',
			'logs',
			);

			foreach ($deny_dir_array as $lines) {
				$htaccess_file = $app_root . '/' . $lines . '/.htaccess';
				if (!is_file($htaccess_file)) {
					file_put_contents($htaccess_file, 'deny from all', LOCK_EX);
				}
			}

			$file_403_content = '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>Directory access is forbidden.</p></body></html>';

			$asset_403_array  = array('assets', 'assets/doit');
			if ($module_state == 'true') {
				$asset_403_array[] = 'modules';
			}

			foreach ($asset_403_array as $lines) {
				$file_403_index = $app_root . '/' . $lines . '/index.html';
				if (!is_dir($file_403_index)) {
					file_put_contents($file_403_index, $file_403_content, LOCK_EX);
				}
			}
		} else {
			$file_403_content = '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>Directory access is forbidden.</p></body></html>';

			foreach ($app_dir_array as $lines) {
				$file_403_index = $app_root . '/' . $lines . '/index.html';
				if (!is_dir($file_403_index)) {
					file_put_contents($file_403_index, $file_403_content, LOCK_EX);
				}
			}
		}

		//生成项目首页index.php
		if (!is_file($app_root . '/index.php')) {
			$current_time = date('Y-m-d H:i:s');
			$app_index_content = <<<EOT
<?php
/**
 * file: index.php
 *
 * application index
 * @author tommy <streen003@gmail.com>
 * @copyright Copyright (C) 2009-2012 www.doitphp.com All rights reserved.
 * @version \$Id: index.php 1.0 {$current_time}Z tommy \$
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
EOT;
			file_put_contents($app_root . '/index.php', $app_index_content, LOCK_EX);
		}

		//生成路由重定向的.htaccess文件
		if ($rewrite_state == 'checked' && ($webserver_name == 'apache') && !is_file($app_root . '/.htaccess')) {
			$file_htaccess_content =<<<EOT
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule !\.(js|ico|txt|gif|jpg|png|css)\$ index.php [NC,L]
EOT;
			file_put_contents($app_root . '/.htaccess', $file_htaccess_content, LOCK_EX);
		}

		//生成项目爬虫引导文件
		if (!is_file($app_root . '/robots.txt')) {
			$file_robots_content =<<<EOT
User-agent: *
Crawl-delay: 10
Disallow: /doitphp/
Disallow: /tools/
Disallow: /application/
Disallow: /assets/
Disallow: /cache/
Disallow: /themes/
Disallow: /logs/
EOT;
			file_put_contents($app_root . '/robots.txt', $file_robots_content, LOCK_EX);
		}

		//生成config文件(此功能只针对mysql专业版)
		if(DOIT_VERSION == 'mysql') {
            $config_file = WEBAPP_ROOT . 'application/config/config.ini.php';
            //当配置文件不存在时
            if (!is_file($config_file)) {
                $config_array = array(
                    'master' => array('host'=>'localhost', 'username'=>'root', 'password'=>'password', 'dbname'=>'database_name', 'port'=>'3306'),
                    'slave' => array(
                        array('host'=>'localhost', 'username'=>'root', 'password'=>'password', 'dbname'=>'database_name', 'port'=>'3306'),
                    ),
                    'driver' => 'mysqli',
                    'charset' => 'utf8',
                    'prefix' => '',
                );
                $config_content  = "<?php\r\n";
                $config_content .= CreateClassFile::get_file_note('config.ini.php', '设置数据库连接参数', 'anyone', '2009-2012 www.doitphp.com', 'config');
				$config_content .= CreateClassFile::get_auth_code();
                $config_content .= "return " . var_export($config_array, true) . ";";
                file_put_contents($config_file, $config_content, LOCK_EX);
            }
		}

		echo 'WebApp项目目录创建成功!';
	}

	/**
	 * AJAX生成config文件
	 */
	public function ajax_create_configAction() {

	    //parse login status
	    $this->parse_login(true);

		$this->parse_webapp_root();

		//get params
		$config_array['driver']		= $this->post('driver_name');
		$config_array['host']		= $this->post('server_name');
		$config_array['username']	= $this->post('user_name');
		$config_array['password']	= $this->post('password');
		$config_array['dbname']		= $this->post('database_name');

		$config_array['charset']	= $this->post('database_encode');
		$config_array['prefix']		= $this->post('database_prefix');

		if (!$config_array['driver']) {
			exit();
		}

		switch ($config_array['driver']) {
			case 'mysqli':
			case 'mysql':
			case 'pdo_mysql':
				$config_array['charset']	= empty($config_array['charset']) ? 'utf8' : str_replace(array('utf-8', 'UTF-8'), 'utf8', $config_array['charset']);
				if (empty($config_array['prefix'])) {
					unset($config_array['prefix']);
				}
				$config_array['port']		= 3306;
				break;

			case 'postgresql':
			case 'pdo_postgresql':
				$config_array['port']	= 5432;
				if (empty($config_array['charset'])) {
					unset($config_array['charset']);
				}
				if (empty($config_array['prefix'])) {
					unset($config_array['prefix']);
				}
				break;

			case 'sqlite2':
			case 'sqlite3':
				if (empty($config_array['dbname'])) {
					unset($config_array['dbname']);
				}
				if (empty($config_array['charset'])) {
					unset($config_array['charset']);
				}
				if (empty($config_array['prefix'])) {
					unset($config_array['prefix']);
				}
				break;

			case 'oracle':
			case 'pdo_oracle':
				$config_array['port']	= 1521;
				$config_array['charset']	= empty($config_array['charset']) ? 'utf8' : $config_array['charset'];
				if (empty($config_array['prefix'])) {
					unset($config_array['prefix']);
				}
				break;

			case 'mssql':
				if (empty($config_array['charset'])) {
					unset($config_array['charset']);
				}
				if (empty($config_array['prefix'])) {
					unset($config_array['prefix']);
				}
				break;

			case 'pdo':
				$config_array['dsn']	= $config_array['host'];
				unset($config_array['host']);

				if (empty($config_array['charset'])) {
					unset($config_array['charset']);
				}
				if (empty($config_array['prefix'])) {
					unset($config_array['prefix']);
				}
				break;
		}

		//parse config file
		$config_dir	 = WEBAPP_ROOT . 'application/config';
		$config_file = $config_dir . '/config.ini.php';
		if (is_file($config_file)) {
			echo 'Config配置文件已存在!';
			exit();
		}

		if (!is_dir($config_dir)) {
			mkdir($config_dir, 0777, true);
		}

		$file_content  = "<?php\r\n";
		$file_content .= CreateClassFile::get_file_note('config.ini.php', '设置数据库连接参数', 'anyone', '2009-2012 www.doitphp.com', 'config');
		$file_content .= CreateClassFile::get_auth_code();
		$file_content .= "return " . var_export($config_array, true).";";

		if (file_put_contents($config_file, $file_content, LOCK_EX)) {
			echo 'Config配置文件创建成功!';
		} else {
			echo 'Config配置文件创建失败!';
		}
	}
}