<?php

/*
 * common function for Lucy by PHP(splited)
 * add _formatTimeStampToMS         2017-02-10                  Barton Joe
 * add _checkPostUrl                2017-02-10                  Barton Joe
 * add _checkRules                  2017-02-08                  Barton Joe
 * base                             2017-02-07                  Barton Joe
 */

function _checkPostUrl($url) {
    //https://255.255.255.255:65535
    $preg = "/\A(http|https|ftp)\:\/\/((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";

    $tmpArr = explode(':', $url);

    switch (TRUE) {
        case (isset($tmpArr[2])):
            if (!preg_match($preg, $tmpArr[0] . ':' . $tmpArr[1])) {
                return FALSE;
            }
            if ($tmpArr[2] <= 0 || $tmpArr[2] > 65535) {
                return FALSE;
            }
            break;
        case (isset($tmpArr[1])):
            if (!preg_match($preg, $tmpArr[0] . ':' . $tmpArr[1])) {
                return FALSE;
            }
            break;
        default:
            return FALSE;
    }
    return TRUE;
}

function _formatTimeStampToMS($timeStamp) {
    list($usec, $sec) = explode(".", $timeStamp);
    $date = date('Y-m-d H:i:s:x', $usec);
    return str_replace('x', $sec, $date);
}
