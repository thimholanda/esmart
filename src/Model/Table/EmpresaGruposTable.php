<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class EmpresaGruposTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->addAssociations([
            'hasOne' => [
                'Usuarios' => ['foreignKey' => 'empresa_grupo_codigo'],
            ]
        ]);
        $this->table('empresa_grupos');
        $this->displayField('empresa_grupo_codigo');
        $this->primaryKey('empresa_grupo_codigo');
    }

}
