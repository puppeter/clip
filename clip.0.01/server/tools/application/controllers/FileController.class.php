<?php
class FileController extends CommonController {

	public function indexAction() {

	    //parse login status
	    $this->parse_login();

	    //get params
	    $dir = $this->get('path');
	    if ($dir) {
	        $dir =  str_replace('//', '/', $dir);
	    }

        $path = WEBAPP_ROOT . $dir;
        $path = str_replace('//', '/', $path);

        if (!is_dir($path)) {
            $this->showMessage('对不起,所要显示文件的目录不存在！');
        }

        //parse system status
        if (substr($dir, 0, 8) == '/doitphp' || substr($dir, 0, 6) == '/tools' || substr($dir, 0, 12) == '/assets/doit') {
           $is_protected = true;
        } else {
           $is_protected = false;
        }

        //parse cache status
	    if (substr($dir, 0, 11) == '/cache/data' || substr($dir, 0, 13) == '/cache/models' || substr($dir, 0, 12) == '/cache/views' || substr($dir, 0, 11) == '/cache/temp' || substr($dir, 0, 19) == '/cache/html/widgets' || substr($dir, 0, 11) == '/cache/html') {
           $cache_status = true;
        } else {
           $cache_status = false;
        }

        //parse create file status
	    if (substr($dir, 0, 24) == '/application/controllers' || substr($dir, 0, 19) == '/application/models' || substr($dir, 0, 20) == '/application/widgets' || substr($dir, 0, 8) == '/modules' || $dir == '/application' || substr($dir, 0, 6) == '/cache' || substr($dir, 0, 12) == '/assets/doit') {
           $file_status = false;
        } else {
           $file_status = true;
        }

        //parse create dir status
        if($dir == '/application/models' || $dir == '/application/config' || $dir == '/application/widgets' || substr($dir, 0, 12) == '/assets/doit') {
            $dir_status = false;
        } else {
            $dir_status = true;
        }

        //parse rename status
        if ($dir == '/application' || $dir == '/cache' || substr($dir, 0, 12) == '/assets/doit') {
            $rename_status = false;
        } else {
            $rename_status = true;
        }
        $protect_array = array('application', 'assets', 'cache', 'doitphp', 'tools', 'modules', 'themes', 'logs', 'default', 'views');

        $file_object = new DirectoryIterator($path);

        $file_array = array();
        foreach ($file_object as $lines) {
            //文件过滤
            if ($lines->isDot()) {
                continue;
            }
            $mod = '';
            if ($lines->isReadable()) {
                $mod .= 'r ';
            }
            if ($lines->isWritable()) {
                $mod .= 'w ';
            }
            if ($lines->isExecutable()) {
                $mod .= 'x ';
            }

            //parse ico image
            $extension = strtolower(substr(strrchr($lines->getFilename(), '.'), 1));
            switch ($extension) {
                case 'php':
                    $ico = 'php.gif';
                    break;

                case 'html':
                    $ico = 'htm.gif';
                    break;

                case 'txt':
                    $ico = 'txt.gif';
                    break;

                case 'css':
                    $ico = 'css.gif';
                    break;

                case 'js':
                    $ico = 'js.gif';
                    break;

                case 'gif':
                   $ico = 'gif.gif';
                   break;

                case 'jpg':
                case 'jpeg':
                   $ico = 'jpg.gif';
                   break;

                case 'png':
                    $ico = 'image.gif';
                    break;

                default:$ico = '';
            }

            $file_array[] = array(
            'name'	        => $lines->getFilename(),
            'size'	        => self::byte_format($lines->getSize()),
            'isdir'         => $lines->isDir(),
            'time'	        => date('Y-m-d H:i:s', $lines->getMTime()),
            'ico'           => $ico,
            'mod'			=> $mod,
            'ext'			=> $extension,
            );
        }

        //parse return url
        $parent_url = str_replace('\\', '/', dirname($dir));
        if ($dir && $dir != '/' && $parent_url != '/') {
            $return_url = str_replace('//', '/', $this->getSelfUrl() . '/?path=' . $parent_url);
        } else {
            $return_url = $this->getSelfUrl();
        }

        //assign params
		$this->assign(array(
		'dir'         => $dir,
		'file_data'	  => $file_array,
		'path'		  => $path,
		'return_url'  => $return_url,
		'is_system'	  => $is_protected,
		'cache_status'=> $cache_status,
		'file_status' => $file_status,
		'dir_status'  => $dir_status,
		'rename_status'=> $rename_status,
		'protect_array'=> $protect_array,
		'baseUrl'	   => $this->getAssetUrl('doit/js'),
		));

		//display page
		$this->display();
	}

	/**
	 * ajax显示创建目录thickbox页面
	 */
	public function ajax_create_dir_boxAction() {

	    //parse login status
	    $this->parse_login(true);

	    //parse params
	    $dir = $this->get('path');
	    if ($dir) {
	        $dir =  str_replace('//', '/', $dir);
	    }

	    //parse path
	    $path = WEBAPP_ROOT . $dir;
        $path = str_replace('//', '/', $path);

        $writabe_status = (is_dir($path) && is_writable($path)) ? true : false;

       //display page
       $this->render('ajax_create_dir_box', array('path'=>$path, 'writabe_status'=>$writabe_status));
	}

