<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class DocumentosTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->table('documentos');
        $this->displayField('empresa_codigo');
        $this->primaryKey(array('empresa_codigo', 'documento_tipo_codigo', 'documento_numero'));
        //$this->addBehavior('AuditStash.AuditLog');
    }

    /*
     * Retorna os dados de um documento pelo seu numero
     */

    public function findByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero) {
        return $this->find()->where(['documento_numero' => $documento_numero, 'empresa_codigo' => $empresa_codigo])->first();
    }

    /*
     * Altera o status de um documento pelo seu numero e empresa
     * @return total de registros alterados
     */

    public function alteraStatusByNumeroEEmpresaCodigo($empresa_codigo, $documento_numero, $status) {
        $update = $this->query()
                ->update()
                ->set(['documento_status_codigo' => $status])
                ->where(['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero, 'excluido' => 0])
                ->execute();
        return $update->rowCount();
    }

}
