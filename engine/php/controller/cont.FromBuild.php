<?php

class FromBuild {

    private $temp;
    private $sectionTitle;
    private $fields;
    private $validations;
    private $title;
    private $back;
    private $action;
    private $submitText;
    private $buttons;
    private $data;

    public function __construct(&$data) {

        $this->temp = 'default';
        $this->sectionTitle = [];
        $this->title = 'Title';
        $this->back = '';
        $this->action = '/';
        $this->fields = [];
        $this->validations = [];
        $this->submitText = 'שלח';
        $this->buttons = [];

        $this->data = [];
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

    public function addSectionTitle(&$data, $title) {

        $this->sectionTitle[] = $title;
    }

    public function addField(&$data, $obj, $section = 0) {

        $this->fields[$section][] = $obj;

        $this->data[$obj->name] = $obj;
    }

    public function addValidation(&$data, $name, $val) {

        $this->validations[$name] = $val;
    }

    public function getValidationByName(&$data, $name) {

        return $this->validations[$name];
    }

    public function addButtons(&$data, $obj) {

        $this->buttons[] = $obj;
    }

    public function getfieldData(&$data, $name, $validation, $content = '', $requestMethod = 'POST') {

        $validations = explode('/', $validation);

        $i = 0;
        $bool = FALSE;
        $count = count($validations);

        $val = $content;

        while (!$bool && $i < $count && !empty($validations[$i])) {

            $StringData = StringData::get($data, $validations[$i]);
            
            $validations[$i] = StringData::clearData($data, $validations[$i]);
            
            $validationsq = explode('||', $validations[$i]);
            $countq = count($validationsq);

            $boolq = TRUE;
            $j = 0;

            while ($boolq && $j < $countq) {

                if (StringChecks::get($data, $validationsq[$j], $val, $StringData)) {

                    $boolq = FALSE;
                }

                $j++;
            }

            $bool = $boolq;

            $i++;
        }

        $messageErr = !empty($StringData['err']) ? $StringData['err'] : '';

        $this->data[$name]->value = $val;
        $this->data[$name]->err = $bool;
        $this->data[$name]->message_err = $messageErr;

        return (object) ['name' => $name, 'value' => $val, 'err' => $bool, 'message_err' => $messageErr];
    }

    public function fieldBuild(&$data, $field) {

        $data['field'] = $field;

        View::showView('views/php/fromBuild/fields/tpl.' . $field->tag, $data);
    }

    public function getFieldValueByName(&$data, $field) {

        if (isset($_POST['submit'])) {
            if (isset($_POST[$field->name])) {
                return $_POST[$field->name];
            } else {
                return $field->defaultValue;
            }
        } else {
            return $field->defaultValue;
        }
    }

    public function getClassFieldValidation(&$data, $err = fulse) {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if ($err) {

                return 'error';
            } else {

                return 'success';
            }
        }

        return '';
    }

    public function cleanWhoErrors(&$data) {

        $bool = true;

        foreach ($this->data as $val) {
            if ($val->err) {
                return false;
            }
        }

        return $bool;
    }

    public function active(array &$data) {

        foreach ($this->fields as $section) {

            foreach ($section as $val) {

                $this->getfieldData($data, $val->name, $this->getValidationByName($data, $val->name), $this->getFieldValueByName($data, $val));
            }
        }
    }

    public function show(&$data) {

        if (isset($_POST['submit'])) {

            if (!empty($_POST['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {

                $data['token'] = Token::make($data);
            } else {

                View::showView('views/php/fromBuild/temp/tpl.form.err.token', $data);
            }
        } else {

            $data['token'] = Token::make($data);
        }

        $data['objFromBuild'] = $this;

        View::showView('views/php/fromBuild/temp/tpl.' . $this->temp, $data);
    }

}
