<?php
class ControllerController extends CommonController {

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

	public function advanced_controllerAction() {

	    //parse login status
	    $this->parse_login();

		//assign params
		$this->assign(array(
		));

		//display page
		$this->display();
	}

	/**
	 * ajax创建Controller文件
	 */
	public function ajax_create_controllerAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
		$controllerName 		    = $this->post('controller_name');
		$controller_view_dir_state 	= $this->post('controller_view_dir_state');
		$controller_view_file_state = $this->post('controller_view_file_state');
		$controller_view_file_type  = $this->post('controller_view_file_type');

		//parse controller name
		if (!$controllerName) {
			exit();
		}

		$this->parse_webapp_root();

		//parse controller file name and file path
		$controllerName 		= ucfirst(strtolower($controllerName)) . 'Controller';
		$controller_file_dir 	= WEBAPP_ROOT . 'application/controllers';
		$controller_file		= $controller_file_dir . '/' . $controllerName . '.class.php';

	    //parse controller dir
		if (!is_dir($controller_file_dir)) {
			mkdir($controller_file_dir, 0777, true);
		}

	    //分析所要创建的controller文件是否 存在
		if (is_file($controller_file)) {
			echo '所要创建的Controller文件已经存在!';
			exit();
		}

		//创建Controller文件、视图目录、视图文件
        $controller_file_content 	= "<?php\r\n" . CreateClassFile::get_file_note($controllerName . '.class.php', 'todo...', null, null, 'Controller') . "class ".$controllerName." extends Controller {\r\n\r\n" . CreateClassFile::get_function_note('Enter description...', 1, 'string') . CreateClassFile::get_function_code('indexAction', 1) . "}";
		$result 		            = file_put_contents($controller_file, $controller_file_content, LOCK_EX);

		if ($controller_view_dir_state == 'checked') {
		    $view_dir = WEBAPP_ROOT . 'application/views/' . strtolower(substr($controllerName, 0, -10));
			if (!is_dir($view_dir)) {
				mkdir($view_dir, 0777, true);
			}
		}

		if ($controller_view_file_state == 'checked') {
		    $view_file_content = "";
		    $view_file    = $view_dir . '/' . (($controller_view_file_type == 1) ? 'index.php' : 'index.html');
		    if (!is_file($view_file)) {
               file_put_contents($view_file, $view_file_content, LOCK_EX);
		    }
		}

