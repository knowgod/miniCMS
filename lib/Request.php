<?php

/**
 * Description of Request
 *
 * @author arkadij
 */
class lib_Request extends lib_Object
{

    public function unsetParam($param)
    {
        unset($_REQUEST[$param]);
    }

    public function setParam($param, $value)
    {
        $_REQUEST[$param] = $value;
        return $this;
    }

    public function getParam($param = NULL, $default = FALSE)
    {
        if (is_null($param)) {
            return $_REQUEST;
        }
        if (isset($_REQUEST[$param])) {
            return $_REQUEST[$param];
        }
        return $default;
    }

    public function getData($key = NULL)
    {
        return $this->getParam($key);
    }

    public function setStatus($code)
    {
        $codes = $this->_getStatusesArray();
        if (isset($codes[$code])) {
            header("HTTP/1.1 {$code} {$codes[$code]}");
        }
    }

    protected function _getStatusesArray()
    {
        return array(
            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found', // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );
    }

    public function __toString()
    {
        return print_r($_REQUEST, 1);
    }

}

?>
