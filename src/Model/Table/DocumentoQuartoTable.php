<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class DocumentoQuartoTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->table('documento_quarto');
        $this->displayField('empresa_codigo');
        $this->primaryKey(array('empresa_codigo', 'quarto_item', 'documento_numero'));
        //$this->addBehavior('AuditStash.AuditLog');
    }

}
