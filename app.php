<?php

/**
 * The main application class
 *
 * @author arkadij
 */
class app
{

    const LOG_LEVEL_ERROR = 'ERROR';
    const LOG_LEVEL_NOTICE = 'NOTICE';
    const LOG_LEVEL_DEBUG = 'DEBUG';

    protected static $_aLogHandlers = array();
    protected static $_config;

    protected static function _getAppDir()
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }

    public static function getConfig($section = '')
    {
        if (!self::$_config) {
            include_once self::_getAppDir() . 'etc/config.php';
            self::$_config = $configuration;
        }
        $conf = self::$_config;
        return empty($section) ? $conf : (isset($conf[$section]) ? $conf[$section] : FALSE);
    }

    public static function autoload($class)
    {
        $filename = self::_getAppDir() . str_replace('_', '/', $class) . '.php';
        if (file_exists($filename)) {
            include_once $filename;
            return TRUE;
        }
        return FALSE;
    }

    /**
     *
     * @param string $model
     * @return model_Abstract
     */
    public static function getModel($modelName)
    {
        $model = ucwords(str_replace('_', ' ', $modelName));
        $class = 'model_' . str_replace(' ', '_', $model);
        self::autoload($class);
        return new $class($modelName);
    }

    /**
     *
     * @param mixed $data
     * @param string $file
     * @return string|boolean
     */
    public static function log($data, $level = self::LOG_LEVEL_DEBUG, $file = 'var/log/system.log')
    {
        if (defined('LOG_ON') && LOG_ON) {
            $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            if (!isset($stack[1]['class'])) {
                $stack[1]['class'] = isset($stack[0]['file']) ? $stack[0]['file'] : 'noClass';
            }
            if (!isset($stack[1]['function'])) {
                $stack[1]['function'] = 'noFunc';
            }
            $before = "{$stack[0]['line']}. {$stack[1]['class']}::{$stack[1]['function']} :\n";

            $info = date('Y-m-d H:i:s') . ' :: ' . $level . ' :: ';
            $str = $info . $before . print_r($data, 1);

            if ($file) {
                if (isset(self::$_aLogHandlers[$file])) {
                    $handler = self::$_aLogHandlers[$file];
                } else {
                    $filename = self::_getAppDir() . $file;
                    $handler = fopen($filename, 'a');
                    if ($handler) {
                        self::$_aLogHandlers[$file] = $handler;
                    }
                }
                if ($handler) {
                    fwrite($handler, $str);
                }
            }
            return $str;
        }
        return FALSE;
    }

}

function __autoload($class)
{
    return app::autoload($class);
}

?>
