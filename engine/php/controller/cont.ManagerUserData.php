<?php

class ManagerUserData {

    public static function updateUserData(array &$data, array $fields, array $httprequest) {

        $count = 0;
        $contentlastid = 0;

        $data['mysql']['executearray'] = [];
        $data['mysql']['table_name'] = 'user_infodata userinfo';
        $data['mysql']['selectingcolumns'] = 'userinfo.* ';
        $data['mysql']['clear'] = TRUE;

        \Database::getDatas($data, 'userinfodata');

        foreach ($data['userinfodata'] as $val) {

            $data['userdata'] = [];

            $json = json_decode($val['metavalue']);

            $data['mysql']['executearray'] = [$json->name, idLoginConnected()];
            $data['mysql']['table_name'] = 'user_metadata';
            $data['mysql']['selectingcolumns'] = ' * ';
            $data['mysql']['WHERE'] = ' WHERE metakey = ? AND userid = ? ';
            $data['mysql']['clear'] = TRUE;

            \Database::getData($data, 'userdata');

            if (!empty($data['userdata']) && in_array_array($json->name, $fields) && !$httprequest[$json->name]['err'] && $json->name != 'username' && $json->name != 'email') {

                $json2 = json_decode($data['userdata']['metavalue']);

                $json2->value = $httprequest[$json->name]['value'];

                $json2 = json_encode($json2);

                $data['mysql']['dataupdate'] = ['metavalue'];
                $data['mysql']['table_name'] = 'user_metadata';
                $data['mysql']['executearray'] = [$json2, $json->name, idLoginConnected()];
                $data['mysql']['WHERE'] = 'WHERE metakey = ? AND userid = ?';
                $data['mysql']['clear'] = TRUE;

                \Database::updateData($data, $count);
            } elseif (empty($data['userdata']) && in_array_array($json->name, $fields) && !empty($httprequest[$json->name]['value']) && !$httprequest[$json->name]['err'] && $json->name != 'username' && $json->name != 'email') {

                $data['mysql']['table_name'] = 'user_metadata';
                $data['mysql']['datainsert'] = [
                    'userid' => '?',
                    'metaname' => '?',
                    'metakey' => '?',
                    'metavalue' => '?',
                    'metatype' => '?',
                    'metamanager' => '?'];

                $data['mysql']['executearray'] = [idLoginConnected(), $json->name, $val['metakey'], '{"name":"' . $json->name . '","value":"' . $httprequest[$json->name]['value'] . '","typebox":"text","datavalidation":"*"}', 'jsondata', 'user-data'];

                \Database::insertData($data, $count, $contentlastid);
            }
        }
    }

    public static function updateUserPhoto(array &$data, string $photo) {

        $count = 0;
        
        $data['mysql']['executearray'] = ['photo', idLoginConnected()];
        $data['mysql']['table_name'] = 'user_metadata';
        $data['mysql']['selectingcolumns'] = ' * ';
        $data['mysql']['WHERE'] = ' WHERE metakey = ? AND userid = ? ';
        $data['mysql']['clear'] = TRUE;

        \Database::getData($data, 'userphotodata');

        $jsonphoto = '{"name":"photo","value":"' . $photo . '","typebox":"text","datavalidation":"*"}';

        if (!empty($data['userphotodata'])) {

            $data['mysql']['dataupdate'] = ['metavalue'];
            $data['mysql']['table_name'] = 'user_metadata';
            $data['mysql']['executearray'] = [$jsonphoto, 'photo', idLoginConnected()];
            $data['mysql']['WHERE'] = 'WHERE metakey = ? AND userid = ?';
            $data['mysql']['clear'] = TRUE;

            \Database::updateData($data, $count);
        } else {

            $data['mysql']['table_name'] = 'user_metadata';
            $data['mysql']['datainsert'] = [
                'userid' => '?',
                'metaname' => '?',
                'metakey' => '?',
                'metavalue' => '?',
                'metatype' => '?',
                'metamanager' => '?'];

            $data['mysql']['executearray'] = [idLoginConnected(), 'photo', 'photo', $jsonphoto, 'jsondata', 'user-data'];

            \Database::insertData($data, $count, $contentlastid);
        }
    }

    public static function getUserPhoto(array &$data, int $userid) {

        $data['mysql']['executearray'] = ['photo', $userid];
        $data['mysql']['table_name'] = 'user_metadata';
        $data['mysql']['selectingcolumns'] = ' * ';
        $data['mysql']['WHERE'] = ' WHERE metakey = ? AND userid = ? ';
        $data['mysql']['clear'] = TRUE;

        \Database::getData($data, 'userdata');

        if (!empty($data['userdata'])) {

            $obj = json_decode($data['userdata']['metavalue']);

            $data['userPhoto'] = $obj->value;
        } else {

            $data['userPhoto'] = '/img/userdefault.png';
        }
    }

}
