<?php

// Service sample SQL
// Initlize    20170124    Joe

define('roles.select.all', <<<SQL
    SELECT 
        ROLE_ID,
        ITEM_ID,
        DESCRIPTION,
        VALUE
    FROM
        M_ROLES
    WHERE
        ROLE_ID > 0
    ORDER BY
        ROLE_ID,
        ITEM_ID,
        VALUE
SQL
);

