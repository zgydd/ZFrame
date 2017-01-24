<?php

// Register logical
// Initlize    20170124    Joe

$url = 'http://127.0.0.1:20001';

$ch = curl_init(); //初始化curl
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents("php://input"));
$response = curl_exec($ch);
if (curl_errno($ch)) {
    print curl_error($ch);
}
curl_close($ch);
//echo '<br/>##################ReisterLine#################<br/>';
$dbData = json_decode($response);

foreach ($dbData->user as $row) {
    foreach ($row as $col) {
        echo $col . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    //var_dump($row);
    echo '<br/>';
}
echo '<br/>';
echo '<br/>';
foreach ($dbData->syohinInner as $row) {
    foreach ($row as $col) {
        echo $col . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    //var_dump($row);
    echo '<br/>';
}

$jsonData = json_decode(file_get_contents('map.json'), true);


//echo $response;
//echo '<br/>##################ReisterLine#################<br/>';
?>
