<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TarifaTiposTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('tarifa_tipos');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'tarifa_tipo_codigo']);
    }

}
