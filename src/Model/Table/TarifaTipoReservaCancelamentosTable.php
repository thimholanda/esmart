<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TarifaTipoReservaCancelamentosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('tarifa_tipo_reserva_cancelamentos');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'tarifa_tipo_codigo', 'reserva_cancelamento_codigo']);
    }
}
