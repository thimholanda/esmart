<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

class DocumentoClientesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('documento_clientes');
        $this->displayField('empresa_codigo');
        $this->primaryKey(array('empresa_codigo', 'documento_numero', 'quarto_item', 'papel_codigo', 'cliente_item'));
        //$this->addBehavior('AuditStash.AuditLog');
    }

    /*
     * Retorna os dados dos clientes hospedes
     */

    public function findHospedesByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero, $quarto_item) {
        $connection = ConnectionManager::get('default');
        return $connection->execute("SELECT DISTINCT(c.cliente_codigo), c.*, dc.fnrh_codigo, dc.cliente_item FROM clientes c"
                        . " INNER JOIN documento_clientes dc ON dc.cliente_codigo = c.cliente_codigo AND dc.papel_codigo = 'hs' "
                        . " WHERE dc.empresa_codigo=:empresa_codigo AND dc.documento_numero=:documento_numero AND dc.quarto_item = :quarto_item "
                . "AND (dc.excluido IS NULL OR dc.excluido = 0) ", ['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero, 'quarto_item' => $quarto_item])
                
                ->fetchAll("assoc");
    }
    
    /*
     * Retorna os dados dos clientes pagantes
     */

    public function findPagantesByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero) {
        $connection = ConnectionManager::get('default');
        return $connection->execute("SELECT DISTINCT(c.cliente_codigo), c.* FROM clientes c"
                        . " INNER JOIN documento_clientes dc ON dc.cliente_codigo = c.cliente_codigo AND dc.papel_codigo = 'pg'"
                        . " WHERE dc.empresa_codigo=:empresa_codigo AND dc.documento_numero=:documento_numero AND (dc.excluido IS NULL OR dc.excluido = 0)", ['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero])
                ->fetchAll("assoc");
    }
    
    /*
     * Retorna os dados dos contratantes pagantes
     */

    public function findContratantesByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero) {
        $connection = ConnectionManager::get('default');
        return $connection->execute("SELECT DISTINCT(c.cliente_codigo), c.* FROM clientes c"
                        . " INNER JOIN documento_clientes dc ON dc.cliente_codigo = c.cliente_codigo AND dc.papel_codigo = 'ct'"
                        . " WHERE dc.empresa_codigo=:empresa_codigo AND dc.documento_numero=:documento_numero AND (dc.excluido IS NULL OR dc.excluido = 0)", ['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero])
                ->fetchAll("assoc");
    }
    
     /*
     * Retorna os dados do contratante da hospedagem
     */
    public function findContratanteByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero) {
        $connection = ConnectionManager::get('default');
        return $connection->execute("SELECT c.nome, c.sobrenome, c.email FROM clientes c"
                        . " INNER JOIN documento_clientes dc ON dc.cliente_codigo = c.cliente_codigo AND dc.papel_codigo = 'ct'"
                        . " WHERE dc.empresa_codigo=:empresa_codigo AND dc.documento_numero=:documento_numero AND (dc.excluido IS NULL OR dc.excluido = 0)", ['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero])
                ->fetchAll("assoc");
    }
    
    /*
     * Retorna os dados dos clientes hospedes
     */

    public function findMaxClienteItemByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero) {
        $connection = ConnectionManager::get('default');
        return $connection->execute("SELECT Max(cliente_item) as max_item FROM documento_clientes dc"
                        . " WHERE dc.empresa_codigo=:empresa_codigo AND dc.documento_numero=:documento_numero", ['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero])
                ->fetchAll("assoc");
    }
    
    /*
     * Retorna os dados dos clientes hospedes
     */

    public function findByFnrhCodigo($fnrh_codigo) {
        $connection = ConnectionManager::get('default');
        return $connection->execute("SELECT * FROM documento_clientes dc"
                        . " WHERE fnrh_codigo = $fnrh_codigo", ['fnrh_codigo' => $fnrh_codigo])
                ->fetchAll("assoc");
    }

}
