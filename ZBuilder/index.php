<?php

/*
 * Builder logical
 * 
 * V0.0.10     20170210    Joe
 * Initlize    20170124    Joe
 */
require_once 'Constant.php';
require_once 'commFunc.php';

//Enter
try {
    $ZData = file_get_contents("php://input");
    if ($ZData == $_CONSTANT_TIMESTAMP_TEST_FLG) {
//    sleep(1);
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

$modelFlg = '';
switch (true) {
    case (!array_key_exists('head', $ZData)):
        echo $_CONSTANT_NOTE_CODE_NO_HEAD;
        break;
    case (!array_key_exists('modelFlg', (array) $ZData->head)):
        echo $_CONSTANT_NOTE_CODE_NO_MODEL_FLG;
        break;
    default :
        $modelFlg = $ZData->head->modelFlg;
        break;
}
if (empty($modelFlg)) {
    echo $_CONSTANT_NOTE_CODE_NO_MODEL_FLG_DATA;
}

//Choose route
$url = NULL;

//Load Libra
if (file_exists('Libra/routeSelecter.php') && abs(filesize('Libra/routeSelecter.php')) > 0) {
    require_once 'Libra/routeSelecter.php';
    $routeRules = new \stdClass();
    $routeSelecter = new \Lucy\Libra();
    $url = $routeSelecter->selectRoute();
} else {
    $url = $_CONSTANT_DEFAULT_TARGET_URL;
}
if (is_null($url)) {
    echo $_CONSTANT_ERR_CODE_NO_TARGET_URL;
    return;
}

if (!_checkPostUrl($url)) {
    echo $_CONSTANT_ERR_CODE_ILLEGAL_URL;
    return;
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

try {
    echo '<br/>##################BuilderLine#################<br/>';
    echo _getLocalIP() . '<br/>';
    echo '--Start curl at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';
    echo '--Target to ' . $url . '<br/>';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ZData));
    $response = curl_exec($ch);
    if (!$response) {
        echo 'Unreched URL';
    }

    if (curl_errno($ch)) {
        print curl_error($ch);
    }
    echo '--Curl closed at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';

//    $resultZData = json_decode($response);
//    array_pop($resultZData->head->dataTo);
//    echo json_encode($resultZData);

    echo $response;
    echo '<br/>##################BuilderLine#################<br/>';
    echo '--Feed back at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';
} catch (Exception $ex) {
    echo $_CONSTANT_ERR_CODE_CURL_POST_EXCEPTION;
    return;
} finally {
    if (!is_null($ch)) {
        curl_close($ch);
    }
}

