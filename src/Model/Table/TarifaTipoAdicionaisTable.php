<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TarifaTipoAdicionaisTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('tarifa_tipo_adicionais');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'tarifa_tipo_codigo', 'adicional_codigo']);
    }
}
