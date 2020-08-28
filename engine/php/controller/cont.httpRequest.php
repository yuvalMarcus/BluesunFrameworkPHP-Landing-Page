<?php

class HttpRequest {

    private $data;

    public function __construct(array &$data) {

        $this->data = [];
        $this->data['httpRequest'] = [];
    }

    public function getData(array &$data): array {

        return $this->data['httpRequest'];
    }

    public function setData(array &$data, array $httpRequest) {

        foreach ($httpRequest as $val) {

            $this->addField($data, $val['name'], $val['text'], $val['validation']);
        }
    }

    public function getValueByNameField(array &$data, string $name, string $content = '', string $requestMethod = 'POST') {

        if ($requestMethod == 'POST') {

            return isset($_POST[$name]) && !$data['httpRequest'][$name]['err'] ? $_POST[$name] : $content;
        }
    }

    public function addField(array &$data, string $name, string $text = '', string $validation, string $content = '', string $requestMethod = 'POST') {

        $validations = explode('/', $validation);

        $i = 0;
        $bool = FALSE;
        $count = count($validations);

        $val = isset($_POST[$name]) ? $_POST[$name] : $content;

        $messageErr = '';

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

        $this->data['httpRequest'][$name] = ['err' => $bool, 'value' => $val, 'name' => $name, 'text' => $text, 'message_err' => $messageErr];
    }

    public function isProper(array &$data, array $arr): bool {

        $bool = false;

        $i = 0;
        $count = count($this->data['httpRequest']);

        while (!$bool && $i < $count) {

            $bool = $this->data['httpRequest'][$arr[$i]['name']]['err'];

            $i++;
        }

        return $bool;
    }

    public function getJson(array &$data): string {

        $obj = new stdClass();

        $obj->formFields = [];

        foreach ($this->data['httpRequest'] as $val) {

            $field = new stdClass();

            $field->name = $val['name'];
            $field->text = $val['text'];
            $field->value = $val['value'];

            $obj->formFields[] = $field;
        }

        return json_encode($obj);
    }

    public function getClassFieldValidation(array &$data, string $name): bool {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if ($data['httpRequest'][$name]['err']) {

                return false;
            } else {

                return true;
            }
        }
    }

}
