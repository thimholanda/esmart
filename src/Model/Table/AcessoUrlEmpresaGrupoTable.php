<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class AcessoUrlEmpresaGrupoTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('acesso_url_empresa_grupo');
        $this->primaryKey(['empresa_grupo_codigo', 'controle', 'acao']);
    }

}
