<?php

namespace App\Model\Entity;

use Cake\ORM\TableRegistry;

class Produto extends AbstractEntityModel {
    /*
     * Modificar lista técnica
     */

    public function prolismod($empresa_codigo, $pai_produto_codigo, $filho_produto_codigo, $qtd, $fator_codigo = null, $excluido = null) {
        $tecnica_lista_table = TableRegistry::get("TecnicaLista");
        $tecnica_lista = array();
        for ($n = 0; $n < sizeof($filho_produto_codigo); $n++) {
            $tecnica_lista_inserir = $tecnica_lista_table->newEntity();
            $tecnica_lista_inserir->empresa_codigo = $empresa_codigo;
            $tecnica_lista_inserir->pai_produto_codigo = $pai_produto_codigo;
            $tecnica_lista_inserir->filho_produto_codigo = $filho_produto_codigo[$n];
            $tecnica_lista_inserir->qtd = $qtd[$n];
            $tecnica_lista_inserir->fator_codigo = $fator_codigo[$n];
            $tecnica_lista_inserir->excluido = $excluido[$n];

            array_push($tecnica_lista, $tecnica_lista_inserir);
        }
        try {
            $tecnica_lista_table->saveMany($tecnica_lista);
        } catch (\Exception $e) {
            $excecao_mensagem = $e->getMessage();
            $retorno['mensagem'] = $this->geral->germencri($empresa_codigo, 154, 3, $pai_produto_codigo, null, null, $excecao_mensagem);
            $retorno['retorno'] = 0;
            return $retorno;
        }

        $retorno['mensagem'] = $this->geral->germencri($empresa_codigo, 153, 3, $pai_produto_codigo);
        $retorno['retorno'] = 1;
        return $retorno;
    }

    /*
     * Exibir lista técnica
     */

    public function prolisexi($empresa_codigo, $pai_produto_codigo, $excluido = null) {
        $excluido_criterio = "";
        if ($excluido != null)
            $excluido_criterio = " AND tl.excluido = $excluido ";

        $tecnica_listas = $this->connection->execute("SELECT 
                    peg2.nome AS filho_produto_nome,
                    tl.filho_produto_codigo AS produto_codigo,
                    qtd,
                    tl.fator_codigo,
                    variavel_fator_nome,
                    tl.excluido
                FROM
                    tecnica_lista tl
                        INNER JOIN
                    empresas e ON tl.empresa_codigo = e.empresa_codigo
                        INNER JOIN
                    produto_empresa_grupos peg ON peg.empresa_grupo_codigo = e.empresa_grupo_codigo
                        AND peg.produto_codigo = tl.pai_produto_codigo
                        INNER JOIN
                    produto_empresa_grupos peg2 ON peg2.empresa_grupo_codigo = e.empresa_grupo_codigo
                        AND peg2.produto_codigo = tl.filho_produto_codigo
                        INNER JOIN
                    fatores f ON tl.fator_codigo = f.fator_codigo
                WHERE
                    tl.empresa_codigo =:empresa_codigo
                        AND peg.produto_codigo =:pai_produto_codigo
                 $excluido_criterio ORDER BY filho_produto_nome", ['empresa_codigo' => $empresa_codigo,
                    'pai_produto_codigo' => $pai_produto_codigo])->fetchAll("assoc");

        return $tecnica_listas;
    }


}
