<?php

/*
 * Register logical
 * 
 * V0.0.10     20170210    Joe
 * Initlize    20170124    Joe
 * 
 */
require_once 'Constant.php';
require_once 'commFunc.php';
require_once 'log.php';

$logHandler = new CLogFileHandler("./logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

//Enter
$serviceFlg = '';
try {
    $ZData = file_get_contents("php://input");
    if ($ZData == $_CONSTANT_TIMESTAMP_TEST_FLG) {
        //sleep(2);
        echo microtime(true);
        return;
    }
    $ZData = json_decode($ZData);
    if (is_null($ZData) || empty($ZData)) {
        echo $_CONSTANT_MSG_NO_POST_DATA;
        return;
    }
} catch (Exception $ex) {
    echo $_CONSTANT_ERR_CODE_EXCEPTION_POST_DATA;
    return;
}

switch (true) {
    case (!property_exists($ZData, 'head') || !array_key_exists('head', $ZData)):
        echo $_CONSTANT_ERR_CODE_NO_HEAD;
        return;
    case (!array_key_exists('servicesList', $ZData->head)):
        echo $_CONSTANT_ERR_CODE_NO_SERVICE_LIST;
        return;
    default :
        $serviceFlg = $ZData->head->servicesList;
        break;
}
if (empty($serviceFlg) || count($serviceFlg) <= 0) {
    echo $_CONSTANT_ERR_CODE_NO_SERVICE_LIST_DATA;
    return;
}

$servicesMap = array();
foreach ($serviceFlg as $service) {
    array_push($servicesMap, _chooseServicesRoute_($service, $_CONSTANT_ROUTE_SELECT_TARGET));
}
//CheckData
try {
    if (array_key_exists('dataFrom', $ZData->head)) {
        if (gettype($ZData->head->dataFrom) === 'array') {
            array_push($ZData->head->dataFrom, _getLocalIP());
        } else {
            echo $_CONSTANT_WRN_CODE_ILLEGAL_FROM_PATH;
        }
    } else {
        echo $_CONSTANT_WRN_CODE_NO_FROM_DATA;
    }
} catch (Exception $ex) {
    echo $_CONSTANT_WRN_CODE_CANT_PUSH_PATH;
}

Log::DEBUG('##################ReisterLine#################');
Log::DEBUG('post data' . json_encode($ZData));
//echo _getLocalIP() . '<br/>';
Log::DEBUG('--Create multi_curl\'s handle at ' . _formatTimeStampToMS(microtime(TRUE)));
$servicesHandle = array();

foreach ($servicesMap as $mapRecord) {
    if (!property_exists($mapRecord, 'url')) {
        Log::DEBUG('XXXXXXXXXXXXX--No property \"url\" in map record!');
        continue;
    }
    if (!_checkPostUrl($mapRecord->url)) {
        Log::DEBUG('XXXXXXXXXXXXX--Unformatable \"url\"(' . $mapRecord->url . ')');
        continue;
    }
    Log::DEBUG('--Target to ' . $mapRecord->url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $mapRecord->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ZData));
    array_push($servicesHandle, $ch);
}

Log::DEBUG('--Start multi_curl at ' . _formatTimeStampToMS(microtime(TRUE)));
$multiCurlMasterHandle = curl_multi_init();

foreach ($servicesHandle as $handle) {
    curl_multi_add_handle($multiCurlMasterHandle, $handle);
}

$active = null;
do {
    $multiCurlExecResult = curl_multi_exec($multiCurlMasterHandle, $active);
} while ($multiCurlExecResult == CURLM_CALL_MULTI_PERFORM);

while ($active && $multiCurlExecResult == CURLM_OK) {
    if (curl_multi_select($multiCurlMasterHandle) != -1) {
        do {
            $multiCurlExecResult = curl_multi_exec($multiCurlMasterHandle, $active);
        } while ($multiCurlExecResult == CURLM_CALL_MULTI_PERFORM);
    }
}
$response = array();
foreach ($servicesHandle as $handle) {
    array_push($response, curl_multi_getcontent($handle));
    curl_multi_remove_handle($multiCurlMasterHandle, $handle);
}
curl_multi_close($multiCurlMasterHandle);
Log::DEBUG('--Multi_curl closed at ' . _formatTimeStampToMS(microtime(TRUE)));
Log::DEBUG('result data' . json_encode($response));
echo json_encode($response);
//foreach ($response as $responseRecord) {
//    echo $responseRecord;
//}
Log::DEBUG('##################ReisterLine#################');
Log::DEBUG('--Feed back at ' . _formatTimeStampToMS(microtime(TRUE)));
