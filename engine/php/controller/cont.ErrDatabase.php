<?php

class ErrDatabase {

    public static function showPage(array &$data) {

        \View::showView('views/php/admin/errdatabase/' . $data['url'][5] . '/tpl.index', $data);
    }

}
