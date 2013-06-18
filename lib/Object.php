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
        return $this;
    }

    /**
     *
     * @param array|string $data
     * @param mixed $value
     */
    public function setData($data, $value = null)
    {
        if (is_array($data)) {
            $this->_data = $data;
        } else {
            $this->_data[$data] = $value;
        }
        return $this;
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
