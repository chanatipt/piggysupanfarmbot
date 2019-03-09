<?php

/* This is web hook program for piggy-farm. It receives events sent from
line API and translates into "command" for Farm Controller via MQTT protocol. */
include "phpMQTT.php";
require "vendor/autoload.php";

/* $server = "iot.eclipse.org"; */
$server = "broker.hivemq.com";
$port = 1883;
$username = "";
$password = "";
$client_id = "piggy-supan-farm-line-bot";

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'V0i3cfZS6ll0DkFLHR2TlrJs58DoWVd8EQrDwGcG/K4WZKrg5Ep92I8Hoi81VObqvs7e41tkQ8cHkYXzQL7yuA/FyDvsrDj8eqGCuO+DFsFY+HnMFSuBTZ/V0qgJ8nEXZKEwolQM/RzqEAqY5rviPQdB04t89/1O/w1cDnyilFU=';
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
		print_r($event);
        if ($event['type'] == 'message') {
            if ($event['message']['type'] == 'text') {
                $text = $event['message']['text'];
                if (($text == 'รด 1')||($text == 'รด1')||($text == 'FeedOn 1')||($text == 'FeedOn1')) {
					$reply_message = 'โอเค จะสั่งรดน้ำโซน 1 นะ';
					$command = 'Feed1On';
                } else if (($text == 'หยุดรด 1')||($text == 'หยุดรด1')||($text == 'FeedOff 1')||($text == 'FeedOff1')) {
                    $reply_message = 'โอเค จะสั่งหยุดรดน้ำโซน 1 แล้ว';
					$command = 'Feed1Off';
                } else if (($text == 'รด 2')||($text == 'รด2')||($text == 'FeedOn 2')||($text == 'FeedOn2')) {
					$reply_message = 'โอเค จะสั่งรดน้ำโซน 2 นะ';
					$command = 'Feed2On';
                } else if (($text == 'รด 3')||($text == 'รด3')||($text == 'FeedOn 3')||($text == 'FeedOn3')) {
					$reply_message = 'โอเค จะสั่งรดน้ำโซน 3 นะ';
					$command = 'Feed3On';
                } else if (($text == 'หยุดรด 2')||($text == 'หยุดรด2')||($text == 'FeedOff 2')||($text == 'FeedOff2')) {
                    $reply_message = 'โอเค จะสั่งหยุดรดน้ำโซน 2 แล้ว';
					$command = 'Feed2Off';
                } else if (($text == 'หยุดรด 3')||($text == 'หยุดรด3')||($text == 'FeedOff 3')||($text == 'FeedOff3')) {
                    $reply_message = 'โอเค จะสั่งหยุดรดน้ำโซน 3 แล้ว';
					$command = 'Feed3Off';
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
                $mqtt->publish("chanatip/piggyfarm/supan/inputs", $command);
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
