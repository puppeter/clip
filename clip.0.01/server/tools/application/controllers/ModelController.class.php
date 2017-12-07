<?php
class ModelController extends CommonController {

	public function indexAction() {

	    //parse login status
	    $this->parse_login();

		//assign params
		$this->assign(array(
		'baseUrl'	   => $this->getAssetUrl('doit/js'),
		));

		//display page
		$this->display();
	}

	/**
	 * 创建单个Model文件
	 */
	public function ajax_create_single_modelAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
		$model_name		= $this->post('model_name');
		$fields_state	= $this->post('fields_state');
		$table_state    = $this->post('table_state');

		//parse params
		if (!$model_name) {
			exit();
		}

	    $this->parse_webapp_root();

		$model_dir 	= WEBAPP_ROOT . 'application/models';
		$model_name	= ucfirst($model_name);

		$model_file	= $model_dir . '/' . $model_name . 'Model.class.php';

		//parse models dir
		if (!is_dir($model_dir)) {
			mkdir($model_dir, 0777, true);
		}

		//parse modle file.
		if (is_file($model_file)) {
			echo $model_name, '的modle文件已存在!';
		} else {

            //分析modle文件的内容
            $model_file_content = "<?php\r\n" . CreateClassFile::get_file_note($model_name . 'Model.class.php', 'Enter description here ...', null, null, 'Model'). "class " . $model_name . "Model extends Model {\r\n";
            //数据表信息
            if ($fields_state == 'checked') {
                $table_fields_array = $this->parse_table_fields($this->parseTableName($model_name));
                if ($table_fields_array['status'] == true) {
                    $model_file_content .= "\r\n" . CreateClassFile::get_function_note('定义数据表主键', 2, 'string') . CreateClassFile::get_function_code('primaryKey', 2, null, false, "return '{$table_fields_array['primary_key'][0]}';") . "\r\n";
                    $model_file_content .= CreateClassFile::get_function_note('定义数据表字段信息', 2, 'array') . CreateClassFile::get_function_code('tableFields', 2, null, false, "return " . var_export($table_fields_array['fields'], true) . ";");
                }
            }
            //自定义数据表
            if ($table_state == 'checked') {
                $model_file_content .= "\r\n" . CreateClassFile::get_function_note('定义数据表名称', 2, 'string') . CreateClassFile::get_function_code('tableName', 2, null, false, "return '" . $this->parseTableName($model_name) . "';");
            }
            $model_file_content .= "\r\n}";

            //创建Model文件
		    if (file_put_contents($model_file, $model_file_content, LOCK_EX)) {
				echo $model_name, '的modle文件创建成功!';
			} else {
				echo $model_name, '的modle文件创建失败!请重新操作';
			}
		}
	}

	/**
	 * 分析model文件所对应的数据表
	 */
	protected function parseTableName($modelName) {
		
		//parse params
		if(!$modelName){
			return false;
		}

		$tableName = preg_replace_callback('#[A-Z]#', array($this, 'parseTagToLower'), $modelName);

		return trim($tableName, '_');
	}

	/**
	 * 将大写字母替换为：”_小写字母“
	 */
	 protected function parseTagToLower($tags){
		
		$tags = trim($tags[0]);

		return str_replace($tags, '_' . strtolower($tags), $tags);
	 }

	/**
	 * 创建全部的Model文件
	 */
	public function ajax_create_all_modelAction() {

	    //parse login status
	    $this->parse_login(true);

	     //get params
		$fields_state	= $this->post('fields_state');
		$table_state    = $this->post('table_state');

	    $this->parse_webapp_root();

		//分析model文件目录
		$model_dir 	= WEBAPP_ROOT . 'application/models';

		if (!is_dir($model_dir)) {
			mkdir($model_dir, 0777, true);
		}

		//数据库连接
	    $db_link= $this->parse_db_link();

		if ($db_link['status'] == true) {
            //实例化MYSQL数据库连接
		    $db = $db_link['db'];
            $model_name	= strtolower($db_link['prefix'] . $model_name);

            //获取数据库中的数据表
		    $table_list_array = $db->getArray("SHOW TABLES");
		    foreach ($table_list_array as $lines) {
                //获取数据表名及model名称
                $table_name = $lines['Tables_in_' . $db_link['dbname']];
                $model_name = preg_replace('/^'. $db_link['prefix'] . '/', '', $lines['Tables_in_' . $db_link['dbname']]);
                $model_name = ucfirst(strtolower($model_name));

                //分析model文件是否存在
                $model_file = $model_dir . '/' . $this->parseModelName($model_name) . 'Model.class.php';
                if (is_file($model_file)) {
                    continue;
                }

                //分析modle文件的内容
                $model_file_content = "<?php\r\n" . CreateClassFile::get_file_note($this->parseModelName($model_name) . 'Model.class.php', 'Enter description here ...', null, null, 'Model'). "class " . $this->parseModelName($model_name) . "Model extends Model {\r\n";
                //数据表字段信息
                if ($fields_state == 'checked') {
                    //分析数据表字段信息
                    $table_info		= $db->getArray("SHOW FIELDS FROM `{$table_name}`");

					$fields 		= array();
					$primary_key	= array();
					foreach ($table_info as $field_lines) {
						//获取主键信息
						if ($field_lines['Key'] == 'PRI') {
							$primary_key[] = $field_lines['Field'];
						}

						//获取字段信息
						$fields[] = $field_lines['Field'];
					}
                    $model_file_content .= "\r\n" . CreateClassFile::get_function_note('定义数据表主键', 2, 'string') . CreateClassFile::get_function_code('primaryKey', 2, null, false, "return '{$primary_key[0]}';") . "\r\n";
                    $model_file_content .= CreateClassFile::get_function_note('定义数据表字段信息', 2, 'array') . CreateClassFile::get_function_code('tableFields', 2, null, false, "return " . var_export($fields, true) . ";");
                }
                //自定义数据表
                if ($table_state == 'checked') {
                    $model_file_content .= "\r\n" . CreateClassFile::get_function_note('定义数据表名称', 2, 'string') . CreateClassFile::get_function_code('tableName', 2, null, false, "return '" . strtolower($model_name) . "';");
                }
                $model_file_content .= "\r\n}";

                //创建model文件
	            if (!file_put_contents($model_file, $model_file_content, LOCK_EX)) {
					echo $model_name, '的Model文创建失败!';
					break;
				}
		    }
		    echo '全部Model文件已创建完成!';
		} else {
            echo '对不起,当前使用的数据库不是:Mysql!本功能不支持';
		}
	}

	/**
	 * 分析数据表所对应的Model文件名
	 */
	protected function parseModelName($modelName) {
		
		//parse params
		if(!$modelName){
			return false;
		}

		return preg_replace_callback('#_[a-z]#', array($this, 'parseTagToUpper'), $modelName);;
	}

	/**
	 * 将”_小写字母“替换为：大写字母
	 */
	 protected function parseTagToUpper($tags){
		
		$tags = trim(str_replace('_', '', $tags[0]));

		return str_replace($tags, strtoupper($tags), $tags);
	 }

	/**
	 * 清空单个Model缓存文件
	 */
	public function ajax_clear_single_modelAction() {

	    //parse login status
	    $this->parse_login(true);

        $this->parse_webapp_root();

		//get params
		$model_cache_name = strtolower($this->post('model_cache_name'));
		if (!$model_cache_name) {
			exit();
		}

		$model_cache_file = WEBAPP_ROOT . 'cache/models/' . $model_cache_name . '_model.cache.data.php';

		if (is_file($model_cache_file)) {
			unlink($model_cache_file);
		}

		echo $model_cache_name, ' Model缓存文件已删除!';
	}

	/**
	 * 清空全部Model缓存文件
	 */
	public function ajax_clear_all_modelAction() {

	    //parse login status
	    $this->parse_login(true);

	    $this->parse_webapp_root();

		$model_cache_dir 	= WEBAPP_ROOT . 'cache/models';
		$cache_dir_content 	= opendir($model_cache_dir);

		while(false !== ($file = readdir($cache_dir_content))){

			if($file == '.' || $file == '..' || $file == 'index.html' || $file == '.svn' || $file == '.cvs'){
				continue;
			}

			unlink($model_cache_dir . '/' . $file);
		}

		echo 'Model缓存文件全部清除完毕!';
	}

	/**
	 * Ajax完成Model文件列表
	 */
	public function ajax_model_listAction() {

	    //parse login status
	    $this->parse_login(true);

	    //parse model path
	    $model_dir 	= WEBAPP_ROOT . 'application/models';
	    if (!is_dir($model_dir)) {
	        exit();
	    }

	    //获取model目录中的文件
	    $file_list_data = file::readDir($model_dir);

	    $file_list_array = array();
	    foreach ($file_list_data as $key=>$lines) {
	        if ($lines == 'index.html') {
	            continue;
	        }
            $file_list_array[$key]['name'] = substr($lines, 0, -15);
            $file_list_array[$key]['time'] = filectime($model_dir . '/' . $lines);
	    }

	    $this->render('ajax_model_list', array('file_list_array'=>$file_list_array));
	}

	/**
	 * 分析数据表字段信息
	 *
	 * @access protected
	 * @param array $model_name 模型名称
	 * @return array
	 */
	protected function parse_table_fields($model_name) {

        //数据库连接
	    $db_link= $this->parse_db_link();

		if ($db_link['status'] == true) {

		    //实例化MYSQL数据库连接
		    $db = $db_link['db'];
            $model_name	= strtolower($db_link['prefix'] . $model_name);

            //判断所要查询的数据表是否存在
		    $table_list_data = $db->getArray("SHOW TABLES");
		    $table_list_array = array();
		    foreach ($table_list_data as $lines) {
                $table_list_array[] = $lines['Tables_in_' . $db_link['dbname']];
		    }
		    if (!in_array($model_name, $table_list_array)) {
                echo '数据表:' . $model_name . '不存在! 无法获取数据表"字段信息"';
                exit();
		    }

            //查询数据表字段信息
		    $table_info	= $db->getArray("SHOW FIELDS FROM `{$model_name}`");

			$fields 		= array();
			$primary_key 	= array();
			foreach ($table_info as $lines) {
				//获取主键信息
				if ($lines['Key'] == 'PRI') {
					$primary_key[] = $lines['Field'];
				}

				//获取字段信息
				$fields[] = $lines['Field'];
			}
			$status         = true;
		} else {
            $primary_key 	= '';
			$fields			= array();
			$status         = false;
		}

		return array('status'=>$status, 'primary_key'=>$primary_key, 'fields'=>$fields);
	}

	/**
	 * 分析MYSQL数据库连接
	 */
	protected function parse_db_link() {

	    //数据库连接配置文件
		$config_file = WEBAPP_ROOT . 'application/config/config.ini.php';

		if (!is_file($config_file)) {
			echo '连接数据库配置文件不存在,请先创建config文件!';
			exit();
		}
		//加载数据库配置文件.
		$configParams = include $config_file;

		//分析,检测配置文件内容
		if (!is_array($configParams)) {
			echo 'Config配置文件内容错误!';
			exit();
		}

		$params['driver']	= trim($configParams['driver']);
		if ($params['driver'] == 'mysqli' || $params['driver'] == 'mysql' || $params['driver'] == 'pdo_mysql') {

			//当有mysql主从库设置时
			if (isset($configParams['master']) && $configParams['master']) {

				$params['host'] 	= trim($configParams['master']['host']);
				$params['username'] = trim($configParams['master']['username']);
				$params['password'] = trim($configParams['master']['password']);
				$params['dbname'] 	= trim($configParams['master']['dbname']);
				$params['port'] 	= trim($configParams['master']['port']);

			} else {

				$params['host'] 	= trim($configParams['host']);
				$params['username'] = trim($configParams['username']);
				$params['password'] = trim($configParams['password']);
				$params['dbname'] 	= trim($configParams['dbname']);
				$params['port'] 	= trim($configParams['port']);
			}


			//参数默认，编码默认utf8
			$params['charset'] = ($configParams['charset']) ? trim($configParams['charset']) : 'utf8';

			//获取数据表前缀，默认为空
			$prefix = ($configParams['prefix']) ? trim($configParams['prefix']) : '';

		    //实例化$db（数据库连接）
    		switch ($params['driver']) {
    			case 'mysql':
    				$db = db_mysql::getInstance($params);
    				break;
    			case 'pdo_mysql':
    				//分析dsn信息
    				if (!$params['dsn']) {
    					$dsn_array 			= array();
    					$dsn_array['host']	= $params['host'];
    					$dsn_array['dbname']= $params['dbname'];

    					if (!empty($params['port'])){
    						$dsn_array['port']=$params['port'];
    					}
    					$params['dsn'] = sprintf('%s:%s', 'mysql', http_build_query($dsn_array, '', ';'));
    				}
    				$db = db_pdo::getInstance($params);
    				break;
    			default:
    				$db = db_mysqli::getInstance($params);
    		}

            //将数据库的用户名及密码及时从内存中注销，提高程序安全性
			unset($params['username']);
			unset($params['password']);

			return array('status'=>true, 'db'=>$db, 'prefix'=>$prefix, 'dbname'=>$params['dbname']);
		}

		return array('status'=>false);
	}

	/**
	 * 创建Model文件的高级操作
	 */
	public function advanced_modelAction() {
	    //parse login status
	    $this->parse_login();

		//assign params
		$this->assign(array(
		));

		//display page
		$this->display();
	}

	/**
	 * ajax完成model文件的高级功能创建
	 */
	public function ajax_advanced_create_modelAction() {
		//parse login status
	    $this->parse_login(true);

	    //get params
	    $model_name 		= $this->post('model_name_box');
	    $fields_state 		= $this->post('model_field_single_box');
	    $table_state 		= $this->post('model_tabname_single_box');

	    $method_name 		= $this->post('method_name_box');
	    $method_note_status = $this->post('method_note_state');

	    $description		= $this->post('note_description_box');
	    $author				= $this->post('note_author_box');
	    $copyright			= $this->post('note_copyright_box');

	    //parse params
		if (!$model_name) {
			exit();
		}

	    $this->parse_webapp_root();

		$model_dir 	= WEBAPP_ROOT . 'application/models';
		$model_name	= ucfirst($model_name);

		$model_file	= $model_dir . '/' . $model_name . 'Model.class.php';

		//parse models dir
		if (!is_dir($model_dir)) {
			mkdir($model_dir, 0777, true);
		}

		//parse modle file.
		if (is_file($model_file)) {
			echo $model_name, '的modle文件已存在!';
		} else {
			//分析modle文件的内容
            $model_file_content = "<?php\r\n" . CreateClassFile::get_file_note($model_name . 'Model.class.php', $description, $author, $copyright, 'Model'). "class " . $model_name . "Model extends Model {\r\n";
            //数据表信息
            if ($fields_state == 'on') {
                $table_fields_array = $this->parse_table_fields($this->parseTableName($model_name));
                if ($table_fields_array['status'] == true) {
                    $model_file_content .= "\r\n" . CreateClassFile::get_function_note('定义数据表主键', 2, 'string') . CreateClassFile::get_function_code('primaryKey', 2, null, false, "return '{$table_fields_array['primary_key'][0]}';") . "\r\n";
                    $model_file_content .= CreateClassFile::get_function_note('定义数据表字段信息', 2, 'array') . CreateClassFile::get_function_code('tableFields', 2, null, false, "return " . var_export($table_fields_array['fields'], true) . ";");
                }
            }
            //自定义数据表
            if ($table_state == 'on') {
                $model_file_content .= "\r\n" . CreateClassFile::get_function_note('定义数据表名称', 2, 'string') . CreateClassFile::get_function_code('tableName', 2, null, false, "return '" . $this->parseTableName($model_name) . "';");
            }

            //parse method
			if ($method_name) {
	        	$note_status = ($method_note_status == 'on') ? true : false;
	        	$ParseMethod = $this->instance('ParseMoreMethod');
				$model_file_content .= $ParseMethod->parseMethodCode($method_name, null, $note_status);
	        }
            $model_file_content .= "\r\n}";

            //创建Model文件
		    if (file_put_contents($model_file, $model_file_content, LOCK_EX)) {
				echo $model_name, '的modle文件创建成功!';
			} else {
				echo $model_name, '的modle文件创建失败!请重新操作';
			}
		}
	}

	/**
	 * ajax判断model排重
	 */
	public function ajax_parse_repeatAction() {
		//parse login status
	    $this->parse_login(true);

		//get params
		$model_name = $this->post('model_name');
		if (!$model_name) {
			exit();
		}

		$model_file = WEBAPP_ROOT . 'application/models/' . ucfirst($model_name).'Model.class.php';
		if (is_file($model_file)) {
			echo 101;
		}
	}
}