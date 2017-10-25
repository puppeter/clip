<?php
/**
 * Widget.class.php
 *
 * DoitPHP挂件(widget)基类
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: Widget.class.php 1.3 2011-11-13 20:32:01Z tommy $
 * @package core
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

abstract class Widget extends Controller {

    /**
     * 视图缓存文件
     *
     * @var string
     */
    protected $cacheFile;

    /**
     * 视图缓存重写开关
     *
     * @var boolean
     */
    protected $cacheStatus = false;

    /**
     * 视图变量数组
     *
     * @var array
     */
    protected $_options = array();

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
            $cacheId = $this->getWidgetName();
        }
        if (is_null($lifetime)) {
            $lifetime = 31536000;
        }

        //当视图文件格式为:html时
        if (DOIT_VIEW === true) {
            return self::$_view->cache($cacheId, $lifetime);
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
     * 创建缓存文件
     *
     * @access protected
     * @param string $cacheFile 缓存文件名
     * @param string $content 缓存文件内容
     * @return boolean
     */
    protected function createCacheFile($cacheFile, $content = null) {

        //参数分析
        if (!$cacheFile) {
            return false;
        }
        if (is_null($content)) {
            $content = '';
        }

        //分析缓存目录
        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        } else if (!is_writable($cacheDir)) {
            chmod($cacheDir, 0777);
        }

        return file_put_contents($cacheFile, $content, LOCK_EX);
    }

    /**
     * 视图变量赋值操作
     *
     * @access public
     * @param mixted $keys 视图变量名
     * @param string $value 视图变量值
     * @return mixted
     */
    public function assign($keys, $value = null) {

        //参数分析
        if (!$keys) {
            return false;
        }

        //当视图文件格式为:html时
        if (DOIT_VIEW === true) {
            return self::$_view->assign($keys, $value);
        }

        //当$keys为数组时
        if (!is_array($keys)) {
            $this->_options[$keys] = $value;
        } else {
            foreach ($keys as $handle=>$lines) {
                $this->_options[$handle] = $lines;
            }
        }

        return true;
    }

    /**
     * 显示当前页面内容
     *
     * 注:由于挂件程序的视图文件放在特定的目录里,所以挂件不支持多种主题
     * @access public
     * @param sring $viewName    视图文件名
     * @param array $_data        视图变量数组
     * @return void
     */
    public function display($viewName = null) {

        //当视图文件格式为:html时
        if (DOIT_VIEW === true) {
            return self::$_view->display($viewName);
        }

        //模板变量赋值
        if (!empty($this->_options)) {
               extract($this->_options, EXTR_PREFIX_SAME, 'data');
            //清空不必要的内存占用
            $this->_options = array();
        }

        //参数分析
        $viewFile = $this->getViewFile($viewName);

        ob_start();
        include $viewFile;
        $widgetContent = ob_get_clean();

        echo $widgetContent;

        //创建缓存文件
        if ($this->cacheStatus == true) {
            $this->createCacheFile($this->cacheFile, $widgetContent);
        }
    }

    /**
     * 分析挂件(widget)的视图文件路径
     *
     * 注:这里的视图指的是挂件的视图文件
     * @access protected
     * @param string $fileName    视图文件名.注:不含文件后缀
     * @return string            文件路径
     */
    protected function getViewFile($fileName = null) {

        //参数分析
        if (is_null($fileName)) {
            $fileName = $this->getWidgetName();
        }

        return WIDGET_DIR . 'views/' . $fileName . '.php';
    }

    /**
     * 获取当前Widget的名称
     *
     * @access protected
     * @return string
     */
    protected function getWidgetName() {

        return substr(strtolower(get_class($this)), 0, -6);
    }

    /**
     * 设置视图布局(layout)
     *
     * 注:挂件中不支持layout
     * @access public
     * @param string $layoutName    所要设置的layout名称
     * @return boolean
     */
    public function setLayout($layoutName = null) {

        return false;
    }

    /**
     * 设置视图主题
     *
     * 注:挂件中不支持视图主题
     * @access public
     * @param string $themeName 所要设置的网页模板主题名称
     * @return boolean
     */
    public function setTheme($themeName = null) {

        return false;
    }

    /**
     * 加载视图处理类并完成视图类的实例化
     *
     * @access protected
     * @return void
     */
    protected function initView() {

        //当视图文件格式为:php时
        if (DOIT_VIEW === false) {
            return true;
        }

        //分析视图处理类文件路径
        $viewFile     = DOIT_ROOT . 'core/WidgetTemplate.class.php';

        //加载视图类文件
        doit::loadFile($viewFile);

        self::$_view = WidgetTemplate::getInstance();
        self::$_view->widget = $this->getWidgetName();

        return true;
    }

    /**
     * 加载并显示视图片段文件内容
     *
     * 相当于include 代码片段，当$return为:true时返回代码代码片段内容,反之则显示代码片段内容
     * @access public
     * @param string  $fileName 视图片段文件名称
     * @param array   $_data     视图模板变量，注：数组型
     * @param boolean $return    视图内容是否为返回，当为true时为返回，为false时则为显示. 默认为:false
     * @return mixed
     */
    public function render($fileName, $_data = array(), $return = false) {

        //参数分析
        if (!$fileName) {
            return false;
        }

        //分析视图文件的路径
        $viewFile = $this->getViewFile($fileName);

        //模板变量赋值
        if (!empty($_data) && is_array($_data)) {
            extract($_data, EXTR_PREFIX_SAME, 'data');
            unset($_data);
        }

        //获取$fileName所对应的视图片段内容
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        if (!$return) {
            echo $content;
        } else {
            return $content;
        }
    }

    /**
     * 运行挂件
     *
     * @access public
     * @param array $params 参数. 如array('id'=>23)
     * @return void
     */
    public function renderContent($params = null){

        return true;
    }
}