<?php

/*
 * Constant defination for Libra
 * base 2017-02-07 Barton Joe
 */

namespace Lucy;

class LIBRA_CONSTANT {

    private $_PROTOCOL = 'http';
    private $_MAXWAITTIMESTAMP = 9.9;
    private $_TIMEOUT = 300;
    private $_MAPDEFINATION;
    private $_INFODATA;

    public function __construct() {
        $this->_MAPDEFINATION = new \stdClass();
        $this->_MAPDEFINATION->fileName = 'Libra/map.json';
        $this->_MAPDEFINATION->ipKey = 'serviceIp';
        $this->_MAPDEFINATION->portKey = 'servicePort';

        $this->_MAPDEFINATION->errUnFormatableData = 'unformatable data';
        $this->_MAPDEFINATION->errEmptyRecord = 'no route in map';
        $this->_MAPDEFINATION->errNoFile = 'no route map';
        $this->_MAPDEFINATION->errEnReachable = 'no reachble route';
        $this->_MAPDEFINATION->errEnSuitable = 'no suitable route';

        $this->_INFODATA = new \stdClass();
        $this->_INFODATA->testMsg = 'Z_TEST_TIMESTAMP';
    }

    public function __destruct() {
        $this->_PROTOCOL = NULL;
        $this->_MAXWAITTIMESTAMP = NULL;
        $this->_TIMEOUT = NULL;
        $this->_MAPDEFINATION = NULL;
        $this->_INFODATA = NULL;
    }

    public function getProtocol() {
        return $this->_PROTOCOL;
    }

    public function getMaxWaitTime() {
        return $this->_MAXWAITTIMESTAMP;
    }

    public function getTimeOut() {
        return $this->_TIMEOUT;
    }

    public function getMapDefination() {
        return $this->_MAPDEFINATION;
    }

    public function getInfoData() {
        return $this->_INFODATA;
    }

}
