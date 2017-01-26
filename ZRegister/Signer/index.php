<?php

// Service Signer logical
// Initlize    20170124    Joe

$filename = '../map.json';
$ZData = file_get_contents("php://input");
if ($ZData == 'Z_TEST_TIMESTAMP') {
//    sleep(1);
    echo microtime(true);
    return;
}
if (is_null($ZData) || empty($ZData)) {
    echo 'Z_MSG_NO_POSTDATA';
    return;
}
//Check format
$postData = (array) json_decode($ZData);
if (is_null($ZData) || empty($ZData)) {
    echo 'Z_MSG_NO_POSTDATA';
    return;
}

//$content = '';
//$content .= $postData['serviceId'] . ',' . $postData['serviceName'] . ',' . $postData['serviceIp'];
if (!array_key_exists('serviceId', $postData) 
        || !array_key_exists('serviceName', $postData) 
        || !array_key_exists('serviceIp', $postData) 
        || !array_key_exists('servicePort', $postData) || count($postData) != 4) {
    return;
} else {
    $content = json_encode($postData);
}

if (file_exists($filename) && abs(filesize($filename)) > 0) {
    $arr = (array) json_decode(file_get_contents($filename));
    foreach ($arr as $row) {
        if ($row->serviceIp == $postData['serviceIp'] 
                && $row->servicePort == $postData['servicePort']) {
            return;
        }
    }

    array_push($arr, $postData);
    $content = json_encode($arr);
} else {
    $content = '[' . $content . ']';
}

file_put_contents($filename, $content);

