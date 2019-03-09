<?php

include 'phpMQTT.php';
require "vendor/autoload.php";

/* $server = "iot.eclipse.org"; */
$server = "broker.hivemq.com";
$port = 1883;
$username = "";
$password = "";
$client_id = "piggy-supan-farm-line-bot";
$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
if (!$mqtt->connect(true, null, $username, $password)) {
    exit(1);
}
$topics['chanatip/piggyfarm/supan/outputs'] = array("qos" => 0, "function" => "procmsg");
$mqtt->subscribe($topics, 0);
while ($mqtt->proc()) {

}
$mqtt->close();
function procmsg($topic, $msg)
{
    $access_token = 'V0i3cfZS6ll0DkFLHR2TlrJs58DoWVd8EQrDwGcG/K4WZKrg5Ep92I8Hoi81VObqvs7e41tkQ8cHkYXzQL7yuA/FyDvsrDj8eqGCuO+DFsFY+HnMFSuBTZ/V0qgJ8nEXZKEwolQM/RzqEAqY5rviPQdB04t89/1O/w1cDnyilFU=';
    $channelSecret = '9f2ea25304594b5df4c74c8e50228d0f';
	$pushID = 'C863d2206b62ace4687ce03c6cfb8c8dd';
	if ($msg == 'F10') {
		$replyMsg = 'หยุดรดน้ำโซน 1 แล้วจ้า';
	} else if ($msg == 'F11') {
		$replyMsg = 'เริ่มรดน้ำโซน 1 แล้วจ้า';
	} else if ($msg == 'F20') {
		$replyMsg = 'หยุดรดน้ำโซน 2 แล้วจ้า';
	} else if ($msg == 'F21') {
		$replyMsg = 'เริ่มรดน้ำโซน 2 แล้วจ้า';
	} else if ($msg == 'F30') {
		$replyMsg = 'หยุดรดน้ำโซน 3 แล้วจ้า';
	} else if ($msg == 'F31') {
		$replyMsg = 'เริ่มรดน้ำโซน 3 แล้วจ้า';
	} else if ($msg == 'r1') {
		$replyMsg = 'รายงานสถานะนะครับ';
	} else if ($msg == 'f10') {
		$replyMsg = 'ไม่ได้รดน้ำโซน 1 อยู่';
	} else if ($msg == 'f20') {
		$replyMsg = 'ไม่ได้รดน้ำโซน 2 อยู่';
	} else if ($msg == 'f30') {
		$replyMsg = 'ไม่ได้รดน้ำโซน 3 อยู่';
	} else if ($msg == 'f11') {
		$replyMsg = 'กำลังรดน้ำโซน 1 อยู่';
	} else if ($msg == 'f21') {
		$replyMsg = 'กำลังรดน้ำโซน 2 อยู่';
	} else if ($msg == 'f31') {
		$replyMsg = 'กำลังรดน้ำโซน 3 อยู่';
	} else if ($msg == 'e1') {
		$replyMsg = 'มีความผิดพลาดในระบบ';
	}

    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($replyMsg);
    $response = $bot->pushMessage($pushID, $textMessageBuilder);

    echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
    echo "Msg Recieved: " . date("r") . "\n";
    echo "Topic: {$topic}\n\n";
    echo "\t$msg\n\n";
}
