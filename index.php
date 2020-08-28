<?php

// Information and help on the system
// bluesun.house
// bluesun php framework mini

session_name("GOOGLE_AGENT");
session_start();
session_regenerate_id();

include 'engine/php/helper/helper.php';

spl_autoload_register(function($file) {

    if (is_file("engine/php/controller/cont.$file.php")) {

        require "engine/php/controller/cont.$file.php";
    } elseif (is_file("engine/php/database/pdo/model.$file.php")) {

        require "engine/php/database/pdo/model.$file.php";
    } elseif (is_file("engine/php/model/model.$file.php")) {

        require "engine/php/model/model.$file.php";
    } elseif (is_file("engine/php/view/view.$file.php")) {

        require "engine/php/view/view.$file.php";
    } elseif (is_file("engine/php/validation/vali.$file.php")) {

        require "engine/php/validation/vali.$file.php";
    } elseif (is_file("engine/php/language/cont.$file.php")) {

        require "engine/php/language/cont.$file.php";
    } elseif (is_file("controller/php/operation/cont.$file.php")) {

        require "controller/php/operation/cont.$file.php";
    } elseif (is_file("controller/php/content_types/cont.$file.php")) {

        require "controller/php/content_types/cont.$file.php";
    } elseif (is_file("controller/php/board/cont.$file.php")) {

        require "controller/php/board/cont.$file.php";
    } elseif (is_file("controller/php/other/cont.$file.php")) {

        require "controller/php/other/cont.$file.php";
    } elseif (is_file("controller/php/languages/cont.$file.php")) {

        require "controller/php/languages/cont.$file.php";
    } elseif (is_file("controller/php/admin/cont.$file.php")) {

        require "controller/php/admin/cont.$file.php";
    } elseif (is_file("vendor/$file.php")) {

        require "vendor/$file.php";
    } elseif (is_file("expansion/bluesun_framework_php_mailmanager/cont.$file.php")) {

        require "expansion/bluesun_framework_php_mailmanager/cont.$file.php";
    }
});

$app = new App();

?>