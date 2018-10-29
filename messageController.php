<?php
class MessageController{
	private $access_token='JKXlHo9pF4IBxFcKAJW39/DP6zir2qJCzqIQktYdf7hFIFlZ+IOhK8EQ2wLutllHZzssFGVCMLMzuhDfThPHkLdkxNFa2O+nF8YQ1oOVh+f5BTnme7VYo54R2hB56KwuTQKS6GvrXsVNOdHrO1nHigdB04t89/1O/w1cDnyilFU=';
	public function getMessage(){
		header('Content-Type: text/html; charset=utf-8');
		$content = file_get_contents('php://input');
		$events = json_decode($content, true);
        
        // keyword list
		$hello  = array('hello', 'hi', 'halo', 'สวัสดี', 'หวัดดี', 'สัสวดี', 'ทักทาย', 'ฮัลโหล', 'ฮัลโล', 'เฮลโล', 'เฮลโหล');
		$name = array('name', 'ชื่อ', 'ซื่อ', 'ชือ', 'ชิ่อ');
		$laugh = array('55', 'hah', 'ฮ่าๆ', 'ถถถ', 'ฮา');
        $bye = array('ออกไป', 'บาย');
        
		if(!is_null($events['events'])) {
			foreach($events['events'] as $event){
				if($event['type'] == 'message' && $event['message']['type'] == 'text'){
					$textFromUser = $event['message']['text'];
					$text = strtolower($textFromUser);
					$replyToken = $event['replyToken'];
					
					// Talk with user
					if (empty($events['events'][0]['source']['groupId'])) {
						$userId = $events['events'][0]['source']['userId'];
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
							$textSend = $content;
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
        $post=json_encode($data);
        $headers=array('Content-Type: application/json','Authorization: Bearer '.$this->access_token);
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_exec($ch);
        curl_close($ch);
    }
}