<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Utility\Util;
use Cake\Datasource\ConnectionManager;

class DocumentosController extends AppController {

    private $pagesController;
    private $connection;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->connection = ConnectionManager::get('default');
        $this->pagesController = new PagesController();
    }

    public function docpdrcri() {
        $info_tela = $this->pagesController->montagem_tela('docpdrcri');
        //Busca a lista de quartos
        $dados_quartos = $this->connection->execute("SELECT q.quarto_codigo, qt.quarto_tipo_curto_nome FROM quartos q INNER JOIN quarto_tipos qt ON q.empresa_codigo = qt.empresa_codigo"
                        . " AND q.quarto_tipo_codigo = qt.quarto_tipo_codigo WHERE q.empresa_codigo = :empresa_codigo ORDER BY q.quarto_codigo", ['empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo']])->fetchAll("assoc");
        $quarto_por_tipo = array();
        foreach ($dados_quartos as $quarto_dado) {
            $quarto_por_tipo[$quarto_dado['quarto_codigo']] = $quarto_dado['quarto_tipo_curto_nome'];
        }
        $this->set('resblomot_list', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                    'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'],
                    'motivo_tipo_codigo' => "'bc'")));
        $this->set('gerdommot_list', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                    'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'],
                    'motivo_tipo_codigo' => "'mb'")));
        $this->set('quarto_por_tipo', $quarto_por_tipo);
        $this->set('gerdomsta_list', $this->geral->gercamdom('resdocsta', 'bc'));
        $this->set('gerdoctip_list', $this->geral->gercamdom('gerdoctip', "rs,ct,cc,ca,cf,ms", "<>"));
        $status_manutencao_bloqueio = $this->geral->gercamdom('resdocsta', 'mb');

        usort($status_manutencao_bloqueio, function($a, $b) {
            return $a['valor'] <=> $b['valor'];
        });
        $this->set('gerdomsta_mb_list', $status_manutencao_bloqueio);
        $this->set('inicial_padrao_horario', $this->session->read('inicial_padrao_horario'));
        $this->set('final_padrao_horario', $this->session->read('final_padrao_horario'));
        $this->set('serquacod', $this->request->data['quarto_codigo'][0]);
        $this->set('padrao_valor_gertiptit', 'mb');
        $this->set('serinidat', Util::convertDataDMY($this->request->data['inicial_data']));
        $this->set('serfindat', Util::convertDataDMY($this->request->data['final_data']));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

}
