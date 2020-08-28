<?php

class ManagerMenu {

    public function __construct(&$data) {
        
    }

    public function setData(array &$data, int $pid = 0) {

        $data['mysql']['executearray'] = [$pid];
        $data['mysql']['table_name'] = 'menu_tab';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE parent_id = ? ORDER BY position ';
        $data['mysql']['clear'] = TRUE;

        foreach ($data['languages'] as $val) {

            $data['mysql']['selectingcolumns'] .= ' ,(SELECT languages_text.metavalue FROM languages_text WHERE metakey = "' . $val::getMetadata($data)['metakey'] . '" AND objid = menu_tab.id ) ' . $val::getMetadata($data)['metakey'];
        }

        Database::getDatas($data, 'datanav');

        if (!empty($data['datanav'])) {

            foreach ($data['datanav'] as $val) {

                $json = json_decode($val['data']);

                $data['menulevel'] = 0;

                $data['navval'] = $val;

                $data['navval']['url'] = $json->data->link;

                $data['navval']['widget'] = !empty($json->widget) ? $json->widget : 0;

                View::showView('views/php/template/website/public/' . $data['tempname']['metavalue'] . '/tpl.nav_re', $data);
            }
        }
    }

    public function setDatalevelZero(array &$data, int $pid = 0, string $name = '', string $url = '') {

        $data['mysql']['executearray'] = [$pid];
        $data['mysql']['table_name'] = 'menu_tab';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE parent_id = ? ORDER BY position ';
        $data['mysql']['clear'] = TRUE;

        foreach ($data['languages'] as $val) {

            $data['mysql']['selectingcolumns'] .= ' ,(SELECT languages_text.metavalue FROM languages_text WHERE metakey = "' . $val::getMetadata($data)['metakey'] . '" AND objid = menu_tab.id ) ' . $val::getMetadata($data)['metakey'];
        }

        Database::getDatas($data, 'datanav');

        if (!empty($data['datanav'])) {

            $data['menulevel'] ++;

            if ($data['menulevel'] == 1) {

                View::showView('views/php/template/website/public/' . $data['tempname']['metavalue'] . '/tpl.nav_re_menulevel_one', $data);
            } else {

                $data['navval']['hebrew_bluesun_framework_php'] = $name;
                $data['navval']['url'] = $url;

                View::showView('views/php/template/website/public/' . $data['tempname']['metavalue'] . '/tpl.nav_re_menulevel_plus', $data);
            }
        } else {

            $data['navval']['hebrew_bluesun_framework_php'] = $name;
            $data['navval']['url'] = $url;

            View::showView('views/php/template/website/public/' . $data['tempname']['metavalue'] . '/tpl.nav_re_li', $data);
        }
    }

}
