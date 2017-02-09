<?php

// Service sample Constant
// Initlize    20170124    Joe

namespace ZFrame_Service;

class CONSTANT {

    private $_Z_DB_CON;
    private $_Z_SERVICE_PORT = "20002";
    private $_Z_REGISTER_CENTER = ['127.0.0.1'];

    public function __construct() {
        $this->_Z_DB_CON = new \stdClass();
        $this->_Z_DB_CON->type = "mysql";
        $this->_Z_DB_CON->host = "127.0.0.1";
        $this->_Z_DB_CON->port = "3306";
        $this->_Z_DB_CON->user = "root";
        $this->_Z_DB_CON->pwd = "p@55w0rd";
        $this->_Z_DB_CON->dbname = "test";
    }

    public function getDBCon() {
        return $this->_Z_DB_CON;
    }

    public function getServicePort() {
        return $this->_Z_SERVICE_PORT;
    }

    public function getRegisterCenter() {
        return $this->_Z_REGISTER_CENTER;
    }

}

