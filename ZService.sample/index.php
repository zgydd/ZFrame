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
//$ZData = json_decode(file_get_contents("php://input"));
//if (empty($ZData)) {
//    echo 'No post data!';
//    return;
//}

$con = new \ZFrame_Service\ZConnect();
$pdo = $con->_getPdo();

$userById = $con->getUserById($ZData->entity->body);
$inner = $con->getInnerJoin();

$result = new \stdClass();
$result->user=$userById;
$result->syohinInner=$inner;

$ZData->head->dataTo = $ZData->head->dataFrom;
$ZData->routeLine = $ZData->head->dataFrom;
$ZData->head->dataFrom = NULL;
$ZData->entity = $result;

////Route Check
//echo '<br/>##################ServiceLine#################<br/>';
//echo var_dump($result);
//echo '<br/>##################ServiceLine#################<br/>';

//Data Check
echo json_encode($ZData);
//echo '<br/>';
//print_r($ZData);

$con = NULL;
