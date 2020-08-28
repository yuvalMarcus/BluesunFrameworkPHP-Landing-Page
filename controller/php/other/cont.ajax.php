<?php

class Ajax {

    public static function index(array &$data) {

        EnRouter::post($data, '*/-', function($data) {
            
        });

        EnRouter::post($data, '*/methodExist(data=[class|ajax,returnValue|method])', function($data) {

            $method = getMethod($data['method']);

            $strmethod = $method;

            self::$strmethod($data);
        });
    }

    public static function checkDataForm(&$data) {

        $arr = [
            [
                'name' => 'fullname',
                'text' => 'Full Name',
                'validation' => '*(data=[err|Full name is required])/text(data=[err|Invalid characters found])'
            ],
            [
                'name' => 'phone',
                'text' => 'Phone Number',
                'validation' => '*(data=[err|Phone number required])/telephone||telephone2||telephone3||telephoneIsrael(data=[err|Invalid phone number found])'
            ],
            [
                'name' => 'email',
                'text' => 'Email Address',
                'validation' => '*(data=[err|Email address required])/email||email2(data=[err|Invalid email address found])'
            ],
            [
                'name' => 'message',
                'text' => 'Message',
                'validation' => ''
            ],
        ];

        $httpRequest = new httpRequest($data);

        $httpRequest->setData($data, $arr);

        $isProper = false;
        $saveInDB = false;
        $mailSend = true;

        if (!$httpRequest->isProper($data, $arr)) {

            $isProper = true;

            $data['mail']['title'] = 'A new lead from ' . $data['properties']['siteName'];
            $data['mail']['AddReplyTo'] = 'no-reply@gmail.com';
            $data['mail']['CC'] = 'test@gmail.com';
            $data['mail']['BCC'] = 'test@gmail.com';
            $data['mail']['type'] = 'php';

            $data['mail']['sendfrom'] = !empty($_POST['email']) ? $_POST['email'] : '';

            $mailData = json_decode($httpRequest->getJson($data));

            if ($mailData == null) {
                $mailData = new stdClass();
                $mailData->formFields = [];
            }

            \BlueSunFrameworkPHPMiniMailManager::mailContent($data, $mailData->formFields);
            $mailSend = \BlueSunFrameworkPHPMiniMailManager::mailSend($data);
        }

        $obj = new stdClass();

        $obj->dataHttpRequest = $httpRequest->getData($data);
        $obj->isProper = $isProper;
        $obj->saveInDB = $saveInDB;
        $obj->mailSend = $mailSend;

        echo json_encode($obj);
    }

}
