<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CalendariosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('calendarios');
        $this->displayField('pais_codigo');
        $this->primaryKey(['pais_codigo', 'data']);
    }
}
