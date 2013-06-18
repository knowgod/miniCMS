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
            'user_level' => 'INT(10) NOT NULL DEFAULT 1 REFERENCES user_level.id',
        ),
        'data' => array(
            array(
                'id' => 1,
                'key' => 'home',
                'path' => '1',
                'title' => 'Home Page',
                'content' => '<p>Home Page<p>',
                'user_level' => '1',
            ),
            array(
                'id' => 2,
                'key' => 'tree1',
                'path' => '2',
                'title' => 'Tree 1',
                'content' => '<p>Tree 1<p>',
                'user_level' => '1',
            ),
            array(
                'id' => 3,
                'key' => 'tree1-1',
                'path' => '2/3',
                'title' => 'Tree 1-1',
                'content' => '<p>Tree 1-1<p>',
                'user_level' => '1',
            ),
            array(
                'id' => 4,
                'key' => 'tree1-2',
                'path' => '2/4',
                'title' => 'Tree 1-2',
                'content' => '<p>Tree 1-2<p>',
                'user_level' => '1',
            ),
            array(
                'id' => 5,
                'key' => 'tree1-1-2',
                'path' => '2/3/5',
                'title' => 'Tree 1-1-2',
                'content' => '<p>Tree 1-1-2<p>',
                'user_level' => '1',
            ),
        ),
    ),
    'user' => array(
        'table' => array(
            self::PRIMARY_KEY => 'id',
            'id' => 'int(10) unsigned NOT NULL auto_increment',
            'user_level' => 'INT(10) NOT NULL DEFAULT 1',
            'name' => 'VARCHAR(64) NOT NULL UNIQUE',
            'pass' => 'VARCHAR(64) NOT NULL',
            'email' => 'VARCHAR(255) NOT NULL',
        ),
        'data' => array(
            array(
                'id' => lib_Install::SQL_NULL,
                'user_level' => '2',
                'name' => 'user',
                'pass' => model_User::getPasswordHash('user'),
                'email' => 'user@example.com',
            ),
            array(
                'id' => lib_Install::SQL_NULL,
                'user_level' => '3',
                'name' => 'admin',
                'pass' => model_User::getPasswordHash('admin'),
                'email' => 'admin@example.com',
            ),
        ),
    ),
);
?>
