<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Utility\Util;
use App\Utility\GerHtmGer;
use App\Model\Entity\Reserva;
use App\Model\Entity\Estadia;
use App\Model\Entity\Servico;
use App\Model\Entity\DocumentoConta;
use App\Utility\Paginator;
use App\Controller\PagesController;
use Cake\Datasource\ConnectionManager;

class GeralController extends AppController {

    private $pagesController;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->pagesController = new PagesController();
    }

    public function gertelpri() {
        $info_tela = $this->pagesController->montagem_tela('gertelpri');
        $this->session->delete('historico');
        $this->session->delete('paginas_pilha');
        $this->session->delete('is_redirect');

        //Monta o cockpit
        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];
        if ($info_tela['campo_padrao_valor_gerempcod'] == 1 && $info_tela['padrao_valor_gerempcod'] != null && $this->session->check('primeiro_acesso')) {
            $empresa_codigo = $info_tela['padrao_valor_gerempcod'];
            $this->session->delete('primeiro_acesso');
        }

        $quartos_tipos_codigos = $this->geral->gercamdom('resquatip', $empresa_codigo);
        $filtro = array('vazio' => 1, 'ocupado' => 1, 'bloqueado' => 1, 'check_in' => 0, 'check_out' => 0, 'servico' => 0,
            'bloqueio' => 0);
        foreach ($quartos_tipos_codigos as $quarto_tipo_codigo)
            $filtro[$quarto_tipo_codigo['valor']] = 1;

        $estadia = new Estadia();
        $cockpit_dados = $estadia->estpaiatu($empresa_codigo, 'c', $filtro)['estpaiatu_cockpit_dados'];

        $this->set('cockpit_dados', $cockpit_dados);
        $this->set('reserva_total', 0);
        $this->set('logo_empresa', $this->session->read('empresa_selecionada')['logo']);

        if ($this->request->query('p') != null) {
            $this->set('new_gerpagexi', $this->request->query('p'));
        }

        //String de parametros, caso exista
        $parametros_url = '{';
        foreach ($this->request->query as $param => $param_valor) {
            if ($param != 'p') {
                $parametros_url .= "'$param': '$param_valor',";
            }
        }

        //remove a ultima virgula, caso tenha adicionado parametros
        if ($parametros_url != '{')
            $parametros_url = substr($parametros_url, 0, -1);

        $parametros_url .= '}';
        $this->set('parametros_url', $parametros_url);
        $this->set('gerempcod_list', $this->geral->gercamdom('gerempcod', $this->session->read('empresa_selecionada')['empresa_grupo_codigo']));
        $this->set('gergrucod_list', $this->geral->gercamdom('gergrucod'));

        $this->set('empresa_codigo', $empresa_codigo);
        $this->set('empresa_grupo_codigo', $this->session->read('empresa_selecionada')['empresa_grupo_codigo']);
        $this->set($info_tela);
    }

    public function geracever() {
        echo $this->geral->geracever($this->request->data['nome_funcao']);
        $this->autoRender = false;
    }

    public function gerhtmger() {
        $this->set('codigo_gerado', GerHtmGer::gerhtmger());
    }

    function geraceseq() {
        $parametros = array('produto_codigo' => $this->request->data['produto_codigo'],
            'empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
            'empresa_codigo' => $this->request->data['empresa_codigo'],
            'venda_ponto_codigo' => "'" . $this->request->data['venda_ponto_codigo'] . "'");
        $retorno['produto_preco'] = $this->geral->geraceseq('produto_preco', array('preco'), $parametros)['preco'];
        $retorno['servico_taxa_incide'] = $this->geral->geraceseq('servico_taxa_incide', array('servico_taxa_incide'), $parametros)['servico_taxa_incide'];
        echo json_encode($retorno);
        $this->autoRender = false;
    }

    /*
     * Atualiza os dados da empresa selecionada de acordo com o indice selecionada em rescriiini AJAX
     */

    public function gerempsel() {
        $this->autoRender = false;
        $empresa_selecionada = $this->request->data['empresa_selecionada'];
        $this->geral->gerempsel($empresa_selecionada);

        //Atualiza  a quantidade de adultos e crianças
        $reserva = new Reserva();
        $max_adultos = $reserva->resadumax($this->session->read('empresa_selecionada')["empresa_codigo"]);
        $max_criancas = $reserva->rescrimax($this->session->read('empresa_selecionada')["empresa_codigo"], $max_adultos);
        echo $max_adultos . "|" . $max_criancas . "|" . $this->session->read("empresa_selecionada") ["pagante_crianca_idade"] . "|" . $this->session->read("empresa_selecionada")["nao_pagante_crianca_idade"] . "|" . $this->session->read("empresa_selecionada")["diarias_max"];
    }

    public function gergrusel() {

        $empresa_grupo_selecionado = $this->request->data['empresa_grupo_selecionado'];
        $this->geral->gergrusel($empresa_grupo_selecionado);

        $this->autoRender = false;
    }

    public function gerlogexi() {
        $retorno = $this->geral->gerlogexi($this->request->data['tela_codigo'], $this->request->data['idioma'], $this->request->data['empresa_codigo']);
        echo $retorno;

        $this->autoRender = false;
    }

    function gercompes() {
        $info_tela = $this->pagesController->montagem_tela('gercompes');

        if (!$this->request->is('ajax') || isset($_GET['ajax']) || (isset($this->request->data['ajax_form']) && $this->request->data['ajax_form'] == 1)) {

            $historico_busca = $this->pagesController->consomeHistoricoTela('geral/gercompes');
            $this->request->data = array_merge($this->request->data, $historico_busca);
            if ($this->request->is('post') || sizeof($historico_busca) > 0) {
                $envio_planejado_data['comdapini'] = $this->request->data['comdapini'] ?? null;
                $envio_planejado_data['comdapfin'] = $this->request->data['comdapfin'] ?? null;

                //Se tiver exportando para csv, não passa a paginação
                if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                    $pesquisa_comunicacoes = $this->geral->gercompes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['gercomtip'] ?? null, null, $this->request->data['comdocnum'] ?? null, $this->request->data['comdoctip'] ?? null, $this->request->data['gercomdes'] ?? null, $this->request->data['gercomsta'] ?? null, $envio_planejado_data, null, null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], null);

                    $this->response->download('export.csv');
                    $data = $pesquisa_comunicacoes['results'];
                    $_serialize = 'data';
                    $_extract = ['envio_planejada_data', 'documento_tipo_nome', 'documento_numero', 'comunicacao_tipo_nome', 'destinatario_contato',
                        'envio_real_data', 'tentativa_quantidade', 'comunicacao_status_codigo', 'erro_mensagem'];
                    $_header = [$info_tela['rot_comdaptit'], $info_tela['rot_gerdoctip'], $info_tela['rot_resdocnum'], $info_tela['rot_comtipcom'],
                        $info_tela['rot_comcondes'], $info_tela['rot_comdatrea'], $info_tela['rot_comtenenv'], $info_tela['rot_resdocsta'], $info_tela['rot_commsgerr']];
                    $_csvEncoding = "iso-8859-1";
                    $_delimiter = ";";
                    $this->set(compact('data', '_serialize', '_delimiter', '_header', '_extract', '_csvEncoding'));

                    $this->viewBuilder()->className('CsvView.Csv');
                } else {
                    $pesquisa_comunicacoes = $this->geral->gercompes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['gercomtip'] ?? null, null, $this->request->data['comdocnum'] ?? null, $this->request->data['comdoctip'] ?? null, $this->request->data['gercomdes'] ?? null, $this->request->data['gercomsta'] ?? null, $envio_planejado_data, null, null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);

                    $this->set('pesquisa_comunicacoes', $pesquisa_comunicacoes['results']);
                    $this->request->data['pesquisar_comunicacoes'] = 'yes';

                    $this->set($this->request->data);

                    //exibe a paginação
                    $paginator = new Paginator(10);
                    $this->set('paginacao', $paginator->gera_paginacao($pesquisa_comunicacoes['filteredTotal'], $this->request->data['pagina'], 'gercompes', sizeof($pesquisa_comunicacoes['results'])));
                }
            } else
                $this->set('ordenacao_sistema', '1');

            $this->set('gerempcod', $this->session->read('empresa_selecionada')['empresa_codigo'] ?? $this->session->read('empresa_selecionada')['empresa_codigo']);

            $this->set('gerdoctip_list', $this->geral->gercamdom('gerdoctip'));
            $this->set('gercomtip_list', $this->geral->gercamdom('gercomtip'));
            //$this->set('gercomsta_list', $this->geral->gercamdom('gercomsta'));

            $gercomsta_list = $this->geral->gercamdom('gercomsta');
            //Ordena os valores de status de acordo com seu codigo
            usort($gercomsta_list, function($a, $b) {
                return $a['valor'] <=> $b['valor'];
            });
            $this->set('gercomsta_list', $gercomsta_list);


            //Quanto a exibição é feita em dialog
        } else {
            $pesquisa_comunicacoes = $this->geral->gercompes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['gercomtip'] ?? null, $this->request->data['gercomnum'] ?? null, $this->request->data['comdocnum'] ?? null, $this->request->data['comdoctip'] ?? null, $this->request->data['gercomdes'] ?? null, $this->request->data['gercomsta'] ?? null);

            if (count($pesquisa_comunicacoes['registros']) > 0) {
                $retorno = "<table class='table_cliclipes'>
                    <thead>
                            <tr>
                                <th>" . $info_tela['rot_comdaptit'] . "</th>
                                <th>" . $info_tela['rot_comtipcom'] . "</th>
                                <th>" . $info_tela['rot_comcondes'] . "</th>
                                <th>" . $info_tela['rot_comdatrea'] . "</th>
                                <th>" . $info_tela['rot_comtenenv'] . "</th>
                                <th>" . $info_tela['rot_resdocsta'] . "</th>
                                <th>" . $info_tela['rot_commsgerr'] . "</th></tr></thead>";
                foreach ($pesquisa_comunicacoes['registros'] as $value) {
                    $retorno .= "<tr>
                                    <td>" . Util::convertDataDMY($value['envio_planejada_data']) . "</td>                                          
                                    <td>" . $value['comunicacao_tipo_nome'] . "</td>
                                    <td>" . $value['destinatario_contato'] . "</td>
                                    <td>" . Util::convertDataDMY($value['envio_real_data']) . "</td>
                                    <td>" . $value['tentativa_quantidade'] . "</td>
                                    <td>";

                    if ($value['comunicacao_status_codigo'] == 0)
                        $retorno .= $info_tela['rot_comnevtit'];
                    elseif ($value['comunicacao_status_codigo'] == 1)
                        $retorno .= $info_tela['rot_comenvtit'];
                    elseif ($value['comunicacao_status_codigo'] == 2)
                        $retorno .= $info_tela['rot_comenstit'];
                    elseif ($value['comunicacao_status_codigo'] == 3)
                        $retorno .= $info_tela['rot_comeevtit'];
                    $retorno .= "</td>
                                    <td>" . $value['erro_mensagem'] . "</td>
                                </tr>";
                }
                $retorno .= "</table>";
            }else {
                $retorno = $pesquisa_comunicacoes['mensagem'];
            }

            echo $retorno;
            $this->autoRender = false;
        }

        $this->set('comunicacao_pesquisa_max', $this->geral->gercnfpes('comunicacao_pesquisa_max'));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function gercidaut() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $busca = $this->request->query('search');
            $estado_codigo = $this->request->query('estado_codigo');
            $pais = $this->request->query('pais_nome');
            $results = $this->geral->gercidaut($busca, $estado_codigo, $pais);

            $resultArr = array();
            foreach ($results as $result) {
                $resultArr[] = array('label' => $result['cidade_nome'], 'value' => $result['cidade_nome']);
            }
            $this->set('resultArr', $resultArr);
            echo json_encode($resultArr);
        }
    }

    //Call in ajax requests
    public function gerquadis() {

        //Monta a lista de datas
        $datas = explode("|", $this->request->data['datas']);
        if (isset($this->request->data['quarto_codigo']))
            $quarto_codigo = array($this->request->data['quarto_codigo']);
        else
            $quarto_codigo = null;
        $quartos = $this->geral->gerquadis($this->request->data['empresa_codigo'], $datas, 3, array($this->request->data['quarto_tipo_codigo']),
        $quarto_codigo, 3, $this->request->data['documento_numero'] ?? null, $this->request->data['quarto_item'] ?? null);
        $exibir_nome = false;
        $retorno['resultado'] = $quartos['resultado'] ?? null;
        $retorno['quarto_codigo'] = $quartos['quarto_codigo'] ?? array();
        $retorno['exibir_nome'] = $exibir_nome;
        $retorno['mensagem'] = $quartos['mensagem'] ?? '';

        echo json_encode($retorno);
        $this->autoRender = false;
    }

    /*
     * Usada no autocomplete
     */

    public function progeraut() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $texto = $this->request->query('search') ?? null;
            $empresa_codigo = $this->request->query('empresa_codigo') ?? $this->request->data['empresa_codigo'];
            $venda_ponto_codigo = $this->request->query('venda_ponto_codigo') ?? $this->request->data['venda_ponto_codigo'] ?? null;
            $vendavel = $this->request->query('vendavel') ?? $this->request->data['vendavel'] ?? null;
            $quarto_status_codigo = $this->request->query('quarto_status_codigo') ?? $this->request->data['quarto_status_codigo'] ?? null;
            $results = $this->geral->progeraut($texto, null, $this->request->data['produto_codigo'] ?? null, $vendavel ?? null);
            $documento_conta = new DocumentoConta();
            $resultArr = array();
            foreach ($results as $result) {
                if ($result['produto_codigo'] != null) {
                    $parametros = array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $empresa_codigo, 'produto_codigo' => $result['produto_codigo'],
                        'venda_ponto_codigo' => "'" . $venda_ponto_codigo . "'");
                    $geraceseq_dados = $this->geral->geraceseq('produto_preco', array('preco'), $parametros);
                    $geraceseq_taxa = $this->geral->geraceseq('servico_taxa_incide', array('servico_taxa_incide'), $parametros);
                    $geraceseq_horario_modificado = $this->geral->geraceseq('horario_modificacao_tipo', array('horario_modificacao_tipo', 'horario_modificacao_valor'), $parametros);
                    //if(sizeof($geraceseq_horario_modificado) > 0)
                    //    $geraceseq_horario_modificado = $geraceseq_horario_modificado[0];
                    //Habilita ou desabilita o desconto
                    $desconto_habilita = $documento_conta->condeshab($quarto_status_codigo, $result['produto_tipo_codigo'], $result['conta_editavel_preco']);

                    //Identifica a variavel_fator_nome

                    $fator = $this->geral->gercamdom('profatvar', $result['preco_fator_codigo']);
                    if (is_array($fator) && sizeof($fator) > 0)
                        $variavel_fator_nome = $fator[0]['rotulo'];
                    else
                        $variavel_fator_nome = "";

                    $resultArr[] = array('label' => $result['produto_codigo'] . ' - ' . $result['nome'], 'value' => $result['nome'], 'contabil_tipo' => $result['contabil_tipo'],
                    'variavel_fator_nome' => $variavel_fator_nome, 'nome' => $result['nome'], 'produto_codigo' => $result['produto_codigo'],
                    'produto_tipo_codigo' => $result['produto_tipo_codigo'], 'conta_editavel_preco' => $result['conta_editavel_preco'], 'preco_fator_codigo' => $result['preco_fator_codigo'],
                    'horario_modificacao_tipo' => $geraceseq_horario_modificado['horario_modificacao_tipo'] ?? null,
                    'horario_modificacao_valor' => $geraceseq_horario_modificado['horario_modificacao_valor'] ?? null,
                    'preco' => $geraceseq_dados['preco'], 'servico_taxa_incide' => $geraceseq_taxa['servico_taxa_incide'], 'desconto_habilita' => $desconto_habilita);
                }
            }
            $this->set('resultArr', $resultArr);
            echo json_encode($resultArr);
        }
    }

    public function germotpes() {
        $info_tela = $this->pagesController->montagem_tela('germotpes');

        $historico_busca = $this->pagesController->consomeHistoricoTela('geral/germotpes');
        $this->request->data = array_merge($this->request->data, $historico_busca);
        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'] ?? $this->session->read('empresa_selecionada')['empresa_codigo'];
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
            $pesquisa_motivos = $this->geral->germotpes($empresa_codigo, $this->request->data['gerdoctip'] ?? null, $this->request->data['gerdocnum'] ?? null, $this->request->data['quarto_item'] ?? null, $inicial_data, $final_data, $estadia_data, $criacao_data, $this->request->data['germottip'] ?? null, $this->request->data['germotcod'] ?? null, $this->request->data['c_codigo'] ?? null, $this->request->data['gerusucod'] ?? null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);

            $this->set('pesquisa_motivos', $pesquisa_motivos['results']);
            $this->set($this->request->data);
            //exibe a paginação
            $paginator = new Paginator(10);
            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_motivos['filteredTotal'], $this->request->data['pagina'], 'germotpes', sizeof($pesquisa_motivos['results'])));
        } else {
            if ($info_tela['padrao_valor_gerdattip'] != '')
                $gerdattip = $info_tela['padrao_valor_gerdattip'];
            else
                $gerdattip = 'criacao';

            $ordenacao_coluna = "inicial_data|motivo_tipo_codigo|motivo_codigo";
            $ordenacao_tipo = "asc|asc|asc";

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

            $pesquisa_motivos = $this->geral->germotpes($empresa_codigo, null, null, null, $inicial_data, $final_data, $estadia_data, $criacao_data, null, null, null, null, $ordenacao_coluna, $ordenacao_tipo, 1);

            $paginator = new Paginator(10);
            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_motivos['filteredTotal'], 1, 'germotpes', sizeof($pesquisa_motivos['results'])));

            $this->set('pesquisa_motivos', $pesquisa_motivos['results']);
            $this->set('gerdattip', $gerdattip);
            $this->set('ordenacao_coluna', $ordenacao_coluna);
            $this->set('ordenacao_tipo', $ordenacao_tipo);
            $this->set('ordenacao_sistema', '1');
        }


        $this->set($info_tela);

        $vetor_germottip = $this->geral->gercamdom('germottip');


        $vetor_germotcod = array();
        foreach ($vetor_germottip as $motivo_tipo_codigo) {
            $gercamdom_germotcod = $this->geral->gercamdom('germotcod', $motivo_tipo_codigo['valor']);
            foreach ($gercamdom_germotcod as $key => $value) {
                $vetor_germotcod[$motivo_tipo_codigo['valor']][$value['valor']] = $value['rotulo'];
            }
        }

        $this->set('vetor_germotcod', $vetor_germotcod);
        $this->set('vetor_germottip', $this->geral->gercamdom('germottip'));
        $this->set('gerusucod_list', $this->geral->gercamdom('gerusucod', $this->session->read('empresa_selecionada')['empresa_grupo_codigo']));
        $this->set('gerdoctip_list', $this->geral->gercamdom('gerdoctip', "ca,cc,cf,ct", "<>"));

        $vetor_gerusucod = array();
        foreach ($this->geral->gercamdom('gerusucod') as $key => $value) {
            $vetor_gerusucod[$value['valor']] = $value['rotulo'];
        }

        $this->set('vetor_gerusucod', $vetor_gerusucod);

        if (sizeof($this->session->read('historico')) > 0)
            $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
        else
            $this->set('pagina_referencia', '');

        $this->viewBuilder()->setLayout('ajax');
    }

    /*
     * Cria tarifas nulas no banco de dados
     */

    public function gertarcri() {
        $this->geral->gertarcri();
        $this->autoRender = false;
    }

    /*
     * Implantação de empresa
     */

    public function gerempimp() {

        if ($this->request->is('post')) {
            $connection = ConnectionManager::get('default');

            $connection->begin();
            $gerempimp = array();
            if ($this->request->data['form'] ?? "" != "") {
                parse_str($this->request->data['form'], $gerempimp);
            }

            $agora = $this->geral->geragodet(2, $gerempimp['gercodref']);
            $empresa_referencia = $gerempimp['gercodref'];

            $max_empresa_codigo = $connection->execute("SELECT MAX(empresa_codigo) as max_empresa_codigo FROM empresas ")->fetchAll("assoc")[0]['max_empresa_codigo'];

            $empresa_codigo_novo = $max_empresa_codigo + 1;

            try {
                //Se for um novo grupo
                if (isset($gerempimp['gernovgru'])) {
                    $empresa_grupo_codigo = $gerempimp['gercodgru'];
                    $empresa_grupo_codigo_referencia = $connection->execute("SELECT empresa_grupo_codigo FROM empresas WHERE empresa_codigo = :empresa_codigo", ['empresa_codigo' => $empresa_referencia])->fetchAll("assoc")[0]['empresa_grupo_codigo'];

                    //Cadastra o grupo novo
                    $connection->execute("INSERT INTO `empresa_grupos`
                                        (`empresa_grupo_codigo`) VALUES ($empresa_grupo_codigo)");

                    //motivo_empresa_grupos
                    $connection->execute("INSERT INTO `motivo_empresa_grupos`
                                        (`empresa_grupo_codigo`,
                                        `motivo_tipo_codigo`,
                                        `motivo_codigo`,
                                        `motivo_nome`)
                                       SELECT $empresa_grupo_codigo,
                                        `motivo_tipo_codigo`,
                                        `motivo_codigo`,
                                        `motivo_nome` FROM motivo_empresa_grupos  WHERE empresa_grupo_codigo = $empresa_grupo_codigo_referencia ");

                    //acesso_perfis
                    $connection->execute("INSERT INTO `acesso_perfis`
                                        (`empresa_grupo_codigo`,
                                        `acesso_perfil_codigo`,
                                        `acesso_perfil_nome`,
                                        `criacao_data`,
                                        `excluido`)
                                       SELECT $empresa_grupo_codigo,
                                        `acesso_perfil_codigo`,
                                        `acesso_perfil_nome`,
                                        '" . $agora . "',
                                        `excluido` FROM acesso_perfis WHERE empresa_grupo_codigo = $empresa_grupo_codigo_referencia ");

                    //tela_elementos
                    $connection->execute("INSERT INTO `tela_elementos`
                                        (`empresa_grupo_codigo`,
                                        `tela_codigo`,
                                        `elemento_codigo`,
                                        `campo_propriedade`,
                                        `campo_padrao_valor`)
                                       SELECT $empresa_grupo_codigo,
                                        `tela_codigo`,
                                        `elemento_codigo`,
                                        `campo_propriedade`,
                                        `campo_padrao_valor` FROM tela_elementos WHERE empresa_grupo_codigo = $empresa_grupo_codigo_referencia ");

                    //acesso_url_empresa_grupo_perfil
                    $connection->execute("INSERT INTO `acesso_url_empresa_grupo_perfil`
                                        (`empresa_grupo_codigo`,
                                        `acesso_perfil_codigo`,
                                        `controle`,
                                        `acao`)
                                       SELECT $empresa_grupo_codigo,
                                        `acesso_perfil_codigo`,
                                        `controle`,
                                        `acao`  FROM acesso_url_empresa_grupo_perfil WHERE empresa_grupo_codigo = $empresa_grupo_codigo_referencia ");

                    //produto_empresa_grupos (apenas uma lista)
                    $connection->execute("INSERT INTO `produto_empresa_grupos`
                                        (`empresa_grupo_codigo`,
                                        `produto_codigo`,
                                        `nome`,
                                        `descricao`,
                                        `produto_tipo_codigo`,
                                        `adicional`,
                                        `imagem`,
                                        `preco`,
                                        `preco_fator_codigo`,
                                        `variavel_fator_codigo`,
                                        `fixo_fator_codigo`,
                                        `servico_taxa_incide`,
                                        `contabil_tipo`,
                                        `modificacao_usuario`,
                                        `criacao_data`,
                                        `criacao_usuario`,
                                        `modificacao_data`,
                                        `excluido`,
                                        `conta_editavel_preco`,
                                        `horario_modificacao_tipo`,
                                        `horario_modificacao_valor`,
                                        `estornavel`,
                                        `automatica_criacao_codigo`)
                                       SELECT $empresa_grupo_codigo,
                                        `produto_codigo`,
                                        `nome`,
                                        `descricao`,
                                        `produto_tipo_codigo`,
                                        `adicional`,
                                        `imagem`,
                                        `preco`,
                                        `preco_fator_codigo`,
                                        `variavel_fator_codigo`,
                                        `fixo_fator_codigo`,
                                        `servico_taxa_incide`,
                                        `contabil_tipo`,
                                        `modificacao_usuario`,
                                        '" . $agora . "',
                                        `criacao_usuario`,
                                        `modificacao_data`,
                                        `excluido`,
                                        `conta_editavel_preco`,
                                        `horario_modificacao_tipo`,
                                        `horario_modificacao_valor`,
                                        `estornavel`,
                                        `automatica_criacao_codigo` FROM produto_empresa_grupos WHERE 
                                        empresa_grupo_codigo = $empresa_grupo_codigo_referencia AND produto_codigo IN(1,200,201,202,203,204,205, 301, 302, 303, 306,307,351,353, 356, 357) ");
                } else {
                    $empresa_grupo_codigo = $connection->execute("SELECT empresa_grupo_codigo FROM empresas WHERE empresa_codigo = :empresa_codigo", ['empresa_codigo' => $empresa_referencia])->fetchAll("assoc")[0]['empresa_grupo_codigo'];
                    $empresa_grupo_codigo_referencia = $empresa_grupo_codigo;
                }

                //Duplica o cadastro da empresa, alterando apenas os dados inseridos em tela   
                $this->gerempmod($empresa_referencia, $empresa_codigo_novo, $empresa_grupo_codigo, $gerempimp['gerempnom'], $gerempimp['gerempraz'], $gerempimp['gerempins'], $gerempimp['gerempcnp']
                        , $gerempimp['gerempema'], $gerempimp['geremptdd'], $gerempimp['geremptnu'], $gerempimp['gerempcep'], $gerempimp['geremppai']
                        , $gerempimp['gerempest'], $gerempimp['gerempcid'], $gerempimp['gerempbai'], $gerempimp['geremplog'], $gerempimp['gerempnum'], $gerempimp['geremptax']);

                //Cadastra os usuarios
                for ($i = 0; $i < sizeof($gerempimp['gerusunom']); $i++) {
                    if ($gerempimp['gerusunom'][$i] != null && $gerempimp['gerusunom'][$i] != "") {
                        //Busca os campos usuario_area e acesso_perfil_codigo
                        $usuario_dados = $connection->execute("SELECT usuario_area, acesso_perfil_codigo FROM usuarios WHERE empresa_grupo_codigo = $empresa_grupo_codigo_referencia AND usuario_cargo = '" . $gerempimp['gerusucar'][$i] . "' LIMIT 1")->fetchAll('assoc');

                        $connection->execute("INSERT INTO usuarios (empresa_grupo_codigo, venda_canal_codigo, nome, usuario_email, usuario_login, "
                                . "usuario_area, usuario_cargo, usuario_senha,  acesso_perfil_codigo, login_tentativa_qtd, criacao_data) "
                                . "VALUES ($empresa_grupo_codigo, 0, '" . $gerempimp['gerusunom'][$i] . " " . $gerempimp['gerususob'][$i] . "', '" . $gerempimp['gerusuema'][$i] . "',"
                                . " '" . $gerempimp['gerusulog'][$i] . "',  '" . $usuario_dados[0]['usuario_area'] . "', '" . $gerempimp['gerusucar'][$i] . "',"
                                . " '*A4B6157319038724E3560894F7F932C8886EBFCF', '" . $usuario_dados[0]['acesso_perfil_codigo'] . "', 0 , '" . $agora . "') ");

                        //Cria os itens de comunicação para usuário alterar a senha
                        $destinatario_contato['destinatario_contato'] = $gerempimp['gerusuema'][$i];
                        $destinatario_contato['destinatario_nome'] = $gerempimp['gerusunom'][$i];
                        $destinatario_contato['destinatario_sobrenome'] = $gerempimp['gerususob'][$i];

                        $this->geral->gercomcri($empresa_codigo_novo, 'us', $gerempimp['gerusulog'][$i], $gerempimp['gerusunom'][$i], $gerempimp['gerususob'][$i], array($destinatario_contato));
                    }
                }

                //Salva os tipos de quarto            
                for ($i = 0; $i < sizeof($gerempimp['gerqticod']); $i++) {
                    if ($gerempimp['gerqticod'][$i] != null && $gerempimp['gerqticod'][$i] != "" && $gerempimp['gerqtinom'][$i] != null && $gerempimp['gerqtinom'][$i] != "") {
                        $max_adultos = 0;
                        $max_criancas = 0;
                        $nome_curto = "";
                        if ($gerempimp['germaxadu'][$i] != "")
                            $max_adultos = $gerempimp['germaxadu'][$i];
                        if ($gerempimp['germaxcri'][$i] != "")
                            $max_criancas = $gerempimp['germaxcri'][$i];
                        if ($gerempimp['gerqtincu'][$i] != "")
                            $nome_curto = $gerempimp['gerqtincu'][$i];

                        $connection->execute("INSERT INTO quarto_tipos (empresa_codigo, quarto_tipo_codigo, quarto_tipo_nome, adulto_quantidade, "
                                . "crianca_quantidade, acesso_sequencia_codigo, quarto_tipo_curto_nome, excluido, criacao_data) "
                                . "VALUES ($empresa_codigo_novo, '" . $gerempimp['gerqticod'][$i] . "', '" . $gerempimp['gerqtinom'][$i] . "', " . $max_adultos . ","
                                . "" . $max_criancas . ", 0, '" . $nome_curto . "', 0 , '" . $agora . "') ");
                    }
                }

                //Salva os quartos           
                for ($i = 0; $i < sizeof($gerempimp['gerquanum']); $i++) {
                    if ($gerempimp['gerquanum'][$i] != null && $gerempimp['gerquanum'][$i] != "")
                        $connection->execute("INSERT INTO quartos (empresa_codigo, quarto_codigo, quarto_nome, quarto_tipo_codigo, excluido, criacao_data) "
                                . "VALUES ($empresa_codigo_novo, '" . $gerempimp['gerquanum'][$i] . "', '', "
                                . "" . $gerempimp['gerquaqti'][$i] . ", 0 , '" . $agora . "') ");
                }

                //Cadastra os produtos
                $connection->execute("INSERT INTO `produto_empresas`
                                        (`empresa_codigo`,
                                        `produto_codigo`,
                                        `descricao`,
                                        `preco`,
                                        `servico_taxa_incide`,
                                        `modificacao_usuario`,
                                        `criacao_data`,
                                        `criacao_usuario`,
                                        `modificacao_data`,
                                        `excluido`,
                                        `horario_modificacao_valor`,
                                        `horario_modificacao_tipo`)
                                        SELECT $empresa_codigo_novo,
                                        `produto_codigo`,
                                        `descricao`,
                                        `preco`,
                                        `servico_taxa_incide`,
                                        `modificacao_usuario`,
                                        `criacao_data`,
                                        `criacao_usuario`,
                                        `modificacao_data`,
                                        `excluido`,
                                        `horario_modificacao_valor`,
                                        `horario_modificacao_tipo` FROM produto_empresas  WHERE empresa_codigo = $empresa_referencia  AND produto_codigo IN(1,200,201,202,203,204,205, 301, 302, 303, 306,307,351,353, 356, 357)");

                //Inicio das duplicidades dos cadastros em tabelas diversas
                //motivo_empresas
                $connection->execute("INSERT INTO `motivo_empresas`
                                        (`empresa_codigo`,
                                        `motivo_tipo_codigo`,
                                        `motivo_codigo`,
                                        `motivo_nome`)
                                       SELECT $empresa_codigo_novo,
                                        `motivo_tipo_codigo`,
                                        `motivo_codigo`,
                                        `motivo_nome` FROM motivo_empresas  WHERE empresa_codigo = $empresa_referencia");
                //acesso_controle
                $connection->execute("INSERT INTO `acesso_controles`
                                        (`empresa_grupo_codigo`,
                                        `empresa_codigo`,
                                        `acesso_perfil_codigo`,
                                        `objeto_codigo`,
                                        `acesso_tipo`, `criacao_data`)
                                       SELECT $empresa_grupo_codigo,
                                        $empresa_codigo_novo,
                                        `acesso_perfil_codigo`,
                                        `objeto_codigo`,
                                        `acesso_tipo`, '" . $agora . "' FROM acesso_controles  WHERE empresa_codigo = $empresa_referencia AND empresa_grupo_codigo = $empresa_grupo_codigo_referencia");

                //desconto_acesso_perfil_codigo
                $connection->execute("INSERT INTO `desconto_acesso_perfil_codigos`
                                        (`empresa_codigo`,
                                        `acesso_perfil_codigo`,
                                        `max_desconto_percentual`,
                                        `cortesia`,
                                        `max_total_valor`)
                                        SELECT $empresa_codigo_novo,
                                        `acesso_perfil_codigo`,
                                        `max_desconto_percentual`,
                                        `cortesia`,
                                        `max_total_valor` FROM desconto_acesso_perfil_codigos  WHERE empresa_codigo = $empresa_referencia");

                //desconto_produto_codigo
                $connection->execute("INSERT INTO `desconto_produto_codigos`
                                        (`empresa_codigo`,
                                        `acesso_perfil_codigo`,
                                        `produto_codigo`,
                                        `max_desconto_percentual`,
                                        `cortesia`,
                                        `max_total_valor`, `criacao_data`)
                                        SELECT $empresa_codigo_novo,
                                        `acesso_perfil_codigo`,
                                        `produto_codigo`,
                                        `max_desconto_percentual`,
                                        `cortesia`,
                                        `max_total_valor`, '" . $agora . "' FROM desconto_produto_codigos  WHERE empresa_codigo = $empresa_referencia");

                //desconto_produto_tipo_codigo
                $connection->execute("INSERT INTO `desconto_produto_tipo_codigos`
                                        (`empresa_codigo`,
                                        `acesso_perfil_codigo`,
                                        `produto_tipo_codigo`,
                                        `max_desconto_percentual`,
                                        `cortesia`,
                                        `max_total_valor`, `criacao_data`)
                                       SELECT $empresa_codigo_novo,
                                        `acesso_perfil_codigo`,
                                        `produto_tipo_codigo`,
                                        `max_desconto_percentual`,
                                        `cortesia`,
                                        `max_total_valor`, '" . $agora . "' FROM desconto_produto_tipo_codigos  WHERE empresa_codigo = $empresa_referencia");


                //calendarios
                $connection->execute("INSERT INTO `calendarios`
                                        (`empresa_codigo`,
                                        `pais_codigo`,
                                        `data`,
                                        `feriado`)
                                       SELECT $empresa_codigo_novo,
                                        `pais_codigo`,
                                        `data`,
                                        `feriado` FROM calendarios  WHERE empresa_codigo = $empresa_referencia");


                //documento_tipo_painel_cor
                $connection->execute("INSERT INTO `documento_tipo_painel_cor`
                                        (`empresa_codigo`,
                                        `documento_tipo_codigo`,
                                        `documento_status_codigo`,
                                        `cor`)
                                       SELECT $empresa_codigo_novo,
                                        `documento_tipo_codigo`,
                                        `documento_status_codigo`,
                                        `cor` FROM documento_tipo_painel_cor  WHERE empresa_codigo = $empresa_referencia");

                //tarifa_tipos
                $connection->execute("INSERT INTO `tarifa_tipos`
                                        (`empresa_codigo`,
                                        `tarifa_tipo_codigo`,
                                        `tarifa_tipo_nome`,
                                        `condicao`, `criacao_data`)
                                       SELECT $empresa_codigo_novo,
                                        `tarifa_tipo_codigo`,
                                        `tarifa_tipo_nome`,
                                        `condicao`, '" . $agora . "' FROM tarifa_tipos WHERE empresa_codigo = $empresa_referencia");


                //Cria tarifas
                $ano_atual = date('Y');
                $proximo_ano = $ano_atual + 1;
                $inicio_data = date('Y-m-d');
                $fim_data = date($proximo_ano . '-m-25');
                $datas_tarifas = array();

                $iDateFrom = mktime(1, 0, 0, substr($inicio_data, 5, 2), substr($inicio_data, 8, 2), substr($inicio_data, 0, 4));
                $iDateTo = mktime(1, 0, 0, substr($fim_data, 5, 2), substr($fim_data, 8, 2), substr($fim_data, 0, 4));

                if ($iDateTo >= $iDateFrom) {
                    array_push($datas_tarifas, date('Y-m-d', $iDateFrom)); // first entry
                    while ($iDateFrom < $iDateTo) {
                        $iDateFrom += 86400; // add 24 hours
                        array_push($datas_tarifas, date('Y-m-d', $iDateFrom));
                    }
                }

                $tarifas_empresa_referencia = $connection->execute("SELECT `tarifa_tipo_codigo` FROM tarifa_tipos WHERE empresa_codigo = $empresa_referencia")->fetchAll('assoc');

                //Para cada quarto tipo, cria uma tarifa vazia até dez do proximo ano
                for ($i = 0; $i < sizeof($gerempimp['gerqticod']); $i++) {
                    if ($gerempimp['gerqticod'][$i] != null && $gerempimp['gerqticod'][$i] != "" && $gerempimp['gerqtinom'][$i] != null && $gerempimp['gerqtinom'][$i] != "") {
                        foreach ($tarifas_empresa_referencia as $tarifas) {
                            $tarifa_tipo_codigo = $tarifas['tarifa_tipo_codigo'];
                            foreach ($datas_tarifas as $data_tarifa) {
                                $connection->execute("INSERT INTO tarifas (`empresa_codigo`,
                                                        `quarto_tipo_codigo`,
                                                        `data`,
                                                        `tarifa_tipo_codigo`,
                                                        `adulto_quantidade`, `criacao_data`)
                                                        VALUES ($empresa_codigo_novo, " . $gerempimp['gerqticod'][$i] . ","
                                        . " '" . $data_tarifa . "', $tarifa_tipo_codigo, 0, '" . $agora . "')");
                            }
                        }
                    }
                }

                //tarifa_tipo_adicionais
                $connection->execute("INSERT INTO `tarifa_tipo_adicionais`
                                        (`empresa_codigo`,
                                        `tarifa_tipo_codigo`,
                                        `produto_codigo`,
                                        `incluido`, `criacao_data`)
                                       SELECT $empresa_codigo_novo,
                                        `tarifa_tipo_codigo`,
                                        `produto_codigo`,
                                        `incluido`, '" . $agora . "' FROM tarifa_tipo_adicionais WHERE empresa_codigo = $empresa_referencia");

                //comunicacao_empresa
                $connection->execute("INSERT INTO `comunicacao_empresa`
                                        (`empresa_codigo`,
                                        `comunicacao_tipo_codigo`,
                                        `comunicacao_meio_codigo`,
                                        `documento_tipo_codigo`,
                                        `remetente_contato`,
                                        `remetente_nome`,
                                        `responder_para`,
                                        `destinatario_papel_codigo`,
                                        `destinatario_cliente_item`,
                                        `assunto`,
                                        `corpo_template`,
                                        `envio_evento`,
                                        `envio_tempo`,
                                        `acesso_expiracao_evento`,
                                        `acesso_expiracao_tempo`)
                                       SELECT $empresa_codigo_novo,
                                        `comunicacao_tipo_codigo`,
                                        `comunicacao_meio_codigo`,
                                        `documento_tipo_codigo`,
                                        `remetente_contato`,
                                        `remetente_nome`,
                                        `responder_para`,
                                        `destinatario_papel_codigo`,
                                        `destinatario_cliente_item`,
                                        `assunto`,
                                        `corpo_template`,
                                        `envio_evento`,
                                        `envio_tempo`,
                                        `acesso_expiracao_evento`,
                                        `acesso_expiracao_tempo` FROM comunicacao_empresa  WHERE empresa_codigo = $empresa_referencia");

                //chaves
                $connection->execute("INSERT INTO `chaves`
                                        (`empresa_codigo`,
                                        `objeto_codigo`,
                                        `chave`,
                                        `usuario_codigo`,
                                        `tentativa_qtd`,
                                        `tentativa_tempo`,
                                        `expiracao_tempo`)
                                       SELECT $empresa_codigo_novo,
                                        `objeto_codigo`,
                                        `chave`,
                                        `usuario_codigo`,
                                        `tentativa_qtd`,
                                        `tentativa_tempo`,
                                        `expiracao_tempo` FROM chaves  WHERE empresa_codigo = $empresa_referencia");

                //pagamento_prazos
                $connection->execute("INSERT INTO `pagamento_prazos`
                                        (`empresa_codigo`,
                                        `pagamento_prazo_codigo`,
                                        `pagamento_prazo_nome`,
                                        `pagamento_prazo_tipo`, `criacao_data`)
                                       SELECT $empresa_codigo_novo,
                                        `pagamento_prazo_codigo`,
                                        `pagamento_prazo_nome`,
                                        `pagamento_prazo_tipo`, '" . $agora . "' FROM pagamento_prazos  WHERE empresa_codigo = $empresa_referencia");

                //pagamento_prazo_parcelas
                $connection->execute("INSERT INTO `pagamento_prazo_parcelas`
                                        (`empresa_codigo`,
                                        `pagamento_prazo_codigo`,
                                        `parcela_codigo`,
                                        `fracao`,
                                        `evento`,
                                        `tempo`,
                                        `pagamento_requerido_evento`, `criacao_data`)
                                      SELECT $empresa_codigo_novo,
                                        `pagamento_prazo_codigo`,
                                        `parcela_codigo`,
                                        `fracao`,
                                        `evento`,
                                        `tempo`,
                                        `pagamento_requerido_evento`, '" . $agora . "' FROM pagamento_prazo_parcelas  WHERE empresa_codigo = $empresa_referencia");


                //reserva_confirmacoes
                $connection->execute("INSERT INTO `reserva_confirmacoes`
                                        (`empresa_codigo`,
                                        `reserva_confirmacao_codigo`,
                                        `reserva_confirmacao_nome`,
                                        `reserva_confirmacao_tipo`,
                                        `valor_tipo`,
                                        `evento`,
                                        `tempo_hora`, `criacao_data`)
                                     SELECT $empresa_codigo_novo,
                                        `reserva_confirmacao_codigo`,
                                        `reserva_confirmacao_nome`,
                                        `reserva_confirmacao_tipo`,
                                        `valor_tipo`,
                                        `evento`,
                                        `tempo_hora`, '" . $agora . "' FROM reserva_confirmacoes  WHERE empresa_codigo = $empresa_referencia");

                //reserva_cancelamentos
                $connection->execute("INSERT INTO `reserva_cancelamentos`
                                        (`empresa_codigo`,
                                        `reserva_cancelamento_codigo`,
                                        `reserva_cancelamento_nome`,
                                        `reserva_cancelamento_tipo`,
                                        `reserva_cancelamento_politica`,
                                        `fracao`,
                                        `evento`,
                                        `tempo_hora`, `criacao_data`)
                                      SELECT $empresa_codigo_novo,
                                        `reserva_cancelamento_codigo`,
                                        `reserva_cancelamento_nome`,
                                        `reserva_cancelamento_tipo`,
                                        `reserva_cancelamento_politica`,
                                        `fracao`,
                                        `evento`,
                                        `tempo_hora`, '" . $agora . "' FROM reserva_cancelamentos  WHERE empresa_codigo = $empresa_referencia");


                //tarifa_tipo_pagamento_prazos
                $connection->execute("INSERT INTO `tarifa_tipo_pagamento_prazos`
                                        (`empresa_codigo`,
                                        `tarifa_tipo_codigo`,
                                        `pagamento_prazo_codigo`,
                                        `tarifa_variacao`, `criacao_data`)
                                     SELECT $empresa_codigo_novo,
                                        `tarifa_tipo_codigo`,
                                        `pagamento_prazo_codigo`,
                                        `tarifa_variacao`, '" . $agora . "' FROM tarifa_tipo_pagamento_prazos  WHERE empresa_codigo = $empresa_referencia");

                //tarifa_tipo_reserva_cancelamentos
                $connection->execute("INSERT INTO `tarifa_tipo_reserva_cancelamentos`
                                        (`empresa_codigo`,
                                        `tarifa_tipo_codigo`,
                                        `reserva_cancelamento_codigo`, `criacao_data`)
                                     SELECT $empresa_codigo_novo,
                                        `tarifa_tipo_codigo`,
                                        `reserva_cancelamento_codigo`, '" . $agora . "' FROM tarifa_tipo_reserva_cancelamentos  WHERE empresa_codigo = $empresa_referencia");

                //tarifa_tipo_reserva_confirmacoes
                $connection->execute("INSERT INTO `tarifa_tipo_reserva_confirmacoes`
                                        (`empresa_codigo`,
                                        `tarifa_tipo_codigo`,
                                        `reserva_confirmacao_codigo`, `criacao_data`)
                                     SELECT $empresa_codigo_novo,
                                        `tarifa_tipo_codigo`,
                                        `reserva_confirmacao_codigo`, '" . $agora . "' FROM tarifa_tipo_reserva_confirmacoes  WHERE empresa_codigo = $empresa_referencia");
            } catch (\Exception $e) {
                $connection->rollback();
                $retorno['mensagem'] = $this->geral->germencri($empresa_referencia, 139, 2, $empresa_codigo_novo, $gerempimp['gerempnom'], null, $e->getMessage())['mensagem'];
                $retorno['retorno'] = 0;
                $retorno['mensagem']['botao_1_texto'] = 'Ok';
                echo json_encode($retorno);
                $this->autoRender = false;
                exit();
            }
            // $connection->rollback();
            $connection->commit();
            $retorno['mensagem'] = $this->geral->germencri($empresa_referencia, 138, 2, $empresa_codigo_novo, $gerempimp['gerempnom'])['mensagem'];
            $retorno['retorno'] = 1;
            echo json_encode($retorno);
            $this->autoRender = false;
        }

        $this->set('dominio_estados_lista', $this->geral->gercamdom('clicadest', $this->session->read('pais_codigo_padrao')));
        $this->set('dominio_cidades_lista', $this->geral->gercamdom('clicadcid'));
        $dominio_paises = $this->geral->gercamdom('clicadpai');
        $this->set('dominio_paises_lista', $dominio_paises);
        $this->set('ddi_padrao', $this->session->read('ddi_padrao'));
        $this->set('pais_nome_padrao', $this->session->read('pais_nome_padrao'));
        $this->set('estado_codigo_padrao', $this->session->read('empresa_selecionada')['estado']);
        $this->set('dominio_ddi_lista', $this->geral->gercamdom('clicelddi'));
        $this->viewBuilder()->setLayout('ajax');
    }

    public function gerempmod($empresa_codigo, $empresa_codigo_novo, $empresa_grupo_codigo, $nome_fantasia, $razao_social, $inscricao_estadual, $cnpj, $email, $tel_ddi, $tel_numero, $cep, $pais, $estado, $cidade, $bairro, $logradouro, $numero, $turismo_taxa) {
        $connection = ConnectionManager::get('default');
        $agora = $this->geral->geragodet(2, $empresa_codigo);
        $nova_empresa = $connection->execute("INSERT INTO empresas (`empresa_codigo`,
                                        `empresa_grupo_codigo`,
                                        `empresa_nome_fantasia`,
                                        `empresa_razao_social`,
                                        `empresa_cnpj`,
                                        `empresa_ie`,
                                        `cama_crianca_idade`,
                                        `nao_pagante_crianca_idade`,
                                        `pagante_crianca_idade`,
                                        `crianca_pagamento_tipo`,
                                        `pagante_crianca_taxa`,
                                        `pagante_crianca_valor`,
                                        `quarto_max_qtd`,
                                        `excluido`,
                                        `diarias_max`,
                                        `externo_motor_nome`,
                                        `externo_motor_url`,
                                        `horario_fuso`,
                                        `reserva_expiracao`,
                                        `reserva_intervalo`,
                                        `logradouro`,
                                        `cep`,
                                        `bairro`,
                                        `estado`,
                                        `cidade`,
                                        `pais_codigo`,
                                        `numero`,
                                        `complemento`,
                                        `email`,
                                        `tel_ddi`,
                                        `tel_ddd`,
                                        `tel_numero`,
                                        `inicial_padrao_horario`,
                                        `final_padrao_horario`,
                                        `email_acesso_checkin_online_expiracao`,
                                        `email_acesso_avaliacao_expiracao`,
                                        `proxima_reserva_arrumacao_prazo`,
                                        `servico_taxa`,
                                        `turismo_taxa`,
                                        `check_out_tardio_tolerancia`,
                                        `hospede_taxa`,
                                        `diaria_taxa`,
                                        `modificacao_usuario`,
                                        `criacao_data`,
                                        `criacao_usuario`,
                                        `modificacao_data`,
                                        `tarifa_manual_entrada`)                                        
                                 SELECT $empresa_codigo_novo,
                                        $empresa_grupo_codigo,
                                        '" . $nome_fantasia . "',
                                        '" . $razao_social . "',
                                        '" . $cnpj . "',
                                        '" . $inscricao_estadual . "',
                                        `cama_crianca_idade`,
                                        `nao_pagante_crianca_idade`,
                                        `pagante_crianca_idade`,
                                        `crianca_pagamento_tipo`,
                                        `pagante_crianca_taxa`,
                                        `pagante_crianca_valor`,
                                        `quarto_max_qtd`,
                                        `excluido`,
                                        `diarias_max`,
                                        `externo_motor_nome`,
                                        `externo_motor_url`,
                                        `horario_fuso`,
                                        `reserva_expiracao`,
                                        `reserva_intervalo`,
                                        '" . $logradouro . "',
                                        '" . $cep . "',
                                        '" . $bairro . "',
                                        '" . $estado . "',
                                        '" . $cidade . "',
                                        '" . $pais . "',
                                        '" . $numero . "',
                                        `complemento`,
                                        '" . $email . "',
                                        '" . $tel_ddi . "',
                                        `tel_ddd`,
                                        '" . $tel_numero . "',
                                        `inicial_padrao_horario`,
                                        `final_padrao_horario`,
                                        `email_acesso_checkin_online_expiracao`,
                                        `email_acesso_avaliacao_expiracao`,
                                        `proxima_reserva_arrumacao_prazo`,
                                        `servico_taxa`,
                                        '" . $turismo_taxa . "',
                                        `check_out_tardio_tolerancia`,
                                        `hospede_taxa`,
                                        `diaria_taxa`,
                                        `modificacao_usuario`,
                                        '" . $agora . "',
                                        `criacao_usuario`,
                                        `modificacao_data`,
                                        `tarifa_manual_entrada` FROM empresas WHERE empresa_codigo = :empresa_codigo", ['empresa_codigo' => $empresa_codigo]);
    }

}
