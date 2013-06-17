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

    public function addData(array $data)
    {
        foreach ($data as $key => $val) {
            $this->_data[$key] = $val;
        }
    }

    public function setData(array $data)
    {
        $this->_data = $data;
    }

    public function getData($key = NULL)
    {
        if (!empty($key)) {
            return isset($this->_data[$key]) ? $this->_data[$key] : FALSE;
        }
        return $this->_data;
    }

}

?>
