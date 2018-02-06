<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TarifasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('tarifas');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'quarto_tipo_codigo', 'data', 'tarifa_tipo_codigo', 'adulto_quantidade']);
    }
}
