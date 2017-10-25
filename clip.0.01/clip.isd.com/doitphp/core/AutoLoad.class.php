<?php
/**
 * AutoLoad.class.php
 *
 * DoitPHP自动加载引导类
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: AutoLoad.class.php 1.0 2012-01-18 22:48:01Z tommy $
 * @package core
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

class AutoLoad {

    /**
     * DoitPHP核心类引导数组
     *
     * 用于自动加载文件运行时,引导路径
     * @var array
     */
    public static $coreClassArray = array(
    'Request'           => 'core/Request.class.php',
    'Model'             => 'core/Model.class.php',
    'db_mysqli'         => 'core/db/db_mysqli.class.php',
    'db_mysql'          => 'core/db/db_mysql.class.php',
    'db_pdo'            => 'core/db/db_pdo.class.php',
    'Log'               => 'core/Log.class.php',
    'Widget'            => 'core/Widget.class.php',
    'View'              => 'core/View.class.php',
    'Template'          => 'core/Template.class.php',
    'WidgetTemplate'    => 'core/WidgetTemplate.class.php',
    'Module'            => 'core/Module.class.php',
    'script'            => 'libraries/script.class.php',
    'html'              => 'libraries/html.class.php',
    'pager'             => 'libraries/pager.class.php',
    'cookie'            => 'libraries/cookie.class.php',
    'session'           => 'libraries/session.class.php',
    'cache'             => 'libraries/cache/cache.class.php',
    'cache_db'          => 'libraries/cache/cache_db.class.php',
    'cache_php'         => 'libraries/cache/cache_php.class.php',
    'cache_file'        => 'libraries/cache/cache_file.class.php',
    'configure'         => 'libraries/configure.class.php',
    'file'              => 'libraries/file.class.php',
    'image'             => 'libraries/image.class.php',
    'pincode'           => 'libraries/pincode.class.php',
    'check'             => 'libraries/check.class.php',
    'player'            => 'libraries/player.class.php',
    'language'          => 'libraries/language.class.php',
    'upload'            => 'libraries/upload.class.php',
    'excel'             => 'libraries/excel.class.php',
    'curl'              => 'libraries/curl.class.php',
    'client'            => 'libraries/client.class.php',
    'zip'               => 'libraries/zip.class.php',
    'cache_memcache'    => 'libraries/cache/cache_memcache.class.php',
    'cache_xcache'      => 'libraries/cache/cache_xcache.class.php',
    'pinyin'            => 'libraries/pinyin.class.php',
    'text'              => 'libraries/text.class.php',
    'ftp'               => 'libraries/ftp.class.php',
    'mkhtml'            => 'libraries/mkhtml.class.php',
    'queue'             => 'libraries/queue.class.php',
    'tree'              => 'libraries/tree.class.php',
    'form'              => 'libraries/form.class.php',
    'cart'              => 'libraries/cart.class.php',
    'xml'               => 'libraries/xml.class.php',
    'csv'               => 'libraries/csv.class.php',
    'calendar'          => 'libraries/calendar.class.php',
    'http'              => 'libraries/http.class.php',
    'wsdl'              => 'libraries/wsdl.class.php',
    'encrypt'           => 'libraries/encrypt.class.php',
    'cache_apc'         => 'libraries/cache/cache_apc.class.php',
    'cache_eaccelerator'=> 'libraries/cache/cache_eaccelerator.class.php',
    'db_sqlite'         => 'core/db/db_sqlite.class.php',
    'db_redis'          => 'core/db/db_redis.class.php',
    'db_oracle'         => 'core/db/db_oracle.class.php',
    'db_postgres'       => 'core/db/db_postgres.class.php',
    'db_mssql'          => 'core/db/db_mssql.class.php',
    'db_mongo'          => 'core/db/db_mongo.class.php'
    );

    /**
     * 项目文件的自动加载
     *
     * doitPHP系统自动加载核心类库文件(core目录内的文件)及运行所需的controller文件、model文件、widget文件等
     *
     * 注:并非程序初始化时将所有的controller,model等文件都统统加载完,再执行其它.理解本函数前一定要先理解AutoLoad的作用.
     * 当程序运行时发现所需的文件没有找到时,AutoLoad才会被激发,按照index()的程序设计来完成对该文件的加载
     *
     * @access public
     * @param string $className 所需要加载的类的名称,注:不含后缀名
     * @return void
     */
    public static function index($className) {

        //doitPHP核心类文件的加载分析
        if (isset(self::$coreClassArray[$className])) {
            //当$className在核心类引导数组中存在时, 加载核心类文件
            doit::loadFile(DOIT_ROOT . self::$coreClassArray[$className]);
        } elseif (substr($className, -10) == 'Controller') {
            //controller文件自动载分析
            if (is_file(CONTROLLER_DIR . $className . '.class.php')) {
                //当文件在controller根目录下存在时,直接加载.
                doit::loadFile(CONTROLLER_DIR . $className . '.class.php');
            } else {
                //从controller的名称里获取子目录名称,注:controller文件的命名中下划线'_'相当于目录的'/'.
                $pos = strpos($className, '_');
                if ($pos !== false) {
                    //当$controller中含有'_'字符时
                    $childDirName      = strtolower(substr($className, 0, $pos));
                    $controllerFile     = CONTROLLER_DIR . $childDirName . '/' . $className . '.class.php';
                    if (is_file($controllerFile)) {
                        //当子目录中所要加载的文件存在时
                        doit::loadFile($controllerFile);
                    } else {
                        //当文件在子目录里没有找到时
                        Controller::halt('The File:' . $className .'.class.php is not exists!');
                    }
                } else {
                    //当controller名称中不含有'_'字符串时
                    Controller::halt('The File:' . $className .'.class.php is not exists!');
                }
            }
        } else if (substr($className, -5) == 'Model') {
            //modlel文件自动加载分析
            if (is_file(MODEL_DIR . $className . '.class.php')) {
                //当所要加载的model文件存在时
                doit::loadFile(MODEL_DIR . $className . '.class.php');
            } else {
                //当所要加载的文件不存在时,显示错误提示信息
                Controller::halt('The Model file: ' . $className . ' is not exists!');
            }
        } else if(substr($className, -6) == 'Widget') {
            //加载所要运行的widget文件
            if (is_file(WIDGET_DIR . $className . '.class.php')) {
                //当所要加载的widget文件存在时
                doit::loadFile(WIDGET_DIR . $className . '.class.php');
            } else {
                Controller::halt('The Widget file: ' . $className . ' is not exists!');
            }
        } else {
            //分析扩展目录文件
            if (is_file(EXTENSION_DIR . $className . '.class.php')) {
                //当扩展目录内文件存在时,则加载文件
                doit::loadFile(EXTENSION_DIR . $className . '.class.php');
            } else {
                //支持自定义auto load设置,适用于第三方扩展程序(模块)文件的自动加载
                $autoloadConfigFile = CONFIG_DIR . 'autoload.ini.php';
                if(!is_file($autoloadConfigFile)) {
                    //当所要加载的文件不存在时,提示错误信息
                    Controller::halt('The File:' . $className .'.class.php is not exists!');
                }
                //分析自定义自动加载
                $autoloadArray = Controller::getConfig('autoload');
                $autoStatus = false;
                foreach ((array)$autoloadArray as $rules) {
                    //将设置的规则中的*替换为所要加载的文件类名
                    $autoloadFile = str_replace('*', $className, $rules);
                    //当自定义自动加载的文件存在时
                    if (is_file($autoloadFile)) {
                        doit::loadFile($autoloadFile);
                        $autoStatus = true;
                        break;
                    }
                }
                //当执行完自定义自动加载规则后,还没有找到所要加载的文件时,提示错误信息
                if ($autoStatus == false) {
                    Controller::halt('The file of class ' . $className .' is not exists!');
                }
            }
        }
    }
}