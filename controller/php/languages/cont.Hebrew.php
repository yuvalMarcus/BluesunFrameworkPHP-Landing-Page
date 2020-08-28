<?php

class Hebrew {

    public static $textArr = [
    ];

    public static function getIcon(&$data) {

        return '';
    }

    public static function getTextLang(array &$data, string $text) {

        $text = !empty(self::$textArr[$text]) ? self::$textArr[$text] : $text;

        return $text;
    }

    public static function getMetadata(array &$data) {

        return [
            'metaname' => 'hebrew',
            'metakey' => 'hebrew_default'
        ];
    }

}
