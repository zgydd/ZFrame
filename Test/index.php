<?php

$url = 'http://127.0.0.1:10000';

$data = new stdClass();
$data->head = new stdClass();
$data->head->a = 'a';
$data->head->b = 'b';
$data->head->c = 'c';
$data->body = '1';
$data = json_encode($data);

$ch = curl_init(); //初始化curl
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    print curl_error($ch);
}
curl_close($ch);
echo '<br/>##################IndexLine#################<br/>';
echo $response;
echo '<br/>##################IndexLine#################<br/>';

?>