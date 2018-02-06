<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ReservaConfirmacoesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('reserva_confirmacoes');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'reserva_confirmacao_codigo']);
    }
}