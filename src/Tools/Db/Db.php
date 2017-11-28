<?php
declare(strict_types = 1);

namespace Tools\Db;

use Tools\Config\Builder\NativeBuilder;
use Tools\Config\Config;

class Db
{
    /**
     * @var \PDO[]
     */
    protected static $instance = [];

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    /**
     * @param $connection
     * @return \PDO
     * @throws \Exception
     */
    public static function getConnection($connection) : \PDO
    {
        if (!isset(static::$instance[$connection])) {

            $dbConfig = new Config(new NativeBuilder("config/db.php"));
            if ($dbConfig->localhost == null) {
                throw new \Exception("Missing config for selected connection");
            }

            static::$instance[$connection] = new \PDO("mysql:host={$dbConfig->localhost['host']};dbname={$dbConfig->localhost['db']};charset={$dbConfig->localhost['charset']}", $dbConfig->localhost['user'], $dbConfig->localhost['pass']);
        }

        return static::$instance[$connection];
    }
}