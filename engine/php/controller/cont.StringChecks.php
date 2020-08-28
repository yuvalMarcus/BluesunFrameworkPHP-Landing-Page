<?php

class StringChecks {

    static private $characters = ['-', '*', '^', '!', '&', '=', 'checkingTelephone', 'checkingTelephone2', 'checkingText'];
    static private $charactersFunctions = [
        '-' => 'checkingNoteEmpty',
        '*' => 'checkingNoteAll',
        '^' => 'checkingNoteNumber',
        '!' => 'checkingNoteExcept',
        '&' => 'checkingNotePreg',
        '=' => 'checkingNoteValue',
        '?' => 'checkingNoteQuery',
        'classExist' => 'checkingClassExist',
        'methodExist' => 'checkingMethodExist',
        'telephone' => 'checkingTelephone',
        'telephone2' => 'checkingTelephone2',
        'telephone3' => 'checkingTelephone3',
        'telephoneIsrael' => 'checkingTelephoneIsrael',
        'text' => 'checkingText',
        'textKey' => 'checkingTextKey',
        'textSlug' => 'checkingTextSlug',
        'email' => 'checkingEmail',
        'email2' => 'checkingEmail2',
        'username' => 'checkingUsername',
        'password' => 'checkingPassword'];

    public function get(&$data, $validation, $val, $dataValidation = [], $bool = false) {

        $ob = new self();

        if (empty($dataValidation)) {

            $dataValidation = StringData::get($data, $validation);
        }

        if ($bool) {

            $validation = str_replace('(data=[' . getStringBetween($validation, '(data=[', '])') . '])', '', $validation);
        }

        $fun = !empty(self::$charactersFunctions[$validation[0]]) ? $validation[0] : $validation;

        $fun = !empty(self::$charactersFunctions[$fun]) ? self::$charactersFunctions[$fun] : 'emptyNoFun';

        return method_exists($ob, $fun) ? self::$fun($data, $validation, $val, $dataValidation) : false;
    }

    public function checkingNoteEmpty(&$data, $validation, $val, $dataValidation = []) {

        if (!empty($val)) {

            return false;
        } else {

            return true;
        }
    }

    public static function checkingNoteAll(&$data, $validation, $val, $dataValidation = []) {

        if (empty($val)) {

            return false;
        } else {

            if (!empty($dataValidation['returnValue'])) {

                $data[$dataValidation['returnValue']] = $val;
            }

            return true;
        }
    }

    public function checkingNoteNumber(&$data, $validation, $val, $dataValidation = []) {

        if (is_numeric($val) || empty($val)) {

            if (!empty($dataValidation['returnValue'])) {

                $data[$dataValidation['returnValue']] = $val;
            }

            return true;
        } else {

            return false;
        }
    }

    public function checkingNotePreg(&$data, $validation, $val, $dataValidation = []) {

        if (!preg_match('/' . substr($validation, 1) . '/', $val) || empty($val)) {

            return false;
        } else {

            if (!empty($dataValidation['returnValue'])) {

                $data[$dataValidation['returnValue']] = $val;
            }

            return true;
        }
    }

    public static function checkingNoteValue(&$data, $validation, $val, $dataValidation = []) {

        if ($val == substr($validation, 1)) {

            if (!empty($dataValidation['returnValue'])) {

                $data[$dataValidation['returnValue']] = $val;
            }

            return true;
        } else {

            return false;
        }
    }

