<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Servico;
use App\Utility\Util;
use App\Utility\Paginator;
use Cake\Datasource\ConnectionManager;
use App\Controller\PagesController;
use Cake\Core\Configure;

class ServicosController extends AppController {

    private $servico;
    private $connection;
    private $pagesController;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->servico = new Servico();
        $this->connection = ConnectionManager::get('default');
        $this->pagesController = new PagesController();
    }

    public function serdoccri() {
        $info_tela = $this->pagesController->montagem_tela('serdoccri');

        if ($this->request->is('post')) {

            if ($this->request->data['usar_padrao_horario']) {

                $this->request->data['serinidat'] .= ' ' . $this->session->read('inicial_padrao_horario');
                if (isset($this->request->data['serfindat']) && $this->request->data['serfindat'] != '')
                    $this->request->data['serfindat'] .= ' ' . $this->session->read('final_padrao_horario');
                else
                    $this->request->data['serfindat'] = $this->request->data['serinidat'];
            }else {
                if (!isset($this->request->data['serfindat']) || $this->request->data['serfindat'] == '')
                    $this->request->data['serfindat'] = $this->request->data['serinidat'];
            }
            $retorno = $this->servico->serdoccri($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['serdoctip'], $this->request->data['serinidat'], $this->request->data['serfindat'], strval($this->request->data['serquacod']), $this->request->data['serdocsta'] ?? null, $this->request->data['serdocmot'] ?? null, $this->request->data['serdoctxt'] ?? null);
            $this->session->write('retorno_footer', $retorno['mensagem']['mensagem']);

            //Reseta a data caso tenha concatenado o horario padrao
            $this->request->data['serfindat'] = explode(" ", $this->request->data['serfindat'])[0];
            $this->request->data['serinidat'] = explode(" ", $this->request->data['serinidat'])[0];

            // $this->set($this->request->data);
            if (isset($this->request->data['serdoctip'])) {
                if ($this->request->data['serdoctip'] == 'mb' || $this->request->data['serdoctip'] == 'ms') {
                    $this->set('gerdommot_list', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                                'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'],
                                'motivo_tipo_codigo' => "'" . $this->request->data['serdoctip'] . "'")));
                } else {
                    $this->set('serfindat', '');
                    $this->set('serdocmot', '');
                }
            }

            if (isset($this->request->data['url_redirect_after']))
                $this->autoRender = false;
        } else {
            $this->set('serinidat', $this->geral->geratudat());
            $this->set('serfindat', Util::convertDataDMY(Util::addDate(Util::convertDataSQL($this->geral->geratudat()), "1")));

            //Seta o planejado como padrao
            $this->set('serdocsta', 1);

            if ($info_tela['padrao_valor_gertiptit'] != '') {
                if ($info_tela['padrao_valor_gertiptit'] == 'mb' || $info_tela['padrao_valor_gertiptit'] == 'ms') {
                    $this->set('gerdommot_list', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                                'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'],
                                'motivo_tipo_codigo' => "'" . $info_tela['padrao_valor_gertiptit'] . "'")));
                } else {
                    $this->set('serdocmot', '');
                }
            }
        }

        //Busca a lista de quartos
        $dados_quartos = $this->connection->execute("SELECT q.quarto_codigo, qt.quarto_tipo_curto_nome FROM quartos q INNER JOIN quarto_tipos qt ON q.empresa_codigo = qt.empresa_codigo"
                        . " AND q.quarto_tipo_codigo = qt.quarto_tipo_codigo WHERE q.empresa_codigo = :empresa_codigo ORDER BY q.quarto_codigo", ['empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo']])->fetchAll("assoc");
        $quarto_por_tipo = array();
        foreach ($dados_quartos as $quarto_dado) {
            $quarto_por_tipo[$quarto_dado['quarto_codigo']] = $quarto_dado['quarto_tipo_curto_nome'];
        }

        $this->set('quarto_por_tipo', $quarto_por_tipo);
        $this->set('gerdoctip_list', $this->geral->gercamdom('gerdoctip', "rs,ct", "<>"));
        $status_lista = $this->geral->gercamdom('resdocsta', 'ca');
        usort($status_lista, function($a, $b) {
            return $a['valor'] <=> $b['valor'];
        });

        $this->set('gerdomsta_list', $status_lista);
        $this->set('gerempcod', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $this->set('inicial_padrao_horario', $this->session->read('inicial_padrao_horario'));
        $this->set('final_padrao_horario', $this->session->read('final_padrao_horario'));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function serdocmod() {

        //Busca o servico passado por parametro
        $empresa_codigo = trim($this->request->params['pass'][0] ?? '');
        $documento_numero = trim($this->request->params['pass'][1] ?? '');
        $documento_tipo_codigo = trim($this->request->params['pass'][2] ?? '');

        $info_tela = $this->pagesController->montagem_tela('serdocpes');
        $historico_busca = $this->pagesController->consomeHistoricoTela('servicos/serdocmod/' . $empresa_codigo . '/' . $documento_numero . '/' . $documento_tipo_codigo);
        $this->request->data = array_merge($this->request->data, $historico_busca);

        if ($this->request->is('post') || sizeof($historico_busca) > 0) {

            //Está editando multiplos, pela tela de busca
            if (isset($this->request->data['modifica_multiplos']) && $this->request->data['modifica_multiplos'] == 1) {

                //Verifica se marcou selecionar todos, de todas as paginas
                if ($this->request->data['todos_selecionados'] == 0) {
                    for ($i = 0; $i < sizeof($this->request->data['indices_selecionados']); $i++) {
                        $retorno = $this->servico->serdocmod($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['documento_tipos'][$this->request->data['indices_selecionados'][$i]], $this->request->data['documento_numeros'][$this->request->data['indices_selecionados'][$i]], $this->request->data['quarto_codigos'][$this->request->data['indices_selecionados'][$i]], $this->request->data['tipo_acao'], $this->request->data['documento_status'][$this->request->data['indices_selecionados'][$i]], $this->request->data['inicial_datas'][$this->request->data['indices_selecionados'][$i]], $this->request->data['final_datas'][$this->request->data['indices_selecionados'][$i]]);
                        $this->session->write('retorno_footer', $retorno['mensagem']['mensagem']);
                    }
                } else {
                    //Modifica todos de todas as paginas, refazendo a busca novamente pelos criterios feitos (assumimos o risco de que novos serviços possam ser criados desde a busca)  
                    $serdocnum = $this->request->data['serdocnum_copia'];
                    $resquacod = array();
                    if ($this->request->data['resquacod_copia'] != "")
                        $resquacod = explode(',', $this->request->data['resquacod_copia']);

                    $resquatip = array();
                    if ($this->request->data['resquatip_copia'] != "")
                        $resquatip = explode(',', $this->request->data['resquatip_copia']);

                    $gertiptit = array();
                    if ($this->request->data['gertiptit_copia'] != "")
                        $gertiptit = explode(',', $this->request->data['gertiptit_copia']);

                    $gerdocsta = array();
                    if ($this->request->data['gerdocsta_copia'] != "")
                        $gerdocsta = explode(',', $this->request->data['gerdocsta_copia']);

                    $germottit = array();
                    if ($this->request->data['germottit_copia'] != "")
                        $germottit = explode(',', $this->request->data['germottit_copia']);

                    if ($this->request->data['gerdattip_copia'] == 'entrada') {
                        $inicial_data['inicial'] = $this->request->data['gerdatini_copia'] ?? null;
                        $inicial_data['final'] = $this->request->data['gerdatfin_copia'] ?? null;
                    } else
                        $inicial_data = null;

                    if ($this->request->data['gerdattip_copia'] == 'saida') {
                        $final_data['inicial'] = $this->request->data['gerdatini_copia'] ?? null;
                        $final_data['final'] = $this->request->data['gerdatfin_copia'] ?? null;
                    } else
                        $final_data = null;

                    if ($this->request->data['gerdattip_copia'] == 'estadia') {
                        $estadia_data['inicial'] = $this->request->data['gerdatini_copia'] ?? null;
                        $estadia_data['final'] = $this->request->data['gerdatfin_copia'] ?? null;
                    } else
                        $estadia_data = null;

                    if ($this->request->data['gerdattip_copia'] == 'criacao') {
                        $criacao_data['inicial'] = $this->request->data['gerdatini_copia'] ?? null;
                        $criacao_data['final'] = $this->request->data['gerdatfin_copia'] ?? null;
                    } else
                        $criacao_data = null;

                    $pesquisa_servicos = $this->servico->serdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], $serdocnum, $gertiptit, $gerdocsta, $inicial_data, $final_data, $estadia_data, $criacao_data, $resquacod, $resquatip, $germottit);
                    //Após refazer a busca com os criterios originais, executa a ação em todos os itens encontrados;
                    foreach ($pesquisa_servicos['results'] as $servico) {
                        $retorno = $this->servico->serdocmod($this->session->read('empresa_selecionada')['empresa_codigo'], $servico['documento_tipo_codigo'], $servico['documento_numero'], $servico['quarto_codigo'], $this->request->data['tipo_acao'], $servico['documento_status_codigo'], $servico['inicial_data'], $servico['final_data']);
                        $this->session->write('retorno_footer', $retorno['mensagem']['mensagem']);
                    }
                }
                //Atualização de um único serviço
            } else {
                //Verifica se o serviço está com a data habilitada via tela
                if (!array_key_exists('serinidat', $this->request->data))
                    $this->request->data['serinidat'] = $this->request->data['anterior_inicial_data'];

                if (!array_key_exists('serfindat', $this->request->data))
                    $this->request->data['serfindat'] = $this->request->data['anterior_final_data'];

                if ($this->request->data['usar_padrao_horario']) {
                    $this->request->data['serinidat'] .= ' ' . $this->session->read('inicial_padrao_horario');
                    if (isset($this->request->data['serfindat']) && $this->request->data['serfindat'] != '')
                        $this->request->data['serfindat'] .= ' ' . $this->session->read('final_padrao_horario');
                    else
                        $this->request->data['serfindat'] = $this->request->data['serinidat'];
                }else {
                    if (!isset($this->request->data['serfindat']) || $this->request->data['serfindat'] == '')
                        $this->request->data['serfindat'] = $this->request->data['serinidat'];
                }

                $retorno = $this->servico->serdocmod($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['serdoctip'], $this->request->data['serdocnum'], strval($this->request->data['serquacod']), $this->request->data['serdocsta'], $this->request->data['anterior_documento_status_codigo'], $this->request->data['serinidat'], $this->request->data['serfindat'], empty($this->request->data['serdocmot']) ? 'null' : $this->request->data['serdocmot'], empty($this->request->data['serdoctxt']) ? 'null' : $this->request->data['serdoctxt'], $this->request->data['anterior_inicial_data'], $this->request->data['anterior_final_data'], $this->request->data['anterior_motivo_codigo'] ?? "", $this->request->data['anterior_texto'] ?? "");

                $this->set($this->request->data);
                //deve ser na sessão, pois sempre que se modifica um serviço, preçiso redirecionar
                $this->session->write('retorno_footer', $retorno['mensagem']['mensagem']);
            }
            $this->autoRender = false;
        } else {
            //Verifica a permissão para editar
            $acesso = $this->geral->geracever('serdocmod');
            $dis_serdocmod = "";
            if ($acesso != "") {
                $dis_serdocmod = "disabled";
            }
            $this->set('dis_serdocmod', $dis_serdocmod);
            $dados_servico = $this->servico->serdocexi($empresa_codigo, $documento_tipo_codigo, $documento_numero);
            $servico = $dados_servico['servico'];
            $referenciado_documento = $dados_servico['referenciado_documento'] ?? null;

            $array_map = array('gerempcod' => $empresa_codigo, 'serquacod' => $servico['quarto_codigo'], 'serqtinom' => $servico['quarto_tipo_nome'], 'serdoctip' => $servico['documento_tipo_codigo'],
                'serinidat' => date('d/m/Y', strtotime($servico['inicial_data'])), 'serfindat' => date('d/m/Y', strtotime($servico['final_data'])),
                'serdocnum' => $servico['documento_numero'], 'serdocsta' => $servico['documento_status_codigo'], 'serdocmot' => $servico['motivo_codigo'],
                'serdoctxt' => $servico['texto'], 'dados_serdocref' => $referenciado_documento);

            $this->set($array_map);

            $status_lista = $this->geral->gercamdom('resdocsta', $servico['documento_tipo_codigo']);
            usort($status_lista, function($a, $b) {
                return $a['valor'] <=> $b['valor'];
            });

            $this->set('gerdomsta_list', $status_lista);

            if (in_array($array_map['serdoctip'], array('bc', 'mb', 'ms')))
                $this->set('gerdommot_list', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                            'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'],
                            'motivo_tipo_codigo' => "'" . $array_map ['serdoctip'] . "'")));

            $info_tela = $this->pagesController->montagem_tela('serdocmod');

            $this->set($this->request->data);
            $this->set('gerquacod_list', $this->geral->gercamdom('resquacod', $this->session->read('empresa_selecionada')['empresa_codigo']));
            $this->set('gerdoctip_list', $this->geral->gercamdom('gerdoctip', "rs,ct", "<>"));

            $this->set('inicial_padrao_horario', $this->session->read('inicial_padrao_horario'));
            $this->set('final_padrao_horario', $this->session->read('final_padrao_horario'));
            $this->set($info_tela);
            if (sizeof($this->session->read('historico')) > 0)
                $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
            else
                $this->set('pagina_referencia', '');
            $this->viewBuilder()->setLayout(
                    'ajax');
        }
    }

    public function serdocpes($pdf = null) {

        if (!isset($pdf)) {
            $pdf = $this->request->data['pdf'] ?? null;
        }
        $info_tela = $this->pagesController->montagem_tela('serdocpes');
        $historico_busca = $this->pagesController->consomeHistoricoTela('servicos/serdocpes');
        $this->request->data = array_merge($this->request->data, $historico_busca);
        if ($this->request->is('post') || sizeof($historico_busca) > 0) {

            if ($this->request->data['gerdattip'] == 'entrada') {
                $inicial_data['inicial'] = $this->request->data['gerdatini'] ?? null;
                $inicial_data['final'] = $this->request->data['gerdatfin'] ?? null;
            } else
                $inicial_data = null;

            if ($this->request->data['gerdattip'] == 'saida') {
                $final_data['inicial'] = $this->request->data['gerdatini'] ?? null;
                $final_data['final'] = $this->request->data['gerdatfin'] ?? null;
            } else
                $final_data = null;

            if ($this->request->data['gerdattip'] == 'estadia') {
                $estadia_data['inicial'] = $this->request->data['gerdatini'] ?? null;
                $estadia_data['final'] = $this->request->data['gerdatfin'] ?? null;
            } else
                $estadia_data = null;

            if ($this->request->data['gerdattip'] == 'criacao') {
                $criacao_data['inicial'] = $this->request->data['gerdatini'] ?? null;
                $criacao_data['final'] = $this->request->data['gerdatfin'] ?? null;
            } else
                $criacao_data = null;

            $gerdocsta = array();
            if (array_key_exists('gerdocsta', $this->request->data)) {
                if (!is_array($this->request->data['gerdocsta']))
                    $this->request->data['gerdocsta'] = explode(",", $this->request->data['gerdocsta']);
                $gerdocsta = $this->request->data['gerdocsta'];
            }

            $gertiptit = array();
            if (array_key_exists('gertiptit', $this->request->data)) {
                if (!is_array($this->request->data['gertiptit']))
                    $this->request->data['gertiptit'] = explode(",", $this->request->data['gertiptit']);
                $gertiptit = $this->request->data['gertiptit'];
            }

            $resquatip = array();
            if (array_key_exists('resquatip', $this->request->data))
                $resquatip = $this->request->data['resquatip'];

            $resquacod = array();
            if (array_key_exists('resquacod', $this->request->data)) {
                if (!is_array($this->request->data['resquacod']))
                    $this->request->data['resquacod'] = explode(',', $this->request->data['resquacod']);
                $resquacod = $this->request->data['resquacod'];
            }

            $germottit = array();
            if (array_key_exists('germottit', $this->request->data))
                $germottit = $this->request->data['germottit'];

            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data ['export_csv']) && $this->request->data ['export_csv'] == '1') {

                $pesquisa_servicos = $this->servico->serdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['serdocnum'] ?? null, $this->request->data['gertiptit'], 
                        $this->request->data['gerdocsta'] ?? null, $inicial_data, $final_data, $estadia_data, $criacao_data, $resquacod, $resquatip, $germottit, 
                        $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], null);

                $this->response->download('export.csv');
                $data = $pesquisa_servicos['results'];
                $_serialize = 'data';
                $_extract = ['inicial_data', 'final_data', 'quarto_codigo', 'quarto_tipo_nome', 'documento_numero', 'documento_tipo_nome', 'documento_status_nome'];
                $_header = [$info_tela['rot_gerdatini'], $info_tela['rot_gerdatfin'], $info_tela['rot_resquacod'], $info_tela['rot_resquatip'],
                    $info_tela['rot_gerdoctit'], $info_tela['rot_gertiptit'], $info_tela['rot_resdocsta']];
                $_csvEncoding = "iso-8859-1";
                $_delimiter = ";";
                $this->set(compact('data', '_serialize', '_delimiter', '_header', '_extract', '_csvEncoding'));

                $this->viewBuilder()->className('CsvView.Csv');
            } else {
                $pesquisa_servicos = $this->servico->serdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['serdocnum'] ?? null, $gertiptit, $gerdocsta, $inicial_data, $final_data,
                        $estadia_data, $criacao_data, $resquacod, $resquatip, $germottit, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);
                $this->set('pesquisa_servicos', $pesquisa_servicos['results']);
                $this->set('total_itens_pesquisa', $pesquisa_servicos['filteredTotal']);
                $this->request->data['pesquisar_servicos'] = 'yes';

                //exibe a paginação
                $paginator = new Paginator(10);
                $this->set('paginacao', $paginator->gera_paginacao($pesquisa_servicos['filteredTotal'], $this->request->data['pagina'], 'serdocpes', sizeof($pesquisa_servicos['results'])));
            }
        } else {
            if ($info_tela['padrao_valor_gerdocsta'] != '')
                $gerdocsta = explode("|", $info_tela['padrao_valor_gerdocsta']);
            else
                $gerdocsta = ['1', '2'];

            if ($info_tela['padrao_valor_resquatip'] != '')
                $resquatip = explode("|", $info_tela['padrao_valor_resquatip']);
            else
                $resquatip = null;

            if ($info_tela['padrao_valor_gertiptit'] != '')
                $gertiptit = explode("|", $info_tela['padrao_valor_gertiptit']);
            else
                $gertiptit = null;

            if ($info_tela['padrao_valor_gerdattip'] != '')
                $gerdattip = $info_tela['padrao_valor_gerdattip'];
            else
                $gerdattip = 'estadia';

            if ($gerdattip == 'entrada') {
                $inicial_data['inicial'] = $this->geral->geragodet(1);
                $inicial_data['final'] = Util::addYear($this->geral->geragodet(1), 1);
                $this->set('gerdatini', Util::convertDataDMY($inicial_data['inicial']));
                $this->set('gerdatfin', Util::convertDataDMY($inicial_data['final']));
            } else
                $inicial_data = null;

            if ($gerdattip == 'saida') {
                $final_data['inicial'] = $this->geral->geragodet(1);
                $final_data['final'] = Util::addYear($this->geral->geragodet(1), 1);
                $this->set('gerdatini', Util::convertDataDMY($final_data['inicial']));
                $this->set('gerdatfin', Util::convertDataDMY($final_data['final']));
            } else
                $final_data = null;

            if ($gerdattip == 'estadia') {
                $estadia_data['inicial'] = $this->geral->geragodet(1);
                $estadia_data['final'] = Util::addYear($this->geral->geragodet(1), 1);
                $this->set('gerdatini', Util::convertDataDMY($estadia_data['inicial']));
                $this->set('gerdatfin', Util::convertDataDMY($estadia_data['final']));
            } else
                $estadia_data = null;

            if ($gerdattip == 'criacao') {
                $criacao_data['inicial'] = $this->geral->geragodet(1);
                $criacao_data['final'] = Util::addYear($this->geral->geragodet(1), 1);
                $this->set('gerdatini', Util::convertDataDMY($criacao_data['inicial']));
                $this->set('gerdatfin', Util::convertDataDMY($criacao_data['final']));
            } else
                $criacao_data = null;

            $ordenacao_coluna = "inicial_data|final_data|quarto_codigo|documento_tipo_nome|";
            $ordenacao_tipo = "desc|desc|asc|asc|";

            $this->set('gerdocsta', $gerdocsta);
            $this->set('gerdattip', $gerdattip);
            $this->set('resquatip', $resquatip);
            $this->set('gertiptit', $gertiptit);
            $this->set('ordenacao_coluna', $ordenacao_coluna);
            $this->set('ordenacao_tipo', $ordenacao_tipo);
            $this->set('ordenacao_sistema', '1');
            //Execuçao automática

            $pesquisa_servicos = $this->servico->serdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], null, $gertiptit, $gerdocsta, $inicial_data, $final_data, $estadia_data, $criacao_data, null,
                    $resquatip, null, $ordenacao_coluna, $ordenacao_tipo, 1);
            $this->set('pesquisa_servicos', $pesquisa_servicos['results']);
            $this->set('total_itens_pesquisa', $pesquisa_servicos['filteredTotal']);
            $this->request->data['pesquisar_servicos'] = 'yes';


            //exibe a paginação
            $paginator = new Paginator(10);
            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_servicos['filteredTotal'], 1, 'serdocpes', sizeof($pesquisa_servicos['results'])));
        }

        $lista_quarto_codigo = $this->geral->gercamdom('resquacod', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $lista_quarto_tipo = $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $lista_documento_tipo = $this->geral->gercamdom('gerdoctip', "rs,ct,bc", "<>");

        $this->set('empresa_codigo', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $this->set('gerquacod_list', $lista_quarto_codigo);
        $this->set('serquatip_list', $lista_quarto_tipo);
        $this->set('gerdoctip_list', $lista_documento_tipo);

        if (isset($pdf) && $pdf == 'servico') {
            $this->viewBuilder()->options([
                'pdfConfig' => [
                    'orientation' => 'portrait',
                    'filename' => 'relatorioServicos'
                ]
            ]);

            $texto_filtros = "";
            unset($arr_filtros);
            $arr_filtros = array();

            foreach (array_keys($this->request->data) as $chave) {
                if (!empty($this->request->data[$chave])) {
                    switch ($chave) {
                        case 'serdocnum':
                            $arr_filtros[] = 'Número do documento: ' . $this->request->data[$chave];
                            break;
                        case 'resquacod':
                            foreach ($lista_quarto_codigo as $lista) {
                                if ($lista['valor'] == $this->request->data[$chave]) {
                                    $arr_filtros[] = 'Tipo de quarto: ' . $lista['rotulo'];
                                    break;
                                }
                            }
                            $arr_filtros[] = 'Número do quarto: ' . $this->request->data[$chave];
                            break;
                        case 'resquatip':
                            foreach ($lista_quarto_tipo as $lista) {
                                if ($lista['valor'] == $this->request->data[$chave]) {
                                    $arr_filtros[] = 'Tipo de quarto: ' . $lista['rotulo'];
                                    break;
                                }
                            }
                            break;
                        case 'serdoctip':
                            foreach ($lista_documento_tipo as $lista) {
                                if ($lista['valor'] == $this->request->data[$chave]) {
                                    $arr_filtros[] = 'Tipo de quarto: ' . $lista['rotulo'];
                                    break;
                                }
                            }
                            break;
                        /* case 'serdocmot':
                          $arr_filtros[] = 'Motivo: ' . $this->request->data[$chave];
                          break; */
                        case 'serinidat_inicial':
                            $arr_filtros[] = 'Data inicial: de ' . $this->request->data['serinidat_inicial'] . ' a ' . $this->request->data['serinidat_final'];
                            break;
                        case 'serfindat_inicial':
                            $arr_filtros[] = 'Data final: de ' . $this->request->data['serfindat_inicial'] . ' a ' . $this->request->data['serfindat_final'];
                            break;
                    }
                }
            }

            $texto_filtros = implode('<br />', $arr_filtros);

            $this->set('texto_filtros', $texto_filtros);

            $this->set($this->request->data);

            $this->set('empresa_nome', $this->session->read('empresa_selecionada')['empresa_nome_fantasia']);
            $this->set('nome_relatorio', 'Relatório de Serviços por Data');
        } else {
//Busca a lista de quartos
            $dados_quartos = $this->connection->execute("SELECT q.quarto_codigo, qt.quarto_tipo_curto_nome FROM quartos q INNER JOIN quarto_tipos qt ON q.empresa_codigo = qt.empresa_codigo"
                            . " AND q.quarto_tipo_codigo = qt.quarto_tipo_codigo WHERE q.empresa_codigo = :empresa_codigo ORDER BY q.quarto_codigo", ['empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo']])->fetchAll("assoc");
            $quarto_por_tipo = array();
            foreach ($dados_quartos as $quarto_dado) {
                $quarto_por_tipo[$quarto_dado['quarto_codigo']] = $quarto_dado['quarto_tipo_curto_nome'];
            }


            $this->set('quarto_por_tipo', $quarto_por_tipo);
            //Busca a lista de motivos
            $dados_motivos = $this->connection->execute("SELECT CONCAT(motivo_tipo_codigo, '_', motivo_codigo) as motivo_codigo, motivo_nome FROM motivo_empresas WHERE empresa_codigo = :empresa_codigo AND motivo_tipo_codigo IN ('ms', 'mb') ORDER BY motivo_nome", ['empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo']])->fetchAll("assoc");
            $this->set('gerdommot_list', $dados_motivos);

            $gerdomsta_list = $this->geral->gercamdom('resdocsta', 'ca');
            //Ordena os valores de status de acordo com seu codigo
            usort($gerdomsta_list, function($a, $b) {
                return $a['valor'] <=> $b['valor'];
            });
            $this->set('gerdomsta_list', $gerdomsta_list);


            $this->set('servico_pesquisa_max', $this->session->read('servico_pesquisa_max'));
            $this->set($info_tela);

            $this->set($this->request->data);

            if (sizeof($this->session->read('historico')) > 0)
                $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
            else
                $this->set('pagina_referencia', '');
            $this->viewBuilder()->setLayout(
                    'ajax');
        }
    }

    public function sercamrel() {

        $info_tela = $this->pagesController->montagem_tela('sercamrel');

        if ($this->request->is('post')) {
            $pesquisa_servicos = $this->servico->sercamrel($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['serdocdat'], null, null, null, null, null);
           
            $this->set('serdocdat', $this->request->data['serdocdat']);
            $this->set('empresa_nome', $this->session->read('empresa_selecionada')['empresa_nome_fantasia']);
            $this->set('nome_relatorio', 'Relatório de Camareira');

            $this->set('serdocpes_dados', $pesquisa_servicos['serdocpes_dados']);
            $this->set('serrefexi_dados', $pesquisa_servicos['serrefexi_dados']);
            $this->set($info_tela);
            $this->viewBuilder()
                    ->options(['config' => [
                            'filename' => 'relatorioCamareira',
                        ],
            ]);
            
            Configure::write('CakePdf', [
                'engine' => [
                    'className' => 'CakePdf.DomPdf'
                ],
                'orientation' => 'landscape',
            ]);
        } else {
            $this->set('serdocdat', Util::convertDataDMY($this->geral->geratudat()));
            $this->set($this->request->data);
            $this->set($info_tela);
            $this->viewBuilder()->setLayout('ajax');
        }
    }

}
