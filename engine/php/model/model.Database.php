<?php

class Database {

    public static $typeDatabase = 'mysql';

    public static function updateTypeDatabase(array &$data, string $type = 'mysql') {

        self::$typeDatabase = $type;
    }

    public static function getData(array &$data, string $name, array &$array = []) {

        switch (self::$typeDatabase) {
            case 'mysql':

                PDOGetSelectData::queryComplex($data, $array, $ob);

                if (!empty($array[0])) {

                    $data[$name] = $array[0];
                }

                break;
            default :
                $data[$name] = [];
                break;
        }
    }

    public static function getDatas(array &$data, string $name, array &$array = []) {

        switch (self::$typeDatabase) {
            case 'mysql':

                PDOGetSelectData::queryComplex($data, $array, $ob);

                $data[$name] = $array;

                break;
            default :
                $data[$name] = [];
                break;
        }
    }

    public static function getDatasAddInLoop(array &$data, string $name) {

        switch (self::$typeDatabase) {
            case 'mysql':

                PDOGetSelectData::queryComplex($data, $array, $ob);

                foreach ($array as $val) {

                    $data[$name][] = $val;
                }

                break;
            default :
                $data[$name] = [];
                break;
        }
    }

    public static function updateData(array &$data, int &$count) {

        switch (self::$typeDatabase) {
            case 'mysql':

                PDOGetUpdateData::queryComplex($data, $count, $ob);

                break;
            default :
                break;
        }
    }

    public static function updateMultiData(array &$data, int &$count) {

        switch (self::$typeDatabase) {
            case 'mysql':

                PDOGetUpdateData::queryComplexMultiData($data, $count);

                break;
            default :
                break;
        }
    }

    public static function insertData(array &$data, int &$count, int &$lastid) {

        switch (self::$typeDatabase) {
            case 'mysql':

                PDOGetInsertIntoData::queryComplex($data, $count, $lastid, $testatorob);

                break;
            default :
                break;
        }
    }

    public static function insertMultiData(array &$data, int &$count) {

        switch (self::$typeDatabase) {
            case 'mysql':

                PDOGetInsertIntoData::queryComplexMultiData($data, $count);

                break;
            default :
                break;
        }
    }

    public static function deleteData(array &$data, int &$count) {

        switch (self::$typeDatabase) {
            case 'mysql':

                PDODeleteData::queryComplex($data, $count, $ob);

                break;
            default :
                break;
        }
    }

    public static function getTables(array &$data, string $name, array &$array = []) {

        switch (self::$typeDatabase) {
            case 'mysql':

                PDOGetShowTables::queryComplex($data, $array, $ob);

                $data[$name] = $array;

                break;
            default :
                break;
        }
    }

}
