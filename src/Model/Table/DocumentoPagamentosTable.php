<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class DocumentoPagamentosTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->displayField('empresa_codigo');
        $this->table('documento_pagamentos');
        $this->primaryKey(array('empresa_codigo', 'documento_numero','quarto_item', 'conta_item', 'pagamento_forma_codigo'));
        //$this->addBehavior('AuditStash.AuditLog');
    }    
}
