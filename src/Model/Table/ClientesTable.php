<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

class ClientesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('clientes');
        $this->displayField('cliente_codigo');
        $this->primaryKey(array('cliente_codigo', 'empresa_grupo_codigo'));
        //$this->addBehavior('AuditStash.AuditLog');
    }

    public function buildRules(RulesChecker $rules) {
        //$rules->add($rules->isUnique(['email']));

        return $rules;
    }

    /*
     * Retorna os dados de um cliente pelo seu codigo
     */

    public function findByClienteCodigoEEmpresaGrupoCodigo($empresa_grupo_codigo, $cliente_codigo) {
        /*  return $this->find()->where(['cliente_codigo' => $cliente_codigo, 
          'empresa_grupo_codigo' => $empresa_grupo_codigo])->all(); */

        $connection = ConnectionManager::get('default');
        return $connection->execute("SELECT * FROM clientes "
                                . " WHERE empresa_grupo_codigo= :empresa_grupo_codigo AND cliente_codigo= :cliente_codigo", ['empresa_grupo_codigo' => $empresa_grupo_codigo, 'cliente_codigo' => $cliente_codigo])
                        ->fetchAll("assoc");
    }
}
