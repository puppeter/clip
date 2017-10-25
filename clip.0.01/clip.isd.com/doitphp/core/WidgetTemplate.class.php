<?php
/**
 * WidgetTemplate.class.php
 *
 * 用于完成对挂件视图文件(仅限html文件)的编译及加载工作
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: WidgetTemplate.class.php 1.0 2012-01-30 23:15:00Z tommy $
 * @package core
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

class WidgetTemplate extends Template {

    /**
     * widget的名称,默认为null
     *
     * @var string
     */
    public $widget;


    /**
     * 构造函数
     *
     * 初始化运行环境,或用于运行环境中基础变量的赋值
     * @access public
     * @return boolean;
     */
    public function __construct() {

        //定义视图目录及编译目录
        $this->viewDir    = WIDGET_DIR . 'views' . DIRECTORY_SEPARATOR;
        $this->compileDir = CACHE_DIR . 'views/widgets' . DIRECTORY_SEPARATOR;

        return true;
    }

    /**
     * 设置视图的主题
     *
     * @access public
     * @param string $themeName 所要设置的网页模板主题名称
     * @return boolean
     */
    public function setTheme($themeName = 'default') {

        return false;
    }

    /**
     * 设置视图文件布局结构的文件名(layout)
     *
     * layout默认为:main
     * @access public
     * @param string $layoutName     所要设置的layout名称
     * @return boolean
     */
    public function setLayout($layoutName = null) {

        return false;
    }

    /**
     * 加载局部视图
     *
     * @access protected
     * @param string $fileName      局部视图文件名
     * @param array   $_data     视图模板变量，注：数组型
     * @param boolean $return    视图内容是否为返回，当为true时为返回，为false时则为显示. 默认为:false
     * @return boolean
     */
    public function render($fileName, $_data = array(), $return = false) {

        return false;
    }

    /**
     * 分析视图缓存文件名
     *
     * @access protected
     * @param string $cacheId 缓存ID
     * @return string
     */
    protected function parseCacheFile($cacheId) {

        return CACHE_DIR . 'html/widgets/' . md5($cacheId) . '.widget.html';
    }

    /**
     * 分析视图缓存文件是否需要重新创建
     *
     * @access public
     * @param string $cacheId 缓存ID
     * @param integer $lifetime 缓存文件生存周期, 默认为一年
     * @return boolean
     */
    public function cache($cacheId = null, $lifetime = null) {

        //参数分析
        if (is_null($cacheId)) {
            $cacheId = $this->widget;
        }
        if (is_null($lifetime)) {
            //默认缓存生命周期为:一年
            $lifetime = 31536000;
        }

        //获取缓存文件
        $cacheFile = $this->parseCacheFile($cacheId);
        if (is_file($cacheFile) && (filemtime($cacheFile) + $lifetime >= time())) {
            include $cacheFile;
            return true;
        }

        $this->cacheStatus = true;
        $this->cacheFile   = $cacheFile;

        return false;
    }

    /**
     * 显示视图文件
     *
     * @access public
     * @param string $fileName    视图名
     * @return void
     */
    public function display($fileName = null) {

        //参数分析
        $fileName = is_null($fileName) ? $this->widget : $fileName;

        //分析视图变量
        if (!empty($this->_options)) {
            extract($this->_options, EXTR_PREFIX_SAME, 'data');
            //清空不必要的内存占用
            $this->_options = array();
        }

        //获取视图文件及编译文件
        $viewFile      = $this->getViewFile($fileName);
        $compileFile   = $this->getCompileFile($fileName);

        //分析视图编译文件是否需要重新生成
        if ($this->isCompile($viewFile, $compileFile)) {
            $viewContent = $this->loadViewFile($viewFile);
            //重新生成编译缓存文件
            $this->createCompileFile($compileFile, $viewContent);
        }

        //加载编译缓存文件
        ob_start();
        include $compileFile;
        $htmlContent = ob_get_clean();

        //显示视图内容
        echo $htmlContent;

        //创建视图缓存文件
        if ($this->cacheStatus == true) {
            $this->createCacheFile($this->cacheFile, $htmlContent);
        }
    }

    /**
     * 单件模式调用方法
     *
     * @access public
     * @return object
     */
     public static function getInstance(){

         if (!self::$_instance instanceof self) {
             self::$_instance = new self();
         }

        return self::$_instance;
    }
}