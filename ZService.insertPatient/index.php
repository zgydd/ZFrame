<?php

/*
 * Service sample logical
 * 
 * V0.0.10     20170210    Joe
 * Initlize    20170124    Joe
 * 
 */

require_once 'Constant.php';
require_once 'ZConnect/PDO.php';
require_once 'log.php';

$logHandler = new CLogFileHandler("./logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

function _formatTimeStampToMS($timeStamp) {
    list($usec, $sec) = explode(".", $timeStamp);
    $date = date('Y-m-d H:i:s:x', $usec);
    return str_replace('x', $sec, $date);
}

//Enter
$ZData = file_get_contents("php://input");
if ($ZData == $_CONSTANT_TIMESTAMP_TEST_FLG) {
    //sleep(1);
    echo microtime(true);
    return;
}
$ZData = json_decode($ZData);
if (is_null($ZData) || empty($ZData)) {
    echo $_CONSTANT_MSG_NO_POST_DATA;
    return;
}
Log::DEBUG('##################ServiceLine#################');
Log::DEBUG('post data' . json_encode($ZData));
Log::DEBUG('--Start init connection at ' . _formatTimeStampToMS(microtime(TRUE)));
$con = new \ZFrame_Service\ZConnect();
Log::DEBUG('--Start get pdo at ' . _formatTimeStampToMS(microtime(TRUE)));
$pdo = $con->_getPdo();

$ZData->entity->patientId = $con->insertPatient($ZData->entity);

Log::DEBUG('--Create result at ' . _formatTimeStampToMS(microtime(TRUE)));
$result = new \stdClass();
$result->resultData = $ZData->entity;
try {
    $ZData->head->dataTo = $ZData->head->dataFrom;
    $ZData->routeLine = $ZData->head->dataFrom;
    $ZData->head->dataFrom = NULL;
} catch (Exception $ex) {
    $result->additionalMessage = $_CONSTANT_TIMESTAMP_TEST_FLG;
}
$ZData->entity = $result;

//Route Check
Log::DEBUG('result data' . json_encode($ZData));
echo json_encode($ZData);
Log::DEBUG('##################ServiceLine#################');
Log::DEBUG('--Feed back at ' . _formatTimeStampToMS(microtime(TRUE)));

$con = NULL;
