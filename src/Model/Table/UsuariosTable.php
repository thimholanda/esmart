<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class UsuariosTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->addAssociations([
            'belongsTo' => [
                'EmpresaGrupos' => ['foreignKey' => 'empresa_grupo_codigo'],
            ]
        ]);
        $this->table('usuarios');
        $this->displayField('usuario_codigo');
        $this->primaryKey('usuario_codigo');
    }

}
