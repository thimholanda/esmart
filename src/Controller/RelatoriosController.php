<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Controller\PagesController;
use Cake\Datasource\ConnectionManager;

class RelatoriosController extends AppController {

    private $pagesController;
    private $connection;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->pagesController = new PagesController();
        $this->connection = ConnectionManager::get('redshift');
    }

    public function relatorio() {
        $info_tela = $this->pagesController->montagem_tela('gertelpri');
        $busca_receitas_mes_produto = $this->connection->execute("select d.no_nome_mes, p.no_produto, sum(vl_total_item_praticado) 
                                from dw_pms.f_item_conta ic inner join dw_pms.d_produto p on ic.d_produto_item = p.cd_produto inner join dw_pms.d_data d on ic.d_data_competencia = d.cd_data
                                where ic.cd_documento_conta_pms not in (901)
                                group by d.no_nome_mes, p.no_produto")->fetchAll("assoc");
        
        //Coloca o mes como chave
        $receitas_por_mes_produto = array();
        foreach($busca_receitas_mes_produto as $receita_produto){
            $receitas_por_mes_produto[$receita_produto['no_nome_mes']][] = $receita_produto;
        }

        $receitas_por_mes = $this->connection->execute("select d.no_nome_mes, sum(vl_total_item_praticado) 
                                from dw_pms.f_item_conta ic inner join dw_pms.d_produto p on ic.d_produto_item = p.cd_produto inner join dw_pms.d_data d on ic.d_data_competencia = d.cd_data
                                where ic.cd_documento_conta_pms not in (901)
                                group by d.no_nome_mes")->fetchAll("assoc");

        $receitas_por_produto = $this->connection->execute("select p.no_produto, sum(vl_total_item_praticado) 
                                from dw_pms.f_item_conta ic inner join dw_pms.d_produto p on ic.d_produto_item = p.cd_produto inner join dw_pms.d_data d on ic.d_data_competencia = d.cd_data
                                where ic.cd_documento_conta_pms not in (901)
                                group by p.no_produto")->fetchAll("assoc");

        $this->set('receitas_por_mes_produto', $receitas_por_mes_produto);
        $this->set('receitas_por_mes', $receitas_por_mes);
        $this->set('receitas_por_produto', $receitas_por_produto);

        $this->set($info_tela);
    }

}
