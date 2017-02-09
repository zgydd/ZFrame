<?php

// Router logical
// Initlize    20170124    Joe
require_once 'Constant.php';
//Enter
$routeFlg = '';
try {
    $ZData = file_get_contents("php://input");
    if ($ZData == 'Z_TEST_TIMESTAMP') {
        //sleep(2);
        echo microtime(true);
        return;
    }
    $ZData = json_decode($ZData);
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
$preg = "/\A(http|https|ftp)\:\/\/((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
$url = NULL;

//Load Libra
if (file_exists('Libra/routeSelecter.php') && abs(filesize('Libra/routeSelecter.php')) > 0) {
    require_once 'Libra/routeSelecter.php';
    $routeRules = new \stdClass();
    $routeRules->target = $_CONSTANT_ROUTE_SELECT_TARGET;
    $routeRules->operator = 1;
    $routeRules->condition = $routeFlg;
    $routeSelecter = new \Lucy\Libra($routeRules);
    $url = $routeSelecter->selectRoute();
} else {
    //$url = 'http://127.0.0.1:5000';
}
if (is_null($url)) {
    echo 'err_route_005';
    return;
}

$tmpArr = explode(':', $url);

if (isset($tmpArr[2])) {
    if (!preg_match($preg, $tmpArr[0] . ':' . $tmpArr[1])) {
        echo $url;
        return;
    } else {
        if ($tmpArr[2] <= 0 || $tmpArr[2] > 65535) {
            echo 'err_route_999';
            return;
        }
    }
} else {
    echo 'err_route_999';
    return;
}

//preg_match a Ip address
//$url = 'http://127.0.0.1:20001';
//CheckData
if (array_key_exists('dataFrom', $ZData->head)) {
    if (gettype($ZData->head->dataFrom) === 'array') {
        array_push($ZData->head->dataFrom, _getLocalIP());
    } else {
//        echo 'wrn_route_001';
    }
} else {
//    echo 'wrn_route_001';
}


try {
    echo '<br/>##################RouterLine#################<br/>';
    echo '--Start curl at ' . microtime(TRUE) . '<br/>';
    echo '--Target to ' . $url . '<br/>';
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
    echo '--Curl closed at ' . microtime(TRUE) . '<br/>';

//    $resultZData = json_decode($response);
//    array_pop($resultZData->head->dataTo);
//    echo json_encode($resultZData);
    echo $response;
    echo '--Feed back at ' . microtime(TRUE) . '<br/>';
    echo '<br/>##################RouterLine#################<br/>';
} catch (Exception $ex) {
    echo 'err_route_006';
    return;
} finally {
    if (!is_null($ch)) {
        curl_close($ch);
    }
}