		echo ($result == true) ? $controllerName . '文件创建成功!' : '对不起,' . $controllerName . '文件创建失败!';
	}

	/**
	 * ajax创建Widget文件
	 */
	public function ajax_create_widgetAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
		$widget_name		    = $this->post('widget_name');
		$widget_view_file_state = $this->post('widget_view_file_state');

		//parse widget name
		if (!$widget_name) {
			exit();
		}

		$this->parse_webapp_root();

		$widget_name 	= ucfirst($widget_name);
		$widget_dir 	= WEBAPP_ROOT . 'application/widgets';

		//parse widget dir
		if (!is_dir($widget_dir)) {
			mkdir($widget_dir, 0777, true);
		}

	    $widget_file	= $widget_dir . '/' . $widget_name . 'Widget.class.php';
		if (is_file($widget_file)) {
			echo '所要创建的widget文件已存在!';
			exit();
		}

        $widget_file_content 	= "<?php\r\n" . CreateClassFile::get_file_note($widget_name . 'Widget.class.php', 'Enter description here ...', null, null, 'Widget'). "class {$widget_name}Widget extends Widget {\r\n\r\n" . CreateClassFile::get_function_note('Main method', 1, 'string', array(array('params', 'array', 'null', '参数'))) . CreateClassFile::get_function_code('renderContent', 1, array('params'=>'null')) . "\r\n}";
		$result 		= file_put_contents($widget_file, $widget_file_content, LOCK_EX);

		if ($widget_view_file_state == 'checked') {
            $view_file = $widget_dir . '/views/' . strtolower($widget_name) . '.php';
			if(!is_file($view_file)){
				file_put_contents($view_file, "", LOCK_EX);
			}
		}

		echo ($result == true) ? $widget_name . '的widget文件创建成功!' : $widget_name . '的widget文件创建失败!';
	}

	/**
	 * ajax创建Module文件
	 */
	public function ajax_create_moduleAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
		$module_name		    = strtolower($this->post('module_name'));
		$module_view_dir_state	= $this->post('module_view_dir_state');
		$module_view_file_state = $this->post('module_view_file_state');
		if (!$module_name) {
			exit();
		}

		$this->parse_webapp_root();

		//创建模块目录(modules).
		if (!is_dir(WEBAPP_ROOT . 'modules')) {
			mkdir(WEBAPP_ROOT . 'modules', 0777, true);
		}

		$module_dir = WEBAPP_ROOT . 'modules/' . $module_name;
		if (!is_dir($module_dir)) {
			mkdir($module_dir, 0777, true);
		}

		$module_name = ucfirst($module_name);
		$module_file	= $module_dir . '/' . $module_name . 'Module.class.php';

		//创建视图目录
		if ($module_view_dir_state == 'checked' && !is_dir($module_dir . '/views')) {
			mkdir($module_dir . '/views', 0777, true);
		}

		if (is_file($module_file)) {
			echo '所要创建的', $module_name , ' module文件已存在!';
			exit();
		}

	    $module_file_content	= "<?php\r\n" . CreateClassFile::get_file_note($module_name . 'Module.class.php', 'Enter description here ...', null, null, 'Module') . CreateClassFile::get_auth_code() . "class " . $module_name . "Module extends Module {\r\n\r\n" . CreateClassFile::get_function_note('构造函数', 1, 'unknown') . CreateClassFile::get_function_code('__construct', 1). "\r\n}";
        $result = file_put_contents($module_file, $module_file_content, LOCK_EX);

        //parse module view file
        if ($module_view_file_state == 'checked') {
            $module_view_file = $module_dir . '/views/' . strtolower($module_name) . '.php';
            if (!is_file($module_view_file)) {
                file_put_contents($module_view_file, "", LOCK_EX);
            }
        }

		echo ($result == true) ? $module_name . ' Module文件创建成功!' : $module_name . ' Module文件创建失败!';
	}

	/**
	 * ajax完成Controller文件列表
	 */
	public function ajax_controller_listAction() {

	    //parse login status
	    $this->parse_login(true);

	    //parse controller dir path
	    $controller_file_dir 	= WEBAPP_ROOT . 'application/controllers';
        if (!is_dir($controller_file_dir)) {
            exit();
        }

        //获取Controller目录中的文件
	    $file_list_data = file::readDir($controller_file_dir);

	    $file_list_array = array();
	    foreach ($file_list_data as $key=>$lines) {
	         if ($lines == 'index.html') {
	            continue;
	        }
            $file_list_array[$key]['name'] = substr($lines, 0, -20);
            $file_list_array[$key]['time'] = filectime($controller_file_dir . '/' . $lines);
	    }

	    $this->render('ajax_controller_list', array('file_list_array'=>$file_list_array));
	}

	/**
	 * ajax完成Widget文件列表
	 */
	public function ajax_widget_listAction() {

	    //parse login status
	    $this->parse_login(true);

	    //parse widget dir path
	    $widget_dir 	= WEBAPP_ROOT . 'application/widgets';
	    if (!is_dir($widget_dir)) {
	        exit();
	    }

        //获取Widget目录中的文件
	    $file_list_data = file::readDir($widget_dir);

	    $file_list_array = array();
	    foreach ($file_list_data as $key=>$lines) {
	         if ($lines == 'index.html' || $lines == 'views') {
	            continue;
	        }
            $file_list_array[$key]['name'] = substr($lines, 0, -16);
            $file_list_array[$key]['time'] = filectime($widget_dir . '/' . $lines);
	    }

	    $this->render('ajax_widget_list', array('file_list_array'=>$file_list_array));
	}

	/**
	 * ajax完成Module文件列表
	 */
	public function ajax_module_listAction() {

	    //parse login status
	    $this->parse_login(true);

	    //parse module dir path
	    $module_dir = WEBAPP_ROOT . 'modules';
	    if (!is_dir($module_dir)) {
	        exit();
	    }

	     //获取Module目录中的文件
	    $file_list_data = file::readDir($module_dir);

	    $file_list_array = array();
	    foreach ($file_list_data as $key=>$lines) {
	         if ($lines == 'index.html') {
	            continue;
	        }
            $file_list_array[$key]['name'] = $lines;
            $file_list_array[$key]['time'] = filectime($module_dir . '/' . $lines);
	    }

	    $this->render('ajax_module_list', array('file_list_array'=>$file_list_array));
	}

	/**
	 * ajax完成Controller文件高级功能
	 */
	public function ajax_advanced_create_controllerAction() {

	    //parse login status
	    $this->parse_login(true);

	    //parse params
	    $controllerName = $this->post('controller_name_box');
	    if (empty($controllerName)) {
	        exit();
	    }
	    $action_name         = $this->post('action_name_box');
	    $view_dir_status     = $this->post('controller_view_state');
        $view_file_status    = $this->post('controller_view_file_state');
        $view_file_type      = $this->post('controller_view_file_ex');

        $method_name         = $this->post('method_name_box');
        $description_info    = $this->post('note_description_box');
        $author_info         = $this->post('note_author_box');
        $copyright_info      = $this->post('note_copyright_box');

        $action_note_status  = $this->post('action_note_state');
        $method_note_status  = $this->post('method_note_state');

	    $this->parse_webapp_root();

		//parse controller file name and file path
		$controllerName 		= ucfirst(strtolower($controllerName)) . 'Controller';
		$controller_file_dir 	= WEBAPP_ROOT . 'application/controllers';
		$controller_file		= $controller_file_dir . '/' . $controllerName . '.class.php';

	    //parse controller dir
		if (!is_dir($controller_file_dir)) {
			mkdir($controller_file_dir, 0777, true);
		}

	    //分析所要创建的controller文件是否 存在
		if (is_file($controller_file)) {
			echo '所要创建的Controller文件已经存在!';
			exit();
		}

		/**
		 * 创建Controller文件
		 */
		$controller_file_content = "<?php\r\n";

		//handel file note
		if ($description_info || $author_info || $copyright_info) {
            $controller_file_content .= CreateClassFile::get_file_note($controllerName . '.class.php', $description_info, $author_info, $copyright_info, 'Controller');
		}

		$controller_file_content .= "class ".$controllerName." extends Controller {\r\n";

		//handle action
        if (empty($action_name)) {
            $action_name = 'index';
        } else {
            //将中文的逗号替换为英文的逗号
            $action_name = str_replace('；', ';', $action_name);
        }
        $action_name_array = explode(';', $action_name);
        foreach ($action_name_array as $lines) {
            $action_single_name = strtolower(trim($lines));
            if ($action_note_status == 'on') {
                $controller_file_content .= "\r\n" . CreateClassFile::get_function_note('Enter description here ...', 1, 'string');
            }
            $controller_file_content .= CreateClassFile::get_function_code($action_single_name . 'Action', 1);
        }

        //handel method
        if ($method_name) {
        	$note_status = ($method_note_status == 'on') ? true : false;
        	$ParseMethod = $this->instance('ParseMoreMethod');
			$controller_file_content .= $ParseMethod->parseMethodCode($method_name, 2, $note_status);
        }

        $controller_file_content .= "\r\n}";

        $result = file_put_contents($controller_file, $controller_file_content, LOCK_EX);

       	/**
		 * 创建视图目录
		 */
	    if ($view_dir_status == 'on') {
		    $view_dir = WEBAPP_ROOT . 'application/views/' . strtolower(substr($controllerName, 0, -10));
			if (!is_dir($view_dir)) {
				mkdir($view_dir, 0777, true);
			}
		}

        /**
		 * 创建视图文件
		 */
	    if ($view_file_status == 'on') {
	        foreach ($action_name_array as $lines) {
	            $action_single_name = strtolower(trim($lines));
    	        $view_file    = $view_dir . '/' . $action_single_name . (($view_file_type == 1) ? '.php' : '.html');
    		    if (!is_file($view_file)) {
                   file_put_contents($view_file, "", LOCK_EX);
    		    }
	        }
		}

        echo ($result == true) ? $controllerName . '文件创建成功!' : '对不起,' . $controllerName . '文件创建失败!';
	}

	/**
	 * 判断是否有重复的Controller文件
	 */
	public function ajax_parse_repeatAction() {

		//parse login status
	    $this->parse_login(true);

		//get params
		$controllerName = $this->post('controller_name');
		if (!$controllerName) {
			exit();
		}

		$controller_file = WEBAPP_ROOT . 'application/controllers/' . ucfirst(strtolower($controllerName)).'Controller.class.php';
		if (is_file($controller_file)) {
			echo 101;
		}
	}
}