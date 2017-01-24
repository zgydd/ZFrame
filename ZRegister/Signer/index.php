<?php

// Service Signer logical
// Initlize    20170124    Joe

$filename = '../map.json';
$ZData = file_get_contents("php://input");
//Check format
$postData = (array) json_decode($ZData);

//$content = '';
//$content .= $postData['serviceId'] . ',' . $postData['serviceName'] . ',' . $postData['serviceIp'];
if (!array_key_exists('serviceId', $postData) || !array_key_exists('serviceName', $postData) || !array_key_exists('serviceIp', $postData) || count($postData) != 3) {
    return NULL;
} else {
    $content = json_encode($postData);
}

if (file_exists($filename) && abs(filesize($filename)) > 0) {
    $arr = (array) json_decode(file_get_contents($filename));
    foreach ($arr as $row) {
        if ($row->serviceIp == $postData['serviceIp'])
            return NULL;
    }

    array_push($arr, $postData);
    $content = json_encode($arr);
} else {
    $content = '[' . $content . ']';
}

file_put_contents($filename, $content);
?>
