<?php

function getTextLanguage(&$data, $val, $text = 'name') {

    return !empty($val[$text . '_' . $data['defaultLanguage'] . '_bluesun_framework_php']) ? $val[$text . '_' . $data['defaultLanguage'] . '_bluesun_framework_php'] : $val[$text . '_english_bluesun_framework_php'];
}

function getDataGroup(&$data, $id) {

    $data['mysql']['executearray'] = [$id];
    $data['mysql']['table_name'] = 'usergroup';
    $data['mysql']['selectingcolumns'] = '*';
    $data['mysql']['WHERE'] = 'WHERE id = ?';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'dedata');

    return $data['dedata'];
}

function getPhoto(&$data, $id, $metakey = 'photos') {

    $data['mysql']['executearray'] = [$id, $metakey];
    $data['mysql']['table_name'] = 'website_content_items_metadata';
    $data['mysql']['selectingcolumns'] = '*';
    $data['mysql']['WHERE'] = 'WHERE objid = ? AND metakey = ?';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'photosdata');

    if (!empty($data['photosdata'])) {

        if (!empty(json_decode($data['photosdata']['metavalue'])[0]->url)) {
            return json_decode($data['photosdata']['metavalue'])[0]->url;
        } elseif (!empty($data['photosdata']['metavalue'])) {
            return $data['photosdata']['metavalue'];
        } else {
            return getDefaultPhoto($data);
        }

        return json_decode($data['photosdata']['metavalue'])[0]->url;
    } else {

        return getDefaultPhoto($data);
    }
}

function getDefaultPhoto(&$data) {

    return '/upload/files/original/defaultphoto.png';
}

function getIdGroupRegister(&$data) {

    return 2;
}

function loginUser(&$data, $userid, $username) {

    $login = TRUE;
    $user_id = $userid;
    $user_name = $username;
    $user_groups = [];

    $data['mysql']['executearray'] = [$userid];
    $data['mysql']['table_name'] = 'user_group_roles_relationship';
    $data['mysql']['selectingcolumns'] = 'groupid';
    $data['mysql']['WHERE'] = 'WHERE userid = ?';
    $data['mysql']['clear'] = TRUE;

    \Database::getDatas($data, 'usergroups');

    foreach ($data['usergroups'] as $val) {

        $user_groups[] = $val['groupid'];
    }

    Session::make('is_login', $login);
    Session::make('user_id', $user_id);
    Session::make('user_name', $user_name);

    foreach ($user_groups as $group) {

        Permission::getPermissions($data, $group, 'roles');

        foreach ($data['roles'] as $role) {

            $json = json_decode($role['data']);

            if (empty($_SESSION[$role['name']])) {

                Session::make($role['name'], $json->value);
            }
        }
    }

    Session::make('user_agent', $_SERVER['HTTP_USER_AGENT']);
    Session::make('user_ip', $_SERVER['REMOTE_ADDR']);
}

