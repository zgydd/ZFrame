<?php

// Router logical
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
$routeFlg = '';
try {
    $ZData = json_decode(file_get_contents("php://input"));
    if (is_null($ZData) || empty($ZData)) {
        echo 'Z_MSG_NO_POSTDATA';
        return;
    }
} catch (Exception $ex) {
    echo 'err_route_001';
    return;
}
switch (true) {
    case (!array_key_exists('head', $ZData)):
        echo 'err_route_002';
        return;
    case (!array_key_exists('routeFlg', $ZData->head)):
        echo 'err_route_003';
        return;
    default :
        $routeFlg = $ZData->head->routeFlg;
        break;
}
if (empty($routeFlg)) {
    echo 'err_route_004';
    return;
}

//Choose route
$filename = 'map.json';
$requiredAddress = array();
$url = '';


if (file_exists($filename) && abs(filesize($filename)) > 0) {
    $arr = (array) json_decode(file_get_contents($filename));
    foreach ($arr as $row) {
        if ($routeFlg === $row->routeFlg) {
            array_push($requiredAddress, 'http://' . $row->builderIp . ':' . $row->builderPort);
        }
    }

    if (count($requiredAddress) === 0) {
        echo 'err_route_005';
        return;
    }
    //How to choose a route
//    foreach ($requiredAddress as $row) {
//    }
    $url = $requiredAddress[0];
} else {
    echo 'err_route_005';
    return;
}

if (empty($url)) {
    echo 'err_route_005';
    return;
}
//preg_match a Ip address
//$url = 'http://127.0.0.1:20001';
//CheckData
if (array_key_exists('dataFrom', $ZData->head)) {
    if (gettype($ZData->head->dataFrom) === 'array') {
        array_push($ZData->head->dataFrom, getLocalIP());
    } else {
//        echo 'wrn_route_001';
    }
} else {
//    echo 'wrn_route_001';
}


try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ZData));
    $response = curl_exec($ch);
    if (!$response) {
        echo 'Unreched--------------------------------';
    }

    if (curl_errno($ch)) {
        print curl_error($ch);
    }

//    //Route Check
//    echo '<br/>##################RouterLine#################<br/>';
//    echo $response;
//    echo '<br/>##################RouterLine#################<br/>';
    //In Route
    $resultZData = json_decode($response);
    array_pop($resultZData->head->dataTo);
    echo json_encode($resultZData);
} catch (Exception $ex) {
    echo 'err_route_006';
    return;
} finally {
    if (!\is_null($ch)) {
        curl_close($ch);
    }
}

