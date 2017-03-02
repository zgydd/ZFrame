<?php

//Test project
require_once 'commFunc.php';

$url = NULL;

$ZData = file_get_contents("php://input");

//Load Libra
if (file_exists('Libra/routeSelecter.php') && abs(filesize('Libra/routeSelecter.php')) > 0) {
    require_once 'Libra/routeSelecter.php';
    $routeSelecter = new \Lucy\Libra();
    $url = $routeSelecter->selectRoute();
} else {
    //$url = 'http://127.0.0.1:3000';
}
if (is_null($url)) {
    return;
}

if (!_checkPostUrl($url)) {
    echo $url;
    return;
}

if ($ZData == NULL || empty($ZData)) {
    $data = new stdClass();
    $data->uuid = 'Z_localhost_' . microtime(true);

    $data->head = new stdClass();
    $data->head->routeFlg = 'Z_ROUTE_1';
    $data->head->modelFlg = 'Z_MODEL_0';
    $data->head->servicesList = array('Z_SRV_0', 'Z_SRV_1');
    $data->head->dataFrom = array('localhost');
    $data->head->dataTo = NULL;

    $data->entity = new stdClass();
    $data->entity->body = '1';
} else {
    $data = json_decode($ZData);
    $data->uuid .= microtime(true);
}
$data = json_encode($data);
echo 'Post data<br/>';
var_dump($data);
echo '<br/>##################IndexLine#################<br/>';
echo '--Start curl at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';
echo '--Target to ' . $url . '<br/>';
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
echo '--Curl closed at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';
echo $response;
echo '<br/>##################IndexLine#################<br/>';
echo '--Feed back at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';

