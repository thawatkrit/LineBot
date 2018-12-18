<?php

$GLOBALS['userList'] = array();
class MessageController{
	private $access_token = 'gAUGCPQSFxlvvlwwvO3EuUCQFJZR5cAf2hCBlZRrHJOXYlJYgEXS4Ba+xBr2VGmt4Kre3ID9eusD3DSx8JgMPJWR0uBrdUCh8FV6VIpDr+vSSYIKqcYhV/U3ujDyPv6LP+BQo61lH5Us2K+HIU2TFQdB04t89/1O/w1cDnyilFU=';

	public function getMessage(){
		header('Content-Type: text/html; charset=utf-8');
		$content = file_get_contents('php://input');
		$events = json_decode($content, true);

        // keyword list
		$hello  = array('hello', 'hi', 'halo', 'สวัสดี', 'หวัดดี', 'สัสวดี', 'ทักทาย', 'ฮัลโหล', 'ฮัลโล', 'เฮลโล', 'เฮลโหล');
		$name = array('name', 'ชื่อ', 'ซื่อ', 'ชือ', 'ชิ่อ');
		$laugh = array('55', 'hah', 'ฮ่าๆ', 'ถถถ', 'ฮา');
        $bye = array('ออกไป', 'บาย');
		$bedTime = array('นอนกี่โมง', 'นอนยัง');
		$cute = array('เธอน่ารัก','เธอน่ารักอะ','น่ารักจัง');
		$damn = array('ควย', 'ควาย','โง่','สัส','เหี้ย');
		$eat = array('กินไรยัง');
		$goodNight = array('ฝันดี', 'ฝันดีครับ');
		$hangOut = array('ไปเที่ยวกันไหม', 'ไปเที่ยวกัน','ไปเที่ยวกันมั้ย');
		$howAreYou = array('เป็นไงบ้างอะ','เป็นอย่างไร','เป็นไงบ้าง','เป็นไง');
		$howAreYouDo = array('ทำไรอยู่','ทำอะไรอยู่');
        
		if(!is_null($events['events'])) {
			foreach($events['events'] as $event){
				if($event['type'] == 'message' && $event['message']['type'] == 'text'){
					$textFromUser = $event['message']['text'];
					$text = strtolower($textFromUser);
					$replyToken = $event['replyToken'];
					$type = $event['source']['type'];
					
					// Talk with user
					if ($type === 'user') {
						$userId = $events['events'][0]['source']['userId'];
						$userIndex = $this->checkUserId($userId);

						
						if ($userIndex === -1) {
							$GLOBALS['userList'][count($GLOBALS['userList'])] = new User($userId);
							$GLOBALS['userList'][count($GLOBALS['userList']) - 1]->saveMessage($text);
						} else {
							$GLOBALS['userList'][$userIndex]->saveMessage($text);
						}

						if ($this->strposa($text, $hello)){
							$textSend = $events['events'][0]['source']['userId'];
						}
						else if ($this->strposa($text, $name)) {
							$textSend = "ชื่อ Creeper ครับ";
						}
						else if ($this->strposa($text, $bye)) {
							$textSend = "ไปไหน";
						}
						else {
							// $textSend = $content;
							$textSend = count($GLOBALS['userList']);
						}
					}
					
					// Talk with group
					else {
						if($this->strposa($text, $hello)){
							$textSend = "สวัสดีครับ";
						}
						else if ($this->strposa($text, $name)) {
							$textSend = "ชื่อ sloth ครับ";
						}
						else if ($this->strposa($text, $laugh)) {
							$random = rand(0,1);
							switch ($random) {
								case 0 : $textSend = "ขำควยไร"; break;
								case 1 : $textSend = "laugh_sticker";
							}
						}
						else if ($this->strposa($text, $bye)) {
							$textSend = "leave";
                        }
					}
					$this->sendMessage($textSend,$replyToken);
					break;
				}
			}
		}
    }
	
	// Check word in array
	public function strposa($haystack, $needle, $offset = 0) {
	    if(!is_array($needle)) $needle = array($needle);
	    foreach($needle as $query) {
	        if(strpos($haystack, $query, $offset) !== false) return true;
	    }
	    return false;
	}
	
	public function checkUserId($userId) {
		for($i = 0; $i < count($GLOBALS['userList']); $i++) {
			if ($userId == $GLOBALS['userList'][$i]->getUserId()) {
				return $i;
			}
		}
		return -1;
	}

	// Send request to Messaging API
    public function sendMessage($textSend, $replyToken) {
		// Send message
        $messages=[
            'type'=>'text',
            'text'=>$textSend
        ];
        $url='https://api.line.me/v2/bot/message/reply';
        $data=[
            'replyToken'=>$replyToken,
            'messages'=>[$messages]
        ];
        $post = json_encode($data);
        $headers = array('Content-Type: application/json','Authorization: Bearer '.$this->access_token);
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_exec($ch);
        curl_close($ch);
    }
}

class User{
	private $userId;
	private $messageList;
	public function __construct($userId) {
		$this->userId = $userId;
	}
	public function getUserId() {
		return $this->userId;
	}
	public function saveMessage($message) {
		array_push($this->messageList,$message);
	}
	public function getMessages() {
		return $this->messageList;
	}
}