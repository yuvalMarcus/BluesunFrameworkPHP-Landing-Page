<?php

class GoogleMail {

    public static function con(array &$data) {

        header('Content-Type: text/html; charset=utf-8');

        $email = $data['mail']['sendemail'];

        require_once('phpmailer/cont.phpmailer.php');
        require_once("phpmailer/cont.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

        $mail = new PHPMailer();

        $body = $data['mail']['body']; // PLACE YOUR DATA HERE....

        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
        $mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port = 587;                   // set the SMTP port for the GMAIL server
        $mail->Username = "";  // GMAIL username
        $mail->Password = "";            // GMAIL password
        $mail->From = $data['properties']['siteName'];
        $mail->FromName = $data['mail']['namesend']; /* This is the header!! */
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->AddReplyTo($data['mail']['AddReplyTo'], "");

        $mail->Subject = $data['mail']['title']; // the subject

        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);

        $mail->smtpConnect(
                array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true
                    )
                )
        );

        $mail->AddAddress($email, 'mail');
        if ($mail->Send()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
