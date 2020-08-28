<?php

class BlueSunFrameworkPHPMiniMailManager {

    public static function mailContent(array &$data, array $mailData = []) {

        $data['mail']['namesend'] = $data['properties']['siteName'];
        $data['mail']['title'] = !empty($data['mail']['title']) ? $data['mail']['title'] : 'mail from website ' . $data['properties']['siteName'];
        $data['mail']['body'] = '<div style="direction: rtl;background-color: #fff;"><div style="max-width: 960px;margin: auto;">';

        if (!empty($mailData)) {

            foreach ($mailData as $val) {

                $data['mail']['body'] .= '<h3>' . $val->text . ' : </h3>' . $val->value . '<hr>';
            }
        }

        $data['mail']['body'] .= '</div></div>';

        $data['mail']['body'] = !empty($data['mail']['body']) ? $data['mail']['body'] : 'body empty';
        $data['mail']['sendemail'] = !empty($data['mail']['sendemail']) ? $data['mail']['sendemail'] : $data['properties']['emailSite'];
        $data['mail']['AddReplyTo'] = !empty($data['mail']['AddReplyTo']) ? $data['mail']['AddReplyTo'] : $data['properties']['siteName'];
        $data['mail']['CC'] = !empty($data['mail']['CC']) ? $data['mail']['CC'] : '';
        $data['mail']['BCC'] = !empty($data['mail']['BCC']) ? $data['mail']['BCC'] : '';
    }

    public static function mailSend(array &$data) {

        $data['mail']['AddReplyTo'] = !empty($data['mail']['AddReplyTo']) ? $data['mail']['AddReplyTo'] : '';

        if (!empty($data['mail']['type'])) {
            switch ($data['mail']['type']) {
                case 'google':
                    $contmail = 'GoogleMail';
                    break;
                case 'php':
                    $contmail = 'PhpMail';
                    break;
                default:
                    $contmail = 'GoogleMail';
                    break;
            }
        } else {
            $contmail = 'GoogleMail';
        }

        return $contmail::con($data);
    }

}
