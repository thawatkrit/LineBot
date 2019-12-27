<?php
    if (isset($_POST["send"])) {
        $text = $_POST["text"];
        $userId = $_POST["userId"];
        $access_token = '';

        $headers = array('Content-Type: application/json', 'Authorization: Bearer '.$access_token);

        $messages = [
            'type' => 'text',
            'text' => $text
        ];
        $data = [
            'to' => $userId,
            'messages' => [$messages]
        ];

        $post = json_encode($data);

        $curl = curl_init('https://api.line.me/v2/bot/message/push');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        
        curl_exec($curl);
        curl_close($curl);

        header("location: index.php");
        exit();
    }
    else {
        header("location: index.php");
        exit();
    }
