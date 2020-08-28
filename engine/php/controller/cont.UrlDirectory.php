<?php

class UrlDirectory {

    public static $domin = '';


    public static function get(): string {

        return '/';
    }

    public static function getDomin(): string {

        return self::$domin;
    }

    public static function getHref(): string {

        return '/';
    }

}
