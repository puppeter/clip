<?php
/**
 * Module.class.php
 *
 * DoitPHP系统module的基类
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: Module.class.php 1.0 2011-3-24 20:10:00Z tommy $
 * @package core
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

abstract class Module extends Controller {

    /**
     * 分析视图缓存
     *
     * @access public
     * @param string $cacheId 缓存ID
     * @param integer $lifetime 缓存周期
     * @return boolean
     */
    public function cache($cacheId = null, $lifetime = null) {

        return false;
    }

    /**
     * 设置视图布局
     *
     * 注:Module不支持布局
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
     * 扩展module里本功能是不支持的,所以返回false
     * @access public
     * @param string $themeName 所要设置的网页模板主题名称
     * @return boolean
     */
    public function setTheme($themeName = null) {

        return false;
    }

    /**
     * 获取当前扩展module目录的路径
     *
     * @access public
     * @return string    目录的路径
     */
    public function getModuleRoot() {

        return MODULE_DIR . $this->getModuleName() . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取当前Module目录的URL
     *
     * @access public
     * @return string    当前Moudle目录的URL
     */
    public function getModuleUrl() {

        return $this->getBaseUrl() . 'modules/' . $this->getModuleName() . '/';
    }

    /**
     * 扩展module的视图显示
     *
     * 注:扩展module的视图文件放在特定的目录里,所以扩展module不支持多种主题
     * @access public
     * @param sring $viewName 视图文件名
     * @param array $_data    视图变量数组(所要赋值的视图变量)
     * @return void
     */
    public function display($viewName = null) {

        //参数分析
        $viewFile = $this->getViewFile($viewName);

        ob_start();
        include $viewFile;
        echo ob_get_clean();
    }

    /**
     * 获取当前Module的名称
     *
     * @access protected
     * @return string    Module名称
     */
    protected function getModuleName() {

        return substr(strtolower(get_class($this)), 0, -6);
    }

    /**
     * 分析module的视图文件
     *
     * @access protected
     * @param stirng $fileName 视图文件名称
     * @return string
     */
    protected function getViewFile($fileName = null) {

        //获取当前module文件路径
        $moduleName = $this->getModuleName();

        //参数分析
        if (!$fileName) {
            $fileName = $moduleName;
        }

        return MODULE_DIR . $moduleName . '/views/' . $fileName . '.php';
    }

    /**
     * 加载并显示视图片段文件内容
     *
     * @access public
     * @param string  $fileName 视图片段文件名称
     * @param array   $_data     视图模板变量，注：数组型
     * @param boolean $return    视图内容是否为返回，当为true时为返回，为false时则为显示. 默认为:false
     * @return void
     */
    public function render($fileName, $_data = array(), $return = false){

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
}