    public static function checkingNoteQuery(&$data, $validation, $val, $dataValidation = []) {

        $action = !empty($dataValidation['action']) ? 'notExists' : 'exists';

        $table = !empty($dataValidation['table']) ? $dataValidation['table'] : '';
        $column = !empty($dataValidation['column']) ? $dataValidation['column'] : '';

        $data['result'] = [];

        $data['mysql']['executearray'] = [$val];
        $data['mysql']['table_name'] = $table;
        $data['mysql']['selectingcolumns'] = '*';
        $data['mysql']['JOIN'] = null;
        $data['mysql']['WHERE'] = 'WHERE ' . $column . ' = ?';
        $data['mysql']['LIMIT'] = null;
        $data['mysql']['OTHER'] = null;
        $data['mysql']['clear'] = true;
        $data['mysql']['setselectquery'] = null;
        $data['mysql']['selectquery'] = false;

        Database::getData($data, 'result');

        switch ($action) {
            case 'exists':

                if (!empty($data['result'])) {

                    if (!empty($dataValidation['current']) && $dataValidation['current'] == $data['result'][$column]) {
                        return false;
                    }

                    if (!empty($dataValidation['returnValue'])) {

                        $data[$dataValidation['returnValue']] = $val;
                    }

                    return true;
                } else {

                    return false;
                }

                break;
            case 'notExists':

                if (empty($data['result'])) {

                    return true;
                } else {

                    if (!empty($dataValidation['current']) && $dataValidation['current'] == $data['result'][$column]) {

                        return true;
                    }

                    if (!empty($dataValidation['returnValue'])) {

                        $data[$dataValidation['returnValue']] = $val;
                    }

                    return false;
                }

                break;
        }
    }

    public static function checkingClassExist(&$data, $validation, $val, $dataValidation = []) {

        $stringFirstInClassName = !empty($dataValidation['stringFirstInClassName']) ? $dataValidation['stringFirstInClassName'] : '';

        $class = getClass($val, $stringFirstInClassName);

        if (class_exists($class)) {

            if (!empty($dataValidation['stringFirstInClassName'])) {

                $data['stringFirstInClassName'] = $dataValidation['stringFirstInClassName'];
            }

            if (!empty($dataValidation['returnValue'])) {

                $data[$dataValidation['returnValue']] = $class;
            }

            return true;
        } else {

            return false;
        }
    }

    public static function checkingMethodExist(&$data, $validation, $val, $dataValidation = []) {

        $class = !empty($dataValidation['class']) ? $dataValidation['class'] : '';

        $method = getMethod($val);

        if (method_exists($class, $method)) {

            if (!empty($dataValidation['returnValue'])) {

                $data[$dataValidation['returnValue']] = $method;
            }

            return true;
        } else {

            return false;
        }
    }

    public function checkingTelephone(&$data, $validation, $val, $dataValidation = []) {

        if (preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $val) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingTelephone2(&$data, $validation, $val, $dataValidation = []) {

        if (preg_match('/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{7})$/', $val) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingTelephone3(&$data, $validation, $val, $dataValidation = []) {

        if (preg_match('/^0(5[012345678]|7[47]){1}(\-)?[^0\D]{1}\d{6}$/', $val) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingTelephoneIsrael(&$data, $validation, $val, $dataValidation = []) {

        if (preg_match('/^\(?[\d]{3}\)?[\s-]?[\d]{3}[\s-]?[\d]{4}$/', $val) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingText(&$data, $validation, $val, $dataValidation = []) {

        if (preg_match('/^[א-תa-zA-Z0-9 ,]*$/', $val) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingTextSlug(&$data, $validation, $val, $dataValidation = []) {

        if (preg_match('/^[א-תa-zA-Z0-9-]*$/', $val) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingTextKey(&$data, $validation, $val, $dataValidation = []) {

        if (preg_match('/^[a-zA-Z0-9-]*$/', $val) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingEmail(&$data, $validation, $val, $dataValidation = []) {

        if (filter_var($val, FILTER_VALIDATE_EMAIL) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingEmail2(&$data, $validation, $val, $dataValidation = []) {

        if (filter_var($val, FILTER_VALIDATE_EMAIL) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingUsername(&$data, $validation, $val, $dataValidation = []) {

        if (preg_match('/^[a-zA-Z0-9._-]{3,15}$/', $val) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkingPassword(&$data, $validation, $val, $dataValidation = []) {

        if (preg_match('/^(?=.*?[A-Za-z0-9#?!@$%^&*-]).{4,}$/', $val) || empty($val)) {

            return true;
        } else {

            return false;
        }
    }

}
