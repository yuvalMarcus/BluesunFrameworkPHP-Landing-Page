<?php

class Page {

    public static function index(array &$data) {
        
        $data['pageBuild']->build($data, 'views/php/template/website/public/default/tpl.index.php');

        $output = $data['pageBuild']->show($data);

        echo $output;
    }

    public static function thankYou(array &$data) {
        
        $data['pageBuild']->build($data, 'views/php/template/website/public/default/tpl.thank-you.php');

        $output = $data['pageBuild']->show($data);

        echo $output;
    }

}
