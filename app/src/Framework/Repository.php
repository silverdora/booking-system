<?php

/** 
 * Base repository class to manage database connections.
*/

namespace App\Framework;

use App\Config;
use PDO;
class Repository
{
    private static ?PDO $connection = null;

    protected function  getConnection(): PDO 
    {
        if (self::$connection === null) {
            $this->connect();
        }
        return self::$connection;
    }

    private function connect(): void
    {
        try {
            //create a new conection string
            $connectionString = 'mysql:host=' . Config::DB_SERVER_NAME . ';dbname=' . Config::DB_NAME . ';charset=utf8mb4';

            //create new PDO connection
            self::$connection = new \PDO(
                $connectionString,
                Config::DB_USERNAME,
                Config::DB_PASSWORD
            );

            
        } catch (\PDOException $e){
            //tell PDO to throw exception or error
            self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ATTR_ERRMODE_EXCEPTION);
            error_log($e->getMessage()); //log the real error
            die("DB connection failed"); //display message
        }
    }
}
?>