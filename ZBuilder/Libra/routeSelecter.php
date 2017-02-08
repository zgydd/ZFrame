<?php

/*
 * Libra: Choose a route from map JSON by response
 * base 2017-02-07 Barton Joe
 */

namespace Lucy;

require_once 'commFunc.php';
require_once 'Constant.php';

class Libra {

    private $config;
    private $filename;
    private $routeMap;
    private $route;

    public function __construct() {
        $selectRules = NULL;
        if (func_num_args() > 0) {
            $selectRules = func_get_arg(0);
        }

        $needRules = (!is_null($selectRules) && !empty($selectRules));

        $this->config = new \Lucy\LIBRA_CONSTANT();
        $mapData = $this->config->getMapDefination();
        $this->filename = $mapData->fileName;
        $this->routeMap = array();
        $arr = array();
        if (file_exists($this->filename) && abs(filesize($this->filename)) > 0) {
            $arr = (array) json_decode(file_get_contents($this->filename));
            if (count($arr)) {
                foreach ($arr as $row) {
                    $tmpRecord = (array) $row;
                    if (array_key_exists($mapData->ipKey, $tmpRecord) && array_key_exists($mapData->portKey, $tmpRecord)) {
                        if ($needRules && _checkRules($row, $selectRules) > 100) {
                            continue;
                        }
                        array_push($this->routeMap, $this->config->getProtocol() . '://' . $tmpRecord[$mapData->ipKey] . ':' . $tmpRecord[$mapData->portKey]);
                    } else {
                        //Exception unformatable data
                        $this->route = $mapData->errUnFormatableData;
                    }
                }
            } else {
                //Exception no route in map
                $this->route = $mapData->errEmptyRecord;
            }
        } else {
            //Exception no route map
            $this->route = $mapData->errNoFile;
        }
    }

    public function __destruct() {
        $this->config = NULL;
        $this->filename = NULL;
        $this->routeMap = NULL;
        $this->route = NULL;
    }

    private function _setMyRoute($row, $minTimeStamp) {
        $start = microtime(true);
        $ret = (float) _getRequireTimeStamp($row, $this->config->getTimeOut(), $this->config->getInfoData()->testMsg);
        if (!$ret) {
            //Remove this route
        } else {
            if ($minTimeStamp > ($ret - $start)) {
                $this->route = $row;
                $minTimeStamp = ($ret - $start);
            }
        }
        if (is_null($this->route) || empty($this->route)) {
            //Exception unformatable data
            $this->route = $this->config->getMapDefination()->errEnReachable;
        }
        return $minTimeStamp;
    }

    public function selectRoute() {
        $minTimeStamp = $this->config->getMaxWaitTime();
        foreach ($this->routeMap as $row) {
            $minTimeStamp = $this->_setMyRoute($row, $minTimeStamp);
        }
        if (is_null($this->route) || empty($this->route)) {
            //Exception unformatable data
            $this->route = $this->config->getMapDefination()->errEnSuitable;
        }
        return $this->route;
    }

//    public function selectMultiRoute() {
//        $masterMultiCurl = curl_multi_init();
//        $waitTimeout = $this->config->getTimeOut();
////        $minTimeStamp = $this->config->getMaxWaitTime();
////        $testMsg = $this->config->getInfoData()->testMsg;
//
//        $handles = array();
//
//        foreach ($this->routeMap as $row) {
//            $test_ch = curl_init();
//            curl_setopt($test_ch, CURLOPT_URL, $row);
//            curl_setopt($test_ch, CURLOPT_RETURNTRANSFER, 1);
//
//            curl_setopt($test_ch, CURLOPT_TIMEOUT_MS, $waitTimeout);
//            curl_setopt($test_ch, CURLOPT_CONNECTTIMEOUT_MS, $waitTimeout);
//
//            //curl_setopt($test_ch, CURLOPT_POSTFIELDS, $testMsg);
//
//            curl_multi_add_handle($masterMultiCurl, $test_ch);
//            array_push($handles, $test_ch);
//        }
//        $active = NULL;
//        do {
//            $mrc = curl_multi_exec($masterMultiCurl, $active);
//        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//
//        while ($active && $mrc == CURLM_OK) {
//            if (curl_multi_select($masterMultiCurl) != -1) {
//                do {
//                    $mrc = curl_multi_exec($masterMultiCurl, $active);
//                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//            }
//        }
//
//        foreach ($handles as $value) {
//            curl_multi_remove_handle($masterMultiCurl, $value);
//        }
//
//        curl_multi_close($masterMultiCurl);
//
//        return $this->route;
//    }
}
