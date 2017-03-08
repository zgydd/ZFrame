<?php

// Service sample PDO
// Initlize    20170124    Joe

namespace ZFrame_Service;

require_once 'Config/Constant.php';
require_once 'Config/SqlDef.php';

class ZConnect {

    private $pdo;

    public function __construct() {
        $objConst = new \ZFrame_Service\CONSTANT();

        $conCfg = $objConst->getDBCon();

        $conStr = $conCfg->type . ":host=" . $conCfg->host . ":"
                . $conCfg->port . ";dbname=" . $conCfg->dbname;

        $this->pdo = new \PDO($conStr, $conCfg->user, $conCfg->pwd);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function __destruct() {
        $this->pdo = NULL;
    }

    public function _getPdo() {
        return $this->pdo;
    }

    public function _destroyPdo() {
        $this->pdo = NULL;
    }

    public function insertRole($roleRecord) {
        $stat = $this->pdo->prepare(constant("roles.insert.roleRecord"));
        return $stat->execute(array(
                    ':roleID' => $roleRecord->roleID,
                    ':role' => $roleRecord->role,
                    ':itemID' => $roleRecord->itemID,
                    ':item' => $roleRecord->item,
                    ':description' => $roleRecord->desc,
                    ':value' => $roleRecord->roleValue));
//        $record = $stat->fetchAll();
//        $stat->closeCursor();
//        return $record;
    }

}
