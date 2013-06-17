<?php

$table_definitions = array(
    'user_level' => array(
        'table' => array(
            self::PRIMARY_KEY => 'id',
            'id' => 'int(10) unsigned NOT NULL auto_increment',
            'level' => 'VARCHAR(32) NOT NULL',
        ),
        'data' => array(
            array(1, 'guest'),
            array(2, 'user'),
            array(3, 'admin'),
        ),
    ),
    'page' => array(
        'table' => array(
            self::PRIMARY_KEY => 'id',
            'id' => 'int(10) unsigned NOT NULL auto_increment',
            'key' => 'VARCHAR( 32 ) NOT NULL UNIQUE',
            'path' => 'VARCHAR(16) NOT NULL',
            'title' => 'VARCHAR(64) NOT NULL',
            'content' => 'TEXT',
            'user_level' => 'INT(10) NOT NULL DEFAULT 1',
        ),
    ),
);
?>
