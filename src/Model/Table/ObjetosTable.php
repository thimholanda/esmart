<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ObjetosTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('objetos');
        $this->displayField('objeto_codigo');
        $this->primaryKey('objeto_codigo');
    }
}
