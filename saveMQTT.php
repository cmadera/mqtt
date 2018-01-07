<?php
require("phpMQTT.php");
	
$server = "mqtt.bigbank.com.br";     // change if necessary
$port = 1883;                     // change if necessary
$username = NULL;                   // set your username
$password = NULL;                   // set your password
$client_id = "phpMQTT-bigbankclient003390909"; // make sure this is unique for connecting to sever - you could use uniqid()
use Bluerhinos\phpMQTT;
$mqtt = new phpMQTT($server, $port, $client_id);
if(!$mqtt->connect(true, NULL, $username, $password)) {
	exit(1);
}
include("config.php");

$sql = "SELECT id_topic, nm_topic, id_active FROM topic where id_active='Y'";
$result = $conn->query($sql);

$topics = [];

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$topics[$row["nm_topic"]] = array("qos" => 0, "function" => "procmsg");
        echo "Topic: " . $row["nm_topic"]."\n";
    }
} else {
    echo "0 results";
	$conn->close();
    exit(0);
}
$conn->close();

$mqtt->subscribe($topics, 0);
while($mqtt->proc()){}
$mqtt->close();

function procmsg($topic, $msg){
	include("config.php");
	$sql = "INSERT INTO measure (nm_topic, vl_topic) VALUES ('{$topic}', '{$msg}')";
	$conn->query($sql);
	$conn->close();
}

// nohup php saveMQTT.php &
