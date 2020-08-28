<?php

class ManagerStaticFile {

    private $arrayUrl;
    private $fullUrl;
    private $folder;

    public function __construct() {

        $this->folder = 'staticFileHtml';
        
        $full_url = isset($_GET['url']) ? $_GET['url'] : null;

        if (!preg_match("/^[\/\_\.\-\w\d\sא-ת]+$/", $full_url)) {

            $full_url = '';
        }

        $this->fullUrl = rtrim($full_url, '/');
        $this->arrayUrl = explode('/', $this->fullUrl);
    }

    public function loadPage() {

        if (empty($this->arrayUrl[0])) {

            include $this->folder . '/index.html';
        } else {

            if (is_file( $this->folder . '/' . $this->arrayUrl[0] . '.html')) {

                include $this->folder . '/' . $this->arrayUrl[0] . '.html';
            } else {
                echo 'err';
            }
        }
    }

}
