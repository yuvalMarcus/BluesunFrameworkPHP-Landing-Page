<?php

class GetData {

    public static function metaContent(array &$data, int $id, array &$metadata, bool $asyncOperation = false) {

        $data['mysql']['executearray'] = [$id];
        $data['mysql']['table_name'] = 'website_content_items_metadata';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE objid = ?';
        $data['mysql']['clear'] = TRUE;

        Database::getDatas($data, 'desdata');

        foreach ($data['desdata'] as $val) {

            $metadata[$val['metakey']] = $val['metavalue'];
        }
    }

    public static function metaTemp(array &$data, string $stringcode, array &$metadata) {

        $data['mysql']['executearray'] = [$stringcode];
        $data['mysql']['table_name'] = 'website_template_metadata';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE stringcode = ?';
        $data['mysql']['clear'] = TRUE;

        Database::getDatas($data, 'desdata');

        foreach ($data['desdata'] as $val) {

            $metadata[$val['metakey']] = $val['metavalue'];
        }
    }

    public static function metaZone(array &$data, int $id, array &$metadata) {

        $data['mysql']['executearray'] = [$id];
        $data['mysql']['table_name'] = 'zone_page_metadata';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE zoneid = ?';
        $data['mysql']['clear'] = TRUE;

        Database::getDatas($data, 'desdata');

        foreach ($data['desdata'] as $val) {

            $metadata[$val['metakey']] = $val['metavalue'];
        }
    }

    public static function metaWebData(array &$data, int $id, array &$metadata) {

        $data['mysql']['executearray'] = [$id];
        $data['mysql']['table_name'] = 'webdata_metadata';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE objid = ?';
        $data['mysql']['clear'] = TRUE;

        Database::getDatas($data, 'desdata');

        foreach ($data['desdata'] as $val) {

            $metadata[$val['metakey']] = $val['metavalue'];
        }
    }

    public static function usersData(array &$data, array &$usersdata) {

        $usersdata = [];

        $data['mysql']['executearray'] = [];
        $data['mysql']['table_name'] = 'user';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['clear'] = TRUE;

        Database::getDatas($data, 'users');

        foreach ($data['users'] as $val) {

            $data['mysql']['executearray'] = [$val['id']];
            $data['mysql']['table_name'] = 'user_metadata';
            $data['mysql']['selectingcolumns'] = '*';
            $data['mysql']['WHERE'] = 'WHERE userid = ?';
            $data['mysql']['clear'] = TRUE;

            Database::getDatas($data, 'usersdata');

            $data['mysql']['executearray'] = [$val['id']];
            $data['mysql']['table_name'] = 'user_group_roles_relationship';
            $data['mysql']['selectingcolumns'] = '*';
            $data['mysql']['WHERE'] = 'WHERE userid = ?';
            $data['mysql']['clear'] = TRUE;

            Database::getDatas($data, 'usergrouprolesdata');

            foreach ($data['usersdata'] as $val2) {

                if ($val2['metatype'] == 'jsondata') {

                    $json = json_decode($val2['metavalue']);

                    $usersdata[$val['id']][$val2['metakey']] = $json->value;
                }
            }

            $usergrouprolesdata = [];

            foreach ($data['usergrouprolesdata'] as $val3) {

                $usergrouprolesdata[] = $val3['groupid'];
            }

            $usersdata[$val['id']]['groups'] = $usergrouprolesdata;
        }
    }

    public static function globalData(array &$data, int $id, string $nametable, array &$metadata) {

        $data['mysql']['executearray'] = [$id];
        $data['mysql']['table_name'] = $nametable . '_metadata';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE objid = ?';
        $data['mysql']['clear'] = TRUE;

        Database::getDatas($data, 'desdata');

        foreach ($data['desdata'] as $val) {

            $metadata[$val['metakey']] = $val['metavalue'];
        }
    }

    public static function globalDataByColumnName(array &$data, int $id, string $nametable, string $nameid, array &$metadata) {

        $data['mysql']['executearray'] = [$id];
        $data['mysql']['table_name'] = $nametable . '_metadata';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE ' . $nameid . ' = ?';
        $data['mysql']['clear'] = TRUE;

        Database::getDatas($data, 'desdata');

        foreach ($data['desdata'] as $val) {

            $metadata[$val['metakey']] = $val['metavalue'];
        }
    }

}
