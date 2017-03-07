<?php

//Test project
require_once '../../commFunc.php';

$ZData = file_get_contents("php://input");

$url = 'http://127.0.0.1:3000';

if ($ZData == NULL || empty($ZData)) {
    echo 'No data';
} else {
    $data = json_decode($ZData);
    $data->uuid .= microtime(true);
}
$data = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    print curl_error($ch);
}
curl_close($ch);
echo $response;
