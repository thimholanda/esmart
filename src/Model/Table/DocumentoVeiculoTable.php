<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

class DocumentoVeiculoTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('documento_veiculo');
        $this->primaryKey(array('empresa_codigo', 'documento_numero', 'quarto_item', 'veiculo_item'));
    }

    public function buildRules(RulesChecker $rules) {
        return $rules;
    }

}
