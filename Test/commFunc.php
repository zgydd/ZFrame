<?php

// RouteSelector logical
// Initlize    20170126    Joe

function _getRequireTimeStamp($url) {
    $test_ch = curl_init();
    curl_setopt($test_ch, CURLOPT_URL, $url);
    curl_setopt($test_ch, CURLOPT_RETURNTRANSFER, 1);
    
    curl_setopt($test_ch, CURLOPT_TIMEOUT_MS, 300);
    curl_setopt($test_ch, CURLOPT_CONNECTTIMEOUT_MS, 300);
    
    curl_setopt($test_ch, CURLOPT_POSTFIELDS, 'Z_TEST_TIMESTAMP');
    $response = curl_exec($test_ch);
    curl_close($test_ch);
    return $response;
}

