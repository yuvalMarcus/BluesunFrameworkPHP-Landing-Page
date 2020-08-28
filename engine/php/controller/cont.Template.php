<?php

class Template {

    private $name;
    private $stringcode;
    private $tempmetadata;

    public function __construct(&$data) {

        $data['mysql']['executearray'] = ['tempsite'];
        $data['mysql']['table_name'] = 'websettings';
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['WHERE'] = 'WHERE metakey = ?';
        $data['mysql']['clear'] = TRUE;

        Database::getData($data, 'tempname');

        $this->name = $data['tempname']['metavalue'];

        $this->stringcode = trim(substr(trim(str_replace(array("\r\n", "\r", "\n"), "", file_get_contents('../../views/php/template/website/public/' . $data['tempname']['metavalue'] . '/temp.stringcode.php'))), 10));

        $this->tempmetadata = [];

        GetData::metaTemp($data, $this->stringcode, $this->tempmetadata);
    }

    public function __get($property) {

        if (property_exists($this, $property)) {

            return $this->$property;
        }
    }

    public function __set($property, $value) {

        if (property_exists($this, $property)) {

            $this->$property = $value;
        }

        return $this;
    }

}
