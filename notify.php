<?php

include 'phpMQTT.php';
require "vendor/autoload.php";

$server = "iot.eclipse.org";
$port = 1883;
$username = "";
$password = "";
$client_id = "piggy-supan-farm-line-bot";
$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
if (!$mqtt->connect(true, null, $username, $password)) {
    exit(1);
}
$topics['chanatip/piggysuphan/outputs'] = array("qos" => 0, "function" => "procmsg");
$mqtt->subscribe($topics, 0);
while ($mqtt->proc()) {

}
$mqtt->close();
function procmsg($topic, $msg)
{
    $access_token = 'V0i3cfZS6ll0DkFLHR2TlrJs58DoWVd8EQrDwGcG/K4WZKrg5Ep92I8Hoi81VObqvs7e41tkQ8cHkYXzQL7yuA/FyDvsrDj8eqGCuO+DFsFY+HnMFSuBTZ/V0qgJ8nEXZKEwolQM/RzqEAqY5rviPQdB04t89/1O/w1cDnyilFU=';
    $channelSecret = '9f2ea25304594b5df4c74c8e50228d0f';
	$pushID = 'C863d2206b62ace4687ce03c6cfb8c8dd';	
	if ($msg == 'W0') {
		$replyMsg = 'หยุดรดน้ำแล้วจ้า';
	} else if ($msg == 'W1') {
		$replyMsg = 'เริ่มรดน้ำแล้วจ้า';
	} else if ($msg == 'F0') {
		$replyMsg = 'หยุดใส่ปุ๋ยแล้วจ้า';
	} else if ($msg == 'F1') {
		$replyMsg = 'เริ่มใส่ปุ๋ยแล้วจ้า';
	} else if ($msg == 'r1') {
		$replyMsg = 'รายงานสถานะนะครับ';
	} else if ($msg == 'w0') {
		$replyMsg = 'ไม่ได้รดน้ำอยู่';
	} else if ($msg == 'w1') {
		$replyMsg = 'กำลังรดน้ำอยู่';
	} else if ($msg == 'f0') {
		$replyMsg = 'ไม่ได้ใส่ปุ๋ยอยู่';
	} else if ($msg == 'f1') {
		$replyMsg = 'กำลังใส่ปุ๋ยอยู่';
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
