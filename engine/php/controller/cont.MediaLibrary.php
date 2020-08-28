<?php

class MediaLibrary {

    public static function showLibrary(array &$data) {

        $data['files'] = [];

        $dir = '../../public/php/upload/files/original/';
        $dirurl = '/upload/files/original/';

// Open a directory, and read its contents
        if (is_dir($dir)) {

            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    $data['files'][] = $dirurl . $file;
                }
                closedir($dh);
            }
        }

        $_SESSION['mediaibraryDirFiles'] = '/upload/files/original/';

        \View::showView('views/php/mediaibrary/show/tpl.index', $data);
    }

}
