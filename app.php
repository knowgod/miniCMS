<?php

/**
 * Shorter alias
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * The main application class
 *
 * It starts the application with static method run().
 * Desired controller is selected based on the request parameters.
 *
 * @author arkadij
 */
class app
{
    /**
     * Levels for logging
     */

    const LOG_LEVEL_ERROR = 'ERROR';
    const LOG_LEVEL_NOTICE = 'NOTICE';
    const LOG_LEVEL_DEBUG = 'DEBUG';
    /**
     * Defaults
     */
    const DEFAULT_CONTROLLER = 'page';
    const DEFAULT_CONTROLLER_ACTION = 'view';

    protected static $_aLogHandlers = array();
    protected static $_config;

    public static function getBaseDir()
    {
        return dirname(__FILE__) . DS;
    }

    public static function getBaseUrl()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
    }

    /**
     * Take the whole config or just a section of it
     *
     * @param string $section
     * @return array
     */
    public static function getConfig($section = '')
    {
        if (!self::$_config) {
            include_once self::getBaseDir() . 'etc/config.php';
            self::$_config = $configuration;
        }
        $conf = self::$_config;
        return empty($section) ? $conf : (isset($conf[$section]) ? $conf[$section] : FALSE);
    }

    public static function autoload($class)
    {
        $filename = self::getBaseDir() . str_replace('_', '/', $class) . '.php';
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
    public static function getModel($modelName, $param = NULL)
    {
        $model = ucwords(str_replace('_', ' ', $modelName));
        $class = 'model_' . str_replace(' ', '_', $model);
        self::autoload($class);
        if (!is_null($param)) {
            return new $class($modelName, $param);
        }
        return new $class($modelName);
    }

    /**
     * Use it to log info into file
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
            $str = $info . $before . print_r($data, 1) . "\n";

            if ($file) {
                if (isset(self::$_aLogHandlers[$file])) {
                    $handler = self::$_aLogHandlers[$file];
                } else {
                    $filename = self::getBaseDir() . $file;
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

    /**
     * Construct URL with parameters
     *
     * @param array $params
     * @return type
     */
    public static function getUrl(array $params = array())
    {
        $string = array();
        foreach ($params as $param => $val) {
            $string[] = urlencode($param) . '=' . urlencode($val);
        }
        return self::getBaseUrl() . '?' . implode('&', $string);
    }

    /**
     * The main entry point
     */
    public static function run()
    {
        foreach (self::_getControllerSequence() as $name) {
            if (isset($_REQUEST[$name])) {
                $ctrlName = "controller_" . ucwords($name);
                break;
            }
        }
        if (!isset($ctrlName)) {
            $ctrlName = "controller_" . ucwords(self::DEFAULT_CONTROLLER);
        }
        $controller = new $ctrlName();

        $methods = get_class_methods($controller);
        if (isset($_REQUEST[$name]) && in_array($_REQUEST[$name] . 'Action', $methods)) {
            $action = $_REQUEST[$name] . 'Action';
        } else {
            $action = self::DEFAULT_CONTROLLER_ACTION . 'Action';
        }

        $controller->$action();
    }

    protected static function _getControllerSequence()
    {
        return array(
            'user',
            'page'
        );
    }

}

function __autoload($class)
{
    return app::autoload($class);
}

?>
