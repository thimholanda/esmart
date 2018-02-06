<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class DocumentoMateriaisTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->displayField('empresa_codigo');
        $this->table('documento_materiais');
        $this->primaryKey(array('documento_numero', 'quarto_item', 'conta_item', 'empresa_codigo'));
        //$this->addBehavior('AuditStash.AuditLog');
    }    
}
