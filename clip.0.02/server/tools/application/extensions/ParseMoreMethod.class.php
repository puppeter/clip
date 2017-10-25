<?php
/**
 * ParseMoreMethod.class.php
 *
 * DoitPHP扩展类:多函数代码分析
 * @author tommy <streen003@gmail.com>
 * @copyright Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: ParseMoreMethod.class.php 1.0 2012-02-05 20:27:00Z tommy $
 * @package extension
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

class ParseMoreMethod extends Base {

	/**
	 * 分析多函数的字符串信息
	 *
	 * @access public
	 * @param string $string
	 * @return array
	 */
	public function parseParamsString($string) {

		//parse params
		if (!$string) {
			return false;
		}

		// get memthod array
		$memthod_array = explode(';', $string);

		$return_array = array();
		foreach ($memthod_array as $lines) {
			$lines = trim($lines);
			if (!$lines) {
				continue;
			}
			//parse ":"
			$pos1 = strpos($lines, ':');
			if ($pos1 === false) {
				$return_array[] = $lines;
			} else {
				//get method name
				$memthod_name = trim(substr($lines, 0, $pos1));

				$pos2 = strpos($lines, '[');
				$pos3 = strpos($lines, ']');

				if (($pos2 !== false) && ($pos3 !== false) && ($pos1 < $pos2) && ($pos2 < $pos3)) {
					$value = substr($lines, $pos1 + 1);
					$return_array[$memthod_name] = $this->parseUnit($value);
				} else {
					$return_array[] = $memthod_name;
				}
			}
		}

		return $return_array;
	}

	/**
	 * 分析每个函数单元字符串信息
	 *
	 * @access protected
	 * @param string $string
	 * @return array
	 */
	protected function parseUnit($string) {

		//parse params
		if (!$string) {
			return array();
		}

		//remove first "[" and last "]"
		$pos_first = strpos($string, '[');
		$pos_last  = strrpos($string, ']');

		$string = substr($string, $pos_first + 1, $pos_last - $pos_first -1);

		$pos1 = strpos($string, '[');
		$pos2 = strpos($string, ']', $pos1 +1);

		if (($pos1 === false) && ($pos2 === false)) {
			$temp_array = explode(',', $string);
			$return_array = array();
			foreach ((array)$temp_array as $memthod_name) {
				if ($memthod_name) {
					$return_array[] = array(trim($memthod_name), 'unknown');
				}
			}
			return $return_array;
		}

		$return_array = array();
		while (($pos1 !== false) && ($pos2 !== false) && ($pos2 > $pos1)) {
			$pos3 = strpos($string, '[', $pos1 + 1);
			if (($pos3 !== false) && ($pos3 < $pos2)) {
				break;
			}

			$params_array = explode(',', substr($string, $pos1 + 1, $pos2 - $pos1 -1));
			//去除各元素两边的空格
			$temp_array = array();
			foreach ((array)$params_array as $value) {
				$temp_array[] = trim($value);
			}
			$return_array[] = $temp_array;

			$pos1 = $pos3;
			$pos2 = strpos($string, ']', $pos1 +1);
		}

		return $return_array;
	}

	/**
	 * 获取多函数的文件代码
	 *
	 * @access public
	 * @param string $string
	 * @param string $access 权限
	 * @param boolean $note_status 是否含有注释
	 * @return string
	 */
	public function parseMethodCode($string, $access = null, $note_status = true) {

		//parse params
		if (!$string) {
			return false;
		}

		$params = $this->parseParamsString($string);
		if (!$params || !is_array($params)) {
			return false;
		}

		$return_string = '';
		foreach ($params as $key=>$rows) {
			if (!is_array($rows)) {
				$memthod_array = $this->parseFunctionName($rows, $access);

				if ($note_status == true) {
					$return_string .= "\r\n" . CreateClassFile::get_function_note('Enter description here ...', $memthod_array['access'], 'unknown');
				}
				$return_string .= (($note_status == true) ? "" : "\r\n") . CreateClassFile::get_function_code($memthod_array['name'], $memthod_array['access']);
			} else {
				$memthod_array = $this->parseFunctionName($key, $access);

				$memthod_params = array();
				foreach ((array)$rows as $lines) {
					if (!is_array($lines)) {
						continue;
					}
					if ($lines[2]) {
						//对字符串加上'';
						if (!in_array($lines[2], array('true', 'TRUE', 'false', 'FALSE', 'null', 'Null'))) {
							$lines[2] = "'{$lines[2]}'";
						}
						$memthod_params[$lines['0']] = $lines[2];
					} else {
						$memthod_params[] = $lines['0'];
					}
				}

				if ($note_status == true) {
					$return_string .= "\r\n" . CreateClassFile::get_function_note('Enter description here ...', $memthod_array['access'], 'unknown', $rows);
				}
				$return_string .= (($note_status == true) ? "" : "\r\n") . CreateClassFile::get_function_code($memthod_array['name'], $memthod_array['access'], $memthod_params);
			}
		}

		return $return_string;
	}

	/**
	 * 分析函数名
	 *
	 * @access protected
	 * @param string $function_name
	 * @param string $access 权限
	 * @return array
	 *
	 * @example
	 * return data
	 * array('name'=>.., 'access'=>..);
	 */
	protected function parseFunctionName($function_name, $access = null) {

		//parse params
		if (!$function_name) {
			return false;
		}

		$function_name = trim($function_name);
		$pos = strpos($function_name, '|');
		if ($pos === false) {
			return array('name' => $function_name, 'access' => $this->parseAccessName($access));
		} else {
			$memthod_name = trim(substr($function_name, 0, $pos));
			//parse params
			$access_name = trim(substr($function_name, $pos + 1));
			$access_name = ($access_name) ? $access_name : $access;
			$access_name = $this->parseAccessName($access_name);

			return array('name' => $memthod_name, 'access' => $access_name);
		}
	}

	/**
	 * 分析权限名称
	 *
	 * @access proteteced
	 * @param string $access_name
	 * @return string
	 */
	protected function parseAccessName($access_name = null) {

		//parse params
		if (is_null($access_name)) {
			return 'public';
		}

		if (is_numeric($access_name)) {
			if (!in_array($access_name, array(1, 2, 3))) {

				$access_name = 'public';
			} else {
				$access_array = array(
					1 => 'public',
					2 => 'protected',
					3 => 'private',
				);

				$access_name = $access_array[$access_name];
			}
		} else {
			$access_name = strtolower(trim($access_name));
			$access_name = !in_array($access_name, array('public', 'protected', 'private')) ? 'public' : $access_name;
		}

		return $access_name;
	}
}