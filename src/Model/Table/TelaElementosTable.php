<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class TelaElementosTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->belongsTo('Elementos', [
            'foreignKey' => 'elemento_codigo',
            'alias' => 'EL'
        ]);
        $this->table('tela_elementos');
        $this->displayField('tela_codigo');
        $this->primaryKey(['tela_codigo', 'elemento_codigo', 'empresa_grupo_codigo']);
    }

}
