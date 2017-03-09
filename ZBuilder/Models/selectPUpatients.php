<?php

require_once 'log.php';

$logHandler = new CLogFileHandler("./logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

class puPatients {

    private $url;
    private $servicesMap;

    public function __construct($url) {
        $this->url = $url;
        $this->servicesMap = ['Z_SRV_INSERT_PATIENT', 'Z_SRV_INSERT_SCORE'];
    }

    public function __destruct() {
        $this->url = NULL;
        $this->servicesMap = NULL;
    }

    public function main($data) {
        $stepData = $data;
        
        
        
        
        
        if (is_array($stepData)) {
            $stepData = $stepData[0];
        }
        return $stepData;
    }

    private function _queryCurl($data) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            if (!$response) {
                return '$this->url';
            }
            if (curl_errno($ch)) {
                print curl_error($ch);
            }
            return $response;
        } catch (Exception $ex) {
            return $ex;
        } finally {
            if (!is_null($ch)) {
                curl_close($ch);
            }
        }
    }
}
