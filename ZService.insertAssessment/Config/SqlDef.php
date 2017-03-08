<?php

// Service sample SQL
// Initlize    20170124    Joe

define('assessment.insert.assessmentRecord', <<<SQL
    INSERT INTO T_ASSESSMENT (
        USER_ID,
        ROLE_ID,
        SCORE_ID,
        ITEM_ID,
        ASSESSMENT
    ) VALUES (
        :patientId,
        :roleId,
        :currentScoreId,
        :itemID,
        :score
    )
SQL
);

