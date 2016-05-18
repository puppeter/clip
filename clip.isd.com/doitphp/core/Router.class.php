<?php
/**
 * Router.class.php
 *
 * 获取网址的路由信息类
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: Router.class.php 1.0 2012-01-18 21:35:01Z tommy $
 * @package core
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

abstract class Router {

    /**
     * 分析路由网址, 获取当前的Controller名及action名
     *
     * 通过对URL(网址)的分析,获取当前运行的controller和action,赋值给变量self::controller, 和self::action,
     * 方便程序调用,同时将URL中的所含有的变量信息提取出来 ,写入$_GET全局超级变量数组中.
     *
     * 注:这里的URL的有效部分是网址'?'之前的部分.'?'之后的部分不再被分析,因为'?'之后的URL部分完全属于$_GET正常调用的范畴.
     * 这里的网址分析不支持功能强大的路由功能,只是将网址中的'/'分隔开,经过简单地程序处理提取有用数据.
     * @access public
     * @return array
     */
    public static function Request() {

         //分析网址为传统的url时, 如: index.php?controller=member&action=list&page=12
        if (isset($_GET['controller'])) {
            //获取controller及action名称
            $controllerName      = ($_GET['controller'] == true) ? htmlspecialchars(trim($_GET['controller'])) : DEFAULT_CONTROLLER;
            $actionName          = (isset($_GET['action']) && $_GET['action'] == true) ? htmlspecialchars(trim($_GET['action'])) : DEFAULT_ACTION;

            return array('controller' => ucfirst(strtolower($controllerName)), 'action' => strtolower($actionName));
        }

        //分析包含路由信息的网址
        if (isset($_SERVER['SCRIPT_NAME']) && isset($_SERVER['REQUEST_URI'])) {
            //当项目开启Rewrite设置时
            if (DOIT_REWRITE === false) {
                $pathUrlString = strlen($_SERVER['SCRIPT_NAME']) > strlen($_SERVER['REQUEST_URI']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['REQUEST_URI'];
                $pathUrlString = str_replace($_SERVER['SCRIPT_NAME'], '', $pathUrlString);
            } else {
                $pathUrlString = str_replace(str_replace('/' . ENTRY_SCRIPT_NAME, '', $_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
                //去掉伪静态网址后缀
                $pathUrlString = str_replace(URL_SUFFIX, '', $pathUrlString);
            }

            //如网址(URL)含有'?'(问号),则过滤掉问号(?)及其后面的所有字符串
            $pos = strpos($pathUrlString, '?');
            if ($pos !== false) {
                $pathUrlString = substr($pathUrlString, 0, $pos);
            }

            //当自定义URL路由功能开启时
            if (CUSTOM_URL_ROUTER === true) {
                $routerConfigFile = CONFIG_DIR . 'router.ini.php';
                if (is_file($routerConfigFile)) {
                    //加载router的设置文件
                    $routerArray = require_once $routerConfigFile;
                    //利用正则表达式将自定义的网址替换掉.替换为真实的网址
                    if ($routerArray && is_array($routerArray)) {
                        foreach ($routerArray as $routerKey=>$routerValue) {
                            $routerKey = str_replace(array(':any', ':num'), array('.+?', '[0-9]+'), $routerKey);
                            if (preg_match('#' . $routerKey . '#', $pathUrlString)) {
                                $pathUrlString = preg_replace('#' . $routerKey . '#', $routerValue, $pathUrlString);
                                break;
                            }
                        }
                    }
                }
            }

            //将处理过后的有效URL进行分析,提取有用数据.
            $urlInfoArray = explode(URL_SEGEMENTATION, str_replace('/', URL_SEGEMENTATION, $pathUrlString));

            //获取 controller名称
            $controllerName  = (isset($urlInfoArray[1]) && $urlInfoArray[1] == true) ? $urlInfoArray[1] : DEFAULT_CONTROLLER;
            //获取 action名称
            $actionName  = (isset($urlInfoArray[2]) && $urlInfoArray[2] == true) ? $urlInfoArray[2] : DEFAULT_ACTION;

            //变量重组,将网址(URL)中的参数变量及其值赋值到$_GET全局超级变量数组中
            if (($totalNum = sizeof($urlInfoArray)) > 4) {
                for ($i = 3; $i < $totalNum; $i += 2) {
                    if (!$urlInfoArray[$i]) {
                        continue;
                    }
                    $_GET[$urlInfoArray[$i]] = $urlInfoArray[$i + 1];
                }
            }
            //删除不必要的变量,清空内存占用
            unset($urlInfoArray);

            return array('controller' => ucfirst(strtolower($controllerName)), 'action' => strtolower($actionName));
        }

        //分析当前的运行环境是否为CLI模式
        if (PHP_SAPI == 'cli') {
            $controllerName     =  (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == true) ? ucfirst(strtolower($_SERVER['argv'][1])) : DEFAULT_CONTROLLER;
            $actionName         =  (isset($_SERVER['argv'][2]) && $_SERVER['argv'][2] == true) ? strtolower($_SERVER['argv'][2]) : DEFAULT_ACTION;
            //分析并获取参数, 参数格式如: --param_name=param_value
            if (($totalNum = sizeof($_SERVER['argv'])) > 3) {
                for ($i = 3; $i < $totalNum; $i ++) {
                    //CLI运行环境下参数模式:如 --debug=true, 不支持 -h -r等模式
                    if (substr($_SERVER['argv'][$i], 0, 2) == '--') {
                        $pos = strpos($_SERVER['argv'][$i], '=');
                        if ($pos !== false) {
                            $key                   = substr($_SERVER['argv'][$i], 2, $pos - 2);
                            $_SERVER['argv'][$key] = substr($_SERVER['argv'][$i], $pos + 1);
                            unset($_SERVER['argv'][$i]);
                        }
                    }
                }
            }

            return array('controller' => $controllerName, 'action' => $actionName);
        }

        return array('controller' => DEFAULT_CONTROLLER, 'action' => DEFAULT_ACTION);
    }

    /**
     * 网址(URL)组装操作
     *
     * 组装绝对路径的URL
     * @access public
     * @param string     $route             controller与action
     * @param array     $params         URL路由其它字段
     * @param boolean     $routingMode    网址是否启用路由模式
     * @return string    URL
     */
    public static function createUrl($route, $params = null, $routingMode = true) {

        //参数分析.
        if (!$route) {
            return false;
        }

        //controller, action的URL组装
        $url      = self::getBaseUrl() . ((DOIT_REWRITE === false) ? ENTRY_SCRIPT_NAME . URL_SEGEMENTATION : '');
        if ($routingMode == true) {
            $url .= str_replace('/', URL_SEGEMENTATION, $route);
        } else {
            $route_array = explode('/', $route);
            $url .= '?controller=' . trim($route_array[0]) . '&action=' . trim($route_array[1]);
            unset($route_array);
        }

        //参数$params变量的键(key),值(value)的URL组装
        if (!is_null($params) && is_array($params)) {
            $paramsUrl = array();
            if ($routingMode == true) {
                foreach ($params as $key=>$value) {
                    $paramsUrl[] = trim($key) . URL_SEGEMENTATION . trim($value);
                }
                $url .= URL_SEGEMENTATION . implode(URL_SEGEMENTATION, $paramsUrl) . ((DOIT_REWRITE === false) ? '' : URL_SUFFIX);
            } else {
               $url  .= '&' . http_build_query($params);
            }
        }

        return str_replace('//', URL_SEGEMENTATION, $url);
    }

    /**
     * 获取当前项目的根目录的URL
     *
     * 用于网页的CSS, JavaScript，图片等文件的调用.
     * @access public
     * @return string     根目录的URL. 注:URL以反斜杠("/")结尾
     */
    public static function getBaseUrl() {

        //处理URL中的//或\\情况,即:出现/或\重复的现象
        $url = str_replace(array('\\', '//'), '/', dirname($_SERVER['SCRIPT_NAME']));

        return (substr($url, -1) == '/') ? $url : $url . '/';
    }
}