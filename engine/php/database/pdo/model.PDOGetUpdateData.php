<?php

class PDOGetUpdateData {

    static private $prepare;
    static public $sql;

    public static function queryComplexMultiData(array &$data, &$sum) {

        $sum = 0;

        PDOConnectDB::connectToDatabase()->beginTransaction();

        for ($i = 0; $i < count($data['mysql']['datamultiupdate']); $i++) {

            if (!isset($data['mysql']['multi_setselectquery'][$i]) or ! $data['mysql']['multi_setselectquery'][$i]) {

                $sqlkeyvalue = '';

                foreach ($data['mysql']['datamultiupdate'][$i] as $key => $value) {

                    if ($value != 'NOW()') {

                        $value = PDOConnectDB::connectToDatabase()->quote($value);
                    }

                    $sqlkeyvalue .= "$key=$value ,";
                }

                $sqlkeyvalue = rtrim($sqlkeyvalue, ',');

                $sql = 'UPDATE ' . $data['mysql']['multi_table_name'][$i] . ' SET ' . $sqlkeyvalue . ' ' . $data['mysql']['multi_WHERE'][$i] . ' ' . $data['mysql']['multi_LIMIT'][$i] . ' ' . $data['mysql']['multi_OTHER'][$i] . ' ';
            } else {

                $sql = 'UPDATE ' . $data['mysql']['multi_selectquery'][$i];
            }

            pdo_str_replace_note($sql, '?', $data['mysql']['multiexecutearray'][$i], $newsql);

            $sum += PDOConnectDB::connectToDatabase()->exec($newsql);
        }

        PDOConnectDB::connectToDatabase()->commit();
    }

    public static function queryComplex(array &$data, &$testator, &$testatorob) {

        if (!$data['mysql']['setselectquery']) {

            $sqlkeyvalue = '';

            foreach ($data['mysql']['dataupdate'] as $value) {

                $sqlkeyvalue .= "$value=? ,";
            }

            $sqlkeyvalue = rtrim($sqlkeyvalue, ',');

            $sql = 'UPDATE ' . $data['mysql']['table_name'] . ' SET ' . $sqlkeyvalue . ' ' . $data['mysql']['WHERE'] . ' ' . $data['mysql']['LIMIT'] . ' ' . $data['mysql']['OTHER'] . ' ';
        } else {

            $sql = 'UPDATE ' . $data['mysql']['selectquery'];
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

        $count = self::$prepare->rowCount();

        $testator = $count;

        $testatorob = self::$prepare;

        if ($data['mysql']['clear']) {

            $data['mysql']['executearray'] = [];
            $data['mysql']['table_name'] = '';
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
