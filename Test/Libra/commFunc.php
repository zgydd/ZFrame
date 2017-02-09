<?php

/*
 * splite by common function for Lucy by PHP
 * add _checkRules                  2017-02-08                  Barton Joe
 * base                             2017-02-07                  Barton Joe
 */

function _getRequireTimeStamp($url, $timeout = 300, $testMsg = 'TEST_TIMESTAMP') {
    $test_ch = curl_init();
    curl_setopt($test_ch, CURLOPT_URL, $url);
    curl_setopt($test_ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($test_ch, CURLOPT_TIMEOUT_MS, $timeout);
    curl_setopt($test_ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);

    curl_setopt($test_ch, CURLOPT_POSTFIELDS, $testMsg);
    $response = curl_exec($test_ch);
    curl_close($test_ch);
    return $response;
}

function _checkRules($record, $rules) {

    $p_rules = (array) $rules;
    $p_record = (array) $record;

    if (!array_key_exists('target', $p_rules) || !array_key_exists('operator', $p_rules) || !array_key_exists('condition', $p_rules)) {
        return 102;
    }
    if (!array_key_exists($p_rules['target'], $p_record)) {
        return 103;
    }

    switch ($p_rules['operator']) {
        case 1:
            $arrCondition = explode(',', $p_record[$p_rules['target']]);
            foreach ($arrCondition as $condition) {
                if ($p_rules['condition'] == $condition) {
                    return 1;
                }
            }
            return 201;
        default :
            return 999;
    }
}
