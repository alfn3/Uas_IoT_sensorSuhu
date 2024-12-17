<?php
require("./vendor/autoload.php");
require("./DB.php");

use Bluerhinos\phpMQTT;

$server   = 'x2.revolusi-it.com';
$port     = 1883;
$username = 'usm';
$password = 'usmjaya001';
$client_id = 'Client-G231220060';

$mqtt = new phpMQTT($server, $port, $client_id);

if (!$mqtt->connect(true, NULL, $username, $password)) {
    die(json_encode(["error" => "Failed to connect to MQTT broker"]));
}

// Ambil data dari topik MQTT
$temperature = $mqtt->subscribeAndWaitForMessage('G231220060/temperature', 0);
$humidity = $mqtt->subscribeAndWaitForMessage('G231220060/humidity', 0);
$control = $mqtt->subscribeAndWaitForMessage('G231220060/control', 0);

// Validasi data MQTT
if ($temperature === false || $humidity === false) {
    die(json_encode(["error" => "Failed to receive data from MQTT broker"]));
}

// Parsing data JSON jika diperlukan
$temperature = json_decode($temperature, true) ?? $temperature;
$humidity = json_decode($humidity, true) ?? $humidity;
$control = json_decode($control, true) ?? "Invalid control format";

// Validasi nilai numerik
if (!is_numeric($temperature) || !is_numeric($humidity)) {
    die(json_encode(["error" => "Temperature or Humidity is not numeric"]));
}

$mqtt->close();

// Simpan data ke database
$DB = new DB();
$db_message = $DB->insert($temperature, $humidity);
$sensors_data = $DB->getSensorsData();

// Output dalam format JSON
header('Content-Type: application/json');
$response = json_encode([
    "temperature" => $temperature,
    "humidity" => $humidity,
    "control" => $control,
    "db_message" => $db_message,
    "sensors_data" => $sensors_data
]);

echo $response;
?>
