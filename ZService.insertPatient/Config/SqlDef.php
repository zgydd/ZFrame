<?php

// Service sample SQL
// Initlize    20170124    Joe

define('patient.insert.patientRecord', <<<SQL
    INSERT INTO M_PATIENT (
        `NAME`, 
        CURRENT_SCORE_ID
    ) VALUES (
        :patientName,
        :scoreId
    )
SQL
);

