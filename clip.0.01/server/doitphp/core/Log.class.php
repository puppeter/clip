<?php
/**
 * Log.class.php
 *
 * DoitPHP日志管理
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: Log.class.php 1.3 2010-11-13 20:28:00Z tommy $
 * @package core
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

class Log extends Base {

    /**
     * 写入日志
     *
     * 将日志内容写入日志文件,并且当日志文件大小到达2M时,则写入新的日志文件
     * @access public
     * @param string $message   所要写入的日志内容
     * @param string $level     日志类型. 参数：Warning, Error, Notice
     * @param string $logFileName  日志文件名
     * @return boolean
     */
    public static function write($message, $level = 'Error', $logFileName = null) {

        //参数分析
        if (!$message) {
            return false;
        }

        //当日志写入功能关闭时
        if(DOIT_LOG == false){
            return true;
        }

        $logFileName = self::getLogFile($logFileName);

        //判断日志目录
        $logDir = dirname($logFileName);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        } elseif (!is_writable($logDir)) {
            chmod($logDir, 0777);
        }

        error_log(date('[Y-m-d H:i:s]') . " {$level}: {$message} IP: {$_SERVER['REMOTE_ADDR']}\r\n", 3, $logFileName);
    }

    /**
     * 显示日志内容
     *
     * 显示日志文件内容,以列表的形式显示.便于程序调用查看日志
     * @access public
     * @param string $logFileName 所要显示的日志文件内容,默认为null, 即当天的日志文件名.注:不带后缀名.log
     * @return void
     */
    public static function show($logFileName = null) {

        //参数分析
        $logFileName    = self::getLogFile($logFileName);

        $logContent     = is_file($logFileName) ? file_get_contents($logFileName) : '';

        $listStrArray   = explode("\r\n", $logContent);
        unset($logContent);
        $totalLines    = sizeof($listStrArray);

        //输出日志内容
        echo '<table width="85%" border="0" cellpadding="0" cellspacing="1" style="background:#0478CB; font-size:12px; line-height:25px;">';

        foreach ($listStrArray as $key=>$linesStr) {

            if ($key == $totalLines - 1) {
                continue;
            }

            $bgColor = ($key % 2 == 0) ? '#FFFFFF' : '#C6E7FF';

            echo '<tr><td height="25" align="left" bgcolor="' . $bgColor .'">&nbsp;' . $linesStr . '</td></tr>';
        }

        echo '</table>';
    }

    /**
     * 获取当前日志文件名
     *
     * @access protected
     * @param $logFileName 日志文件名
     * @return string
     */
    protected static function getLogFile($logFileName = null) {
        return LOG_DIR .date('Y-m') . '/' . (is_null($logFileName) ? date('Y-m-d') : $logFileName) . '.log';
    }
}