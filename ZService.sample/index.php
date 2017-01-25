<?php

// Service sample logical
// Initlize    20170124    Joe

require_once 'Config/SqlDef.php';
require_once 'ZConnect/PDO.php';

$ZData = json_decode(file_get_contents("php://input"));
if (empty($ZData)) {
    echo 'No post data!';
    return;
}
//var_dump($ZData->head);
//var_dump($ZData);
//echo constant('sample.select.getData');

$con = new \ZFrame_Service\ZConnect();
$pdo = $con->_getPdo();

$userById = $con->getUserById($ZData->body);
$inner = $con->getInnerJoin();

$result = new \stdClass();
$result->user=$userById;
$result->syohinInner=$inner;

//echo '<br/>##################ServiceLine#################<br/>';
echo json_encode($result);
//echo '<br/>##################ServiceLine#################<br/>';

$con = NULL;
