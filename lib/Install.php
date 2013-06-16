<?php

/**
 * Description of Install
 *
 * @author arkadij
 */
class lib_Install extends model_Abstract
{

    const PRIMARY_KEY = 'PRIMARY_KEY';
    const SQL_NULL = 'NULL';

    private function _getTables()
    {
        include_once '.' . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'install.php';
        return $table_definitions;
    }

    public function install()
    {
        foreach ($this->_getTables() as $table => $aDetails) {
            if (isset($aDetails['table'])) {
                $this->_createTable($table, $aDetails['table']);
                if (isset($aDetails['data'])) {
                    $this->_insertData($table, $aDetails['data']);
                }
            }
        }
    }

    private function _insertData($table, array $rows)
    {
        if ($conn = $this->_getConnection()) {
            $queries = array();
            foreach ($rows as $row) {
                foreach ($row as &$el) {
                    if (self::SQL_NULL != $el) {
                        $el = "'{$el}'";
                    }
                }
                $queries[] = "REPLACE INTO `{$table}` VALUES(" . implode(',', $row) . ")";
            }
            $res = $conn->multi_query(implode('; ', $queries));

            do {
                if ($result = $conn->store_result()) {
                    app::log(array(implode(";\n", $queries) . ';', $res, $result)); //!!!!
                    $result->free();
                }
                $conn->more_results();
            } while ($conn->next_result());

            if ($conn->errno) {
                app::log($conn->errno . ' - ' . $conn->error, app::LOG_LEVEL_ERROR);
            }
        }
    }

    private function _createTable($table, array $fields)
    {
        if ($conn = $this->_getConnection()) {
            if (isset($fields[self::PRIMARY_KEY])) {
                $key = "PRIMARY KEY (`{$fields[self::PRIMARY_KEY]}`)";
                unset($fields[self::PRIMARY_KEY]);
            }
            $details = array();
            foreach ($fields as $name => $definition) {
                $details[] = "`{$name}` {$definition}";
            }
            if (isset($key)) {
                $details[] = $key;
            }
            $sql = "CREATE TABLE IF NOT EXISTS `{$table}` (" . implode(', ', $details) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $res = $conn->query($sql);
            $conn->store_result();
            app::log(array($sql, $res)); //!!!!
            if (TRUE !== $res) {
                app::log($conn->errno . ' - ' . $conn->error, app::LOG_LEVEL_ERROR);
                throw new Exception("Error creating table `{$table}`.");
            }
            app::log("Table `{$table}` created.", app::LOG_LEVEL_NOTICE);
        }
        return FALSE;
    }

}

?>
