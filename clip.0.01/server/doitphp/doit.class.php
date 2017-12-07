<?php
/**
 * doit.class.php < MYSQL专业版 >
 *
 * DoitPHP核心类,并初始化框架的基本设置
 * @author tommy <streen003@gmail.com>
 * @copyright Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: doit.class.php 1.5 2012-10-01 12:45:01Z tommy $
 * @package core
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

/**
 * 定义错误提示级别
 */
error_reporting(E_ALL^E_NOTICE);

/**
 * 定义DoitPHP框架文件所在路径
 */
if (!defined('DOIT_ROOT')) {
    define('DOIT_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}
/**
 * 设置程序开始执行时间.根据实际需要,自行开启,如开启去掉下面的//
 */
//define('DOIT_START_TIME',microtime(true));

/**
 +------------------------------------------------------------------------------------
 * 定义项目contrller, model, view, widget, config, extension目录的路径
 +------------------------------------------------------------------------------------
 */

/**
 * 项目controller目录的路径
 */
if (!defined('CONTROLLER_DIR')) {
    define('CONTROLLER_DIR', APP_ROOT . 'application/controllers' . DIRECTORY_SEPARATOR);
}

/**
 * 项目model目录的路径
 */
if (!defined('MODEL_DIR')) {
    define('MODEL_DIR', APP_ROOT . 'application/models' . DIRECTORY_SEPARATOR);
}

/**
 * 项目view目录的路径
 */
if (!defined('VIEW_DIR')) {
    define('VIEW_DIR', APP_ROOT . 'application/views' . DIRECTORY_SEPARATOR);
}

/**
 * 项目config目录的路径
 */
if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', APP_ROOT . 'application/config' . DIRECTORY_SEPARATOR);
}

/**
 * 项目widget目录的路径
 */
if (!defined('WIDGET_DIR')) {
    define('WIDGET_DIR', APP_ROOT . 'application/widgets' . DIRECTORY_SEPARATOR);
}

/**
 * 项目extension目录的路径
 */
if (!defined('EXTENSION_DIR')) {
    define('EXTENSION_DIR', APP_ROOT . 'application/extensions' . DIRECTORY_SEPARATOR);
}

/**
 * 项目缓存文件存放目录的路径
 */
if (!defined('CACHE_DIR')) {
    define('CACHE_DIR', APP_ROOT . 'cache' . DIRECTORY_SEPARATOR);
}

/**
 * 项目运行日志文件存放目录的路径
 */
if (!defined('LOG_DIR')) {
    define('LOG_DIR', APP_ROOT . 'logs' . DIRECTORY_SEPARATOR);
}

/**
 * 项目扩展模块目录的路径
 */
if (!defined('MODULE_DIR')) {
    define('MODULE_DIR', APP_ROOT . 'modules' . DIRECTORY_SEPARATOR);
}

/**
 * 项目主题目录的路径
 */
if (!defined('THEME_DIR')) {
    define('THEME_DIR', APP_ROOT . 'themes' . DIRECTORY_SEPARATOR);
}

/**
 * 项目语言包文件存放目录的路径
 */
if (!defined('LANG_DIR')) {
    define('LANG_DIR', APP_ROOT . 'application/language' . DIRECTORY_SEPARATOR);
}

/**
 +------------------------------------------------------------------------------------
 * 定义项目的主要配置信息,包括DEBUG调试,重写规则,默认的controller,action,时区等设置
 + -----------------------------------------------------------------------------------
 */

/**
 * 设置是否开启调试模式.开启后,程序运行出现错误时,显示错误信息,便于程序调试.
 * 默认为关闭,如需开启,将下面的false改为true.
 */
if (!defined('DOIT_DEBUG')) {
    define('DOIT_DEBUG', false);
}

/**
 * 设置URL的Rewrite功能是否开启,如开启后,需WEB服务器软件如:apache或nginx等,要开启Rewrite功能.
 * 默认为关闭,如需开启,只需将false换成true.
 */
if (!defined('DOIT_REWRITE')) {
    define('DOIT_REWRITE', false);
}

/**
 * 设置日志写入功能是否开启
 * 默认为开启,如需关闭,只需将true换成false.
 */
if (!defined('DOIT_LOG')) {
    define('DOIT_LOG', true);
}

/**
 * 设置时区,默认时区为东八区(中国)时区.
 * 如需更改时区,将'Asia/ShangHai'设置你所需要的时区.
 */
if (!defined('DOIT_TIMEZONE')) {
    define('DOIT_TIMEZONE', 'Asia/ShangHai');
}

/**
 * 设置系统默认的controller名称,默认为:Index
 * 如需更改,将Index换成所需要的.
 * 注:为提高不同系统平台的兼容性,名称首字母要大写,其余小写.
 */
if (!defined('DEFAULT_CONTROLLER')) {
    define('DEFAULT_CONTROLLER', 'Index');
}

/**
 *设置 系统默认的action名称,默认为index
 *如需更改,将index换成所需名称.
 *注:名称要全部使用小写字母.
 */
if (!defined('DEFAULT_ACTION')) {
    define('DEFAULT_ACTION', 'index');
}

/**
 * 定义网址路由的分割符
 * 注：分割符不要与其它网址参数等数据相冲突
 */
if (!defined('URL_SEGEMENTATION')) {
    define('URL_SEGEMENTATION', '/');
}

/**
 * 定义路由网址的伪静态网址的后缀
 * 注：不要忘记了.(点)
 */
if (!defined('URL_SUFFIX')) {
    define('URL_SUFFIX', '.html');
}

/**
 * 定义自定义URL路由规则开关
 */
if (!defined('CUSTOM_URL_ROUTER')) {
    define('CUSTOM_URL_ROUTER', false);
}

/**
 * 定义入口文件名
 */
if (!defined('ENTRY_SCRIPT_NAME')) {
    define('ENTRY_SCRIPT_NAME', 'index.php');
}

/**
 * 定义是否开启视图状态 注：当为true时视图文件格式为html;反之为php
 */
if (!defined('DOIT_VIEW')) {
    define('DOIT_VIEW', false);
}

/**
 * 加载路由网址分析文件
 */
require_once DOIT_ROOT . 'core/Router.class.php';

/**
 * Doitphp框架核心全局控制类
 *
 * 用于初始化程序运行及完成基本设置
 * @author tommy <streen003@gmail.com>
 * @version 1.0
 */
abstract class doit {

    /**
     * 控制器(controller)
     *
     * @var string
     */
    public static $controller;

    /**
     * 动作(action)
     *
     * @var string
     */
    public static $action;

    /**
     * 对象注册表
     *
     * @var array
     */
    public static $_objects = array();

    /**
     * 载入的文件名(用于PHP函数include所加载过的)
     *
     * @var array
     */
    public static $_incFiles = array();

    /**
     * 项目运行函数
     *
     * 供项目入口文件(index.php)所调用,用于启动框架程序运行
     * @access public
     * @return object
     */
    public static function run() {

        //定义变量_app
        static $_app = array();

        //分析URL,获取controller和action的名称
        $url_params  = Router::Request();

        self::$controller = $url_params['controller'];
        self::$action     = $url_params['action'];

        $appId = self::$controller . '_' . self::$action;

        if (!isset($_app[$appId])) {

            //通过实例化及调用所实例化对象的方法,来完成controller中action页面的加载
            $controller = self::$controller . 'Controller';
            $action     = self::$action . 'Action';

            //加载基本文件:Base,Controller基类
            self::loadFile(DOIT_ROOT . 'core/Base.class.php');
            self::loadFile(DOIT_ROOT . 'core/Controller.class.php');

            //加载当前要运行的controller文件
            if (is_file(CONTROLLER_DIR . $controller . '.class.php')) {
                //当文件在controller根目录下存在时,直接加载.
                self::loadFile(CONTROLLER_DIR . $controller . '.class.php');
            } else {
                //从controller的名称里获取子目录名称,注:controller文件的命名中下划线'_'相当于目录的'/'.
                $pos = strpos($controller, '_');
                if ($pos !== false) {
                    //当$controller中含有'_'字符时
                    $childDirName     = strtolower(substr($controller, 0, $pos));
                    $controllerFile   = CONTROLLER_DIR . $childDirName . '/' . $controller . '.class.php';

                    if (is_file($controllerFile)) {
                        //当子目录中所要加载的文件存在时
                        self::loadFile($controllerFile);
                    } else {
                        //当文件在子目录里没有找到时
                        self::display404Error();
                    }
                } else {
                    //当controller名称中不含有'_'字符串时
                    self::display404Error();
                }
            }

            //创建一个页面控制对象
            $appObject = new $controller();

            if (method_exists($controller, $action)){
                $_app[$appId] = $appObject->$action();
            } else {
                //所调用方法在所实例化的对象中不存在时.
                self::display404Error();
            }
        }

        return $_app[$appId];
    }

    /**
     * 显示404错误提示
     *
     * 当程序没有找到相关的页面信息时,或当前页面不存在.
     * @access public
     * @return void
     */
    private static function display404Error() {

        //判断自定义404页面文件是否存在,若不存在则加载默认404页面
        is_file(VIEW_DIR . 'error/error404.html') ? self::loadFile(VIEW_DIR . 'error/error404.html') : self::loadFile(DOIT_ROOT . 'views/html/error404.html');

        //既然提示404错误信息,程序继续执行下去也毫无意义,所以要终止(exit).
        exit();
    }

    /**
     * 获取当前运行的controller名称
     *
     * @example $controllerName = doit::getControllerName();
     * @access public
     * @return string controller名称(字母全部小写)
     */
    public static function getControllerName() {

        return strtolower(self::$controller);
    }

    /**
     * 获取当前运行的action名称
     *
     * @example $actionName = doit::getActionName();
     * @access public
     * @return string action名称(字母全部小写)
     */
    public static function getActionName() {

        return self::$action;
    }

    /**
     * 返回唯一的实例(单例模式)
     *
     * 程序开发中,model,module, widget, 或其它类在实例化的时候,将类名登记到doitPHP注册表数组($_objects)中,当程序再次实例化时,直接从注册表数组中返回所要的对象.
     * 若在注册表数组中没有查询到相关的实例化对象,则进行实例化,并将所实例化的对象登记在注册表数组中.此功能等同于类的单例模式.
     *
     * 注:本方法只支持实例化无须参数的类.如$object = new pagelist(); 不支持实例化含有参数的.
     * 如:$object = new pgelist($total_list, $page);
     *
     * <code>
     * $object = doit::singleton('pagelist');
     * </code>
     *
     * @access public
     * @param string $className  要获取的对象的类名字
     * @return object 返回对象实例
     */
    public static function singleton($className) {

        //参数分析
        if (!$className) {
            return false;
        }

        $key = trim($className);

        if (isset(self::$_objects[$key])) {
            return self::$_objects[$key];
        }

        return self::$_objects[$key] = new $className();
    }

    /**
     * 静态加载文件(相当于PHP函数require_once)
     *
     * include 以$fileName为名的php文件,如果加载了,这里将不再加载.
     * @param string $fileName 文件路径,注:含后缀名
     * @return boolean
     */
    public static function loadFile($fileName) {

        //参数分析
        if (!$fileName) {
            return false;
        }

        //判断文件有没有加载过,加载过的直接返回true
        if (!isset(self::$_incFiles[$fileName])) {

            //分析文件是不是真实存在,若文件不存在,则只能...
            if (!is_file($fileName)) {
                //当所要加载的文件不存在时,错误提示
                Controller::halt('The file:' . $fileName . ' not found!');
            }

            include_once $fileName;
            self::$_incFiles[$fileName] = true;
        }

        return self::$_incFiles[$fileName];
    }
}

/**
 * 自动加载引导文件的加载
 */
include_once DOIT_ROOT . 'core/AutoLoad.class.php';

/**
 * 调用SPL扩展,注册__autoload()函数.
 */
spl_autoload_register(array('AutoLoad', 'index'));