function registerUser(&$data, $username, $password, $email, $uservalidity = -1) {

    $count = 0;
    $countsum = 0;
    $iduser = 0;

    $data['mysql']['table_name'] = 'user';
    $data['mysql']['datainsert'] = [
        'username' => '?',
        'password' => '?',
        'email' => '?',
    ];

    $data['mysql']['executearray'] = [$username, passwordHashFun($data, $password), $email];

    Database::insertData($data, $count, $iduser);

    if ($iduser) {

        $lastid = 0;

        $data['mysql']['table_name'] = 'user_group_roles_relationship';
        $data['mysql']['datainsert'] = [
            'userid' => '?',
            'groupid' => '?',
            'created_re' => '?',
            'update_re' => '?',
        ];

        $data['mysql']['executearray'] = [$iduser, getIdGroupRegister($data), time(), time()];

        Database::insertData($data, $count, $lastid);

        $countsum = $countsum + $count;

        $data['mysql']['table_name'] = 'user_metadata';
        $data['mysql']['datainsert'] = [
            'userid' => '?',
            'metaname' => '?',
            'metakey' => '?',
            'metavalue' => '?',
            'metatype' => '?',
            'metamanager' => '?',
        ];

        $data['mysql']['executearray'] = [$iduser, 'lastconnectiontime', 'lastconnectiontime', time(), 'timenumber', 'user-data'];

        Database::insertData($data, $count, $lastid);

        $countsum = $countsum + $count;

        $data['mysql']['table_name'] = 'user_metadata';
        $data['mysql']['datainsert'] = [
            'userid' => '?',
            'metaname' => '?',
            'metakey' => '?',
            'metavalue' => '?',
            'metatype' => '?',
            'metamanager' => '?',
        ];

        $data['mysql']['executearray'] = [$iduser, 'timecreated', 'timecreated', time(), 'timenumber', 'user-data'];

        Database::insertData($data, $count, $lastid);

        $countsum = $countsum + $count;

        $data['mysql']['table_name'] = 'user_metadata';
        $data['mysql']['datainsert'] = [
            'userid' => '?',
            'metaname' => '?',
            'metakey' => '?',
            'metavalue' => '?',
            'metatype' => '?',
            'metamanager' => '?',
        ];

        $data['mysql']['executearray'] = [$iduser, 'uservalidity', 'uservalidity', $uservalidity, 'timenumber', 'user-data'];

        Database::insertData($data, $count, $lastid);

        $countsum = $countsum + $count;
    }

    Session::make('result', 'User created successfully');

    $data['mail']['namesend'] = $username;
    $data['mail']['title'] = 'Welcome, Thank you for registering';
    $data['mail']['body'] = '<h3>Welcome, Thank you for registering</h3>Your login information<hr><h3>Username : </h3>' . $username . '<hr><h3>Password : </h3>' . $password . '<hr><h3>Email : </h3>' . $email . '<hr>Link Website : <a href="https://www.thinkcyber.online/">www.thinkcyber.online</a><hr>For any problems you can contact us on the site';
    $data['mail']['sendemail'] = $email;

    BlueSunFrameworkPHPMiniMailManager::mailSend($data);

    return $iduser;
}

function passwordHashFun(&$data, $string) {

    return sha1(sha1($string . 'bluesun_framework_php_3dvdn3ds7v33kf_2d-vddn83' . $string));
}

function getSlugUserCp(&$data) {

    $data['mysql']['executearray'] = ['usercp'];
    $data['mysql']['table_name'] = 'zone_page';
    $data['mysql']['selectingcolumns'] = '*';
    $data['mysql']['WHERE'] = 'WHERE type = ? AND stringcode = "bluesun_framework_php-system-pages-manager-protected"';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'dataslug');

    return $data['dataslug']['slug'];
}

function getRandomPassword(&$data, $length) {

    $str = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890';

    $pass = '';

    $count = strlen($str) - 1;

    for ($i = 0; $i < $length; $i++) {

        $rand = rand(0, $count);

        $pass .= $str[$rand];
    }

    return $pass;
}

function getSlugLogin(&$data) {

    $data['mysql']['executearray'] = ['login'];
    $data['mysql']['table_name'] = 'zone_page';
    $data['mysql']['selectingcolumns'] = '*';
    $data['mysql']['WHERE'] = 'WHERE type = ? AND stringcode = "bluesun_framework_php-system-pages-manager-protected"';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'dataslug');

    return $data['dataslug']['slug'];
}

function getSlugRegister(&$data) {

    $data['mysql']['executearray'] = ['register'];
    $data['mysql']['table_name'] = 'zone_page';
    $data['mysql']['selectingcolumns'] = '*';
    $data['mysql']['WHERE'] = 'WHERE type = ? AND stringcode = "bluesun_framework_php-system-pages-manager-protected"';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'dataslug');

    return $data['dataslug']['slug'];
}

