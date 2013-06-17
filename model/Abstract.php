<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modelAbstract
 *
 * @author arkadij
 */
abstract class model_Abstract extends lib_Object
{

    /**
     * @var mysqli
     */
    protected static $_dbConnection;

    /**
     * @var string
     */
    protected $_dbTable;

    /**
     * @var string
     */
    protected $_modelName;

    public function __construct($modelName)
    {
        $this->_modelName = $modelName;
        if (!$this->_dbTable) {
            $this->_dbTable = "{$this->_modelName}";
        }
    }

    public function load($id, $field = NULL)
    {
        static $recursiveCall;
        if ($conn = $this->_getConnection()) {
            if (is_null($field)) {
                $sql = "SELECT * FROM `{$this->_dbTable}` WHERE `id`=$id LIMIT 1;";
            } else {
                $sql = "SELECT * FROM `{$this->_dbTable}` WHERE `{$field}`='$id' LIMIT 1;";
            }
            $result = $conn->query($sql);
            app::log(array($sql, $result, $conn->errno, $conn->error)); //!!!!

            /**
             * Table doesn't exist - means application is not installed
             */
            if ('1146' == $conn->errno) {
                if ($recursiveCall) {
                    app::log('Unable to finish installation!', app::LOG_LEVEL_ERROR);
                    return $this;
                }
                $this->_createDbStructure();
                $recursiveCall = TRUE;
                $this->load($id, $field);
                $recursiveCall = FALSE;
            }
            if ($result instanceof mysqli_result && $result->num_rows) {
                $this->addData($result->fetch_assoc());
            } else {
                $this->id = 0;
            }
        }
        app::log($this->_data); //!!!!
        return $this;
    }

    public function save()
    {
        return $this;
    }

    public function delete($id = NULL)
    {
        return $this;
    }

    private final function _createDbStructure()
    {
        if ($conn = $this->_getConnection()) {
            $installer = new lib_Install('install');
            try {
                $installer->install();
            } catch (Exception $e) {
                app::log($e->getMessage(), app::LOG_LEVEL_ERROR);
            }
        }
    }

    protected function _getConnection()
    {
        if (!self::$_dbConnection) {
            $config = app::getConfig('db');
            $mysqli = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);
            if ($mysqli->connect_errno) {
                self::$_dbConnection = FALSE;
                app::log($mysqli->errno . ' - ' . $mysqli->error, app::LOG_LEVEL_ERROR);
            } else {
                self::$_dbConnection = $mysqli;
            }
        }
        return self::$_dbConnection;
    }

}

?>
