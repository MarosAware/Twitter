<?php

class Database
{
    private static
        $username = 'root',
        $password = 'coderslab',
        $host = 'localhost',
        $database = 'Twitter',
        $dbObj = null;

    public static function setUsername($value)
    {
        self::$username = $value;
    }

    public static function setPassword($value)
    {
        self::$password = $value;
    }

    public static function setHost($value)
    {
        self::$host = $value;
    }

    public static function setDatabase($value)
    {
        self::$database = $value;
    }


    public static function connect()
    {
        try {
                //if our connection obj link is not set we create new connection
            if (!isset(self::$dbObj) OR self::$dbObj === null) {

                self::$dbObj =
                    new PDO('mysql:host=' . self::$host . ';dbname=' . self::$database, self::$username, self::$password);

                self::$dbObj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connected";
            }

            return self::$dbObj;

        } catch(PDOException $e) {
            echo 'Database connection failed. Error msg: '. $e->getMessage();
        }
    }
}