	/**
	 * ajax显示新建文件thickbox页面
	 */
	public function ajax_create_file_boxAction() {

	    //parse login status
	    $this->parse_login(true);

	    //parse params
	    $dir = $this->get('path');
	    if ($dir) {
	        $dir =  str_replace('//', '/', $dir);
	    }

	    $extension_status = ($dir == '/application/extensions') ? true : false;


	    //parse path
	    $path = WEBAPP_ROOT . $dir;
        $path = str_replace('//', '/', $path);

        $writabe_status = (is_dir($path) && is_writable($path)) ? true : false;

       //display page
       $this->render('ajax_create_file_box', array('writabe_status'=>$writabe_status, 'path'=>$path, 'extension_status'=>$extension_status));
	}

	/**
	 * ajax显示更改文件名thickbox页面
	 */
	public function ajax_rename_boxAction() {

	    //parse login status
	    $this->parse_login(true);

	    //parse params
	    $dir         = $this->get('path');
	    $fileName   = $this->get('file_name');
	    $isdir       = $this->get('isdir');
	    if ($dir) {
	        $dir =  str_replace('//', '/', $dir);
	    }

	    //parse path
	    $path = WEBAPP_ROOT . $dir;
        $path = str_replace('//', '/', $path);

        $writabe_status = (is_dir($path) && is_writable($path)) ? true : false;

       //display page
       include self::$_view->getViewFile();
	}

	/**
	 * ajax显示文件编辑thickbox页面
	 */
	public function ajax_edit_file_boxAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
	    $fileName = $this->get('file');
	    if (!$fileName) {
	        exit();
	    }

	    //获取文件内容
	    if(!is_file($fileName)) {
	        exit('The file is not exists!');
	    }

	    $writabe_status = is_writable($fileName) ? true : false;

	    $file_content = file_get_contents($fileName);

