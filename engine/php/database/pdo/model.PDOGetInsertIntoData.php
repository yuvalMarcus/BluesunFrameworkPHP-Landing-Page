<?php

class PDOGetInsertIntoData {

    static private $prepare;
    static public $sql;

    public static function queryComplexMultiData(array &$data, &$sum) {

        $sum = 0;

        PDOConnectDB::connectToDatabase()->beginTransaction();

        for ($i = 0; $i < count($data['mysql']['multi_datainsert']); $i++) {

            $tablenamein = $data['mysql']['multi_table_name'][$i];

            $sql = "INSERT INTO $tablenamein (";

            $sqlkey = '';

            foreach ($data['mysql']['multi_datainsert'][$i] as $key => $value) {

                $sqlkey .= "$key ,";
            }

            $sqlkey = rtrim($sqlkey, ',');

            $sql .= $sqlkey . ") VALUES (";

            $sqlvalue = '';

            foreach ($data['mysql']['multi_datainsert'][$i] as $key => $value) {

                if ($value != 'NOW()') {

                    $value = PDOConnectDB::connectToDatabase()->quote($value);
                }

                $sqlvalue .= " $value ,";
            }

            $sqlvalue = rtrim($sqlvalue, ',');

            $sql .= $sqlvalue . ")";

            $sum += PDOConnectDB::connectToDatabase()->exec($sql);

            $data['mysql']['multi_datainsert_lastid'][] = PDOConnectDB::connectToDatabase()->lastInsertId();
        }

        PDOConnectDB::connectToDatabase()->commit();
    }

    public static function queryComplex(array &$data, &$testator, &$lastid, &$testatorob) {

        if (!$data['mysql']['setselectquery']) {

            $sqlkeyvalue = '';

            foreach ($data['mysql']['datainsert'] as $key => $value) {

                $sqlkeyvalue .= "$key ,";
            }

            $sqlkeyvalue = rtrim($sqlkeyvalue, ',');

            $sqlkeyvalue .= ") VALUES (";

            foreach ($data['mysql']['datainsert'] as $value) {

                if ($value != '?') {

                    if ($value != 'NOW()') {

                        $sqlkeyvalue .= " '$value' ,";
                    } else {

                        $sqlkeyvalue .= " $value ,";
                    }
                } else {

                    $sqlkeyvalue .= " $value ,";
                }
            }

            $sqlkeyvalue = rtrim($sqlkeyvalue, ',');


            $sql = 'INSERT INTO ' . $data['mysql']['table_name'] . ' ( ' . $sqlkeyvalue . ' ) ';
        } else {

            $sql = 'INSERT INTO ' . $data['mysql']['selectquery'];
        }

        self::SetPrepare($sql);

        self::SetSQL($sql);

        for ($i = 0; $i < count($data['mysql']['executearray']); $i++) {

            $numindex = $i + 1;

            self::$prepare->bindParam($numindex, $data['mysql']['executearray'][$i]);

            $numindex++;
        }


        self::$prepare->execute();

        $data['mysql']['errorInfo'] = null;

        $data['mysql']['errorInfo'] = self::$prepare->errorInfo();

        $lastid = PDOConnectDB::connectToDatabase()->lastInsertId();

        $count = self::$prepare->rowCount();

        $testator = $count;

        $testatorob = self::$prepare;
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
