<?php

class ContentManager {

    public static function getContent(array &$data, string $namekeyfront = '', string $conenttype = 'zone', string $namemetadata = '', bool $byLanguage = false, $languageKey = '') {

        $data['byLanguage'] = $byLanguage;

        $data['languageKey'] = $languageKey;

        $data['contentmanager']['namekeyfront'] = $namekeyfront;

        $i = 0;
        foreach ($data['contenttypename'] as $valc) {

            if ($data[$namemetadata][$namekeyfront . 'contenttype'] == $valc) {

                $data['contenttypeclass'][$i]::getConentAdmin($data, $namemetadata, $namekeyfront, $conenttype);

                break;
            }

            $i++;
        }
    }

    public static function getContentFront(array &$data, string $namekeyfront = '', string $conenttype = 'zone', string $namemetadata = '', bool $byLanguage = false, $languageKey = '') {

        $data['byLanguage'] = $byLanguage;

        $data['languageKey'] = $languageKey;

        $i = 0;
        foreach ($data['contenttypename'] as $valc) {

            if ($data[$namemetadata][$namekeyfront . 'contenttype'] == $valc) {

                $data['contenttypeclass'][$i]::getConentFront($data, $namemetadata, $namekeyfront, $conenttype);

                break;
            }

            $i++;
        }
    }

    public static function getFilter(array &$data, string $namekeyfront, string $conenttype = 'zone', string $namemetadata = '') {

        $data['contentmanager']['namekeyfront'] = $namekeyfront;

        $data['filterdata'] = $data[$namemetadata][$namekeyfront . 'contenttype'];

        \View::showView('views/php/admin/getcontent/filter/tpl.index', $data);
    }

}
