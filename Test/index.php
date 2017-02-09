<?php

//Test project
//echo '<>' date("Y-m-d H:i:s", time());
//$routeSelector = new \ZFrame_Common\ZRouteSelector();
//
//$chooseRoute = $routeSelector->selectRoute();
//
//echo '<br/>' . $chooseRoute . '<br/><br/>';
//function getRequireTimeStamp($url) {
//    $test_ch = curl_init();
//    curl_setopt($test_ch, CURLOPT_URL, $url);
//    curl_setopt($test_ch, CURLOPT_RETURNTRANSFER, 1);
//    
//    curl_setopt($test_ch, CURLOPT_TIMEOUT_MS, 300);
//    curl_setopt($test_ch, CURLOPT_CONNECTTIMEOUT_MS, 300);
//    
//    curl_setopt($test_ch, CURLOPT_POSTFIELDS, 'Z_TEST_TIMESTAMP');
//    $response = curl_exec($test_ch);
//    curl_close($test_ch);
//    return $response;
//}
//
//$urls = array('http://127.0.0.1:3000', 'http://127.0.0.1:5000', 'http://127.0.0.1:10000', 'http://127.0.0.1:20000', 'http://127.0.0.1:20001', 'http://127.0.0.1:9999');
//$minTimeStamp = 9.9;
//$choseRoute = '';
//
//$result = array();
//
//echo date("Y-m-d H:i:s", time());
//echo '<br/>';
//foreach ($urls as $row) {
//    $start = microtime(true);
//    $ret = getRequireTimeStamp($row);
//    if(!$ret){
//        //Remove this route
//    }  else {
//        if($minTimeStamp > ($ret-$start)){
//            $choseRoute = $row;
//        }
//    }
////    if (!$ret) {
////        echo 'false';
////    } else {
////        echo '<br/>' . ($ret - $start);
////    }
////    echo '<br/>#########'.$ret.'##########<br/>';
//    
//}
//echo '<br/>';
//echo date("Y-m-d H:i:s", time());
//echo '<br/>';
//echo $choseRoute;
////echo '<br/>';
////var_dump($result);

$preg = "/\A(http|https|ftp)\:\/\/((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
$url = NULL;

//Load Libra
if (file_exists('Libra/routeSelecter.php') && abs(filesize('Libra/routeSelecter.php')) > 0) {
    require_once 'Libra/routeSelecter.php';
    $routeSelecter = new \Lucy\Libra();
    $url = $routeSelecter->selectRoute();
} else {
    //$url = 'http://127.0.0.1:3000';
}
if (is_null($url)) {
    return;
}

$tmpArr = explode(':', $url);

if (isset($tmpArr[2])) {
    if (!preg_match($preg, $tmpArr[0] . ':' . $tmpArr[1])) {
        echo $url;
        return;
    } else {
        if ($tmpArr[2] <= 0 || $tmpArr[2] > 65535) {
            echo $url;
            return;
        }
    }
} else {
    echo $url;
    return;
}

$data = new stdClass();

$data->uuid = 'Z_localhost_' . microtime(true);

$data->head = new stdClass();
$data->head->routeFlg = 'Z_ROUTE_1';
$data->head->modelFlg = 'Z_MODEL_0';
$data->head->servicesList = array('Z_SRV_0', 'Z_SRV_1');
$data->head->dataFrom = array('localhost');
$data->head->dataTo = NULL;

$data->entity = new stdClass();
$data->entity->body = '1';

$data = json_encode($data);
echo 'Post data<br/>';
var_dump($data);
echo '<br/>##################IndexLine#################<br/>';
echo '--Start curl at ' . microtime(TRUE) . '<br/>';
echo '--Target to ' . $url . '<br/>';
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
echo '--Curl closed at ' . microtime(TRUE) . '<br/>';
echo $response;
//switch ($response) {
//    case 'err_route_001':
//    case 'err_route_002':
//    case 'err_route_003':
//    case 'err_route_004':
//    case 'err_route_005':
//    case 'err_route_006':
//    case 'err_route_999':
//        echo $response;
//        break;
//    default :
//        echo $response;
////        $dbData = json_decode($response);
////
////        var_dump($dbData->head);
////        echo '<br/>';
////        foreach ($dbData->entity->user as $row) {
////            foreach ($row as $col) {
////                echo $col . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
////            }
////            //var_dump($row);
////            echo '<br/>';
////        }
////        echo '<br/>';
////        foreach ($dbData->entity->syohinInner as $row) {
////            foreach ($row as $col) {
////                echo $col . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
////            }
////            //var_dump($row);
////            echo '<br/>';
////        }
////        echo '<br/>';
////        var_dump($dbData->routeLine);
////        break;
//}
//Data Check
echo '--Feed back at ' . microtime(TRUE) . '<br/>';
echo '<br/>##################IndexLine#################<br/>';

