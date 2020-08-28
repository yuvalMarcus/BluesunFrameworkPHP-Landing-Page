<?php

class View {

    public static function showView(string $file, array &$data, bool $bool = true) {

        if ($bool) {

            foreach ($data as $key => $val) {

                $$key = $val;
            }
        }

        include "$file.php";
    }

    public static function showViewNoFilter(string $file, array &$data) {

        include "$file.php";
    }

    public static function showViewTypeFile(string $file, array &$data) {

        foreach ($data as $key => $val) {

            $$key = $val;
        }

        include "$file";
    }

    public static function showFile(array &$data, string $file) {

        include "$file.php";
    }

}
