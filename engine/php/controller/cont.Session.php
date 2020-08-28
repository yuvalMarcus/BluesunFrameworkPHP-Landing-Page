<?php

class Session {

    public static function make(string $name, string $val, string $tmp = 'suc') {

        $_SESSION[$name] = $val;
        $_SESSION[$name . '.tmp'] = $tmp;
    }

    public static function show(string $name, bool $delet = true) {

        if (isset($_SESSION[$name])) {

            $data['session'] = $_SESSION[$name];

            View::showView('views/php/session/tpl.show.' . $_SESSION[$name . '.tmp'], $data);

            if ($delet) {

                unset($_SESSION[$name]);
                unset($_SESSION[$name . 'tmp']);
            }
        }
    }

    public static function showText(string $name, string $del = '', $delet = true): string {

        $text = '';

        if (isset($_SESSION[$name])) {

            $text = $_SESSION[$name];

            if ($delet) {

                unset($_SESSION[$name]);
                unset($_SESSION[$name . 'tmp']);
            }
        } else {

            $text = $del;
        }

        return $text;
    }

    public static function showArray(string $name, array $del = [], $delet = true): array {

        $array = [];

        if (isset($_SESSION[$name])) {

            if (!empty($_SESSION[$name]))
                $array = $_SESSION[$name];
            else
                $array = [];

            if ($delet) {

                unset($_SESSION[$name]);
                unset($_SESSION[$name . 'tmp']);
            }
        } else {

            $array = $del;
        }

        return $array;
    }

}
