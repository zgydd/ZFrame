<?php

// Register logical
// Initlize    20170124    Joe
require_once 'Constant.php';

function chooseServicesRoute($serviceMark, $selectTarget) {
    $preg = "/\A(http|https|ftp)\:\/\/((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
    $result = new \stdClass();
    $result->service = $serviceMark;
    $result->url = NULL;

    //Load Libra
    if (file_exists('Libra/routeSelecter.php') && abs(filesize('Libra/routeSelecter.php')) > 0) {
        require_once 'Libra/routeSelecter.php';
        $routeRules = new \stdClass();
        $routeRules->target = $selectTarget;
        $routeRules->operator = 1;
        $routeRules->condition = $serviceMark;
        $routeSelecter = new \Lucy\Libra($routeRules);
        $result->url = $routeSelecter->selectRoute();
    } else {
        $result->url = 'UnReach';
    }

    $tmpArr = explode(':', $result->url);

    if (isset($tmpArr[2])) {
        if (!preg_match($preg, $tmpArr[0] . ':' . $tmpArr[1])) {
            $result->url = 'UnFormat IP';
        } else {
            if ($tmpArr[2] <= 0 || $tmpArr[2] > 65535) {
                $result->url = 'UnFormat Port';
            }
        }
    } else {
        
    }

    return $result;
}

//Enter
$serviceFlg = '';
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
    default :
        if (array_key_exists('servicesList', $ZData->head)) {
            $serviceFlg = $ZData->head->servicesList;
        }
        break;
}

$servicesMap = array();

if (!empty($serviceFlg) && count($serviceFlg) > 0) {
    foreach ($serviceFlg as $service) {
        array_push($servicesMap, chooseServicesRoute($service, $_CONSTANT_ROUTE_SELECT_TARGET));
    }
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

echo '<br/>##################ReisterLine#################<br/>';
echo '--Start multi_curl at ' . microtime(TRUE) . '<br/>';
$servicesHandle = array();

foreach ($servicesMap as $mapRecord) {
    echo '--Target to ' . $mapRecord->url . '<br/>';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $mapRecord->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ZData));
    array_push($servicesHandle, $ch);
}

$mh = curl_multi_init();

foreach ($servicesHandle as $handle) {
    curl_multi_add_handle($mh, $handle);
}

$active = null;
do {
    $mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

while ($active && $mrc == CURLM_OK) {
    if (curl_multi_select($mh) != -1) {
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
}
$response = array();
foreach ($servicesHandle as $handle) {
    array_push($response, curl_multi_getcontent($handle));
    curl_multi_remove_handle($mh, $handle);
}
curl_multi_close($mh);
echo '--Multi_curl closed at ' . microtime(TRUE) . '<br/>';

foreach ($response as $responseRecord) {
    echo $responseRecord;
}
echo '--Feed back at ' . microtime(TRUE) . '<br/>';
echo '<br/>##################ReisterLine#################<br/>';
