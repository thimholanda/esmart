<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

class TecnicaListaTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('tecnica_lista');
        $this->primaryKey(array('empresa_codigo', 'pai_produto_codigo', 'filho_produto_codigo'));
    }

    public function buildRules(RulesChecker $rules) {
        return $rules;
    }
}
