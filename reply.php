<?php

/* This is web hook program for piggy-farm. It receives events sent from
line API and translates into "command" for Farm Controller via MQTT protocol. */
include "phpMQTT.php";
require "vendor/autoload.php";

$server = "iot.eclipse.org";
$port = 1883;
$username = "";
$password = "";
$client_id = "piggy-farm-line-bot";

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'W7uUjdWdAR5rlMAhTCHZ11ESL1m/amYYEaMsvoFpy6Y8KcqL19qJp7sb/pGWiLqtSlgd+udUui8LBYAvaeds+YnHozApjfeoTH9kDhbdA3Y+vwaabNcbIhAKv/aR8EbuDe5JqkiYk+at/grNx9ERHgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);
$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

/* get request array and decode into an array */
$request = file_get_contents('php://input');
$request_array = json_decode($request, true);

/* In case the incoming request array is available,
loop through events in the incoming request array */
if (sizeof($request_array['events']) > 0) {
    foreach ($request_array['events'] as $event) {
        /* reply to users to
        acknowledge their messages */
		$reply_message = '';
		$command = '';
        $reply_token = $event['replyToken'];
        if ($event['type'] == 'message') {
            if ($event['message']['type'] == 'text') {
                $text = $event['message']['text'];
                if (($text == 'รดน้ำ')||($text == 'WaterOn')) {
					$reply_message = 'โอเค จะสั่งรดน้ำนะ';
					$command = 'WaterOn';
                } else if (($text == 'หยุดรดน้ำ')||($text == 'WaterOff')) {
                    $reply_message = 'โอเค จะสั่งหยุดรดน้ำแล้ว';
					$command = 'WaterOff';
                } else if (($text == 'ใส่ปุ๋ย')||($text == 'FertilizerOn')) {
                    $reply_message = 'โอเค จะสั่งใส่ปุ๋ยนะ';
					$command = 'FertilizerOn';
                } else if (($text == 'หยุดใส่ปุ๋ย')||($text == 'FertilizerOff')) {
                    $reply_message = 'โอเค จะสั่งหยุดใส่ปุ๋ยแล้ว';
					$command = 'FertilizerOff';
                } else if (($text == 'หยุดระบบ')||($text == 'SystemOff')) {
                    $reply_message = 'รับทราบ จะสั่งหยุดระบบแล้ว';
					$command = 'SystemOff';
                } else if (($text == 'เริ่มระบบ')||($text == 'SystemOn')) {
                    $reply_message = 'รับทราบ จะสั่งเปิดระบบแล้ว';
					$command = 'SystemOn';
                } else if (($text == 'รายงาน')||($text == 'Report')) {
                    $reply_message = 'รอสักครู่ ปั่นรายงานก่อน';
					$command = 'Report';
                } else {
                    $reply_message = 'ขอโทษนะไม่เข้าใจ "' . $text . '"';
                }
            }
        }

        if (strlen($reply_message) > 0) {
			/* send back acknowledge message */
            $data = [
                'replyToken' => $reply_token,
                'messages' => [['type' => 'text', 'text' => $reply_message]],
            ];
            $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

            $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);

			/* send valid command to Farm Controller */
            if ($mqtt->connect(true, null, $username, $password) && ($command !== '')) {
//                $mqtt->publish("chanatip/piggybkk/inputs", $command);
                $mqtt->publish("chanatip/piggysuphan/inputs", $command);
                $mqtt->close();
            } else {
                echo "MQTT publish Time out!\n";
            }
            echo "Result: " . $send_result . "\r\n";
        }
    }
}

/* This is the reply message function */
function send_reply_message($url, $post_header, $post_body)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
