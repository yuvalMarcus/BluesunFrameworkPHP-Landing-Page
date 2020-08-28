<?php

class CustomField {

    public static function get(array &$data, int $id, string $stringcode = '', string $type = 'custom') {

        $data['mysql']['executearray'] = [$id, $stringcode, 'metadata-' . $type];
        $data['mysql']['table_name'] = 'website_extensions metadata';
        $data['mysql']['selectingcolumns'] = " metadata.*";
        $data['mysql']['WHERE'] = 'WHERE metadata.obj = ? AND metadata.stringcode = ? AND metadata.type = ?';
        $data['mysql']['clear'] = true;

        $type = str_replace('-', '_', $type);

        foreach ($data['languages'] as $val) {
            $data['mysql']['selectingcolumns'] .= ' ,(SELECT languages_text.metavalue FROM languages_text WHERE metakey = "name_' . $val::getMetadata($data)['metakey'] . '" AND metatype = "language_metadata_' . $type . '_name_text" AND objid = metadata.id) name_' . $val::getMetadata($data)['metakey'] . ' ,(SELECT languages_text.metavalue FROM languages_text WHERE metakey = "description_' . $val::getMetadata($data)['metakey'] . '" AND metatype = "language_metadata_' . $type . '_description_text" AND objid = metadata.id) description_' . $val::getMetadata($data)['metakey'];
        }

        \Database::getDatas($data, 'customfielddata');
    }

}
