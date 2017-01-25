<?php

// Register logical
// Initlize    20170124    Joe
function getLocalIP() {
    $preg = "/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
//    exec("ipconfig", $out, $stats);
//    if (!empty($out)) {
//        foreach ($out AS $row) {
//            if (strstr($row, "IP") && strstr($row, ":") && !strstr($row, "IPv6")) {
//                $tmpIp = explode(":", $row);
//                if (preg_match($preg, trim($tmpIp[1]))) {
//                    return trim($tmpIp[1]);
//                }
//            }
//        }
//    }
    exec("ifconfig", $out, $stats);
    if (!empty($out)) {
        if (isset($out[1]) && strstr($out[1], 'addr:')) {
            $tmpArray = explode(":", $out[1]);
            $tmpIp = explode(" ", $tmpArray[1]);
            if (preg_match($preg, trim($tmpIp[0]))) {
                return trim($tmpIp[0]);
            }
        }
    }
    return '127.0.0.1';
}

//Enter
$ZData = json_decode(file_get_contents("php://input"));
if (is_null($ZData) || empty($ZData)) {
    echo 'Z_MSG_NO_POSTDATA';
    return;
}

//Choose route
$filename = 'map.json';
$url = '';
if (file_exists($filename) && abs(filesize($filename)) > 0) {
    $arr = (array) json_decode(file_get_contents($filename));
    //How to choose a route
//    foreach ($arr as $row) {
//    }
    $url = 'http://' . $arr[0]->serviceIp . ':' . $arr[0]->servicePort;
} else {
    
}

if (empty($url)) {
    echo 'No Route to Serveices!!';
    return NULL;
}
//preg_match a Ip address
//$url = 'http://127.0.0.1:20001';
//CheckData


array_push($ZData->head->dataFrom, getLocalIP());

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ZData));
$response = curl_exec($ch);
if (curl_errno($ch)) {
    print curl_error($ch);
}
curl_close($ch);

////Route Check
//echo '<br/>##################ReisterLine#################<br/>';
//echo $response;
//echo '<br/>##################ReisterLine#################<br/>';
//In Route
$resultZData = json_decode($response);
array_pop($resultZData->head->dataTo);
echo json_encode($resultZData);

////Data Check
//$dbData = json_decode($response);
//
//foreach ($dbData->user as $row) {
//    foreach ($row as $col) {
//        echo $col . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
//    }
//    //var_dump($row);
//    echo '<br/>';
//}
//echo '<br/>';
//echo '<br/>';
//foreach ($dbData->syohinInner as $row) {
//    foreach ($row as $col) {
//        echo $col . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
//    }
//    //var_dump($row);
//    echo '<br/>';
//}
//
//$jsonData = json_decode(file_get_contents('map.json'), true);
