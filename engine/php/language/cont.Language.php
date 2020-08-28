<?php

class Language {

    public static $typeLang = 'hebrew';
    public static $typeLangKey = 'bluesun_framework_php_hebrew';

    public static function getTextLang(array &$data, string $text) {

        $method = strtoupper(self::$typeLang[0]) . substr(self::$typeLang, 1);

        return $method::getTextLang($data, $text);
    }

    public static function getLanguages(array &$data) {

        $data['languages'] = [];

        foreach (glob("../../controller/php/languages/" . "cont.*") as $val) {

            $data['languages'][] = substr($val, 36, -4);
        }
    }

    public static function setLanguages(array &$data, int $objid = 0, string $stringcode = 'none', string $metatype = 'none', $values = [], string $addToMetakey = '', string $typeData = 'array', int &$count) {

        $countSum = $count;
        $lastid = 0;

        foreach ($data['languages'] as $val) {

            if (empty($addToMetakey) || $addToMetakey == '') {
                $metakeyToValue = $val::getMetadata($data)['metakey'];
            } else {
                $metakeyToValue = $addToMetakey . '_' . $val::getMetadata($data)['metakey'];
            }

            switch ($typeData) {
                case 'object':
                    $value = $values->$metakeyToValue;
                    break;
                case 'array':
                    $value = (is_object($values[$metakeyToValue])) ? $values[$metakeyToValue]->value : $values[$metakeyToValue];
                    break;
            }

            $metakey = $metakeyToValue;

            $data['language_' . $addToMetakey . $val::getMetadata($data)['metakey']] = [];

            $data['mysql']['executearray'] = [$stringcode, $metatype, $objid];
            $data['mysql']['table_name'] = 'languages_text';
            $data['mysql']['selectingcolumns'] = '*';
            $data['mysql']['WHERE'] = 'WHERE stringcode = ? AND metakey = "' . $metakey . '" AND metatype = ? AND objid = ?';
            $data['mysql']['clear'] = TRUE;

            \Database::getData($data, 'language_' . $addToMetakey . $val::getMetadata($data)['metakey']);

            if (!empty($data['language_' . $addToMetakey . $val::getMetadata($data)['metakey']])) {

                $data['mysql']['dataupdate'] = ['metavalue'];
                $data['mysql']['table_name'] = 'languages_text';
                $data['mysql']['executearray'] = [$value, $stringcode, $metatype, $objid];
                $data['mysql']['WHERE'] = 'WHERE stringcode = ? AND metakey = "' . $metakey . '" AND metatype = ? AND objid = ?';
                $data['mysql']['clear'] = TRUE;

                \Database::updateData($data, $count);

                $countSum = $countSum + $count;
            } else {

                $data['mysql']['table_name'] = 'languages_text';
                $data['mysql']['datainsert'] = [
                    'objid' => '?',
                    'stringcode' => '?',
                    'metakey' => '?',
                    'metavalue' => '?',
                    'metaname' => '?',
                    'metatype' => '?',];

                $data['mysql']['executearray'] = [$objid, $stringcode, $metakey, $value, $val::getMetadata($data)['metaname'], $metatype];

                \Database::insertData($data, $count, $lastid);

                $countSum = $countSum + $count;
            }
        }

        $count = $countSum;
    }

    public static function getLanguageByKey(array &$data, $key = '') {

        foreach ($data['languages'] as $language) {

            if ($language::getMetadata($data)['metakey'] == $key)
                return $language;
        }

        return null;
    }

}
