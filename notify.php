<?php

include 'phpMQTT.php';
require "vendor/autoload.php";

$server = "iot.eclipse.org";
$port = 1883;
$username = "";
$password = "";
$client_id = "piggy-farm-line-bot";
$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
if (!$mqtt->connect(true, null, $username, $password)) {
    exit(1);
}
//$topics['chanatip/piggybkk/outputs'] = array("qos" => 0, "function" => "procmsg");
$topics['chanatip/piggysuphan/outputs'] = array("qos" => 0, "function" => "procmsg");
$mqtt->subscribe($topics, 0);
while ($mqtt->proc()) {

}
$mqtt->close();
function procmsg($topic, $msg)
{
    $access_token = 'W7uUjdWdAR5rlMAhTCHZ11ESL1m/amYYEaMsvoFpy6Y8KcqL19qJp7sb/pGWiLqtSlgd+udUui8LBYAvaeds+YnHozApjfeoTH9kDhbdA3Y+vwaabNcbIhAKv/aR8EbuDe5JqkiYk+at/grNx9ERHgdB04t89/1O/w1cDnyilFU=';
    $channelSecret = '06e34e972681b7ad4b6431475c81f9c6';
	$pushID = 'Cee8d8a171a1356684fb550e250acb35a';	
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
