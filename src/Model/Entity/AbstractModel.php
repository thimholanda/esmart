<?php
namespace App\Model\Entity;

use Cake\Datasource\ConnectionManager;
use Cake\Network\Session;

abstract class AbstractModel{
    protected $session;
    protected $connection;
    
    public function __construct() {
       $this->session = new Session();
       $this->connection = ConnectionManager::get('default');
    }
}