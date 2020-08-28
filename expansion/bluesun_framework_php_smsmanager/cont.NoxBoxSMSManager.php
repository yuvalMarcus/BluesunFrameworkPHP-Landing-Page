<?php

class bluesun_framework_phpSMSManager {

    public static function mailSend(array &$data) {

        $check = false;

        if (!empty($data['sms']['phone'])) {

            $check = true;

            $message_text = $data['sms']['body'];
            $recepients = $data['sms']['phone'];

            $sms_user = "hackeru";
            $sms_password = "53649857";
            $sms_sender = "HackerU";

            $message_text = preg_replace("/\r|\n/", "", $message_text);

            $xml = '';
            $xml .= '<Inforu>' . PHP_EOL;
            $xml .= ' <User>' . PHP_EOL;
            $xml .= '  <Username>' . htmlspecialchars($sms_user) . '</Username>' . PHP_EOL;
            $xml .= '  <Password>' . htmlspecialchars($sms_password) . '</Password>' . PHP_EOL;
            $xml .= ' </User>' . PHP_EOL;
            $xml .= ' <Content Type="sms">' . PHP_EOL;
            $xml .= '  <Message>' . htmlspecialchars($message_text) . '</Message>' . PHP_EOL;
            $xml .= ' </Content>' . PHP_EOL;
            $xml .= ' <Recipients>' . PHP_EOL;
            $xml .= '  <PhoneNumber>' . htmlspecialchars($recepients) . '</PhoneNumber>' . PHP_EOL;
            $xml .= ' </Recipients>' . PHP_EOL;
            $xml .= ' <Settings>' . PHP_EOL;
            $xml .= '   <Sender>' . htmlspecialchars($sms_sender) . '</Sender>' . PHP_EOL;
            $xml .= ' </Settings>' . PHP_EOL;
            $xml .= '</Inforu>';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://api.inforu.co.il/SendMessageXml.ashx?InforuXML=' . urlencode($xml));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        return $check;
    }

}
