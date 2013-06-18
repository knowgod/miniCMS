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

    /**
     * Load model data from DB table.
     *
     * Can load by ID - primary field
     * or by any custom field.
     * Anyway you will get only the 1st row.
     *
     * @staticvar bool $recursiveCall Flag describes if we have executed installer and call this recursively.
     * @param mixed $id Object ID or value of custom field
     * @param string $field Custom field name
     * @return model_Abstract
     */
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
//            app::log(array($sql, $result, $conn->errno, $conn->error)); //!!!!

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
//        app::log($this->_data); //!!!!
        return $this;
    }

    /**
     * Returns collection of objects.
     *
     * @param string $orderByField Order collection by custom field
     * @return array Collection
     */
    protected function _getCollection($orderByField = 'id')
    {
        if ($conn = $this->_getConnection()) {
            $sql = "SELECT * FROM `{$this->_dbTable}` ORDER BY `{$orderByField}`;";
            $result = $conn->query($sql);
            $collection = array();
            if ($result instanceof mysqli_result && $result->num_rows) {
                while ($row = $result->fetch_assoc()) {
                    $model = app::getModel($this->_modelName);
                    $model->setData($row);
                    $collection[$row['id']] = $model;
                }
            }

            return $collection;
        }
    }

    /**
     * Save current model info into DB table
     *
     * @return model_Abstract
     */
    public function save()
    {
        return $this;
    }

    /**
     * Delete selected object from DB table
     *
     * @param int $id If not set - delete current model object
     * @return bool
     */
    public function delete($id = NULL)
    {
        return FALSE;
    }

    /**
     * Install table structure and data into DB
     *
     * Run if considered that installation is not complete.
     * Usually executed at first application run.
     */
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

    /**
     * Create DB connection using details from configuration
     *
     * @return mysqli
     */
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
