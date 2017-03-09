<?php

// Service sample SQL
// Initlize    20170124    Joe

define('roles.select.all', <<<SQL
    SELECT 
        M_ROLES.ID,
        M_ROLES.ROLE_ID,
        M_ROLES.ITEM_ID,
        M_ROLES.DESCRIPTION,
        M_ROLES.`VALUE`,
        dic1.`VALUE` AS ROLE,
        dic2.`VALUE` AS ITEM
    FROM
        M_ROLES
    LEFT JOIN
        M_DICTIONARY dic1 ON (dic1.`TYPE` = 1
            AND dic1.ITEM_ID = M_ROLES.ROLE_ID)
    LEFT JOIN
        M_DICTIONARY dic2 ON (dic2.`TYPE` = 2
            AND dic2.ITEM_ID = CONCAT(M_ROLES.ROLE_ID, '-', M_ROLES.ITEM_ID))
    WHERE
        ROLE_ID > 0
    ORDER BY ROLE_ID , ITEM_ID , `VALUE`
SQL
);

