<?php

class Post {

    public static function get(array $data, string $name, $def = false): string {

        if (isset($_POST[$name])) {

            return $data['xssClean']->clean_input($_POST[$name]);
        } else {

            return $def;
        }
    }

    public static function make(array $data, string $name, string $val) {

        $_POST[$name] = $data['xssClean']->clean_input($val);
    }

}
