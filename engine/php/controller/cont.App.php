<?php

class App {

    private $httprouter = 'controller/php/http.router';

    public function __construct() {

        $data['dirServer'] = substr($_SERVER['DOCUMENT_ROOT'], 0, -10);

        define('DIR_SERVER', $data['dirServer']);

        define('defineEmpty', '');

        // url bulging //

        $full_url = isset($_GET['url']) ? $_GET['url'] : null;

        if (!preg_match("/^[\/\_\.\-\w\d\sא-ת]+$/", $full_url)) {

            $full_url = '';
        }

        $url = rtrim($full_url, '/');
        $url = explode('/', $url);

        $data['url'] = $url;
        $data['url']['fullurl'] = $full_url;

        ///// ------- /////
        // Set Languages //

        \Language::$typeLang = 'english';

        $data['defaultLanguage'] = \Language::$typeLang;

        \Language::getLanguages($data);

        ///// ------- /////
        // sql data default bulging //
        ///// ------- /////
        // router data default //

        $data['router']['choice'] = FALSE;

        $data['router.cont']['forward'] = NULL;
        $data['router.cont']['backwards'] = NULL;

        $data['from']['validation'] = NULL;

        ///// ------- /////
        // web data default //
        //$data['ste'] = simplexml_load_file('../controller/admin/ste.xml');
        ///// ------- /////
        // Site Properties //

        $data['properties']['siteName'] = 'Website name';
        
        $data['properties']['emailSite'] = 'test@gmail.com';

        // temp data //

        $data['template'] = new stdClass();


        $data['template']->name = 'default';

        ///// ------- /////
        // contenttype data //
        ///// ------- /////
        // class default router data //

        $data['router']['classdefault'] = 'DefaultBoard';

        //$data['board'] = new DefaultBoard($data);
        ///// ------- /////
        // settings //

        $data['settings']['ajaxurl'] = 'ajax';

        $data['ajax-plugins'] = [
        ];

        $data['ash'] = Token::make($data, 'ash');


        ///// ------- /////
        // mangment url and page website //
        ///// ------- /////
        // mangment page html website //

        $data['pageBuild'] = new PageBuild($data);

        ///// ------- /////
        // mangment menu global //
        ///// ------- /////
        // xssClean global //

        $data['xssClean'] = new xssClean();

        ///// ------- /////
        // files views upload data //

        $filesdir = "views/php/viewfiles/";
        $filesdirfull = glob($filesdir . "tpl.*");

        $data['filesloadconent'] = [];

        foreach ($filesdirfull as $val) {
            $str = strstr($val, "tpl.");
            $data['filesloadconent'][$str]['conent'] = $val;
        }

        ///// ------- /////
        // hash password

        $data['passwordToHashFun'] = 'passwordHashFun';
        //$data['hashToPasswordFun'] = 'hashPasswordhFun';
        ///// ------- /////
        ///// ------- /////

        View::showFile($data, $this->httprouter);
    }

}
