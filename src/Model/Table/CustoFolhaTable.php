<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

class CustoFolhaTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('custo_folha');
        $this->primaryKey(array('empresa_codigo', 'documento_tipo_codigo', 'documento_numero', 'quarto_item', 'conta_item', 'produto_codigo'));
    }

    public function buildRules(RulesChecker $rules) {
        return $rules;
    }
}
