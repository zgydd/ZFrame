<?php

// Service sample SQL
// Initlize    20170124    Joe

define('sample.select.getData', <<<SQL
    SELECT * FROM M_USER WHERE id = :id
SQL
);

define('sample.select.getInner', <<<SQL
    SELECT
        M_USER.name AS uName,
        M_USER.value AS uValue,
        M_PRODUCT.name as pName,
        M_PRODUCT.value AS pValue
    FROM
        M_PRODUCT
    INNER JOIN
        M_USER ON (M_PRODUCT.user_id = M_USER.id)
SQL
);
?>