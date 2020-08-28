<?php

class PDODB {

    static private $prepare;
    static public $sql;

    public function SetPrepare($sql = null) {

        if (is_null(self::$prepare) and ! is_null($sql) or self::$sql != $sql) {

            self::$prepare = PDOConnectDB::connectToDatabase()->prepare($sql);
        }
    }

    public function SetSQL($sql = null) {

        if (is_null(self::$sql) or self::$sql != $sql) {

            self::$sql = $sql;
        }
    }

}
