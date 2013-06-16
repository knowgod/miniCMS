<?php

/**
 * Description of lib_Object
 *
 * @author arkadij
 */
class lib_Object
{

    /**
     * @var array
     */
    protected $_data = array();

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : FALSE;
    }

}

?>
