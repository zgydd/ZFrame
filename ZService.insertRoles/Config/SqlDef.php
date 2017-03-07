<?php

// Service sample SQL
// Initlize    20170124    Joe

define('roles.insert.roleRecord', <<<SQL
    INSERT INTO M_ROLES (
        ROLE_ID,
        ITEM_ID,
        DESCRIPTION, 
        `VALUE`
    ) VALUES (
        :roleID,
        :itemID,
        :description,
        :value
    )
SQL
);

