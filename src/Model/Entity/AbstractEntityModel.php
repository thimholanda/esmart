<?php

namespace App\Model\Entity;

use Cake\Datasource\ConnectionManager;
use Cake\Network\Session;
use App\Model\Entity\Geral;


abstract class AbstractEntityModel {

    protected $session;
    protected $connection;
    protected $geral;

    public function __construct() {
        $this->session = new Session();
        $this->connection = ConnectionManager::get('default');
        $this->geral = new Geral();
    }

    function getLastQuery() {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }

}