function getSlugLogout(&$data) {

    $data['mysql']['executearray'] = ['logout'];
    $data['mysql']['table_name'] = 'zone_page';
    $data['mysql']['selectingcolumns'] = '*';
    $data['mysql']['WHERE'] = 'WHERE type = ? AND stringcode = "bluesun_framework_php-system-pages-manager-protected"';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'dataslug');

    return $data['dataslug']['slug'];
}

function getSlugLostPassword(&$data) {

    $data['mysql']['executearray'] = ['lost-password'];
    $data['mysql']['table_name'] = 'zone_page';
    $data['mysql']['selectingcolumns'] = '*';
    $data['mysql']['WHERE'] = 'WHERE type = ? AND stringcode = "bluesun_framework_php-system-pages-manager-protected"';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'dataslug');

    return $data['dataslug']['slug'];
}

function getSlugNewPassword(&$data) {

    $data['mysql']['executearray'] = ['new-password'];
    $data['mysql']['table_name'] = 'zone_page';
    $data['mysql']['selectingcolumns'] = '*';
    $data['mysql']['WHERE'] = 'WHERE type = ? AND stringcode = "bluesun_framework_php-system-pages-manager-protected"';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'dataslug');

    return $data['dataslug']['slug'];
}

function GetSlugOfZonesWeb(&$data, $string) {

    switch ($string) {
        case "usercp";
            return getSlugUserCp($data);
            break;
        case "login";
            return getSlugLogin($data);
            break;
        case "register";
            return getSlugRegister($data);
            break;
        case "logout";
            return getSlugLogout($data);
            break;
        case "lostpassword";
            return getSlugLostPassword($data);
            break;
        case "newpassword";
            return getSlugNewPassword($data);
            break;
        default;
            return "";
            break;
    }
}

function getContentErrorPage(&$data, $string) {

    switch ($string) {
        case "404";
            return getContentErrorPage404($data);
            break;
        default;
            return "";
            break;
    }
}

function getContentErrorPage404(&$data) {

    $data['mysql']['executearray'] = ['error-404', 'bluesun_framework_php-system-pages-error'];
    $data['mysql']['table_name'] = 'website_content_items';
    $data['mysql']['selectingcolumns'] = 'id';
    $data['mysql']['WHERE'] = 'WHERE type = ? AND stringcodeplugin = ?';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'dedata');

    $data['metadata'] = [];

    \GetData::metaContent($data, $data['dedata']['id'], $data['metadata']);

    $data['zonestructuredata'] = json_decode($data['metadata']['structuredata']);

    \ContentManager::getContentFront($data, '', 'manager_conent', 'metadata');
}

