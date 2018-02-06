<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

class DocumentoContasTabelaTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->displayField('empresa_codigo');
        $this->table('documento_contas');
        $this->primaryKey(array('documento_numero', 'quarto_item', 'conta_item', 'empresa_codigo'));
        //$this->addBehavior('AuditStash.AuditLog');
    }

    public function findItensReferenciam($empresa_codigo, $documento_numero, $quarto_item, $conta_item) {
        return $this->find()->where(['documento_numero' => $documento_numero, 'empresa_codigo' => $empresa_codigo, 'quarto_item' => $quarto_item, 'referenciado_item' => $conta_item])->all();
    }

    /*
     * Retorna os dados dos adicionais
     */

    public function findAdicionaisByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero) {
        $connection = ConnectionManager::get('default');
        $empresa_grupo_codigo = $connection->execute("SELECT empresa_grupo_codigo FROM empresas WHERE empresa_codigo = :empresa_codigo", ['empresa_codigo' => $empresa_codigo])->fetchAll('assoc')[0]['empresa_grupo_codigo'];

        return $connection->execute("SELECT DISTINCT(dm.produto_codigo) , dm.produto_qtd, dc.documento_numero, dc.quarto_item, dc.total_valor as total_valor,"
                                . " peg.nome as nome, f.preco_fator_nome FROM documento_contas dc INNER JOIN documento_materiais dm "
                                . "ON dc.empresa_codigo=dm.empresa_codigo AND dc.conta_item=dm.conta_item AND dc.documento_numero = dm.documento_numero AND dc.quarto_item=dm.quarto_item "
                                . " INNER JOIN produto_empresa_grupos peg ON peg.produto_codigo = dm.produto_codigo AND peg.empresa_grupo_codigo = :empresa_grupo_codigo "
                                . " INNER JOIN fatores f ON peg.preco_fator_codigo = f.fator_codigo "
                                . "INNER JOIN produto_empresas pe ON pe.empresa_codigo=dm.empresa_codigo AND dm.produto_codigo = pe.produto_codigo "
                                . " WHERE dc.empresa_codigo=:empresa_codigo AND dc.documento_numero=:documento_numero"
                                . " AND pe.empresa_codigo=:empresa_codigo AND peg.adicional = 1", ['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero, 'empresa_grupo_codigo' => $empresa_grupo_codigo])
                        ->fetchAll("assoc");
    }

    public function findPagamentoByEmpresaDocumentoItem($empresa_codigo, $documento_numero, $conta_item) {
        $connection = ConnectionManager::get('default');

        return $connection->execute("SELECT * FROM documento_contas dc INNER JOIN documento_pagamentos dp "
                                . "ON dc.empresa_codigo=dp.empresa_codigo AND dc.conta_item=dp.conta_item AND dc.documento_numero = dp.documento_numero "
                                . " WHERE dc.empresa_codigo=:mpresa_codigo AND dc.documento_numero=:documento_numero"
                                . " AND dc.empresa_codigo=:empresa_codigo AND dp.conta_item = :conta_item", ['empresa_codigo' => $empresa_codigo, 'documento_numero' => $documento_numero, 'conta_item' => $conta_item])
                        ->fetchAll("assoc")[0];
    }

}
