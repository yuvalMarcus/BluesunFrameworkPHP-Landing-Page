<?php

class Notification {

    public static function add(array &$data, string $val, int $userid, string $type = 'green', string $datan = '') {

        $lastid = 0;
        $count = 0;
        
        $data['mysql']['table_name'] = 'website_notifications';
        $data['mysql']['datainsert'] = [
            'type' => '?',
            'objid' => '?',
            'userid' => '?',
            'message' => '?',
            'data' => '?',];

        $data['mysql']['executearray'] = [$type, 0, $userid, $val, $datan];

        \Database::insertData($data, $count, $lastid);
    }

    public static function show(array &$data, array $val) {

        $data['content'] = $val['message'];
        $data['id'] = $val['id'];

        \View::showView('views/php/template/website/public/' . $data['tempname']['metavalue'] . '/tpl.notification.' . $val['type'], $data);
    }

    public static function delete(array &$data, int $id) {

        $count = 0;
        
        $data['mysql']['table_name'] = 'website_notifications';
        $data['mysql']['executearray'] = [idLoginConnected(), $id];
        $data['mysql']['WHERE'] = 'WHERE userid = ? AND id = ? ';
        $data['mysql']['clear'] = TRUE;

        \Database::deleteData($data, $count);
    }

}
