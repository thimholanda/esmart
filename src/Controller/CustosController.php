<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use App\Model\Entity\Custo;
use App\Model\Entity\Reserva;
use App\Controller\PagesController;
use App\Utility\Util;

class CustosController extends AppController {

    private $pagesController;
    private $custo;
    private $reserva;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->custo = new Custo();
        $this->reserva = new Reserva();
        $this->connection = ConnectionManager::get('default');
        $this->pagesController = new PagesController();
    }

    public function cusfolexi() {
        $info_tela = $this->pagesController->montagem_tela('cusfolexi');

        $custo_folha_itens = $this->custo->cusfolexi($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['documento_tipo_codigo'], $this->request->data['documento_numero'], $this->request->data['quarto_item'], $this->request->data['conta_item']);
        if (sizeof($custo_folha_itens) == 0)
            array_push($custo_folha_itens, array('produto_codigo' => '', 'qtd' => '1', 'fator_codigo' => '', 'produto_nome' => '', 'unitario_custo' => 0, 'total_custo' => 0, 'excluido' => 0));

        $this->set('pai_produto_codigo', $this->request->data['pai_produto_codigo']);
        $this->set('pai_produto_nome', $this->request->data['pai_produto_nome']);
        $this->set('pai_unidade_medida', $this->request->data['pai_unidade_medida']);
        $this->set('pai_produto_qtd', $this->request->data['pai_produto_qtd']);
        $this->set('pai_variavel_fator_codigo', $this->request->data['pai_variavel_fator_codigo']);
        $this->set('custo_folha_itens', $custo_folha_itens);
        $this->set('documento_numero', $this->request->data['documento_numero']);
        $this->set('quarto_item', $this->request->data['quarto_item']);
        $this->set('conta_item', $this->request->data['conta_item']);

        //Deixar dinamico de acordo com o perfil de acesso
        $this->set('origem_clique', 'lapis');
        $this->set('unidades_medida', $this->geral->gercamdom('profatvar'));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function cusfolmod() {

        if ($this->request->is('post')) {
            $anterior_folha = $this->custo->cusfolexi($this->session->read('empresa_selecionada')['empresa_codigo'], 'rs', $this->request->data['documento_numero'], $this->request->data['quarto_item'], $this->request->data['conta_item']);
            for ($i = 0; $i < sizeof($this->request->data['proprofil']); $i++) {
                if ($this->request->data['prolisexc'][$i] == 1) {
                    unset($this->request->data['proprofil'][$i]);
                    unset($this->request->data['qtd'][$i]);
                    unset($this->request->data['prounimed'][$i]);
                    unset($this->request->data['unitario_custo'][$i]);
                    unset($this->request->data['total_custo'][$i]);
                    unset($this->request->data['prolisexc'][$i]);
                }
            }
            
            $this->request->data['proprofil'] = array_values($this->request->data['proprofil']);
            $this->request->data['qtd'] = array_values($this->request->data['qtd']);
            $this->request->data['prounimed'] = array_values($this->request->data['prounimed']);
            $this->request->data['unitario_custo'] = array_values($this->request->data['unitario_custo']);
            $this->request->data['total_custo'] = array_values($this->request->data['total_custo']);
            $this->request->data['prolisexc']= array_values($this->request->data['prolisexc']);
            

            foreach ($anterior_folha as $item_anterior) {
                //Foi um item removido
                if (!in_array($item_anterior['produto_codigo'], $this->request->data['proprofil'])) {
                    array_push($this->request->data['proprofil'], $item_anterior['produto_codigo']);
                    array_push($this->request->data['qtd'], $item_anterior['qtd']);
                    array_push($this->request->data['prounimed'], $item_anterior['fator_codigo']);
                    array_push($this->request->data['unitario_custo'], Util::uticonval_us_br($item_anterior['unitario_custo']));
                    array_push($this->request->data['total_custo'], Util::uticonval_us_br($item_anterior['total_custo']));
                    array_push($this->request->data['prolisexc'], 1);
                }
            }
                        
            $retorno_resveimod = $this->custo->cusfolmod($this->session->read('empresa_selecionada')['empresa_codigo'], 'rs', $this->request->data['documento_numero'], $this->request->data['quarto_item'], $this->request->data['conta_item'], $this->request->data['proprofil'], $this->request->data['qtd'], $this->request->data['prounimed'], Util::uticonval_br_us($this->request->data['unitario_custo']), Util::uticonval_br_us($this->request->data['total_custo']), $this->request->data['prolisexc']);

            $this->session->write('retorno_footer', $retorno_resveimod['mensagem']['mensagem']);

            //Redireciona para a listagem 
            /* $this->request->data['back_page'] = 'custos/cusfolexi';
              $this->geral->gerpagsal('custos/cusfolexi', $this->request->data);
              $this->setAction('cusfolexi'); */
        }

        $this->autoRender = false;
    }

    public function cuscomexi() {
        if ($this->request->is('post')) {
            $custo_item = $this->custo->cuscomexi($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['produto_codigo']);

            echo json_encode($custo_item);
        }

        $this->autoRender = false;
    }

    public function cusresexi() {
        $info_tela = $this->pagesController->montagem_tela('cusresexi');
        $custo_itens = $this->custo->cusresexi($this->session->read('empresa_selecionada')['empresa_codigo'], 'rs', $this->request->data['documento_numero'] ?? $this->request->query['documento_numero'], $this->request->data['quarto_item'] ?? $this->request->query['quarto_item']);

        $dados_reserva_selecionada = $this->reserva->resdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], "rs", $this->request->data['documento_numero'] ?? $this->request->query['documento_numero']);

        $dados_resdocpes_quarto_item = array_search($this->request->data['quarto_item'] ?? $this->request->query['quarto_item'], array_column($dados_reserva_selecionada['results'], 'quarto_item'));

        $this->set('custo_itens', $custo_itens);
        $this->set('cabecalho_conta', $dados_reserva_selecionada['results']);
        $this->set('documento_numero_selecionado', $this->request->data['documento_numero'] ?? $this->request->query['documento_numero']);
        $this->set('dados_resdocpes_quarto_item', $dados_resdocpes_quarto_item);
        $this->set('quarto_item_selecionado', $this->request->data['quarto_item'] ?? $this->request->query['quarto_item']);
        $this->set('documento_tipo_selecionado', 'rs');
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function cusfoldet() {
        $empresa_codigo = $this->request->data['empresa_codigo'];
        $produto_codigo = $this->request->data['produto_codigo'];
        $produto_qtd = $this->request->data['produto_qtd'];
        $variavel_fator_codigo = $this->request->data['variavel_fator_codigo'];

        $custo_folha = $this->custo->cusfoldet($empresa_codigo, $produto_codigo, $produto_qtd, $variavel_fator_codigo);
        echo json_encode($custo_folha);
        $this->autoRender = false;
    }

}
