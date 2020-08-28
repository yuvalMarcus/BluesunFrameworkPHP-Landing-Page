<?php

class TimeInLink {

    public static function active(&$data, $stringcode = '', $linkStringCode = '', $linkActiveStringCode = '') {

        $bool = FALSE;

        $data['mysql']['executearray'] = [$stringcode, $linkStringCode];
        $data['mysql']['table_name'] = 'website_extensions item';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE item.stringcode = ? AND item.name = ? ';
        $data['mysql']['clear'] = TRUE;

        Database::getData($data, 'linksdata');


        $bool = false;
        $pos = 0;
        if (!empty($data['linksdata'])) {

            foreach (json_decode($data['linksdata']['data'])->code as $val) {

                if ($val == $linkActiveStringCode) {
                    $bool = true;
                    break;
                }
                $pos++;
            }
        }

        if ($bool) {
            self::updateTime($data, $stringcode, $linkStringCode, $linkActiveStringCode, $pos);
        } else {
            self::createTime($data, $stringcode, $linkStringCode, $linkActiveStringCode);
        }
    }

    public static function updateTime(&$data, $stringcode = '', $linkStringCode = '', $linkActiveStringCode = '', $pos = 0) {

        $data['mysql']['executearray'] = [$stringcode, $linkStringCode];
        $data['mysql']['table_name'] = 'website_extensions item';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE item.stringcode = ? AND item.name = ? ';
        $data['mysql']['clear'] = TRUE;

        Database::getData($data, 'linksdata');

        $count = 0;

        $obj = json_decode($data['linksdata']['data']);

        $obj->timeInLink[$pos] = $obj->timeInLink[$pos] + ( 60 * 1000 );

        $json = json_encode($obj);

        $data['mysql']['dataupdate'] = ['data'];
        $data['mysql']['table_name'] = 'website_extensions item';
        $data['mysql']['executearray'] = [$json, $stringcode, $linkStringCode];
        $data['mysql']['WHERE'] = 'WHERE item.stringcode = ? AND item.name = ? ';
        $data['mysql']['clear'] = TRUE;

        Database::updateData($data, $count);
    }

    public static function createTime(&$data, $stringcode = '', $linkStringCode = '', $linkActiveStringCode = '') {

        $data['mysql']['executearray'] = [$stringcode, $linkStringCode];
        $data['mysql']['table_name'] = 'website_extensions item';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE item.stringcode = ? AND item.name = ? ';
        $data['mysql']['clear'] = TRUE;

        Database::getData($data, 'linksdata');

        $count = 0;

        $obj = json_decode($data['linksdata']['data']);

        $obj->opne[] = time();
        $obj->code[] = $linkActiveStringCode;
        $obj->timeInLink[] = ( 60 * 1000 );

        $json = json_encode($obj);

        $data['mysql']['dataupdate'] = ['data'];
        $data['mysql']['table_name'] = 'website_extensions item';
        $data['mysql']['executearray'] = [$json, $stringcode, $linkStringCode];
        $data['mysql']['WHERE'] = 'WHERE item.stringcode = ? AND item.name = ? ';
        $data['mysql']['clear'] = TRUE;

        Database::updateData($data, $count);
    }

}
