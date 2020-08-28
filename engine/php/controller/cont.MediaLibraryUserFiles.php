<?php

class MediaLibraryUserFiles {

    public static function showLibrary(array &$data) {

        $data['files'] = [];

        $dir = '../../public/php/upload/users/' . idLoginConnected() . '/files/original/';
        $dirurl = '../../upload/users/' . idLoginConnected() . '/files/original/';

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

        $_SESSION['mediaibraryUserDirFiles'] = '../../upload/users/' . idLoginConnected() . '/files/original/';

        \View::showView('views/php/mediaibraryuserfiles/show/tpl.index', $data);
    }

    public static function showLibraryScript(array &$data) {

        \View::showView('views/php/mediaibraryuserfiles/show/tpl.script', $data);
    }

}
