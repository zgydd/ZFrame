<?php

require_once 'log.php';

$logHandler = new CLogFileHandler("./logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

class admission {

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
        $stepData->entity->currentScoreId = $this->_getRandomStr(25);
        for ($i = 0; $i < count($this->servicesMap); $i++) {
            if (is_string($stepData)) {
                $stepData = json_decode($stepData);
            }
            if (is_array($stepData)) {
                $stepData = json_decode($stepData[0]);
            }
            $stepData->head->servicesList = array($this->servicesMap[$i]);
            if (is_null($stepData->head->dataFrom)) {
                $stepData->head->dataFrom = $stepData->head->dataTo;
                $stepData->head->dataTo = NULL;
            }
            $stepData = json_decode($this->_queryCurl($stepData));
        }
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

    private function _getRandomStr($len) {
        $result = '';
        $seedStr = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_#');
        for ($i = 0; $i < $len; $i++) {
            $radNum = rand(0, 64);
            $result .= substr($seedStr, $radNum, 1);
        }
        return $result;
    }

}
