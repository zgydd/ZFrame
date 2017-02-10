<?php

/*
 * Service sample001 logical
 *
 * V0.0.10     20170210    Joe
 * Initlize    20170124    Joe
 *
 */

require_once 'Constant.php';
require_once 'Config/SqlDef.php';
require_once 'ZConnect/PDO.php';

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

echo '<br/>##################ServiceLine#################<br/>';
echo '--Start init connection at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';
$con = new \ZFrame_Service\ZConnect();
echo '--Start get pdo at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';
$pdo = $con->_getPdo();
echo '--Select user at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';
$userById = $con->getUserById($ZData->entity->body);
echo '--Get data at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';
$inner = $con->getInnerJoin($ZData->entity->body);

echo '--Create result at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';
$result = new \stdClass();
$result->user = $userById;
$result->syohinInner = $inner;

try {
	$ZData->head->dataTo = $ZData->head->dataFrom;
	$ZData->routeLine = $ZData->head->dataFrom;
	$ZData->head->dataFrom = NULL;
} catch (Exception $ex) {
	$result->additionalMessage = $_CONSTANT_TIMESTAMP_TEST_FLG;
}
$ZData->entity = $result;

//Route Check
echo json_encode($ZData);
echo '<br/>##################ServiceLine#################<br/>';
echo '--Feed back at ' . _formatTimeStampToMS(microtime(TRUE)) . '<br/>';

$con = NULL;