function cleanString($text) {
    // 1) convert á ô => a o
    $text = preg_replace("/[áàâãªä]/u", "a", $text);
    $text = preg_replace("/[ÁÀÂÃÄ]/u", "A", $text);
    $text = preg_replace("/[ÍÌÎÏ]/u", "I", $text);
    $text = preg_replace("/[íìîï]/u", "i", $text);
    $text = preg_replace("/[éèêë]/u", "e", $text);
    $text = preg_replace("/[ÉÈÊË]/u", "E", $text);
    $text = preg_replace("/[óòôõºö]/u", "o", $text);
    $text = preg_replace("/[ÓÒÔÕÖ]/u", "O", $text);
    $text = preg_replace("/[úùûü]/u", "u", $text);
    $text = preg_replace("/[ÚÙÛÜ]/u", "U", $text);
    $text = preg_replace("/[’‘‹›‚]/u", "'", $text);
    $text = preg_replace("/[“”«»„]/u", '"', $text);
    $text = str_replace("–", "-", $text);
    $text = str_replace(" ", " ", $text);
    $text = str_replace("ç", "c", $text);
    $text = str_replace("Ç", "C", $text);
    $text = str_replace("ñ", "n", $text);
    $text = str_replace("Ñ", "N", $text);

    //2) Translation CP1252. &ndash; => -
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark 
    $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook 
    $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark 
    $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis 
    $trans[chr(134)] = '&dagger;';    // Dagger 
    $trans[chr(135)] = '&Dagger;';    // Double Dagger 
    $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent 
    $trans[chr(137)] = '&permil;';    // Per Mille Sign 
    $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron 
    $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark 
    $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE 
    $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark 
    $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark 
    $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark 
    $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark 
    $trans[chr(149)] = '&bull;';    // Bullet 
    $trans[chr(150)] = '&ndash;';    // En Dash 
    $trans[chr(151)] = '&mdash;';    // Em Dash 
    $trans[chr(152)] = '&tilde;';    // Small Tilde 
    $trans[chr(153)] = '&trade;';    // Trade Mark Sign 
    $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron 
    $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark 
    $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE 
    $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis 
    $trans['euro'] = '&euro;';    // euro currency symbol 
    ksort($trans);

    foreach ($trans as $k => $v) {
        $text = str_replace($v, $k, $text);
    }

    // 3) remove <p>, <br/> ...
    $text = strip_tags($text);

    // 4) &amp; => & &quot; => '
    $text = html_entity_decode($text);

    // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
    $text = preg_replace('/[^(\x20-\x7F)]*/', '', $text);

    $targets = array('\r\n', '\n', '\r', '\t');
    $results = array(" ", " ", " ", "");
    $text = str_replace($targets, $results, $text);

    //XML compatible
    /*
      $text = str_replace("&", "and", $text);
      $text = str_replace("<", ".", $text);
      $text = str_replace(">", ".", $text);
      $text = str_replace("\\", "-", $text);
      $text = str_replace("/", "-", $text);
     */

    return ($text);
}

function hyphenize($string) {
    $dict = array(
        "I'm" => "I am",
        "thier" => "their",
    );
    return strtolower(
            preg_replace(
                    array('#[\\s-]+#', '#[^A-Za-z0-9\. -]+#'), array('-', ''),
                    // the full cleanString() can be download from http://www.unexpectedit.com/php/php-clean-string-of-utf8-chars-convert-to-similar-ascii-char
                    cleanString(
                            str_replace(// preg_replace to support more complicated replacements
                                    array_keys($dict), array_values($dict), urldecode($string)
                            )
                    )
            )
    );
}

function getSlug($string, $re = 0, $slugstring = '') {

    if ($re) {

        $slugstring = $string . '-' . $re;
    } else {

        $string = str_replace("/", "-", str_replace(" ", "-", strtolower($string)));

        $slugstring = $string;
    }

    $re++;

    $data['mysql']['executearray'] = [$slugstring];
    $data['mysql']['table_name'] = 'zone_page';
    $data['mysql']['selectingcolumns'] = 'id';
    $data['mysql']['WHERE'] = 'WHERE slug = ?';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'slugdata');

    if (!empty($data['slugdata'])) {

        getSlug($string, $re, $slugstring);
    } else {

        return $slugstring;
    }
}

function getSlugForUrl(&$data, $string, $stringcodeplugin, $type, $orginal = '', $pos = 0) {

    if (!empty($orginal) && $string == $orginal) {
        return $string;
    }

    if ($pos == 0) {
        $string = hyphenize($string);
    }

    $data['slug'] = [];

    $data['mysql']['executearray'] = [$string, $stringcodeplugin, $type];
    $data['mysql']['table_name'] = 'website_content_items';
    $data['mysql']['selectingcolumns'] = 'id';
    $data['mysql']['WHERE'] = 'WHERE slug = ? AND stringcodeplugin = ? AND type = ?';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'slug');

    if (empty($data['slug'])) {

        $data['slug'] = [];

        return $string;
    }

    $pos++;

    $string = $string . '-' . $pos;

    return getSlugForUrl($data, $string, $stringcodeplugin, $type, $orginal, $pos);
}

