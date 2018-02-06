<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use App\Model\Entity\Estadia;
use App\Model\Entity\Reserva;
use App\Model\Entity\DocumentoConta;
use App\Utility\Util;
use App\Utility\Paginator;
use App\Controller\PagesController;

class EstadiasController extends AppController {

    private $estadia;
    private $reserva;
    private $connection;
    private $documento_conta;
    private $pagesController;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->estadia = new Estadia();
        $this->reserva = new Reserva();
        $this->documento_conta = new DocumentoConta();
        $this->connection = ConnectionManager::get('default');
        $this->pagesController = new PagesController();
    }

    public function estchicri() {

        if ($this->request->is('post') && isset($this->request->data['ajax'])) {
            $empresa_codigo = $this->request->data['empresa_codigo'];
            $documento_numero = $this->request->data['documento_numero'];
            $quarto_item = $this->request->data['quarto_item'];
            $estchicri_retorno = $this->estadia->estchicri($empresa_codigo, $documento_numero, $quarto_item, $this->request->data['validado'] ?? 0);

            if (isset($estchicri_retorno['retorno']) && $estchicri_retorno['retorno'] == 1)
                $this->session->write('retorno_footer', $estchicri_retorno['mensagem']);
            echo json_encode($estchicri_retorno);

            $this->autoRender = false;
        } else {
            $info_tela = $this->pagesController->montagem_tela('estchicri');

            $empresa_codigo = $this->request->data['empresa_codigo'] ?? $this->request->params['pass'][0] ?? $this->request->query['empresa_codigo'] ?? 0;
            $quarto_codigo = $this->request->data['quarto_codigo'] ?? $this->request->params['pass'][1] ?? $this->request->query['quarto_codigo'] ?? 0;
            $documento_numero = $this->request->data['documento_numero'] ?? $this->request->params['pass'][2] ?? $this->request->query['documento_numero'] ?? 0;
            $quarto_item = $this->request->data['quarto_item'] ?? $this->request->params['pass'][3] ?? $this->request->query['quarto_item'] ?? 0;
            $tela_exibicao = $this->request->data['tela_exibicao'] ?? $this->request->params['pass'][5] ?? $this->request->query['tela_exibicao'] ?? 'checkin_revisao_e_alocacao';

            $reserva_dados = $this->reserva->resdocpes($empresa_codigo, "rs", $documento_numero, $quarto_item);
            $pesquisa_contas = $this->documento_conta->conresexi($empresa_codigo, $documento_numero);

            $indice_quarto_item_atual = array_search($quarto_item, array_column($reserva_dados['results'], 'quarto_item'));

            //Caso ja tenha sido feito um checkin
            //Busca todos os quarto itens encontrados nessas contas 
            $quarto_itens = array();
            foreach ($pesquisa_contas as $conta) {
                $quarto_itens[$conta['quarto_item']] = $conta['quarto_item'];
            }
            ksort($quarto_itens);

            //Manipula as datas e os respectivos quartos
            $quartos_por_datas_vetor = explode("|", $reserva_dados['results'][0]['quartos_por_datas']);
            $datas_por_quartos = array();
            $quartos_tipos_alocados = array();
            foreach ($quartos_por_datas_vetor as $quartos_por_data_item) {
                $quartos_por_data_item = trim($quartos_por_data_item);
                if ($quartos_por_data_item != '') {
                    $quarto_codigo = explode(" ", $quartos_por_data_item)[0];
                    $quarto_tipo_codigo = explode(" ", $quartos_por_data_item)[1];
                    $quartos_tipos_alocados[$quarto_codigo] = $quarto_tipo_codigo;
                    $data = explode(" ", $quartos_por_data_item)[2];
                    if (!array_key_exists($quarto_codigo, $datas_por_quartos))
                        $datas_por_quartos[$quarto_codigo][] = $data;
                    else {
                        $verifica_data_existe = array_search($data, $datas_por_quartos[$quarto_codigo]);
                        //Verifica se esta ocupado
                        if ($verifica_data_existe === false) {
                            $datas_por_quartos[$quarto_codigo][] = $data;
                        }
                    }
                }
            }

            //Formata as datas
            foreach ($datas_por_quartos as $key => $datas_quarto_item) {
                for ($i = 0; $i < sizeof($datas_por_quartos[$key]); $i++)
                    $datas_por_quartos[$key][$i] = Util::convertDataDMY($datas_por_quartos[$key][$i]);
            }

            //Dispara a gerquadis para cada linha diferente, considerando a data daquela linha
            foreach ($datas_por_quartos as $key => $datas_quarto_item) {
                for ($i = 0; $i < sizeof($datas_por_quartos[$key]); $i++) {
                    $gerquadis_retorno[$datas_por_quartos[$key][$i]] = $this->geral->gerquadis($empresa_codigo, array(Util::convertDataSQL($datas_por_quartos[$key][$i])), 3, array($quartos_tipos_alocados[$key]), null, 3, $documento_numero, $quarto_item);
                }
            }
            $gerquadis_geral_retorno = $this->geral->gerquadis($empresa_codigo, explode("|", $reserva_dados['results'][0]['datas']), 3, $quartos_tipos_alocados, null, 3, $documento_numero, $quarto_item);

            //Verifica se apenas um quarto foi alocado, pra poder exibir no cabeçalho
            $quarto_unico_alocado = null;
            if (sizeof(json_decode($this->request->data['quartos_alocados'], true)) == 1) {
                $quarto_unico_alocado = array_keys(json_decode($this->request->data['quartos_alocados'], true))[0];
            }

            $quarto_tipo_unico_alocado = null;
            foreach ($quartos_tipos_alocados as $quarto => $quarto_tipo) {
                if ($quarto_tipo_unico_alocado == null)
                    $quarto_tipo_unico_alocado = $quarto_tipo;
                else if ($quarto_tipo_unico_alocado != $quarto_tipo)
                    $quarto_tipo_unico_alocado = null;
            }

            $contas_quarto_item = array();
            foreach ($pesquisa_contas as $value) {
                $contas_quarto_item[$value['quarto_item']][] = $value;
            }
            ksort($contas_quarto_item);
            $retorno_resapgval = $this->reserva->resapgval($empresa_codigo, $documento_numero, $quarto_item, null, 1);
            $this->set('retorno_resapgval', $retorno_resapgval);
            $this->set('contas_quarto_item', $contas_quarto_item);
            $this->set('quarto_itens', $quarto_itens);
            $this->set('pesquisa_contas', $pesquisa_contas);
            $this->set('reserva_dados', $reserva_dados['results']);
            $this->set('contas_quarto_item', $contas_quarto_item);
            $this->set('cabecalho_conta', $reserva_dados['results']);

            $this->set('hospedes_info', $reserva_dados['results'][$indice_quarto_item_atual]['hospedes']);
            $this->set('indice_quarto_item_atual', $indice_quarto_item_atual);
            $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
            $this->set('quarto_tipo_lista', $this->geral->gercamdom('gerquatip', $this->session->read('empresa_selecionada')['empresa_codigo']));
            $this->set('quarto_item', $quarto_item);
            $this->set('quarto_codigo', $quarto_codigo);
            $this->set('quarto_unico_alocado', $quarto_unico_alocado);
            $this->set('quarto_tipo_unico_alocado', $quarto_tipo_unico_alocado);
            $this->set('documento_numero_selecionado', $documento_numero);
            $this->set('total_hospedes', sizeof($reserva_dados['results'][$indice_quarto_item_atual]['hospedes']));
            $this->set('gertipmot_list', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'mt'")));

            $this->set('quartos_alocados', $datas_por_quartos);
            $this->set('gerquadis_retorno', $gerquadis_retorno);
            $this->set('gerquadis_geral_retorno', $gerquadis_geral_retorno);
            $this->set('quartos_tipos_alocados', $quartos_tipos_alocados);
            $this->set('tela_exibicao', $tela_exibicao);
            $this->set('logo_empresa', $this->session->read('empresa_selecionada')['logo']);
            $this->set('quarto_tipo_comprado', $this->request->data['quarto_tipo_comprado']);
            $this->set('opened_acordions', $quarto_item . '|');

            $this->set($info_tela);
            $this->viewBuilder()->setLayout('ajax');
        }
    }

    /*
     * Exibe o dialog para criação do checkout
     */

    public function estchocri() {

        if ($this->request->is('post') && isset($this->request->data['ajax'])) {
            $values = array();
            if ($this->request->data['form'] ?? "" != "")
                parse_str($this->request->data['form'], $values);
            $quarto_item = array();
            if (isset($values['checkout_todos_quartos']) && $values['checkout_todos_quartos'] == 1) {
                for ($i = 1; $i <= $values['total_quartos']; $i++) {
                    $item['quarto_item'] = $i;
                    if (isset($values['motivo_codigo_' . $i])) {
                        $item['motivo_codigo'] = $values['motivo_codigo_' . $i];
                        $item['motivo_texto'] = $values['motivo_texto_' . $i];
                    } else {
                        $item['motivo_codigo'] = null;
                        $item['motivo_texto'] = null;
                    }
                    $quarto_item[$i] = $item;
                }
            } else {
                $quarto_item_checkout = $values['quarto_item_checkout'];
                $item['quarto_item'] = $quarto_item_checkout;
                if (isset($values['motivo_codigo_' . $quarto_item_checkout])) {
                    $item['motivo_codigo'] = $values['motivo_codigo_' . $quarto_item_checkout];
                    $item['motivo_texto'] = $values['motivo_texto_' . $quarto_item_checkout];
                } else {
                    $item['motivo_codigo'] = null;
                    $item['motivo_texto'] = null;
                }
                $quarto_item[$quarto_item_checkout] = $item;
            }

            $estchocri_retorno = $this->estadia->estchocri($values['empresa_codigo'], $values['documento_numero'], $quarto_item);
            if ($estchocri_retorno['retorno'] == 1)
                $this->session->write('retorno_footer', $estchocri_retorno['mensagem']['mensagem']);
            echo json_encode($estchocri_retorno);

            $this->autoRender = false;
        } else {
            $info_tela = $this->pagesController->montagem_tela('estchocri');

            $documento_numero = $this->request->data['documento_numero'] ?? $this->request->params['pass'][0] ?? $this->request->query['documento_numero'] ?? 0;
            $quarto_item = $this->request->data['quarto_item'] ?? $this->request->params['pass'][3] ?? $this->request->query['quarto_item'] ?? 0;
            $opened_accordions = $this->request->data['opened_acordions'] ?? $this->request->data['opened-acordions'] ?? $this->request->params['pass'][4] ?? $this->request->query['opened_acordions'] ?? 0;
            $reserva_dados = $this->reserva->resdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], "rs", $documento_numero);
            $pesquisa_contas = $this->documento_conta->conresexi($this->session->read('empresa_selecionada')['empresa_codigo'], $documento_numero);
            $indice_quarto_item_atual = array_search($quarto_item, array_column($reserva_dados['results'], 'quarto_item'));
            for ($i = 0; $i < sizeof($reserva_dados['results']); $i++) {
                $reserva_dados['results'][$i]['excedido_tempo'] = $this->estadia->estchoval($reserva_dados['results'][$i]['final_data']);
            }

            //Busca todos os quarto itens encontrados nessas contas 
            $quarto_itens = array();
            foreach ($pesquisa_contas as $conta) {
                $quarto_itens[$conta['quarto_item']] = $conta['quarto_item'];
            }
            ksort($quarto_itens);

            $contas_quarto_item = array();
            foreach ($pesquisa_contas as $value) {
                $contas_quarto_item[$value['quarto_item']][] = $value;
            }
            ksort($contas_quarto_item);


            $this->set('contas_quarto_item', $contas_quarto_item);
            $this->set('quarto_itens', $quarto_itens);
            $gerqtppes = $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo']);
            $quarto_tipos = array();
            foreach ($gerqtppes as $quarto_tipo)
                $quarto_tipos[$quarto_tipo['valor']] = $quarto_tipo['rotulo'];

            $this->set('quarto_tipos', $quarto_tipos);
            $this->set('reserva_dados', $reserva_dados['results']);
            $this->set('contas_quarto_item', $contas_quarto_item);
            $this->set('cabecalho_conta', $reserva_dados['results']);
            $this->set('indice_quarto_item_atual', $indice_quarto_item_atual);
            $this->set('pesquisa_contas', $pesquisa_contas);
            $this->set('total_quartos', sizeof($quarto_itens));
            $this->set('empresa_codigo', $this->session->read('empresa_selecionada')['empresa_codigo']);
            $this->set('documento_numero_selecionado', $documento_numero);
            $this->set('opened_acordions', $opened_accordions);
            $this->set($info_tela);
            $this->set('checkout_tardia_motivos', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'ct'")));
        }

        $this->viewBuilder()->setLayout('ajax');
    }

    public function estfnrpes() {
        $info_tela = $this->pagesController->montagem_tela('estfnrpes');

        //veio de um redirect apos envio
        $redirect_envio = false;
        if ($this->session->check('redirect_envio')) {
            $redirect_envio = true;
            $this->session->delete('redirect_envio');
        }

        $historico_busca = $this->pagesController->consomeHistoricoTela('estadias/estfnrpes');
        $this->request->data = array_merge($this->request->data, $historico_busca);

        if ($this->request->is('post') || sizeof($historico_busca) > 0) {
            $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'] ?? $this->session->read('empresa_selecionada')['empresa_codigo'];
            $documento_numero = $this->request->data['resdocnum'] ?? null;
            $documento_status_codigo = $this->request->data['gerdocsta'] ?? null;
            $resdocexc = $this->request->data['resdocexc'] ?? 0;
            $resentdat_inicio = $this->request->data['resentdat_inicio'] ?? null;
            $resentdat_final = $this->request->data['resentdat_final'] ?? null;
            $ressaidat_inicio = $this->request->data['ressaidat_inicio'] ?? null;
            $ressaidat_final = $this->request->data['ressaidat_final'] ?? null;
            $resesttit = $this->request->data['resesttit'] ?? null;
            $envio_status_fnrh = $this->request->data['estfnrsta'] ?? null;
            $envio_data_fnrh = $this->request->data['gerdatenv'] ?? null;
            $snnum = $this->request->data['estfnrnum'] ?? null;
            $resdocord = $this->request->data['gerdocord'] ?? null;

            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                $pesquisa_frnhs = $this->estadia->estfnrpes($empresa_codigo, $documento_numero, $documento_status_codigo, $this->request->data['c_codigo'] ?? null, $resentdat_inicio, $resentdat_final, $ressaidat_inicio, $ressaidat_final, $resesttit, $resdocexc, $envio_status_fnrh, $envio_data_fnrh, $snnum, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], null);

                $this->response->download('export.csv');
                $data = $pesquisa_frnhs;
                unset($data['results']);
                unset($data['filteredTotal']);

                $_serialize = 'data';
                $_extract = ['documento_numero', 'snnum', 'nome'];
                $_header = [$info_tela['rot_resdocnum'], $info_tela['rot_estfnrtit'], $info_tela['rot_resdochos']];
                $_csvEncoding = "iso-8859-1";
                $this->set(compact('data', '_serialize', '_header', '_extract', '_csvEncoding'));

                $this->viewBuilder()->className('CsvView.Csv');
            } else {
                $pesquisa_frnhs = $this->estadia->estfnrpes($empresa_codigo, $documento_numero, $documento_status_codigo, $this->request->data['c_codigo'] ?? null, $resentdat_inicio, $resentdat_final, $ressaidat_inicio, $ressaidat_final, $resesttit, $resdocexc, $envio_status_fnrh, $envio_data_fnrh, $snnum, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);

                $this->set('pesquisa_fnrhs', $pesquisa_frnhs['results'] ?? "");
                $this->set('empresa_codigo', $empresa_codigo);
                $this->request->data['pesquisar_fnrhs'] = 'yes';
                $this->set('exibir_max_exp', $documento_status_codigo == '1' ? '1' : '0');
                $this->set($this->request->data);
                $this->set('estfnrsta', $envio_status_fnrh);

                //exibe a paginação
                $paginator = new Paginator(10);
                $this->set('paginacao', $paginator->gera_paginacao($pesquisa_frnhs['filteredTotal'], $this->request->data['pagina'], 'estfnrpes', sizeof($pesquisa_frnhs['results'])));
            }
        } else {

            $this->set('exibir_max_exp', '0');
            $this->set('dias_max_expiracao', '');
            $this->set('horas_max_expiracao', '');
            $this->set('ordenacao_sistema', '1');
        }

        $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
        $this->set('cliente_papeis_lista', $this->geral->gercamdom('clicadpap'));
        $this->set('select_status', $this->geral->mk_select($this->geral->gercamdom('resdocsta', 'rs'), 'gerdocsta', 'gerdocsta', $this->request->data['gerdocsta'] ?? "", null, true));
        $this->set('select_ordem', $this->geral->mk_select($this->geral->gercamdom('resdocord', 'resdocord'), 'gerdocord', 'gerdocord', $resdocord ?? 'documento_numero', null, false, 'confirmacao_limite'));
        $this->set('reserva_status_list', $this->geral->gercamdom('resdocsta', 'rs'));
        //Verifica permissão de acesso para modificar fnrh
        if ($this->geral->geracever('estfnrmod') != "") {
            $geracever_estfnrmod = "0";
        } else {
            $geracever_estfnrmod = "1";
        }

        $this->set('geracever_estfnrmod', $geracever_estfnrmod);
        $this->set('empresa_codigo', $this->session->read('empresa_selecionada')['empresa_codigo']);

        //Checa as permissões em elementos da tela
        $this->set('ace_resdocmod', $this->geral->gerauuver('reservas', 'resdocmod') ? '' : ' disabled ');
        $this->set('ace_estfnrmod', $this->geral->gerauuver('estadias', 'estfnrmod') ? '' : ' disabled ');
        $this->set('ace_estfnrcen', $this->geral->gerauuver('estadias', 'estfnrcen') ? '' : ' disabled ');
        $this->set('ace_clicadpes', $this->geral->gerauuver('clientes', 'clicadpes') ? '' : ' disabled ');

        $this->set('fnrh_pesquisa_max', $this->geral->gercnfpes('fnrh_pesquisa_max'));
        $this->set($info_tela);

        $this->viewBuilder()->setLayout('ajax');
    }

    public function estfnrmod() {
        $info_tela = $this->pagesController->montagem_tela('estfnrmod');

        $geracever_estfnrmod = "";

        $fnrh_codigo = $this->request->params['pass'][0];
        if ($this->request->is('post')) {
            $retorno = $this->estadia->estfnrmod($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data, null, null, $this->request->data['fnrh_codigo']);
            $this->session->write('retorno', $retorno);
            $this->session->write('redirect_envio', true);
            $this->redirect('/estadias/estfnrpes/');
        } else {
            $dados_fnrh = $this->estadia->estfnrexi(null, null, $fnrh_codigo)[0];
            $dados_fnrh['sndtnascimento'] = Util::convertDataDMY($dados_fnrh['sndtnascimento']);

            $dados_fnrh['snprevent_data'] = Util::convertDataDMY(substr($dados_fnrh['snprevent'], 0, 11));
            $dados_fnrh['snprevent_hora'] = substr($dados_fnrh['snprevent'], 11, 5);
            $dados_fnrh['snprevsai_data'] = Util::convertDataDMY(substr($dados_fnrh['snprevsai'], 0, 11));
            $dados_fnrh['snprevsai_hora'] = substr($dados_fnrh['snprevsai'], 11, 5);

            $dados_fnrh['snpaisres'] = !empty($dados_fnrh['snpaisres']) ? $dados_fnrh['snpaisres'] : $this->session->read("pais_nome_padrao");
            $dados_fnrh['bgstdscpais'] = !empty($dados_fnrh['bgstdscpais']) ? $dados_fnrh['bgstdscpais'] : $this->session->read("pais_nome_padrao");
            $dados_fnrh['bgstdscpaisdest'] = !empty($dados_fnrh['bgstdscpaisdest']) ? $dados_fnrh['bgstdscpaisdest'] : $this->session->read("pais_nome_padrao");
            $dados_fnrh['snestadores'] = !empty($dados_fnrh['snestadores']) ? $dados_fnrh['snestadores'] : $this->session->read("empresa_selecionada")['estado'];
            $dados_fnrh['bgstdscestado'] = !empty($dados_fnrh['bgstdscestado']) ? $dados_fnrh['bgstdscestado'] : $this->session->read("empresa_selecionada")['estado'];
            $dados_fnrh['bgstdscestadodest'] = !empty($dados_fnrh['bgstdscestadodest']) ? $dados_fnrh['bgstdscestadodest'] : $this->session->read("empresa_selecionada")['estado'];

            $this->set($dados_fnrh);
            /* if ($dados_fnrh['snpaisres'] == '' || $dados_fnrh['snpaisres'] == null)
              $this->set('snpaisres', $this->session->read("empresa_selecionada")['pais']->pais_nome);

              if ($dados_fnrh['bgstdscpais'] == '' || $dados_fnrh['bgstdscpais'] == null)
              $this->set('bgstdscpais', $this->session->read("empresa_selecionada")['pais']->pais_nome);

              if ($dados_fnrh['bgstdscpaisdest'] == '' || $dados_fnrh['bgstdscpaisdest'] == null)
              $this->set('bgstdscpaisdest', $this->session->read("empresa_selecionada")['pais']->pais_nome); */

            $paises_table = TableRegistry::get('Paises');

            $this->set('dominio_estados_lista_snestadores', !empty($dados_fnrh['snpaisres']) ? $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['snpaisres'])->pais_codigo) : $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']));

            $this->set('dominio_estados_lista_bgstdscestado', !empty($dados_fnrh['bgstdscpais']) ? $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['bgstdscpais'])->pais_codigo) : $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']));

            $this->set('dominio_estados_lista_bgstdscestadodest', !empty($dados_fnrh['bgstdscpaisdest']) ? $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['bgstdscpaisdest'])->pais_codigo) : $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']));

            /*


              if ($dados_fnrh['snpaisres'] == '' || $dados_fnrh['snpaisres'] == null)
              $this->set('dominio_estados_lista_snestadores', $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']));
              else
              $this->set('dominio_estados_lista_snestadores', $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['snpaisres'])->pais_codigo));

              if ($dados_fnrh['bgstdscpais'] == '' || $dados_fnrh['bgstdscpais'] == null)
              $this->set('dominio_estados_lista_bgstdscestado', $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']));
              else
              $this->set('dominio_estados_lista_bgstdscestado', $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['bgstdscpais'])->pais_codigo));

              if ($dados_fnrh['bgstdscpaisdest'] == '' || $dados_fnrh['bgstdscpaisdest'] == null)
              $this->set('dominio_estados_lista_bgstdscestadodest', $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']));
              else
              $this->set('dominio_estados_lista_bgstdscestadodest', $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['bgstdscpaisdest'])->pais_codigo)); */


            $fnrh_envio_table = TableRegistry::get('FnrhEnvios');
            $dados_envio = $fnrh_envio_table->findByFnrhCodigo($fnrh_codigo);

            $array_envios = ['1' => 'nao_enviado', '3' => 'nao_enviado', '4' => 'nao_enviado'];
            foreach ($dados_envio as $envio_fnrh) {
                if ($envio_fnrh['envio_status'] == '1')
                    $array_envios[$envio_fnrh['fnrh_servico']] = '1';
            }

            $editaveis = 'todos';
            //Se checkout enviado com sucesso
            if ($array_envios['4'] == '1') {
                $editaveis = 'nenhum';
                //Se checkin enviado com sucesso
            } else if ($array_envios['3'] == '1') {
                $editaveis = 'nenhum_exceto_entrada';
                //Se inserir enviado com sucesso
            } else if ($array_envios['1'] == '1') {
                $editaveis = 'nenhum_exceto_entrada_saida';
            }

            $this->set('editaveis', $editaveis);
        }
        $this->set('fnrh_codigo', $fnrh_codigo);
        $this->set('dominio_viagem_motivos_lista', $this->geral->gercamdom('estmotvia'));
        $this->set('dominio_transporte_meios_lista', $this->geral->gercamdom('estmeitra'));

        $this->set('dominio_ddi_lista', $this->geral->gercamdom('clicelddi'));
        $this->set('dominio_nacionalidades_lista', $this->geral->gercamdom('clicadpai'));
        $this->set('dominio_paises_lista', $this->geral->gercamdom('clicadpai'));

        //Busca os dados salvos pela sessão
        $info_envio['envio_status'] = $this->session->read('envio_status');
        $info_envio['retorno_msg'] = $this->session->read('retorno_msg');
        $info_envio['envio_data'] = $this->session->read('envio_data');

        $this->set('info_envio', $info_envio);

        $this->session->delete('envio_status');
        $this->session->delete('retorno_msg');
        $this->session->delete('envio_data');
        if ($geracever_estfnrmod == '0') {
            $this->set('disabled', 'disabled');
        } else {
            $this->set('disabled', "");
        }
        $this->set($info_tela);
        $this->set('pagina_referencia', $this->session->read('paginas_pilha')[sizeof($this->session->read('paginas_pilha')) - 1]);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function estfnrmoc() {

        $info_tela = $this->pagesController->montagem_tela('estfnrmd2');
        $historico_busca = $this->pagesController->consomeHistoricoTela('estadias/estfnrmd2/');
        $this->request->data = array_merge($this->request->data, $historico_busca);
        $geracever_estfnrmod = "";

        if ($this->request->is('post') || sizeof($historico_busca) > 0) {
            $atualizar_sucesso = 1;
            $this->connection->begin();
            //Está editando
            if (isset($this->request->data['fnrhs_editadas'])) {
                $fnrhs = explode("|", substr($this->request->data['fnrhs_editadas'], 0, -1));
                foreach ($fnrhs as $fnrh_codigo) {
                    $dados_fnrh['snnumcpf'] = $this->request->data["snnumcpf_" . $fnrh_codigo];
                    $dados_fnrh['sntipdoc'] = $this->request->data["sntipdoc_" . $fnrh_codigo];
                    $dados_fnrh['snnumdoc'] = $this->request->data["snnumdoc_" . $fnrh_codigo];
                    $dados_fnrh['snorgexp'] = $this->request->data["snorgexp_" . $fnrh_codigo];
                    $dados_fnrh['snnomecompleto'] = $this->request->data["snnomecompleto_" . $fnrh_codigo];
                    $dados_fnrh['snemail'] = $this->request->data["snemail_" . $fnrh_codigo];
                    $dados_fnrh['snocupacao'] = $this->request->data["snocupacao_" . $fnrh_codigo];
                    $dados_fnrh['snnacionalidade'] = $this->request->data["snnacionalidade_" . $fnrh_codigo];
                    $dados_fnrh['sndtnascimento'] = $this->request->data["sndtnascimento_" . $fnrh_codigo];
                    $dados_fnrh['snsexo'] = $this->request->data["snsexo_" . $fnrh_codigo];
                    $dados_fnrh['sncelularddi'] = $this->request->data["sncelularddi_" . $fnrh_codigo];
                    $dados_fnrh['sncelularddd'] = $this->request->data["sncelularddd_" . $fnrh_codigo];
                    $dados_fnrh['sncelularnum'] = $this->request->data["sncelularnum_" . $fnrh_codigo];
                    $dados_fnrh['sntelefoneddi'] = $this->request->data["sntelefoneddi_" . $fnrh_codigo];
                    $dados_fnrh['sntelefoneddd'] = $this->request->data["sntelefoneddd_" . $fnrh_codigo];
                    $dados_fnrh['sntelefonenum'] = $this->request->data["sntelefonenum_" . $fnrh_codigo];
                    $dados_fnrh['snresidencia'] = $this->request->data["snresidencia_" . $fnrh_codigo];
                    $dados_fnrh['snpaisres'] = $this->request->data["snpaisres_" . $fnrh_codigo];
                    $dados_fnrh['snestadores'] = $this->request->data["snestadores_" . $fnrh_codigo] ?? '';
                    $dados_fnrh['sncidaderes'] = $this->request->data["sncidaderes_" . $fnrh_codigo];
                    $dados_fnrh['bgstdsccidade'] = $this->request->data["bgstdsccidade_" . $fnrh_codigo];
                    $dados_fnrh['bgstdscestado'] = $this->request->data["bgstdscestado_" . $fnrh_codigo] ?? '';
                    $dados_fnrh['bgstdscpais'] = $this->request->data["bgstdscpais_" . $fnrh_codigo];
                    $dados_fnrh['bgstdsccidadedest'] = $this->request->data["bgstdsccidadedest_" . $fnrh_codigo];
                    $dados_fnrh['bgstdscestadodest'] = $this->request->data["bgstdscestadodest_" . $fnrh_codigo] ?? '';
                    $dados_fnrh['bgstdscpaisdest'] = $this->request->data["bgstdscpaisdest_" . $fnrh_codigo];
                    $dados_fnrh['snmotvia'] = $this->request->data["snmotvia_" . $fnrh_codigo];
                    $dados_fnrh['sntiptran'] = $this->request->data["sntiptran_" . $fnrh_codigo];
                    $dados_fnrh['snprevent_data'] = $this->request->data["snprevent_data_" . $fnrh_codigo];
                    $dados_fnrh['snprevent_hora'] = $this->request->data["snprevent_hora_" . $fnrh_codigo];
                    $dados_fnrh['snprevsai_data'] = $this->request->data["snprevsai_data_" . $fnrh_codigo];
                    $dados_fnrh['snprevsai_hora'] = $this->request->data["snprevsai_hora_" . $fnrh_codigo];
                    $dados_fnrh['snnumhosp'] = $this->request->data["snnumhosp_" . $fnrh_codigo];
                    $dados_fnrh['snuhnum'] = $this->request->data["snuhnum_" . $fnrh_codigo];
                    $dados_fnrh['snnum'] = $this->request->data["snnum_" . $fnrh_codigo];
                    $dados_fnrh['snplacaveiculo'] = $this->request->data["snplacaveiculo_" . $fnrh_codigo];
                    $dados_fnrh['sncep'] = $this->request->data["sncep_" . $fnrh_codigo];
                    $retorno = $this->estadia->estfnrmod($this->session->read('empresa_selecionada')['empresa_codigo'], $dados_fnrh, null, null, $fnrh_codigo);

                    if ($retorno['retorno'] == 0) {
                        $atualizar_sucesso = 0;
                        break;
                    }
                }

                if ($atualizar_sucesso) {
                    $this->connection->commit();
                    $retorno = $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 63, 1);
                } else {
                    $this->connection->rollback();
                    $retorno = $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 64, 1);
                }

                $this->session->write('redirect_envio', true);
                $this->session->write('retorno', $retorno);
                $this->redirect('/estadias/estfnrpes');
            } else {
                //Carrega os dados da fnrh para edição 
                $vetor_fnrh = array();
                foreach ($this->request->data['fnrhs'] as $fnrhs_selecionadas) {
                    $fnrhs = explode("|", substr($fnrhs_selecionadas, 0, -1));
                    foreach ($fnrhs as $fnrh_codigo) {
                        $dados_fnrh = $this->estadia->estfnrexi(null, null, $fnrh_codigo)[0];
                        $dados_fnrh['sndtnascimento'] = Util::convertDataDMY($dados_fnrh['sndtnascimento']);
                        $dados_fnrh['snprevent_data'] = Util::convertDataDMY(substr($dados_fnrh['snprevent'], 0, 11));
                        $dados_fnrh['snprevent_hora'] = substr($dados_fnrh['snprevent'], 11, 5);
                        $dados_fnrh['snprevsai_data'] = Util::convertDataDMY(substr($dados_fnrh['snprevsai'], 0, 11));
                        $dados_fnrh['snprevsai_hora'] = substr($dados_fnrh['snprevsai'], 11, 5);


                        $dados_fnrh['snpaisres'] = !empty($dados_fnrh['snpaisres']) ? $dados_fnrh['snpaisres'] : $this->session->read("pais_nome_padrao");
                        $dados_fnrh['bgstdscpais'] = !empty($dados_fnrh['bgstdscpais']) ? $dados_fnrh['bgstdscpais'] : $this->session->read("pais_nome_padrao");
                        $dados_fnrh['bgstdscpaisdest'] = !empty($dados_fnrh['bgstdscpaisdest']) ? $dados_fnrh['bgstdscpaisdest'] : $this->session->read("pais_nome_padrao");
                        $dados_fnrh['snestadores'] = !empty($dados_fnrh['snestadores']) ? $dados_fnrh['snestadores'] : $this->session->read("empresa_selecionada")['estado'];
                        $dados_fnrh['bgstdscestado'] = !empty($dados_fnrh['bgstdscestado']) ? $dados_fnrh['bgstdscestado'] : $this->session->read("empresa_selecionada")['estado'];
                        $dados_fnrh['bgstdscestadodest'] = !empty($dados_fnrh['bgstdscestadodest']) ? $dados_fnrh['bgstdscestadodest'] : $this->session->read("empresa_selecionada")['estado'];

                        /* if ($dados_fnrh['snpaisres'] == '' || $dados_fnrh['snpaisres'] == null)
                          $dados_fnrh['snpaisres'] = $this->session->read("empresa_selecionada")['pais']->pais_nome;
                          if ($dados_fnrh['bgstdscpais'] == '' || $dados_fnrh['bgstdscpais'] == null)
                          $dados_fnrh['bgstdscpais'] = $this->session->read("empresa_selecionada")['pais']->pais_nome;
                          if ($dados_fnrh['bgstdscpaisdest'] == '' || $dados_fnrh['bgstdscpaisdest'] == null)
                          $dados_fnrh['bgstdscpaisdest'] = $this->session->read("empresa_selecionada")['pais']->pais_nome; */

                        $paises_table = TableRegistry::get('Paises');

                        $dados_fnrh['dominio_estados_lista_snestadores'] = !empty($dados_fnrh['snpaisres']) ? $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['snpaisres'])->pais_codigo) : $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']);

                        $dados_fnrh['dominio_estados_lista_bgstdscestado'] = !empty($dados_fnrh['bgstdscpais']) ? $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['bgstdscpais'])->pais_codigo) : $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']);

                        $dados_fnrh['dominio_estados_lista_bgstdscestadodest'] = !empty($dados_fnrh['bgstdscpaisdest']) ? $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['bgstdscpaisdest'])->pais_codigo) : $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']);

                        /*
                          if ($dados_fnrh['snpaisres'] == '' || $dados_fnrh['snpaisres'] == null)
                          $dados_fnrh['dominio_estados_lista_snestadores'] = $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']);
                          else
                          $dados_fnrh['dominio_estados_lista_snestadores'] = $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['snpaisres'])->pais_codigo);

                          if ($dados_fnrh['bgstdscpais'] == '' || $dados_fnrh['bgstdscpais'] == null)
                          $dados_fnrh['dominio_estados_lista_bgstdscestado'] = $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']);
                          else
                          $dados_fnrh['dominio_estados_lista_bgstdscestado'] = $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['bgstdscpais'])->pais_codigo);

                          if ($dados_fnrh['bgstdscpaisdest'] == '' || $dados_fnrh['bgstdscpaisdest'] == null)
                          $dados_fnrh['dominio_estados_lista_bgstdscestadodest'] = $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']);
                          else
                          $dados_fnrh['dominio_estados_lista_bgstdscestadodest'] = $this->geral->gerestdet($paises_table->findByPaisNome($dados_fnrh['bgstdscpaisdest'])->pais_codigo); */

                        //Busca os dados de envio para exibir a mensagem 
                        $fnrh_envio_table = TableRegistry::get('FnrhEnvios');
                        $dados_envio = $fnrh_envio_table->findByFnrhCodigo($fnrh_codigo);

                        $info_envio = array();
                        foreach ($dados_envio as $envio) {
                            if ($envio['envio_status'] == 2) {
                                $info_envio['status_fnrh'] = 'Rejeitada';
                                $info_envio['mensagem_fnrh'] = $envio['retorno_mensagem_nome'];
                                $info_envio['data_fnrh'] = Util::convertDataDMY($envio['envio_data']);
                                break;
                            } else if ($envio['envio_status'] == 0) {
                                $info_envio['status_fnrh'] = 'Não enviada';
                                $info_envio['mensagem_fnrh'] = $envio['retorno_mensagem_nome'];
                                $info_envio['data_fnrh'] = Util::convertDataDMY($envio['envio_data']);
                                break;
                            } else if ($envio['envio_status'] == 1) {
                                $info_envio['status_fnrh'] = 'Recebida';
                                $info_envio['mensagem_fnrh'] = $envio['retorno_mensagem_nome'];
                                $info_envio['data_fnrh'] = Util::convertDataDMY($envio['envio_data']);
                            }
                        }
                        $dados_fnrh['info_envio'] = $info_envio;

                        $array_envios = ['1' => 'nao_enviado', '3' => 'nao_enviado', '4' => 'nao_enviado'];
                        foreach ($dados_envio as $envio_fnrh) {
                            if ($envio_fnrh['envio_status'] == '1')
                                $array_envios[$envio_fnrh['fnrh_servico']] = '1';
                        }

                        $editaveis = 'todos';
                        //Se checkout enviado com sucesso
                        if ($array_envios['4'] == '1') {
                            $editaveis = 'nenhum';
                            //Se checkin enviado com sucesso
                        } else if ($array_envios['3'] == '1') {
                            $editaveis = 'nenhum_exceto_entrada';
                            //Se inserir enviado com sucesso
                        } else if ($array_envios['1'] == '1') {
                            $editaveis = 'nenhum_exceto_entrada_saida';
                        }

                        $dados_fnrh['editaveis'] = $editaveis;

                        $vetor_fnrh[] = $dados_fnrh;
                    }
                }

                $this->set('dados_fnrh', $vetor_fnrh);
                $this->set('fnrhs_editadas', $this->request->data['fnrhs'][0]);
            }
        }
        $this->set('dominio_viagem_motivos_lista', $this->geral->gercamdom('estmotvia'));
        $this->set('dominio_transporte_meios_lista', $this->geral->gercamdom('estmeitra'));
        $this->set('dominio_paises_lista', $this->geral->gercamdom('clicadpai'));
        $this->set('dominio_estados_lista', $this->geral->gerestdet($this->session->read("empresa_selecionada")['pais_codigo']));
        $this->set('dominio_ddi_lista', $this->geral->gercamdom('clicelddi'));
        $this->set('dominio_nacionalidades_lista', $this->geral->gercamdom('clicadpai'));

        if ($geracever_estfnrmod == '0') {
            $this->set('disabled', 'disabled');
        } else {
            $this->set('disabled', "");
        }

        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function estquaalo() {
        if ($this->request->is('post') && isset($this->request->data['ajax'])) {
            if (array_key_exists('quarto_codigo', $this->request->data)) {

                //Quarto codigo ser passado como  um vetor, 
                $quarto_codigo = null;
                if (isset($this->request->data['quarto_codigo'])) {
                    if (is_array($this->request->data['quarto_codigo']))
                        $quarto_codigo = $this->request->data['quarto_codigo'];
                    else
                        $quarto_codigo = explode(",", $this->request->data['quarto_codigo']);
                }

                //limpando as aspas simples se necessário
                foreach ($quarto_codigo as $key => $quarto) {
                    $quarto_codigo[$key] = str_replace("'", "", $quarto);
                }

                //Estadia data deve ser passada como  um vetor
                $estadia_data = null;
                if (isset($this->request->data['estadia_data'])) {
                    if (is_array($this->request->data['estadia_data']))
                        $estadia_data = $this->request->data['estadia_data'];
                    else
                        $estadia_data = explode("|", $this->request->data['estadia_data']);
                }
                //convertendo se necessário a data para formato sql
                foreach ($estadia_data as $key => $data) {
                    $estadia_data[$key] = Util::convertDataSql($data);
                }
                //Quarto tipo codigo ser passado como  um vetor
                $quarto_tipo_codigo = null;
                if (isset($this->request->data['quarto_tipo_codigo'])) {
                    if (is_array($this->request->data['quarto_tipo_codigo']))
                        $quarto_tipo_codigo = $this->request->data['quarto_tipo_codigo'];
                    else
                        $quarto_tipo_codigo = explode(",", $this->request->data['quarto_tipo_codigo']);
                }

                $quarto_tipo_diferente = 0;
                for ($i = 0; $i < sizeof($quarto_codigo); $i++) {
                    if (empty($quarto_codigo[$i])) {
                        $quarto_codigo[$i] = null;
                        if ($quarto_tipo_codigo[$i] != $this->request->data['quarto_tipo_comprado'])
                            $quarto_tipo_codigo[$i] = $this->request->data['quarto_tipo_comprado'];
                    }
                    if ($quarto_tipo_codigo[$i] != $this->request->data['quarto_tipo_comprado'])
                        $quarto_tipo_diferente = 1;
                }

                //Monta o vetor de motivos, caso necessário
                $motivo = null;
                $motivos = array();

                //Primeiro verifica se existem quartos de tipos diferentes
                if ($quarto_tipo_diferente) {
                    $motivo['motivo_tipo_codigo'] = 'mt';
                    $motivo['motivo_codigo'] = $this->request->data['motivo_codigo'];
                    $motivo['motivo_texto'] = $this->request->data['motivo_texto'];
                    array_push($motivos, $motivo);
                }

                $estquaalo_retorno = $this->estadia->estquaalo($this->request->data['empresa_codigo'], $this->request->data['documento_numero'], $this->request->data['quarto_item'], $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $this->request->data['quarto_tipo_comprado'], $this->request->data['quarto_tipo_valida'] ?? 1, null, $motivos);
                if (array_key_exists('mensagem', $estquaalo_retorno))
                    $this->session->write('retorno_footer', $estquaalo_retorno['mensagem']['mensagem']);

                echo json_encode($estquaalo_retorno ?? "");
                $this->autoRender = false;
            }
        } else {
            $info_tela = $this->pagesController->montagem_tela('estquaalo');
            $reserva_dados = $this->reserva->resdocpes($this->request->data['empresa_codigo'], "rs", $this->request->data['documento_numero'], $this->request->data['quarto_item'])['results'][0];
//Manipula as datas e os respectivos quartos
            $quartos_por_datas_vetor = explode("|", $reserva_dados['quartos_por_datas']);
            $datas_por_quartos = array();
            $quartos_tipos_alocados = array();
            foreach ($quartos_por_datas_vetor as $quartos_por_data_item) {
                $quartos_por_data_item = trim($quartos_por_data_item);
                if ($quartos_por_data_item != '') {
                    $quarto_codigo = explode(" ", $quartos_por_data_item)[0];
                    $quarto_tipo_codigo = explode(" ", $quartos_por_data_item)[1];
                    $quartos_tipos_alocados[$quarto_codigo] = $quarto_tipo_codigo;
                    $data = explode(" ", $quartos_por_data_item)[2];
                    if (!array_key_exists($quarto_codigo, $datas_por_quartos))
                        $datas_por_quartos[$quarto_codigo][] = $data;
                    else {
                        $verifica_data_existe = array_search($data, $datas_por_quartos[$quarto_codigo]);
                        //Verifica se esta ocupado
                        if ($verifica_data_existe === false) {
                            $datas_por_quartos[$quarto_codigo][] = $data;
                        }
                    }
                }
            }

            //Formata as datas
            foreach ($datas_por_quartos as $key => $datas_quarto_item) {
                for ($i = 0; $i < sizeof($datas_por_quartos[$key]); $i++)
                    $datas_por_quartos[$key][$i] = Util::convertDataDMY($datas_por_quartos[$key][$i]);
            }

            //Dispara a gerquadis para cada linha diferente, considerando a data daquela linha
            foreach ($datas_por_quartos as $key => $datas_quarto_item) {
                for ($i = 0; $i < sizeof($datas_por_quartos[$key]); $i++) {
                    $gerquadis_retorno[$datas_por_quartos[$key][$i]] = $this->geral->gerquadis($this->request->data['empresa_codigo'], array(Util::convertDataSQL($datas_por_quartos[$key][$i])), 3, array($quartos_tipos_alocados[$key]), null, 3, $reserva_dados['documento_numero'], $reserva_dados['quarto_item']);
                }
            }

            $gerquadis_geral_retorno = $this->geral->gerquadis($this->request->data['empresa_codigo'], explode("|", $reserva_dados['datas']), 3, $quartos_tipos_alocados, null, 3, $reserva_dados['documento_numero'], $reserva_dados['quarto_item']);
            //Verifica se apenas um quarto foi alocado, pra poder exibir no cabeçalho
            $quarto_unico_alocado = null;
            if (sizeof(json_decode($this->request->data['quartos_alocados'], true)) == 1) {
                $quarto_unico_alocado = array_keys(json_decode($this->request->data['quartos_alocados'], true))[0];
            }

            $quarto_tipo_unico_alocado = null;
            foreach ($quartos_tipos_alocados as $quarto => $quarto_tipo) {
                if ($quarto_tipo_unico_alocado == null)
                    $quarto_tipo_unico_alocado = $quarto_tipo;
                else if ($quarto_tipo_unico_alocado != $quarto_tipo)
                    $quarto_tipo_unico_alocado = null;
            }

            $this->set('hospedes_info', $reserva_dados['hospedes'] ?? array());
            $this->set('quarto_tipo_lista', $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo']));
            $this->set('quarto_item', $this->request->data['quarto_item']);
            $this->set('quarto_tipo_comprado', $this->request->data['quarto_tipo_comprado']);
            $this->set('quarto_codigo', $reserva_dados['quarto_codigo']);
            $this->set('total_hospedes', sizeof($reserva_dados['hospedes'] ?? array()));
            $this->set('reserva_dados', $reserva_dados);
            $this->set('gerdatatu', Util::convertDataDMY($this->geral->geragodet(1)));
            $this->set('gerquadis_retorno', $gerquadis_retorno);
            $this->set('gerquadis_geral_retorno', $gerquadis_geral_retorno);
            $this->set('quarto_unico_alocado', $quarto_unico_alocado);
            $this->set('quarto_tipo_unico_alocado', $quarto_tipo_unico_alocado);
            $this->set('quartos_alocados', $datas_por_quartos);
            $this->set('quartos_tipos_alocados', $quartos_tipos_alocados);
            $this->set('gertipmot_list', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'mt'")));
            $this->set($info_tela);
            $this->viewBuilder()->setLayout('ajax');
        }
    }

    public function estfnrenv() {

        if ($this->request->is('post')) {
            $fnrh_envio_table = TableRegistry::get('FnrhEnvios');
            if (isset($this->request->data['fnrhs'])) {
                foreach ($this->request->data['fnrhs'] as $fnrhs_selecionadas) {
                    $fnrhs = explode("|", substr($fnrhs_selecionadas, 0, -1));
                    foreach ($fnrhs as $fnrh_codigo) {
                        $dados_fnrh = $fnrh_envio_table->findByFnrhCodigo($fnrh_codigo);
                        //Verifica  a situação de cada um dos serviços
                        $array_envios = ['1' => 'nao_enviado', '3' => 'nao_enviado', '4' => 'nao_enviado'];
                        foreach ($dados_fnrh as $envio_fnrh) {
                            if ($envio_fnrh['envio_status'] == '1')
                                $array_envios[$envio_fnrh['fnrh_servico']] = 'enviado';
                        }


                        foreach ($array_envios as $servico => $envio) {
                            if ($envio == 'nao_enviado')
                                $this->estadia->estfnrenv($this->session->read('empresa_selecionada')['empresa_codigo'], $fnrh_codigo, $servico);
                        }
                    }
                }
            }

            $this->session->write('retorno', $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 43, 1));
            $this->redirect('/estadias/estfnrpes/');
        }else {
            echo "NOT POST";
            exit();
        }

        $this->autoRender = false;
    }

    /*
     * Exibe o painel de ocupação diário
     */

    public function estpaiatu($pdf = null) {

        if (!isset($pdf)) {
            $pdf = $this->request->data['pdf'] ?? null;
        }
        //echo $pdf;
        $info_tela = $this->pagesController->montagem_tela('estpaiatu');

        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];
        $saida_tipo = $this->request->data['saida_tipo'] ?? 'p';
        $quartos_tipos_codigos = $this->geral->gercamdom('resquatip', $empresa_codigo);

        $historico_busca = $this->pagesController->consomeHistoricoTela('estadias/estpaiatu');
        $this->request->data = array_merge($this->request->data, $historico_busca);

        //primeiro carregamento, sem filtros
        if (!$this->request->is('post')) {
            $filtro = array('vazio' => 1, 'ocupado' => 1, 'bloqueado' => 1, 'check_in' => 0, 'check_out' => 0, 'servico' => 0,
                'bloqueio' => 0);

            foreach ($quartos_tipos_codigos as $quarto_tipo_codigo)
                $filtro[$quarto_tipo_codigo['valor']] = 1;
            $this->set('tipo_quarto_select_all', 1);
            $this->set('situacao_select_all', 1);
        } else {

            $filtro_quarto_tipo_codigo = array();

            if (isset($this->request->data['filtro_quarto_tipo_codigo']))
                $filtro_quarto_tipo_codigo = $this->request->data['filtro_quarto_tipo_codigo'];
            //seta os valores de checkbox não marcados como -1
            foreach ($quartos_tipos_codigos as $quarto_tipo_codigo) {
                $filtro[$quarto_tipo_codigo['valor']] = -1;
                for ($i = 0; $i < sizeof($filtro_quarto_tipo_codigo); $i++) {
                    if ($this->request->data['filtro_quarto_tipo_codigo'][$i] == $quarto_tipo_codigo['valor'])
                        $filtro[$quarto_tipo_codigo['valor']] = 1;
                }
            }

            $filtro['vazio'] = isset($this->request->data['filtro_vazio']) ? 1 : -1;
            $filtro['ocupado'] = isset($this->request->data['filtro_ocupado']) ? 1 : -1;
            $filtro['bloqueado'] = isset($this->request->data['filtro_bloqueado']) ? 1 : -1;
            $filtro['check_out'] = $this->request->data['filtro_check_out'];
            $filtro['check_in'] = $this->request->data['filtro_check_in'];
            $filtro['servico'] = $this->request->data['filtro_servico'];
            $filtro['bloqueio'] = $this->request->data['filtro_bloqueio'];


            $this->set('tipo_quarto_select_all', isset($this->request->data['tipo_quarto_select_all']) ? 1 : -1);
            $this->set('situacao_select_all', isset($this->request->data['situacao_select_all']) ? 1 : -1);
        }

        $respaidat = '';
        if (array_key_exists('respaidat', $this->request->data)) {
            $respaidat = Util::convertDataSQL($this->request->data['respaidat']);
        } else
            $respaidat = $this->geral->geragodet(2, $empresa_codigo);

        $this->set('respaidat', Util::convertDataDMY($respaidat));
        $this->set('data_inicio_com_cerca', Util::convertDataDMY(Util::somaDias($respaidat, -7)));
        $this->set('data_inicio_com_cerca_ano_anterior', Util::convertDataDMY(Util::addYear($respaidat, -1)));

        $painel_ocupacao = $this->estadia->estpaiatu($empresa_codigo, $saida_tipo, $filtro, $this->request->data['respaidat'] ?? null);
        $this->set('estpaiatu_cockpit_dados', $painel_ocupacao['estpaiatu_cockpit_dados']);
        if ($saida_tipo == 'p') {
            $this->set('painel_ocupacao', $painel_ocupacao['estpaiatu_painel_dados']);
            $this->set('estpaiatu_totais', $painel_ocupacao['estpaiatu_totais']);
        }
        $vetor_quarto_tipo_nome = array();
        foreach ($this->geral->gercamdom('gerquatic', $empresa_codigo) as $key => $value) {
            $vetor_quarto_tipo_nome[$value['valor']] = $value['rotulo'];
        }

        $this->set('vetor_quarto_tipo_nome', $vetor_quarto_tipo_nome);

        $this->set('vetor_quarto_tipo', $this->geral->gercamdom('gerquatic', $empresa_codigo));
        $this->set('erros_sercricon', $painel_ocupacao['erros_sercricon']);
        $this->set($info_tela);
        $this->set('filtro', $filtro);

        if (isset($pdf) && $pdf == 'painel') {
            $this->viewBuilder()
                    ->options(['config' => [
                            'orientation' => 'portrait',
                            'filename' => 'painelOcupacao'
                        ]
            ]);

            $tipo_quartos = array();
            foreach ($quartos_tipos_codigos as $k) {
                $tipo_quartos[$k['valor']] = $k['rotulo'];
            }
            $texto_filtros = '';

            // Tipo de Quarto
            unset($arr_filtro);
            $arr_filtro = array();
            $cnt = 0;
            foreach ($filtro as $key => $val) {
                if ($val == 1) {
                    if (is_numeric($key)) {
                        $arr_filtro[] = $tipo_quartos[$key];
                        $cnt++;
                    }
                }
            }

            if ($cnt > 0) {
                $texto_filtros .= '<br /> Tipos de quartos: ';
                if ($cnt == sizeof($tipo_quartos)) {
                    $texto_filtros .= 'todos';
                } else {
                    $texto_filtros .= implode('; ', $arr_filtro);
                }
            } else {
                $texto_filtros .= '<br /> Tipos de quartos: nenhum';
            }

            // SituaÃ§Ã£o do Quarto
            unset($arr_filtro);
            $arr_filtro = array();
            $cnt = 0;
            if ($filtro['vazio'] == 1) {
                $arr_filtro[] = 'vazio';
                $cnt++;
            }
            if ($filtro['ocupado'] == 1) {
                $arr_filtro[] = 'ocupado';
                $cnt++;
            }
            if ($filtro['bloqueado'] == 1) {
                $arr_filtro[] = 'bloqueado';
                $cnt++;
            }
            if ($cnt > 0) {
                $texto_filtros .= '<br /> Situações: ';
                if ($cnt == 3) {
                    $texto_filtros .= 'todas';
                } else {
                    $texto_filtros .= implode('; ', $arr_filtro);
                }
            } else {
                $texto_filtros .= '<br /> Situações: nenhuma';
            }

            // Atividades do Quarto
            unset($arr_filtro_s);
            unset($arr_filtro_n);
            $arr_filtro_s = array();
            $arr_filtro_n = array();
            $cnt = 0;
            if ($filtro['check_out'] != 0) {
                if ($filtro['check_out'] == 1) {
                    $arr_filtro_s[] = 'check-out';
                } else {
                    $arr_filtro_n[] = 'check-out';
                }
            }
            if ($filtro['check_in'] != 0) {
                if ($filtro['check_in'] == 1) {
                    $arr_filtro_s[] = 'check-in';
                } else {
                    $arr_filtro_n[] = 'check-in';
                }
            }
            if ($filtro['servico'] != 0) {
                if ($filtro['servico'] == 1) {
                    $arr_filtro_s[] = 'serviços';
                } else {
                    $arr_filtro_n[] = 'serviços';
                }
            }
            if ($filtro['bloqueio'] != 0) {
                if ($filtro['bloqueio'] == 1) {
                    $arr_filtro_s[] = 'bloqueio';
                } else {
                    $arr_filtro_n[] = 'bloqueio';
                }
            }

            if (sizeof($arr_filtro_s) > 0 || sizeof($arr_filtro_n) > 0) {
                //$texto_filtros .= '<br /> Atividades: ';
                if (sizeof($arr_filtro_s) > 0) {
                    $texto_filtros .= '<br /> Somente quartos com: ' . implode('; ', $arr_filtro_s);
                }
                if (sizeof($arr_filtro_n) > 0) {
                    $texto_filtros .= '<br /> Excluindo quartos com: ' . implode('; ', $arr_filtro_n);
                }
            }

            $this->set('texto_filtros', ltrim($texto_filtros, '<br />'));

            $this->set('empresa_nome', $this->session->read('empresa_selecionada')['empresa_nome_fantasia']);
            $this->set('nome_relatorio', 'Relatório de Ocupação Diária');
        } else {


            $this->viewBuilder()->setLayout('ajax');
            /*
              if (!isset($this->request->data['formato_relatorio']) || !$this->request->data['formato_relatorio']) {
              $this->render('estpaiatu');
              $this->set('reservas_sem_alocacao', $painel_ocupacao['reservas_sem_alocacao']);
              } else
              $this->render('estpaiatu_relatorio'); */
        }
    }

    public function estfnrcen() {
        if (sizeof($this->request->data['fnrhs']) > 0)
            $retorno = $this->estadia->estfnrcen($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['fnrhs'], null);

        $this->session->write('retorno', $retorno);
        $this->session->write('redirect_envio', true);
        $this->redirect('/estadias/estfnrpes/');
        $this->autoRender = false;
    }

    public function estchionl() {

        if ($this->request->is('post')) {
            //Está editando
            if (isset($this->request->data['fnrhs_editadas'])) {

                $fnrhs = explode("|", $this->request->data['fnrhs_editadas']);
                foreach ($fnrhs as $fnrh_codigo) {
                    //Verifica se foi criado um novo cliente. Se foi, não deve atualizar a fnrh antiga, pois uma nova foi criada
                    if ($this->request->data['clicadalt_nome_' . $fnrh_codigo] != '1') {
                        $dados_fnrh['snnumcpf'] = $this->request->data["snnumcpf_" . $fnrh_codigo];
                        $dados_fnrh['sntipdoc'] = $this->request->data["sntipdoc_" . $fnrh_codigo];
                        $dados_fnrh['snnumdoc'] = $this->request->data["snnumdoc_" . $fnrh_codigo];
                        $dados_fnrh['snorgexp'] = $this->request->data["snorgexp_" . $fnrh_codigo];
                        $dados_fnrh['snnomecompleto'] = $this->request->data["snnomecompleto_" . $fnrh_codigo];
                        $dados_fnrh['snemail'] = $this->request->data["snemail_" . $fnrh_codigo];
                        $dados_fnrh['snocupacao'] = $this->request->data["snocupacao_" . $fnrh_codigo];
                        $dados_fnrh['snnacionalidade'] = $this->request->data["snnacionalidade_" . $fnrh_codigo];
                        $dados_fnrh['sndtnascimento'] = $this->request->data["sndtnascimento_" . $fnrh_codigo];
                        $dados_fnrh['snsexo'] = $this->request->data["snsexo_" . $fnrh_codigo];
                        $dados_fnrh['sncelularddi'] = $this->request->data["sncelularddi_" . $fnrh_codigo];
                        $dados_fnrh['sncelularddd'] = $this->request->data["sncelularddd_" . $fnrh_codigo];
                        $dados_fnrh['sncelularnum'] = $this->request->data["sncelularnum_" . $fnrh_codigo];
                        $dados_fnrh['sntelefoneddi'] = $this->request->data["sntelefoneddi_" . $fnrh_codigo];
                        $dados_fnrh['sntelefoneddd'] = $this->request->data["sntelefoneddd_" . $fnrh_codigo];
                        $dados_fnrh['sntelefonenum'] = $this->request->data["sntelefonenum_" . $fnrh_codigo];
                        $dados_fnrh['snresidencia'] = $this->request->data["snresidencia_" . $fnrh_codigo];
                        $dados_fnrh['snpaisres'] = $this->request->data["snpaisres_" . $fnrh_codigo];
                        $dados_fnrh['snestadores'] = $this->request->data["snestadores_" . $fnrh_codigo] ?? '';
                        $dados_fnrh['sncidaderes'] = $this->request->data["sncidaderes_" . $fnrh_codigo];
                        $dados_fnrh['bgstdsccidade'] = $this->request->data["bgstdsccidade_" . $fnrh_codigo];
                        $dados_fnrh['bgstdscestado'] = $this->request->data["bgstdscestado_" . $fnrh_codigo] ?? '';
                        $dados_fnrh['bgstdscpais'] = $this->request->data["bgstdscpais_" . $fnrh_codigo];
                        $dados_fnrh['bgstdsccidadedest'] = $this->request->data["bgstdsccidadedest_" . $fnrh_codigo];
                        $dados_fnrh['bgstdscestadodest'] = $this->request->data["bgstdscestadodest_" . $fnrh_codigo] ?? '';
                        $dados_fnrh['bgstdscpaisdest'] = $this->request->data["bgstdscpaisdest_" . $fnrh_codigo];
                        $dados_fnrh['snmotvia'] = $this->request->data["snmotvia_" . $fnrh_codigo];
                        $dados_fnrh['sntiptran'] = $this->request->data["sntiptran_" . $fnrh_codigo];
                        $dados_fnrh['snprevent_data'] = $this->request->data["snprevent_data_" . $fnrh_codigo];
                        $dados_fnrh['snprevent_hora'] = $this->request->data["snprevent_hora_" . $fnrh_codigo];
                        $dados_fnrh['snprevsai_data'] = $this->request->data["snprevsai_data_" . $fnrh_codigo];
                        $dados_fnrh['snprevsai_hora'] = $this->request->data["snprevsai_hora_" . $fnrh_codigo];
                        $dados_fnrh['snnumhosp'] = $this->request->data["snnumhosp_" . $fnrh_codigo];
                        $dados_fnrh['snuhnum'] = $this->request->data["snuhnum_" . $fnrh_codigo];
                        $dados_fnrh['snplacaveiculo'] = $this->request->data["snplacaveiculo_" . $fnrh_codigo];
                        $dados_fnrh['sncep'] = $this->request->data["sncep_" . $fnrh_codigo];

                        $retorno = $this->estadia->estfnrmod($this->request->data['empresa_codigo'], $dados_fnrh, null, null, $fnrh_codigo);
                    }
                }
                $retorno = $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 49, 1, null, null, null, null, 'pt');
                $this->set('retorno', $retorno);
            }
        } else {

            //Fnrh do primeiro hóspede, depois precisa buscar as fnrhs dos clientes
            //$fnrh_primeiro_hospede = $this->request->query('eao');
            $documento_numero = $this->request->query('eao');
            $empresa_codigo = $this->request->query('ea');
            $vetor_fnrh = array();

            //busca as fnrhs dos hospedes deste contratante            
            $documento_cliente_table = TableRegistry::get('DocumentoClientes');

            $hospedes = $documento_cliente_table->findHospedesByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero);

            $hospede_selecionado = null;
            foreach ($hospedes as $hospede) {
                if ($hospede['cliente_item'] == 1)
                    $hospede_selecionado = $hospede;
            }

            $dados_primeiro_hospede = $documento_cliente_table->findByFnrhCodigo($hospede_selecionado['fnrh_codigo']);

            $contratante = $documento_cliente_table->findContratantesByDocumentoNumeroEEmpresaCodigo($empresa_codigo, $documento_numero);

            $fnrh_editadas = "";
            $total_hospedes = 0;
            foreach ($hospedes as $hospede) {
                $fnrh_codigo = $hospede['fnrh_codigo'];
                $dados_fnrh = $this->estadia->estfnrexi(null, null, $fnrh_codigo)[0];
                $dados_fnrh['sndtnascimento'] = Util::convertDataDMY($dados_fnrh['sndtnascimento']);
                $dados_fnrh['snprevent_data'] = Util::convertDataDMY(substr($dados_fnrh['snprevent'], 0, 11));
                $dados_fnrh['snprevent_hora'] = substr($dados_fnrh['snprevent'], 11, 5);
                $dados_fnrh['snprevsai_data'] = Util::convertDataDMY(substr($dados_fnrh['snprevsai'], 0, 11));
                $dados_fnrh['snprevsai_hora'] = substr($dados_fnrh['snprevsai'], 11, 5);
                $vetor_fnrh[] = $dados_fnrh;
                $fnrh_editadas .= $fnrh_codigo . "|";
                $total_hospedes++;
            }

            $this->set('dados_fnrh', $vetor_fnrh);
            if ($fnrh_editadas != "")
                $fnrh_editadas = substr($fnrh_editadas, 0, -1);
            $this->set('fnrhs_editadas', $fnrh_editadas);

            $empresa_table = TableRegistry::get('Empresas');
            $dados_empresa = $empresa_table->findByEmpresaCodigo($dados_primeiro_hospede[0]['empresa_codigo']);

            $this->set('dominio_viagem_motivos_lista', $this->geral->gercamdom('estmotvia'));
            $this->set('dominio_transporte_meios_lista', $this->geral->gercamdom('estmeitra'));
            $this->set('dominio_paises_lista', $this->geral->gercamdom('clicadpai'));
            $this->set('dominio_estados_lista', $this->geral->gerestdet('br'));
            $this->set('dominio_ddi_lista', $this->geral->gercamdom('clicelddi'));
            $this->set('dominio_nacionalidades_lista', $this->geral->gercamdom('clicadpai'));
            $this->set('empresa_codigo', $dados_primeiro_hospede[0]['empresa_codigo']);
            $this->set('empresa_grupo_codigo', $dados_empresa['empresa_grupo_codigo']);
            $this->set('documento_numero', $dados_primeiro_hospede[0]['documento_numero']);
            $this->set('contratante_codigo', $contratante[0]['cliente_codigo']);
            $this->set('fnrh_codigo_hospede_1', $hospede_selecionado['fnrh_codigo']);
            $this->set('chave', $this->request->query('eac'));
            $this->set('total_hospedes', $total_hospedes);

            $arr_gertelmon = $this->geral->gertelmon($empresa_codigo, 'estfnrmd2', 'pt');
            $this->set(Util::germonrot($arr_gertelmon));
            $this->set(Util::germonfor($arr_gertelmon));
            $this->set(Util::germonval($arr_gertelmon));
            $this->set(Util::germonpro($arr_gertelmon));
        }
    }

}
