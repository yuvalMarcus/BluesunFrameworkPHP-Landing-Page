<?php

class PDOConnectDB {

    static private $conn;

    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASSWORD = 'password';
    const DB_NAME = 'db_name';

    static public function connectToDatabase() {

        if (is_null(self::$conn)) {

            try {

                $dsn = 'mysql:host=localhost;dbname=' . self::DB_NAME . ';charset=utf8';
                @$conn = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); // remain silent 

                self::$conn = $conn;
            } catch (PDOException $e) {

                echo 'Failed: ' . $e->getMessage();
            }
        }


        return self::$conn;
    }

}