function getSlugForUrlEdite(&$data, $string, $stringcodeplugin, $type, $url, $re = 0) {

    /*

      $arrChar = ['q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m','1','2','3','4','5','6','7','8','9','0','-'];

      $string = str_replace("/","-",str_replace(" ","-",strtolower($string)));

      for($i = 0; $i < strlen($string); $i++) {

      if(!in_array($string[$i],$arrChar))
      $string = str_replace($string[$i],"",$string);

      }

     */

    $string = hyphenize($string);

    $string = ($re != 0) ? $string . '-' . $re : $string;

    $data['mysql']['executearray'] = [$string, $stringcodeplugin, $type];
    $data['mysql']['table_name'] = 'website_content_items';
    $data['mysql']['selectingcolumns'] = 'id';
    $data['mysql']['WHERE'] = 'WHERE slug = ? AND stringcodeplugin = ? AND type = ?';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'getSlugForUrlEdite');

    if (!empty($data['getSlugForUrlEdite']) && $url != $string) {

        $re++;

        getSlugForUrlEdite($data, $string, $stringcodeplugin, $type, $url, $re);
    } else {

        return $string;
    }
}

function get_string_back($str, $o) {

    $sum = strlen($str);

    $string = '';

    for ($i = 0; $i < $sum; $i++) {

        if ($str[$i] == $o) {

            return $string;
        }

        $string .= $str[$i];
    }

    return $string;
}

function add_words(&$str, $words) {

    $str .= ' ' . $words;
}

function string_limit_words($string, $word_limit) {

    $words = explode(' ', $string);

    $str = $words[0];

    for ($i = 1; $i < count($words); $i++) {

        if ($i < $word_limit) {

            add_words($str, $words[$i]);
        } else {

            break;
        }
    }


    return $str;
}

function get_info_from_request($name) {

    if (isset($_POST[$name])) {

        $data = $_POST[$name];
    } elseif (isset($_GET[$name])) {

        $data = $_GET[$name];
    } else {

        $data = NULL;
    }

    return $data;
}

function getMethod($str) {

    $method = '';

    $names = explode('-', $str);

    for ($i = 0; $i < count($names); $i++) {

        if ($i != 0) {

            $name = str_split($names[$i]);

            for ($g = 0; $g < count($name); $g++) {

                if ($g != 0) {

                    $method .= $name[$g];
                } else {

                    $method .= strtoupper($name[$g]);
                }
            }
        } else {

            $method .= $names[$i];
        }
    }

    return $method;
}

function buildingMethodOneWord($str, &$method, $forward = NULL, $backwards = NULL) {

    $method = $forward;

    $name = str_split($str);

    for ($i = 0; $i < count($name); $i++) {

        if ($i != 0 or $forward == NULL) {

            $method .= $name[$i];
        } else {

            $method .= strtoupper($name[$i]);
        }
    }

    $method .= $backwards;
}

function getClass(string $str, string $forward = '', string $backwards = ''): string {

    $class = $forward;

    $names = explode('-', $str);

    for ($i = 0; $i < count($names); $i++) {

        $name = str_split($names[$i]);

        for ($g = 0; $g < count($name); $g++) {

            if ($g != 0) {

                $class .= $name[$g];
            } else {

                $class .= strtoupper($name[$g]);
            }
        }
    }

    $class .= $backwards;

    return $class;
}

function order_array($arr, $pid) {

    $arr_new = [];

    foreach ($arr as $key => $val) {

        if ($val['parent_id'] == $pid) {

            $arr_new[$key] = $val;
        }
    }

    return $arr_new;
}

function get_amount_of_character($str, $char, &$amount) {

    $sum = strlen($str);
    $amount = 0;

    for ($i = 0; $i < $sum; $i++) {

        if ($str[$i] == $char) {

            $amount++;
        }
    }
}

function pdo_str_replace_note($str, $char, $newnote, &$newstr) {

    $sum = strlen($str);

    $newstr = '';

    $numnote = 0;

    for ($i = 0; $i < $sum; $i++) {

        if ($str[$i] == $char) {

            $newstr .= PDOConnectDB::connectToDatabase()->quote($newnote[$numnote]);

            $numnote++;
        } else {

            $newstr .= $str[$i];
        }
    }
}

