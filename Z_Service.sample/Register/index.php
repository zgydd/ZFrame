<?php

// Service Register logical
// Initlize    20170124    Joe

require_once '../Config/Constant.php';

function getLocalIP() {
    $preg = "/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
//    exec("ipconfig", $out, $stats);
//    if (!empty($out)) {
//        foreach ($out AS $row) {
//            if (strstr($row, "IP") && strstr($row, ":") && !strstr($row, "IPv6")) {
//                $tmpIp = explode(":", $row);
//                if (preg_match($preg, trim($tmpIp[1]))) {
//                    return trim($tmpIp[1]);
//                }
//            }
//        }
//    }
    exec("ifconfig", $out, $stats);
    if (!empty($out)) {
        if (isset($out[1]) && strstr($out[1], 'addr:')) {
            $tmpArray = explode(":", $out[1]);
            $tmpIp = explode(" ", $tmpArray[1]);
            if (preg_match($preg, trim($tmpIp[0]))) {
                return trim($tmpIp[0]);
            }
        }
    }
    return '127.0.0.1';
}

$objConst = new \ZFrame_Service\CONSTANT();

$myPort = $objConst->getServicePort();

$registerCenter = $objConst->getRegisterCenter();

//$localIp = getLocalIP();
$localIp = '127.0.0.1';

foreach ($registerCenter as $register) {

    $url = 'http://' . $register . ':20000';
//    $data = new stdClass();
//    $data->serviceId = 0;
//    $data->serviceName = 'sample';
//    $data->serviceIp = $localIp;
    $data = ["serviceId" => 0, "serviceName" => 'sample', "serviceIp" => $localIp];
//    echo var_dump($data);
    $ch = curl_init(); //初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        print curl_error($ch);
    }
    curl_close($ch);
}

//echo $myPort;
?>

