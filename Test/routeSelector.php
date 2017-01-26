<?php

// RouteSelector logical
// Initlize    20170126    Joe

namespace ZFrame_Common;

require_once 'commFunc.php';

class ZRouteSelector {

    private $filename;
    private $routeMap;
    private $route;

    public function __construct() {
        $this->filename = 'map.json';
        $this->routeMap = array();
        $arr = array();
        if (file_exists($this->filename) && abs(filesize($this->filename)) > 0) {
            $arr = (array) json_decode(file_get_contents($this->filename));
            if (count($arr)) {
                foreach ($arr as $row) {
                    if (array_key_exists('serviceIp', $row) && array_key_exists('servicePort', $row)) {
                        array_push($this->routeMap, 'http://' . $row->serviceIp . ':' . $row->servicePort);
                    } else {
                        //Exception unformatable data
                    }
                }
            } else {
                //Exception no route in map
            }
        } else {
            //Exception no route map
        }
    }

    public function __destruct() {
        $this->filename = NULL;
        $this->routeMap = NULL;
        $this->route = NULL;
    }

    private function _setMyRoute($row, $minTimeStamp) {
        $start = microtime(true);
        $ret = _getRequireTimeStamp($row);
        if (!$ret) {
            //Remove this route
        } else {
            if ($minTimeStamp > ($ret - $start)) {
                $this->route = $row;
            }
        }
    }

    public function selectRoute() {
        $minTimeStamp = 9.9;
        foreach ($this->routeMap as $row) {
            $this->_setMyRoute($row, $minTimeStamp);
        }
        return $this->route;
    }
}
