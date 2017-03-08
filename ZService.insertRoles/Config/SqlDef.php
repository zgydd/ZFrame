<?php

// Service sample SQL
// Initlize    20170124    Joe

define('roles.insert.roleRecord', <<<SQL
    INSERT INTO M_ROLES (
        ROLE_ID,
        ROLE,
        ITEM_ID,
        ITEM,
        DESCRIPTION, 
        `VALUE`
    ) VALUES (
        :roleID,
        :role,
        :itemID,
        :item,
        :description,
        :value
    )
SQL
);

