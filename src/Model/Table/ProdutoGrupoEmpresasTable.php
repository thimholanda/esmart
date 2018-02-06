<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProdutoGrupoEmpresasTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('produto_empresa_grupos');
        $this->primaryKey(['empresa_grupo_codigo', 'produto_codigo']);
    }

    /*
     * Retorna os dados de um documento pelo seu numero
     */

    public function findByProdutoCodigoEEmpresaGrupoCodigo($empresa_grupo_codigo, $produto_codigo) {
        return $this->find()->where(['produto_codigo' => $produto_codigo, 'empresa_grupo_codigo' => $empresa_grupo_codigo])->first();
    }
    

}
