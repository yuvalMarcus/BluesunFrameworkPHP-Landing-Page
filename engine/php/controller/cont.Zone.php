<?php

class Zone {

    private $data;

    public function __construct(&$data, string $urlCustom = '') {

        $url = empty($urlCustom) ? $data['url'][0] : $urlCustom;

        $this->data = new stdClass();

        $data['zonedata'] = [];
        
        if (!empty($url)) {

            $data['mysql']['executearray'] = [$url];
            $data['mysql']['table_name'] = 'zone_page';
            $data['mysql']['selectingcolumns'] = '*';
            $data['mysql']['WHERE'] = 'WHERE slug = ? ';
            $data['mysql']['clear'] = TRUE;

            Database::getData($data, 'zonedata');

            if (empty($data['zonedata'])) {

                $data['mysql']['executearray'] = ['404'];
                $data['mysql']['table_name'] = 'zone_page';
                $data['mysql']['selectingcolumns'] = '*';
                $data['mysql']['WHERE'] = 'WHERE name = ? ';
                $data['mysql']['clear'] = TRUE;

                Database::getData($data, 'zonedata');
            }
        } else {

            $data['mysql']['executearray'] = [1];
            $data['mysql']['table_name'] = 'zone_page';
            $data['mysql']['selectingcolumns'] = '*';
            $data['mysql']['WHERE'] = 'WHERE home = ? ';
            $data['mysql']['clear'] = TRUE;

            Database::getData($data, 'zonedata');
        }
        
        if (empty($data['zonedata'])) {

            $this->data->show = false;
            $this->data->type = 'error404';
        } else {

            $this->data->show = true;
            $this->data->type = 'found';
            $this->data->data = $data['zonedata'];
            $this->data->objectData = json_decode($this->data->data['data']);
            $this->data->meta = [];

            \GetData::metaZone($data, $this->data->data['id'], $this->data->meta);

            if (!empty($this->data->meta['structuredata']))
                $this->data->objectStructure = json_decode($this->data->meta['structuredata']);
            else
                $this->data->objectStructure = null;
        }
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