	    //display page
        include self::$_view->getViewFile();
	}

	/**
	 * 字节格式化 把字节数格式为 B K M G T 描述的大小
	 *
	 * @param integer $size	文件大小
	 * @param integer $dec	小数点后的位数
	 * @return string
	 */
    protected static function byte_format($size, $dec=2) {

		$a = array("B", "KB", "MB", "GB", "TB", "PB");
		$pos = 0;
		while ($size >= 1024) {
		 	$size /= 1024;
		   	$pos++;
		}
		return round($size,$dec)." ".$a[$pos];
	}

	/**
	 * ajax创建新文件
	 */
	public function ajax_handle_create_fileAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
	    $fileName        = $this->post('file_name');
	    $file_content     = stripslashes(trim($_POST['file_content']));
	    $file_dir         = $this->post('file_dir');
	    if (!$fileName || !$file_dir) {
	        exit();
	    }

	    //判断文件夹是否存在
	    if (!is_dir($file_dir)) {
	        exit('对不起,所创建文件的目录不存在!');
	    }

	    //判断文件夹是否具有写权限
	    if(!is_writable($file_dir)) {
	        exit('对不起,当前目录没有写权限!');
	    }

	    //创建文件
	    $fileName = $file_dir . '/' . $fileName;



	    //分析所要创建的文件内容
	    if (substr($file_dir, -23) == '/application/extensions') {
	    	$className = basename($fileName);
			$fileName    .= '.class.php';

	    	//判断文件是否存在
		    if (is_file($fileName)) {
		        exit('对不起!所要创建的文件已经存在');
		    }
		    if ($file_content) {
		    	$MoreMethodObj = $this->instance(ParseMoreMethod);
		    	$method_string = $MoreMethodObj->parseMethodCode($file_content, 1, true);
		    }


			$file_content  = "<?php\r\n";
			$file_content .= CreateClassFile::get_file_note(basename($fileName), 'Enter description ...', null, null, 'extension');
			$file_content .= CreateClassFile::get_auth_code();
			$file_content .= "class {$className} extends Base {\r\n";
			$file_content .= ($method_string) ? $method_string : '';
			$file_content .= "}";
	    } else {
	      	//判断文件是否存在
		    if (is_file($fileName)) {
		        exit('对不起!所要创建的文件已经存在');
		    }
	    }

	    echo file_put_contents($fileName, $file_content, LOCK_EX) ? 101 : '对不起!操作失败,请重新操作';
	}

	/**
	 * ajax创建文件目录
	 */
	public function ajax_handle_create_dirAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
	    $dirName = $this->post('dir_name');
	    $file_dir = $this->post('file_dir');
	    if (!$dirName || !$file_dir) {
	        exit();
	    }

	    //判断当前文件目录是否存在
	    if (!is_dir($file_dir)) {
	        exit('对不起!当前目录不存在');
	    }

	    //判断文件夹是否具有写权限
	    if(!is_writable($file_dir)) {
	        exit('对不起,当前目录没有写权限!');
	    }

        $dirName = $file_dir . '/' . $dirName;
        //判断所要创建的目录是否存在
        if (is_dir($dirName)) {
            exit('对不起!所要创建的目录已经存在');
        }

        echo mkdir($dirName, 0777) ? 101 : '对不起!操作失败,请重新操作';
	}

	/**
	 * ajax删除文件及目录
	 */
	public function ajax_delete_fileAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
	    $dirName     = $this->post('dir_name');
	    $fileName    = $this->post('file_name');
	    $isdir        = $this->post('isdir');
	    if (!$dirName || !$fileName) {
	        exit();
	    }

	    $fileName = $dirName . '/' . $fileName;
	    if (file_exists($fileName)) {

	        //当删除的文件为目录时
	        if ($isdir == 1) {
                 $result = file::deleteDir($fileName);
	        } else {
	            $result = unlink($fileName);
	        }
	        echo !$result ? '对不起!操作失败,请重新操作' : 101;
	    } else {
	        echo '所在删除的文件已经不存在!';
	    }
	}

	/**
	 * ajax更改文件名
	 */
	public function ajax_handle_rename_fileAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
	    $dirName     = $this->post('dir_name');
	    $fileName    = $this->post('file_name');
	    $old_file_name= $this->post('old_file_name');
	    $isdir        = $this->post('isdir');
	    if (!$dirName || !$fileName || !$old_file_name) {
	        exit();
	    }

	    //判断当前目录是否存在
	    if (!is_dir($dirName)) {
	        exit('对不起!当前目录不存在');
	    }

	    $old_file_name = $dirName . '/' . $old_file_name;
	    $fileName = $dirName . '/' . $fileName;

	    //判断原文件是否存在
	    if(!file_exists($old_file_name)) {
	        exit('对不起!原文件或目录不存在,无法进行更名操作');
	    }

	    //当新文件名或目录与原来不同时
	    if ($old_file_name != $fileName) {
	        //分析所要更改的文件名是否存在
    	    if (file_exists($fileName)) {
                ($isdir == 1) ? exit('对不起!所要更改的新目录名已存在') : exit('对不起!所要更改的新文件名已存在');
    	    }
    	    echo rename($old_file_name, $fileName) ? 101 : '对不起!操作失败,请重新操作';
	    } else {
            echo 101;
	    }
	}

	/**
	 * ajax清空缓存文件
	 */
	public function ajax_clear_cacheAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
	    $dirName = $this->post('dir_name');
	    $path     = $this->post('path');
	    if (!$dirName || !$path) {
	        exit();
	    }

	    //判断缓存目录是否存在
	    if (!is_dir($dirName)) {
            exit('对不起!所要清空的缓存目录不存在');
	    }

		switch ($path) {

            case '/cache/data':
            case '/cache/models':
            case '/cache/temp':
            case '/cache/views/widgets':
                file::clearDir($dirName);
                break;

            case '/cache/views':
                $this->clear_view_cache($dirName);
                break;
        }

        echo 101;
	}

	/**
	 * 清空视图缓存目录中的缓存文件
	 *
	 * @param string $path 目录路径
	 * @return void
	 */
	protected function clear_view_cache($path) {

	    $file_list = file::readDir($path);
	    foreach ($file_list as $file) {
	        if (is_dir($path . '/' . $file)) {
	            //保护widgets目录不被删除
	            if ($file == 'widgets') {
	                continue;
	            }
                self::clear_view_cache($path . '/' . $file);
			    rmdir($path . '/' . $file);
			} else {
				unlink($path . '/' . $file);
			}
	    }
	}

	/**
	 * ajax编辑文件内容
	 */
	public function ajax_handle_edit_fileAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
	    $fileName     = $this->post('file_name');
	    $file_content  = stripslashes(trim($_POST['file_content']));
	    if (!$fileName) {
	        exit();
	    }

	    //判断文件是否存在
	    if (!is_file($fileName)) {
	        exit('对不起!所要编辑的文件不存在');
	    }

	    //判断文件是否具有写权限
	    if(!is_writable($fileName)) {
	        exit('对不起,当前目录没有写权限!');
	    }

	    echo file_put_contents($fileName, $file_content, LOCK_EX) ? 101 : '对不起!操作失败,请得新操作';
	}

	/**
	 * ajax上传文件
	 */
	public function ajax_upload_fileAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
	    $dirName     = $this->post('upload_dir_name');
	    $upload_file  = $_FILES['upload_file'];
        if (!$dirName) {
            exit();
        }

        //判断所上传的目录是否存在
        if (!is_dir($dirName)) {
            exit('对不起,所要上传文件的目录不存在');
        }

        $new_file = $dirName . '/' . $upload_file['name'];
        //判断所要上传的文件是否存在
        if (is_file($new_file)) {
            exit('对不起,所要上传的文件已经 存在!');
        }

        $file_upload_obj = $this->instance('upload');

        $result = $file_upload_obj->setLimitSize(1024*1024*8)->upload($upload_file, $new_file);

        echo (!$result) ? '对不起!操作失败,请重新操作' : 101;
	}
}