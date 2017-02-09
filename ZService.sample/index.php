<?php

// Service sample logical
// Initlize    20170124    Joe

require_once 'Config/SqlDef.php';
require_once 'ZConnect/PDO.php';

//Enter
$ZData = file_get_contents("php://input");
if ($ZData == 'Z_TEST_TIMESTAMP') {
    //sleep(1);
    echo microtime(true);
    return;
}
$ZData = json_decode($ZData);
if (is_null($ZData) || empty($ZData)) {
    echo 'Z_MSG_NO_POSTDATA';
    return;
}

echo '<br/>##################ServiceLine#################<br/>';
echo '--Start init connection at ' . microtime(TRUE) . '<br/>';
$con = new \ZFrame_Service\ZConnect();
echo '--Start get pdo at ' . microtime(TRUE) . '<br/>';
$pdo = $con->_getPdo();
echo '--Select user at ' . microtime(TRUE) . '<br/>';
$userById = $con->getUserById($ZData->entity->body);
echo '--Get data at ' . microtime(TRUE) . '<br/>';
$inner = $con->getInnerJoin();

echo '--Create result at ' . microtime(TRUE) . '<br/>';
$result = new \stdClass();
$result->user=$userById;
$result->syohinInner=$inner;

$ZData->head->dataTo = $ZData->head->dataFrom;
$ZData->routeLine = $ZData->head->dataFrom;
$ZData->head->dataFrom = NULL;
$ZData->entity = $result;

//Route Check
echo json_encode($ZData);
echo '<br/>';
echo '--Feed back at ' . microtime(TRUE) . '<br/>';
echo '<br/>##################ServiceLine#################<br/>';

////Data Check
//echo json_encode($ZData);
////echo '<br/>';
////print_r($ZData);

$con = NULL;
