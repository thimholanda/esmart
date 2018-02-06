<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class FatoresTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('fatores');
        $this->displayField('fator_codigo');
        $this->primaryKey('fator_codigo');
    }
}
