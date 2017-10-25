<?php
/**
 * CreateClassFile.class.php
 *
 * DoitPHP扩展类:生成类文件操作
 * @author tommy <streen003@gmail.com>
 * @copyright Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: CreateClassFile.class.php 1.0 2012-02-04 16:20:00Z tommy $
 * @package extension
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

class CreateClassFile extends Base {

	/**
	 *文件注释
	 *
	 * @access public
	 * @param
	 *
	 * @return string
	 */
	public static function get_file_note($fileName = null, $description = null, $author = null, $copyright = null, $package = null) {

		$string  = "/**\r\n";
		$string .= " * file: {$fileName}\r\n";
		$string .= " *\r\n";
		$string .= " * {$description}\r\n";
		$string .= " * @author {$author}\r\n";
		$string .= " * @copyright Copyright (C) {$copyright} All rights reserved.\r\n";
		$string .= " * @version \$Id: {$fileName} 1.0 " . date('Y-m-d H:i:s'). "Z" . (!is_null($author) ? " {$author}" : "") . " \$\r\n";
		$string .= " * @package {$package}\r\n";
		$string .= " * @since 1.0\r\n";
		$string .= " */\r\n\r\n";

		return $string;
	}

	/**
	 * 函数注释
	 *
	 * @access public
	 * @param string $description 函数作用
	 * @param string $access 权限
	 * @param string $return_type 返回数据类型
	 * @param array $params 函数的参数
	 * @return string
	 *
	 * @example
	 * $parsms数组格式
	 * array(
	 * array(参数名, 数据类型, 默认值),
	 * array(username, string),
	 * array(page, integer, 1),
	 * )
	 */
	public static function get_function_note($description = null, $access = 'public', $return_type = null, $params = array()) {

		//parse params
		$access = self::parse_access_name($access);

		$string  = "\t/**\r\n";
		$string .= "\t * {$description}\r\n";
		$string .= "\t *\r\n";
		$string .= "\t * @access {$access}\r\n";

		if ($params && is_array($params) && is_array($params[0])) {
			$count_num = sizeof($params);
			if ($count_num > 2) {
				$string .= "\t *\r\n";
			}
			foreach ($params as $lines) {
				$string .= "\t * @param {$lines[1]} \${$lines[0]}" . (isset($lines[3]) ? ' '. $lines[3] : ''). "\r\n";
			}
			if ($count_num > 2) {
				$string .= "\t *\r\n";
			}
		}

		$string .= "\t * @return {$return_type}\r\n";
		$string .= "\t */\r\n";

		return $string;
	}

	/**
	 * 函数代码
	 *
	 * @access public
	 * @param stirng $function_name 函数名称
	 * @param string $access 权限 'public', 'protected', 'private',也可以是 1, 2, 3
	 * @param boolean 是否为静态
	 * @param array $params 参数
	 * @param string $content 函数的内容
	 * @return string
	 */
	public static function get_function_code($function_name, $access = 'public', $params = array(), $is_static = false, $content = null) {

		//parse params
		$access = self::parse_access_name($access);

		if ($params && is_array($params)) {
			$params_array = array();
			foreach ($params as $key=>$value) {
				$params_array[] = !is_numeric($key) ? "\${$key} = {$value}" : "\${$value}";
			}
			$params_string = implode(', ', $params_array);
			unset($params_array);
		} else {
			$params_string = '';
		}

		$string  = "\t{$access} " . ($is_static === true ? 'static ' : '') . "function {$function_name}({$params_string}) {\r\n";
		if (!is_null($content) && is_string($content)) {
			$string .= "\t\t{$content}";
		}
		$string .= "\r\n\t}\r\n";

		return $string;
	}

	/**
	 * 分析函数权限
	 *
	 * @access public
	 * @param string $access 权限
	 * @return string
	 */
	public static function parse_access_name($access = 'public') {

		//parse params
		if (!$access) {
			return 'public';
		}

		$access = strtolower(trim($access));
		if (!in_array($access, array('public', 'protected', 'private'))) {
			if (!is_numeric($access)) {
				$access = 'public';
			}
			$access = !in_array($access, array(1, 2, 3)) ? 1 : $access;
			$access_array = array(
				1 => 'public',
				2 => 'protected',
				3 => 'private',
			);
			$access = $access_array[$access];
		}

		return $access;
	}

	/**
	 * 获取类名
	 *
	 * @access public
	 * @param string $fileName 文件名
	 */
	public static function get_class_name($fileName) {

		//parse params
		if (!$fileName) {
			return false;
		}

		$base_name = basename($fileName);
		if (strpos($base_name, '.class.php') === false) {
			return false;
		}

		return trim(str_replace('.class.php', '', $base_name));
	}

	/**
	 * 分析类名称
	 *
	 * @access public
	 * @param stirng $className 类名称
	 * @param string $file_type 文件类型(controller, model, widget, module)
	 * @return string
	 */
	public static function parse_class_name($className, $file_type = null) {

		//parse params
		if (!$className) {
			return false;
		}

		if (is_null($file_type)) {
			return trim($className);
		}

		$file_type = strtolower($file_type);
		if (!in_array($file_type, array('controller', 'model', 'widget', 'module'))) {
			return trim($className);
		}

		return ucfirst(strtolower($className)) . ucfirst($file_type);
	}

	/**
	 * 分析类的文件名
	 *
	 * @access public
	 * @param string $className 类名
	 * @param string $file_type 文件类型(controller, model, widget, module)
	 * @return string
	 */
	public static function parse_file_name($className, $file_type = null) {

		//parse params
		if (!$className) {
			return false;
		}

		return self::parse_class_name($className, $file_type) . '.class.php';
	}

	/**
	 * 获取入口权限代码
	 *
	 * @access public
	 * @return string
	 */
	public static function get_auth_code() {

		return "if (!defined('IN_DOIT')) {\r\n\texit('403:Access Forbidden!');\r\n}\r\n\r\n";
	}
}