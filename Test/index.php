<?php

//Test project

function getRequireTimeStamp($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

$urls = array('http://127.0.0.1:3000', 'http://127.0.0.1:5000', 'http://127.0.0.1:10000', 'http://127.0.0.1:20000', 'http://127.0.0.1:20001', 'http://127.0.0.1:9999');

$result = array();

echo date("Y-m-d H:i:s", time());
echo '<br/>';
foreach ($urls as $row) {
    $start = microtime();
    $ret = getRequireTimeStamp($row);
    $end = microtime();
    if($ret && $ret === 'Z_MSG_NO_POSTDATA'){
        array_push($result, ["timestamp"=>($end-$start),"host"=>$row]);
    }  else {
//        array_push($result, $ret);        
    }   
}
echo '<br/>';
echo date("Y-m-d H:i:s", time());
echo '<br/>';
var_dump($result);



/*
$url = 'http://127.0.0.1:3000';

$data = new stdClass();

$data->uuid = 'Z_localhost_' . time();

$data->head = new stdClass();
$data->head->routeFlg = 'Z_ROUTE_1';
$data->head->modelFlg = 'Z_MODEL_0';
$data->head->servicesList = array();
$data->head->dataFrom = array('localhost');
$data->head->dataTo = NULL;

$data->entity = new stdClass();
$data->entity->body = '1';

$data = json_encode($data);

echo var_dump($data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    print curl_error($ch);
}
curl_close($ch);
echo '<br/>##################IndexLine#################<br/>';
//echo $response;
switch ($response) {
    case 'err_route_001':
    case 'err_route_002':
    case 'err_route_003':
    case 'err_route_004':
    case 'err_route_005':
    case 'err_route_006':
        echo $response;
        break;
    default :
//        var_dump($response);
        $dbData = json_decode($response);

        var_dump($dbData->head);
        echo '<br/>';
        foreach ($dbData->entity->user as $row) {
            foreach ($row as $col) {
                echo $col . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            //var_dump($row);
            echo '<br/>';
        }
        echo '<br/>';
        foreach ($dbData->entity->syohinInner as $row) {
            foreach ($row as $col) {
                echo $col . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            //var_dump($row);
            echo '<br/>';
        }
        echo '<br/>';
        var_dump($dbData->routeLine);
        break;
}
//Data Check
echo '<br/>##################IndexLine#################<br/>';
*/
