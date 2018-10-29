<?php
    if (isset($_POST["send"])) {
        $text = $_POST["text"];
        $access_token = 'gAUGCPQSFxlvvlwwvO3EuUCQFJZR5cAf2hCBlZRrHJOXYlJYgEXS4Ba+xBr2VGmt4Kre3ID9eusD3DSx8JgMPJWR0uBrdUCh8FV6VIpDr+vSSYIKqcYhV/U3ujDyPv6LP+BQo61lH5Us2K+HIU2TFQdB04t89/1O/w1cDnyilFU=';

        $header = array('Content-Type: application/json', 'Authorization: Bearer '.$access_token);
        $userId = "U1cdfde31d77b135318bd76d016f834a7";
        $data = [
            'to' => $userId,
            'messages' => [
                'type' => 'text',
                'text' => $text
            ]
        ];

        $curl = curl_init('https://api.line.me/v2/bot/message/push');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_exec($curl);

        header("location: index.php");
        exit();
    }
    else {
        header("location: index.php");
        exit();
    }