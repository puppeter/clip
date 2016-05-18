<?php
/**
 * Template.class.php
 *
 * doitPHP视图类，用于完成对视图文件(仅限html文件)的编译及加载工作
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: Template.class.php 1.3 2012-01-18 20:32:00Z tommy $
 * @package core
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

class Template extends Base {

    /**
     * 视图实例
     *
     * @var object
     */
    protected static $_instance;

    /**
     * 视图目录
     *
     * @var string
     */
    public $viewDir;

    /**
     * 视图编译缓存目录
     *
     * @var string
     */
    public $compileDir;

    /**
     * 模板标签左侧边限字符
     *
     * @var string
     */
    public $leftDelimiter = '<!--\s?{';

    /**
     * 模板标签右侧边限字符
     *
     * @var string
     */
    public $rightDelimiter = '}\s?-->';

    /**
     * 模板参数信息
     *
     * @var array
     */
    protected $_options = array();

    /**
     * 视图布局
     *
     * @var string
     */
    public $layout;

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
     * 构造函数
     *
     * 初始化运行环境,或用于运行环境中基础变量的赋值
     * @access public
     * @return boolean;
     */
    public function __construct() {

        //定义视图目录及编译目录
        $this->viewDir    = VIEW_DIR;
        $this->compileDir = CACHE_DIR . 'views' . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取视图文件的路径
     *
     * @access protected
     * @param string $fileName    视图名. 注：不带后缀
     * @return string    视图文件路径
     */
    protected function getViewFile($fileName) {

        return $this->viewDir . $fileName . '.html';
    }

    /**
     * 获取视图编译文件的路径
     *
     * @access protected
     * @param string $fileName 视图名. 注:不带后缀
     * @return string    编译文件路径
     */
    protected function getCompileFile($fileName) {

        return $this->compileDir . $fileName . '.cache.php';
    }

    /**
     * 生成视图编译文件
     *
     * @access protected
     * @param string $compileFile 编译文件名
     * @param string $content    编译文件内容
     * @return void
     */
    protected function createCompileFile($compileFile, $content) {

        //分析编译文件目录
        $compileDir = dirname($compileFile);
        $this->makeDir($compileDir);

        $content = "<?php if(!defined('IN_DOIT')) exit(); ?>\r\n" . $content;

        return file_put_contents($compileFile, $content, LOCK_EX);
    }

    /**
     * 缓存重写分析
     *
     * 判断缓存文件是否需要重新生成. 返回true时,为需要;返回false时,则为不需要
     * @access protected
     * @param string $viewFile        视图文件名
     * @param string $compileFile    视图编译文件名
     * @return boolean
     */
    protected function isCompile($viewFile, $compileFile) {

        return (is_file($compileFile) && (filemtime($compileFile) >= filemtime($viewFile))) ? false : true;
    }

    /**
     * 分析创建目录
     */
    protected function makeDir($dirName) {

        //参数分析
        if (!$dirName) {
            return false;
        }

        if (!is_dir($dirName)) {
            mkdir($dirName, 0777, true);
        } else if (!is_writable($dirName)) {
            //更改目录权限
            chmod($dirName, 0777);
        }
    }

    /**
     * 设置视图变量
     *
     * @access public
     * @param mixed $key       视图变量名
     * @param string $value    视图变量数值
     * @return mixed
     */
    public function assign($key, $value = null) {

        //参数分析
        if(!$key) {
            return false;
        }

        //当$key为数组时
        if(is_array($key)) {
            foreach ($key as $k=>$v) {
                $this->_options[$k] = $v;
            }
        } else {
            $this->_options[$key] = $value;
        }

        return true;
    }

    /**
     * 分析视图文件名
     *
     * @access protected
     * @param mixed $fileName    视图名
     * @return stirng
     */
    protected function parseFileName($fileName = null) {

        //当参数为空时，默认当前controller当前action所对应的视图文件
        if (is_null($fileName)) {
            //获取当前的controller及action
            $controllerId     = doit::getControllerName();
            $actionId         = doit::getActionName();
            //生成默认视图文件
            $fileName         = $controllerId . '/' . $actionId;

        } else {
            //分析视图文件里是否调用非当前controller的视图文件
            if (strpos($fileName, '/') !==  false) {
                $fileNameArray      = explode('/', $fileName);
                $fileName           = trim($fileNameArray[0]) . '/' . trim($fileNameArray[1]);
            } else {
                //当视图类在widget里运行时
                $controllerId       = doit::getControllerName();
                $fileName           = $controllerId . '/' . $fileName;
            }
        }

        return $fileName;
    }

    /**
     * 加载视图文件
     *
     * 加载视图文件并对视图标签进行编译
     * @access protected
     * @param string $viewFile     视图文件及路径
     * @return string                 编译视图文件的内容
     */
    protected function loadViewFile($viewFile) {

        //分析视图文件是否存在
        if (!is_file($viewFile)) {
            trigger_error('The view file: ' . $viewFile . ' is not exists!', E_USER_ERROR);
        }

        $viewContent = file_get_contents($viewFile);

        //编译视图标签
        return $this->handleViewFile($viewContent);
    }

    /**
     * 设置视图的主题
     *
     * @access public
     * @param string $themeName 所要设置的网页模板主题名称
     * @return string 视图的主题名
     */
    public function setTheme($themeName = 'default') {

        return $this->viewDir = THEME_DIR . $themeName . DIRECTORY_SEPARATOR;
    }

    /**
     * 设置视图文件布局结构的文件名(layout)
     *
     * layout默认为:main
     * @access public
     * @param string $layoutName      所要设置的layout名称
     * @return string                 layout名称
     */
    public function setLayout($layoutName = null) {

        return $this->layout = $layoutName;
    }

    /**
     * 编译视图标签
     *
     * @access protected
     * @param string $viewContent
     * @return string    编译视图文件的内容
     */
    protected function handleViewFile($viewContent) {

        //参数分析
        if (!$viewContent) {
            return false;
        }

        //正则表达式匹配的模板标签
        $regexArray = array(
        '#'.$this->leftDelimiter.'\s*include\s+(.+?)\s*'.$this->rightDelimiter.'#is',
        '#'.$this->leftDelimiter.'php\s+(.+?)'.$this->rightDelimiter.'#is',
        '#'.$this->leftDelimiter.'\s?else\s?'.$this->rightDelimiter.'#i',
        '#'.$this->leftDelimiter.'\s?\/if\s?'.$this->rightDelimiter.'#i',
        '#'.$this->leftDelimiter.'\s?\/loop\s?'.$this->rightDelimiter.'#i',
        );

        ///替换直接变量输出
        $replaceArray = array(
        "<?php \$this->render('\\1'); ?>",
        "<?php \\1 ?>",
        "<?php } else { ?>",
        "<?php } ?>",
        "<?php } } ?>",
        );

        //对固定的视图标签进行编辑
        $viewContent = preg_replace($regexArray, $replaceArray, $viewContent);

        //处理if, loop, 变量等视图标签
        $patternArray = array(
        '#'.$this->leftDelimiter.'\s*(\$.+?)\s*'.$this->rightDelimiter.'#i',
        '#'.$this->leftDelimiter.'\s?(if\s.+?)\s?'.$this->rightDelimiter.'#i',
        '#'.$this->leftDelimiter.'\s?(elseif\s.+?)\s?'.$this->rightDelimiter.'#i',
        '#'.$this->leftDelimiter.'\s?(loop\s.+?)\s?'.$this->rightDelimiter.'#i',
        '#'.$this->leftDelimiter.'\s*(widget\s.+?)\s*'.$this->rightDelimiter.'#is',
        );
        $viewContent = preg_replace_callback($patternArray, array($this, 'parseTags'), $viewContent);

        //对编辑后的代码进行压缩,去掉多余的空格,换行,制表符
        $viewContent = preg_replace('#\?\>\s*\<\?php\s#s', '', $viewContent);
        $viewContent = str_replace(array("\r\n", "\n", "\t"), '', $viewContent);

        return $viewContent;
    }

    /**
     * 分析编辑视图标签
     *
     * @access protected
     * @param string $tag 视图标签
     * @return string
     */
    protected function parseTags($tag) {

        //变量分析
        $tag = stripslashes(trim($tag[1]));
        //当视图标签为空时
        if(empty($tag)) {
            return '';
        }
        //变量标签处理
        if (substr($tag, 0, 1) == '$') {
            return '<?php echo ' . $this->getVal($tag) . '; ?>';
        } else {
            //分析判断,循环标签
            $tag_sel = array_shift(explode(' ', $tag));
            switch ($tag_sel) {

                case 'if' :
                    return $this->_compileIfTag(substr($tag, 3));
                    break;

                case 'elseif' :
                    return $this->_compileIfTag(substr($tag, 7), true);
                    break;

                case 'loop' :
                    return $this->_compileForeachStart(substr($tag, 5));
                    break;

                case 'widget' :
                    return $this->_compileWidgetTag(substr($tag, 7));
                    break;

                default :
                    return $tag_sel;
                    break;
            }
        }
    }

    /**
     * 处理if标签
     *
     * @access public
     * @param string $tagArgs 标签内容
     * @param bool $elseif 是否为elseif状态
     * @return  string
     */
    protected function _compileIfTag($tagArgs, $elseif = false) {

        //分析标签内容
        preg_match_all('#\-?\d+[\.\d]+|\'[^\'|\s]*\'|"[^"|\s]*"|[\$\w\.]+|!==|===|==|!=|<>|<<|>>|<=|>=|&&|\|\||\(|\)|,|\!|\^|=|&|<|>|~|\||\%|\+|\-|\/|\*|\@|\S#i', $tagArgs, $match);
        $tokens = $match[0];

        //当$match[0]不为空时
        $tokenArray = array();
        foreach ($match[0] as $vaule) {
            $tokenArray[] = $this->getVal($vaule);
        }
        $tokenStr = implode(' ', $tokenArray);
        unset($tokenArray);

        return ($elseif === false) ? '<?php if (' . $tokenStr . ') { ?>' : '<?php } else if (' . $tokenStr . ') { ?>';
    }

    /**
     * 处理foreach标签
     *
     * @access public
     * @param string $tagArgs 标签内容
     * @return string
     */
    protected function _compileForeachStart($tagArgs) {

        //分析标签内容
        preg_match_all('#(\$.+?)\s+(.+)#i', $tagArgs, $match);
        $loopVar = $this->getVal($match[1][0]);

        return '<?php if (is_array(' . $loopVar . ')) { foreach (' . $loopVar . ' as ' . $match[2][0] . ') { ?>';
    }

    /**
     * 处理widget标签
     *
     * @access public
     * @param string $tagArgs 标签内容
     * @return string
     */
    protected function _compileWidgetTag($tagArgs) {

        //判断是否为参数传递标签
        $pos = strpos($tagArgs, '$');

        if ($pos !== false) {
            $widgetId  = trim(substr($tagArgs, 0, $pos));
            $params    = $this->getVal(trim(substr($tagArgs, $pos)));

            return '<?php Controller::widget(\'' . $widgetId . '\', ' . $params . '); ?>';
        }

        return '<?php Controller::widget(\'' . $tagArgs . '\'); ?>';
    }

    /**
     * 处理视图标签中的变量标签
     *
     * @access protected
     * @param string $val 标签名
     * @return string
     */
    protected function getVal($val) {

        if (strpos($val, '.') === false) {
            return $val;
        }

        $valArray = explode('.', $val);
        $_varName = array_shift($valArray);

        return $_varName . '[\'' . implode('\'][\'', $valArray) . '\']';
    }

    /**
     * 加载局部视图
     *
     * @access protected
     * @param string $fileName      局部视图文件名
     * @param array   $_data     视图模板变量，注：数组型
     * @param boolean $return    视图内容是否为返回，当为true时为返回，为false时则为显示. 默认为:false
     * @return string                编译文件路径
     */
    public function render($fileName, $_data = array(), $return = false) {

        //参数分析
        if (!$fileName) {
            return false;
        }

        //视图参数赋值
        if ($_data && is_array($_data)) {
            extract($_data, EXTR_PREFIX_SAME, 'data');
            unset($_data);
        }

        //分析视图文件名
        $fileName       = $this->parseFileName($fileName);

        //获取视图文件及编译文件
        $viewFile       = $this->getViewFile($fileName);
        $compileFile    = $this->getCompileFile($fileName);

        //分析视图编译文件是否需要重新生成
        if ($this->isCompile($viewFile, $compileFile)) {
            $viewContent = $this->loadViewFile($viewFile);
            //重新生成编译缓存文件
            $this->createCompileFile($compileFile, $viewContent);
        } else {
            ob_start();
            include $compileFile;
            $viewContent = ob_get_clean();
        }

        if (!$return) {
            echo $viewContent;
        } else {
            return $viewContent;
        }
    }

    /**
     * 分析视图缓存文件名
     *
     * @access protected
     * @param string $cacheId 缓存ID
     * @return string
     */
    protected function parseCacheFile($cacheId) {

        return CACHE_DIR . 'html/' . doit::getControllerName() . '/' . md5($cacheId) . '.action.html';
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
            $cacheId = doit::getActionName();
        }
        if (is_null($lifetime)) {
            //默认缓存生命周期为:一年
            $lifetime = 31536000;
        }

        //获取缓存文件
        $cacheFile = $this->parseCacheFile($cacheId);
        if (is_file($cacheFile) && (filemtime($cacheFile) + $lifetime >= time())) {
            include $cacheFile;
            exit();
        }

        $this->cacheStatus = true;
        $this->cacheFile   = $cacheFile;

        return true;
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
        $this->makeDir($cacheDir);

        return file_put_contents($cacheFile, $content, LOCK_EX);
    }

    /**
     * 显示视图文件
     *
     * @access public
     * @param string $fileName    视图名
     * @return void
     */
    public function display($fileName = null) {

        //分析视图变量
        if (!empty($this->_options)) {
            extract($this->_options, EXTR_PREFIX_SAME, 'data');
            //清空不必要的内存占用
            $this->_options = array();
        }

        //判断layout视图变量
        if ($this->layout) {
            $layoutFile      = $this->viewDir . 'layout/' . $this->layout . '.html';
            $layoutState    = is_file($layoutFile) ? true : false;
        } else {
            $layoutState    = false;
        }

        //分析视图文件名
        $fileName      = $this->parseFileName($fileName);

        //获取视图文件及编译文件
        $viewFile      = $this->getViewFile($fileName);
        $compileFile   = $this->getCompileFile($fileName);

        //分析视图编译文件是否需要重新生成
        if ($this->isCompile($viewFile, $compileFile)) {
            $viewContent = $this->loadViewFile($viewFile);
            //重新生成编译缓存文件
            $this->createCompileFile($compileFile, $viewContent);
        }

        if (!$layoutState) {
            //加载编译缓存文件
            ob_start();
            include $compileFile;
            $htmlContent = ob_get_clean();
        } else {

            //加载layout文件
            $layoutCompileFile = $this->getCompileFile('layout/' . $this->layout);
            if ($this->isCompile($layoutFile, $layoutCompileFile)) {
                //重新生成layout视图编译文件
                $layoutContent = $this->loadViewFile($layoutFile);
                $this->createCompileFile($layoutCompileFile, $layoutContent);
            }

            //获取视图编译文件内容
            ob_start();
            include $compileFile;
            $content = ob_get_clean();

            //获取所要显示的页面的视图编译内容
            ob_start();
            include $layoutCompileFile;
            $htmlContent = ob_get_clean();
        }

        //显示视图内容
        echo $htmlContent;

        //创建视图缓存文件
        if ($this->cacheStatus == true) {
            $this->createCacheFile($this->cacheFile, $htmlContent);
        }
    }

    /**
     * 析构函数
     *
     * 用于程序运行结束后"打扫战场"
     * @access public
     * @return void
     */
    public function __destruct() {
        //清空不必要的内存占用
        $this->_options = array();
    }

    /**
     * 单件模式调用方法
     *
     * @access public
     * @var object
     */
     public static function getInstance(){

         if (!self::$_instance instanceof self) {
             self::$_instance = new self();
         }

        return self::$_instance;
    }
}