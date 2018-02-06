<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class EmpresaGrupo extends Entity {

    public function findByGrupoCodigo($empresa_grupo_codigo) {
        $empresa_grupo = TableRegistry::get('EmpresaGrupos');
        $query = $empresa_grupo->find()->where(["EmpresaGrupos.empresa_grupo_codigo" => $empresa_grupo_codigo]);
        return $query->first();
    }

}
