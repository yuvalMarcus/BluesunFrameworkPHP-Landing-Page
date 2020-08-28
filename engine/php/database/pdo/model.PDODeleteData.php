<?php

class PDODeleteData {

    static private $prepare;
    static public $sql;

    public static function query(array &$data, &$testator, &$testatorob) {

        $data['mysql']['setselectquery'] = true;

        self::action($data, $testator, $testatorob);

        $data['mysql']['setselectquery'] = false;
    }

    public static function queryComplex(array &$data, &$testator, &$testatorob) {

        self::action($data, $testator, $testatorob);
    }

    public static function action(array &$data, &$testator, &$testatorob) {


        if (!$data['mysql']['setselectquery']) {

            $sql = 'DELETE FROM ' . $data['mysql']['table_name'] . ' ' . $data['mysql']['WHERE'] . ' ' . $data['mysql']['LIMIT'] . ' ' . $data['mysql']['OTHER'] . ' ';
        } else {

            $sql = 'SELECT ' . $data['mysql']['selectquery'];
        }

        self::SetPrepare($sql);

        self::SetSQL($sql);

        for ($i = 0; $i < count($data['mysql']['executearray']); $i++) {

            $g = $i + 1;

            //echo gettype($executearray[$i]); 

            self::$prepare->bindParam($g, $data['mysql']['executearray'][$i], PDO::PARAM_STR);
        }

        self::$prepare->execute();

        $count = self::$prepare->rowCount();

        $testator = $count;

        $testatorob = self::$prepare;

        if ($data['mysql']['clear']) {

            $data['mysql']['executearray'] = [];
            $data['mysql']['table_name'] = '';
            $data['mysql']['selectingcolumns'] = '*';
            $data['mysql']['JOIN'] = null;
            $data['mysql']['WHERE'] = null;
            $data['mysql']['LIMIT'] = null;
            $data['mysql']['OTHER'] = null;
            $data['mysql']['clear'] = false;
            $data['mysql']['selectquery'] = false;
        }
    }

    public static function SetPrepare($sql = null) {

        if (is_null(self::$prepare) and ! is_null($sql) or self::$sql != $sql) {

            self::$prepare = PDOConnectDB::connectToDatabase()->prepare($sql);
        }
    }

    public static function SetSQL($sql = null) {

        if (is_null(self::$sql) or self::$sql != $sql) {

            self::$sql = $sql;
        }
    }

}
