<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;
class DocumentoAdicionaisTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('documento_adicionais');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'documento_numero', 'adicional_codigo']);
    }
}
