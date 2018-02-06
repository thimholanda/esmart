<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class VendaCanalPagamentoFormasTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('venda_canal_pagamento_formas');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'evento', 'venda_canal_codigo', 'pagamento_forma_codigo']);
    }
}
