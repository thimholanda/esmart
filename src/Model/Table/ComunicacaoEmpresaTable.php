<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ComunicacaoEmpresaTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('comunicacao_empresa');
        $this->primaryKey(['empresa_codigo', 'comunicacao_tipo_codigo', 'comunicacao_meio_codigo']);
    }

}
