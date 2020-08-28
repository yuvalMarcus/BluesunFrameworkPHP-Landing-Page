<?php

class PhpMail {

    public static function con(array &$data) {

        header('Content-Type: text/html; charset=utf-8');

        $headers = "From: " . $data['mail']['sendfrom'] . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        if (!empty($data['mail']['CC']))
            $headers .= "CC: " . $data['mail']['CC'] . "\r\n ";
        if (!empty($data['mail']['BCC']))
            $headers .= "BCC: " . $data['mail']['BCC'] . "\r\n ";
        $headers .= "Reply-To: " . $data['mail']['AddReplyTo'];

        return mail($data['mail']['sendemail'], $data['mail']['title'], $data['mail']['body'], $headers);
    }

}