function getDataItem($str) {

    $ids = explode('-', $str);

    return $ids;
}

function slugA(&$data, $slug) {

    $ret = TRUE;

    foreach ($data['dataslug'] as $val) {

        $num = count(explode('/', $val)) - 1;

        $url = '';
        $count = 0;
        foreach ($data['url'] as $valurl) {

            if ($count <= $num) {

                $url .= '/' . $valurl;
            }

            $count++;
        }

        $url = trim($url, "/");


        if ($url == $val && $url != $slug && $val == $slug) {

            $ret = FALSE;
        }
    }

    return $ret;
}

function IfExistsInArray($str, $array, $field = 'id') {

    $co = FALSE;

    foreach ($array as $val) {

        if ($val[$field] == $str) {

            $co = TRUE;
        }
    }

    return $co;
}

function tokenMake(&$data) {

    $token = sha1('token_mack' . 'foo to go' . rand(1, 10000) . '$$');

    $_SESSION['token'] = $token;

    return $token;
}

///

function isLogin() {

    return !empty($_SESSION['is_login']) && $_SESSION['is_login'] == TRUE ? TRUE : FALSE;
}

function nameLoginConnected() {

    return !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : FALSE;
}

function idLoginConnected() {

    return !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : FALSE;
}

function nameUserById(&$data, $id = 0) {

    $data['datausername']['username'] = "לא נמצא משתמש";

    $data['mysql']['executearray'] = [$id];
    $data['mysql']['table_name'] = 'user';
    $data['mysql']['selectingcolumns'] = 'user.username';
    $data['mysql']['WHERE'] = 'WHERE id = ?';
    $data['mysql']['clear'] = TRUE;

    \Database::getData($data, 'datausername');

    return $data['datausername']['username'];
}

function xssclean($string, $safe_level = 0) {

    $xssClean = new xssClean();

    $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

    $someSafeArray = array(
        "thefield" => FILTER_SANITIZE_STRING,
    );
    foreach ($someSafeArray as $fld => &$val)
        $string = filter_var(trim($string), $val);

    $string = $xssClean->clean_input($string, $safe_level);

    return $string;
}

function in_array_array($value, $arr) {

    foreach ($arr as $val) {

        if ($val['name'] == $value) {

            return TRUE;
        }
    }

    return FALSE;
}

function checkHttpRequestIsOk(&$data, $arr) {

    $bool = FALSE;
    $i = 0;
    $count = count($arr);

    while (!$bool && $i < $count) {

        $bool = $data['httpRequest'][$arr[$i]['name']]['err'];

        $i++;
    }

    return $bool;
}

function get_raw_facebook_avatar_url($uid) {
    $array = get_headers('https://graph.facebook.com/' . $uid . '/picture?type=large', 1);
    return (isset($array['Location']) ? $array['Location'] : FALSE);
}

function getArrayOfCountry() {

    $country_array = ["Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe"];

    return $country_array;
}

function getNumberOfFeatured(&$data) {

    $data['mysql']['executearray'] = [idLoginConnected(), 'like'];
    $data['mysql']['table_name'] = 'website_content_items_metadata metadata';
    $data['mysql']['selectingcolumns'] = 'item.*';
    $data['mysql']['JOIN'] = ' JOIN website_content_items item ON item.id = metadata.objid';
    $data['mysql']['WHERE'] = 'WHERE metadata.metavalue = ? AND metadata.metakey = ? ';
    $data['mysql']['clear'] = TRUE;

    Database::getDatas($data, 'featureditems');
}

function isAdminManager(&$data) {
    return !empty($_SESSION['admin-manager']) && $_SESSION['admin-manager'] ? true : false;
}

