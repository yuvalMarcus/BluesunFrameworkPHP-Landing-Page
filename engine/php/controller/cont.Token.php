<?php

class Token {

    public static function make(array &$data, string $name = 'token'): string {

        $token = sha1('token_mack' . 'foo to go' . rand(1, 10000) . '$$');

        $_SESSION[$name] = $token;

        return $token;
    }

}
