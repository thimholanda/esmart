<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

class DocumentoPartidasTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->table('documento_partidas');
        $this->primaryKey(array('empresa_codigo', 'documento_numero', 'quarto_item', 'partida_item'));
        //$this->addBehavior('AuditStash.AuditLog');
    }

    /*
     * Retorna os dados das parcelas
     */

    public function findMaxPartidaItem($empresa_codigo, $documento_numero) {
        $connection = ConnectionManager::get('default');
        $query = $connection->execute("SELECT COALESCE(MAX(partida_item), 0) as max_partida_item FROM documento_partidas dp WHERE "
                        . " dp.empresa_codigo=:empresa_codigo AND dp.documento_numero=:documento_numero", ['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero])
                ->fetchAll("assoc");
        return $query[0]['max_partida_item'];
    }

    public function findPartidasByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero) {
        $connection = ConnectionManager::get('default');
        return $connection->execute("SELECT * FROM documento_partidas dp WHERE "
                                . " dp.empresa_codigo=:empresa_codigo AND dp.documento_numero=:documento_numero", ['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero])
                        ->fetchAll("assoc");
    }

}