function getUserByID(&$data, $userid) {

    $data['mysql']['executearray'] = [$userid];
    $data['mysql']['table_name'] = 'user';
    $data['mysql']['selectingcolumns'] = '*, 
   (SELECT user_metadata.metavalue  FROM user_metadata WHERE userid = user.id AND metakey = "firstname") firstname,
   (SELECT user_metadata.metavalue  FROM user_metadata WHERE userid = user.id AND metakey = "lastname") lastname,
   (SELECT user_metadata.metavalue  FROM user_metadata WHERE userid = user.id AND metakey = "telephone") telephone,
   (SELECT user_metadata.metavalue  FROM user_metadata WHERE userid = user.id AND metakey = "address") address,
   (SELECT user_metadata.metavalue  FROM user_metadata WHERE userid = user.id AND metakey = "city") city,
   (SELECT user_metadata.metavalue  FROM user_metadata WHERE userid = user.id AND metakey = "zipcode") zipcode';
    $data['mysql']['WHERE'] = 'WHERE id = ?';
    $data['mysql']['clear'] = TRUE;

    Database::getData($data, 'userdata');

    return $data['userdata'];
}

function inGetArrayKeyCustomField($data, $key) {

    $bool = false;

    if (!empty($_GET['customfield'])) {

        $customfield = explode("<>", $_GET['customfield']);

        foreach ($customfield as $val) {

            $obj = json_decode($val);

            if (!is_null($obj)) {

                if ($obj->name == $key) {
                    $bool = true;
                }
            } else {

                $bool = true;
            }
        }
    } else {

        $bool = true;
    }
    return $bool;
}

function inGetArrayValueCustomField($data, $key, $valueE) {

    $bool = false;

    if (!empty($_GET['customfield'])) {

        $customfield = explode("<>", $_GET['customfield']);

        foreach ($customfield as $val) {

            $obj = json_decode($val);

            if (!is_null($obj)) {

                if ($obj->name == $key) {
                    foreach ($obj->values as $value) {

                        if ($obj->name == $key && $value == $valueE) {
                            $bool = true;
                        }
                    }
                }
            } else {

                $bool = true;
            }
        }
    } else {

        $bool = true;
    }
    return $bool;
}

function opneWidget(&$data, $id) {

    GetData::globalData($data, $id, 'widgets', $data['metadata']);

    $data['dedata']['id'] = $id;

    ContentManager::getContentFront($data, '', 'widget', 'metadata');
}

function getStringBetween($string, $start, $end) {

    $string = ' ' . $string;

    $ini = strpos($string, $start);

    if ($ini == 0)
        return '';

    $ini += strlen($start);

    $len = strpos($string, $end, $ini) - $ini;

    return substr($string, $ini, $len);
}

function getLanguageById(&$data, $id = 0) {

    $data['mysql']['executearray'] = [$id];
    $data['mysql']['table_name'] = 'zone_page';
    $data['mysql']['selectingcolumns'] = '*';
    $data['mysql']['WHERE'] = 'WHERE id = ? ';
    $data['mysql']['clear'] = TRUE;

    Database::getData($data, 'zonedata');

    return json_decode($data['zonedata']['data'])->byLanguage;
}

function checkIfUserIsLoggedIn(array &$data) {

    if (!empty(isLogin())) {

        $data['userdata'] = [];

        $data['mysql']['executearray'] = [idLoginConnected()];
        $data['mysql']['table_name'] = 'user user';
        $data['mysql']['selectingcolumns'] = '* ,
                (SELECT user_metadata.metavalue  FROM user_metadata WHERE userid = user.id AND metakey = "timecreated") timecreated,
                (SELECT user_metadata.metavalue  FROM user_metadata WHERE userid = user.id AND metakey = "uservalidity") uservalidity,
                (SELECT user_metadata.metavalue  FROM user_metadata WHERE userid = user.id AND metakey = "lastconnectiontime") lastconnectiontime';
        $data['mysql']['WHERE'] = 'WHERE id = ?';
        $data['mysql']['clear'] = TRUE;

        \Database::getData($data, 'userdata');

        if (!empty($data['userdata'])) {

            $lastconnectiontime = (int) $data['userdata']['lastconnectiontime'];

            if ($lastconnectiontime + 300 > time()) {

                return true;
            }
        }
    }

    return false;
}
