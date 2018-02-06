<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FnrhsTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('fnrhs');
        $this->primaryKey(['fnrh_codigo']);
    }
    
}
