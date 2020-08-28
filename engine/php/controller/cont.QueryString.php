<?php

class QueryString {

    public static function get(array &$data, string $name = '', string $type = 'request'): string {

        switch ($type) {
            case 'get':
                return !empty($_GET[$name]) ? $_GET[$name] : '';
                break;
            case 'post':
                return !empty($_POST[$name]) ? $_POST[$name] : '';
                break;
            case 'request':
                return !empty($_REQUEST[$name]) ? $_REQUEST[$name] : '';
                break;
            default:
                return !empty($_REQUEST[$name]) ? $_REQUEST[$name] : '';
                break;
        }
    }
}
