<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Model\Entity\DocumentoConta;
use App\Model\Entity\Reserva;
use App\Utility\Util;
use App\Utility\Paginator;
use App\Controller\PagesController;
use Cake\Core\Configure;

class DocumentocontasController extends AppController {

    private $documento_conta;
    private $reserva;
    private $pagesController;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->documento_conta = new DocumentoConta();
        $this->reserva = new Reserva();
        $this->pagesController = new PagesController();
    }

//Gestao de contas do cliente
    public function conresexi() {
        $info_tela = $this->pagesController->montagem_tela('conresexi');
        $historico_busca = $this->pagesController->consomeHistoricoTela('documentocontas/conresexi');
        $this->request->data = array_merge($this->request->data, $historico_busca);
        if (($this->request->is('post') && isset($this->request->data['ajax']) || isset($this->request->query['resdocnum']) || sizeof($historico_busca) > 0) || isset($this->request->query['forca_busca'])) {

            $selected_radio = 1;
            if (isset($this->request->data['radio_2']))
                $selected_radio = 2;
            else if (isset($this->request->data['radio_3']))
                $selected_radio = 3;

            if (!array_key_exists('opened_acordions', $this->request->data) && array_key_exists('opened_acordions', $this->request->query))
                $this->request->data['opened_acordions'] = $this->request->query['opened_acordions'];

            if (!array_key_exists('opened-acordions', $this->request->data) && array_key_exists('opened-acordions', $this->request->query))
                $this->request->data['opened_acordions'] = $this->request->query['opened-acordions'];
            if (!array_key_exists('ocultar_estornados', $this->request->data) && array_key_exists('ocultar_estornados', $this->request->query))
                $this->request->data['ocultar_estornados'] = $this->request->query['ocultar_estornados'];

            $this->set($this->request->data);

            if ($selected_radio == 1) {
                $documento_numero_busca = isset($this->request->data['resdocnum']) ? $this->request->data['resdocnum'] : $this->request->query['resdocnum'] ?? null;
                if (empty($documento_numero_busca)) {
                    $retorno['mensagem'] = $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 17, 1);
                    $this->session->write('retorno', $retorno);
                } else {
                    $documento_numero = $documento_numero_busca;
                    $dados_reserva_selecionada = $this->reserva->resdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], "rs", $documento_numero);
                    if (sizeof($dados_reserva_selecionada['results']) == 0) {
                        $retorno['mensagem'] = $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 35, 1, $documento_numero);
                        $this->session->write("retorno", $retorno);
                    } else {
                        if (strpos($documento_numero, '-') !== false) {
                            $documento_quarto_item = explode('-', $documento_numero);
                            $pesquisa_contas = $this->documento_conta->conresexi($this->session->read('empresa_selecionada')['empresa_codigo'], $documento_quarto_item[0], $documento_quarto_item[1]);
                        } else {
                            $pesquisa_contas = $this->documento_conta->conresexi($this->session->read('empresa_selecionada')['empresa_codigo'], $documento_numero);
                        }
                        $this->set('radio_checked', '1');
                        $this->set('documento_numero_selecionado', $documento_numero);
                        $this->set('cabecalho_conta', $dados_reserva_selecionada['results']);
                    }
                }
            } else if ($selected_radio == 2) {
                if (empty($this->request->data['cliclicon'])) {
                    $retorno['mensagem'] = $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 17, 1);
                    $this->session->write("retorno", $retorno);
                    $this->redirect('/documentocontas/conresexi/');
                } else {
                    $documento_numero = $this->request->data['documento_numero_selecionado'] ?? 0;
                    $dados_reserva_selecionada = $this->reserva->resdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], "rs", $documento_numero);
                    if (sizeof($dados_reserva_selecionada['results']) == 0) {
                        $retorno['mensagem'] = $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 35, 1, $documento_numero);
                        $this->session->write("retorno", $retorno);
                    } else {
                        if (strpos($this->request->data['documento_numero_selecionado'], '-') !== false) {
                            $documento_quarto_item = explode('-', $this->request->data['documento_numero_selecionado']);
                            $pesquisa_contas = $this->documento_conta->conresexi($this->session->read('empresa_selecionada')['empresa_codigo'], $documento_quarto_item[0], $documento_quarto_item[1]);
                        } else {
                            $pesquisa_contas = $this->documento_conta->conresexi($this->session->read('empresa_selecionada')['empresa_codigo'], $documento_numero);
                        }
                        $this->set('cabecalho_conta', $dados_reserva_selecionada['results']);
                    }
                }
            } else if ($selected_radio == 3) {
                if (empty($this->request->data['resquacod']) || empty($this->request->data['resesttit'])) {
                    $retorno['mensagem'] = $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 17, 1);
                    $this->session->write('retorno', $retorno);
                } else {
                    $documento_numero = $this->reserva->resquaexi($this->request->data['resquacod'], $this->request->data['resesttit'], $this->session->read('empresa_selecionada')['empresa_codigo']);
                    if ($documento_numero == '0') {
                        $retorno['mensagem'] = $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 35, 1, $documento_numero);
                        $this->session->write("retorno", $retorno);
                    } else {
                        if (strpos($documento_numero, '-') !== false) {
                            $documento_quarto_item = explode('-', $documento_numero);
                            $pesquisa_contas = $this->documento_conta->conresexi($this->session->read('empresa_selecionada')['empresa_codigo'], $documento_quarto_item[0], $documento_quarto_item[1]);
                        } else {
                            $pesquisa_contas = $this->documento_conta->conresexi($this->session->read('empresa_selecionada')['empresa_codigo'], $documento_numero);
                        }

                        $dados_reserva_selecionada = $this->reserva->resdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], "rs", $documento_numero);
                        $this->set('documento_numero_selecionado', $documento_numero);
                        $this->set('cabecalho_conta', $dados_reserva_selecionada['results']);
                    }
                }
            }
            $this->set('permite_busca', isset($this->request->data['permite_busca']) ? $this->request->data['permite_busca'] : $this->request->query['permite_busca'] ?? $this->request->query['permite-busca'] ?? null);
            $this->set('radio_checked', $selected_radio);
            //Busca todos os quarto itens encontrados nessas contas (possivelmente uma solução melhor seria fazer isso via ajax lazy)
            $quarto_itens = array();
            if (isset($pesquisa_contas)) {
                foreach ($pesquisa_contas as $conta) {
                    $quarto_itens[$conta['quarto_item']] = $conta['quarto_item'];
                }
            }
            ksort($quarto_itens);

            //Monta a lista de partidas da reserva por quarto item
            foreach ($quarto_itens as $quarto_item)
                $partidas_por_quarto_item[$quarto_item] = $this->reserva->resparexi($this->session->read('empresa_selecionada')['empresa_codigo'], $documento_numero, $quarto_item);

            $this->set('partidas_por_quarto_item', $partidas_por_quarto_item ?? array());
            $this->set('pesquisa_contas', $pesquisa_contas ?? array());


            $contas_quarto_item = array();
            if (isset($pesquisa_contas)) {
                foreach ($pesquisa_contas as $value) {
                    $contas_quarto_item[$value['quarto_item']][] = $value;
                }
                ksort($contas_quarto_item);
            }

            $this->set('contas_quarto_item', $contas_quarto_item);
            $this->set('quarto_itens', $quarto_itens);
            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                $this->response->download('export.csv');
                $_extract = ['data', 'conta_item', 'produto_codigo', 'produto_qtd', 'total_valor'];
                $_header = [$info_tela['rot_gerdattit'], $info_tela['rot_geritetit'], $info_tela['rot_conprocod'], $info_tela['rot_conproqtd'],
                    $info_tela['rot_conpretot'] . " ( " . $this->geral->germoeatr() . " )"];
                $_csvEncoding = "iso-8859-1";
                $_delimiter = ";";
                $data = $pesquisa_contas;
                $_serialize = 'data';
                $this->set(compact('data', '_serialize', '_delimiter','_header', '_extract', '_csvEncoding'));
                $this->viewBuilder()->className('CsvView.Csv');
            }
        } else {
            $this->set($this->request->data);
            $this->set('partidas_lista', array());
//Verifica a permissão para adicionar/modificar conta

            if ($this->geral->geracever('conitecri') != "")
                $geracever_conitecri = "0";
            else
                $geracever_conitecri = "1";

            $this->set('geracever_conitecri', $geracever_conitecri);

            if ($this->geral->geracever('conitemod') != "")
                $geracever_conitemod = "0";
            else
                $geracever_conitemod = "1";

            $this->set('geracever_conitemod', $geracever_conitemod);

            if ($this->geral->geracever('coniteexc') != "")
                $geracever_coniteexc = "0";
            else
                $geracever_coniteexc = "1";

            $this->set('geracever_coniteexc', $geracever_coniteexc);
            $this->set('radio_checked', '1');
            $this->set('permite_busca', '1');
        }
        $this->set($info_tela);

        $this->set('resquacod_list', $this->geral->gercamdom('resquacod', $this->session->read('empresa_selecionada')['empresa_codigo']));
        $this->set('moeda_simbolo', $this->geral->germoeatr());
        $this->set('logo_empresa', $this->session->read('empresa_selecionada')['logo']);

        //Checa as permissões em elementos da tela
        $this->set('ace_conpagcri', $this->geral->gerauuver('documentocontas', 'conpagcri') ? '' : ' disabled ');
        $this->set('ace_conitecri', $this->geral->gerauuver('documentocontas', 'conitecri') ? '' : ' disabled ');
        $this->viewBuilder()->setLayout('ajax');
    }

    public function conitemod() {
        $info_tela = $this->pagesController->montagem_tela('conitemod');
        $historico_busca = $this->pagesController->consomeHistoricoTela('documentocontas/conitemod');
        $this->request->data = array_merge($this->request->data, $historico_busca);

        if ($this->request->is('post') && isset($this->request->data['ajax']) || sizeof($historico_busca) > 0) {
            if (isset($this->request->data['desc_cortesia_tmp'])) {
                //se houve aplicação de um desconto
                if ($this->request->data['desc_cortesia_tmp'] == 'd' || $this->request->data['desc_cortesia_tmp'] == 'c')
                    $total_valor = Util::uticonval_br_us($this->request->data['conproqtd']) * Util::uticonval_br_us($this->request->data['conpreuni'] ?? 0) - $this->request->data['gerdesval_tmp'] ?? 0;
                //se houve aplicação de um acréscimo
                else
                    $total_valor = Util::uticonval_br_us($this->request->data['conproqtd']) * Util::uticonval_br_us($this->request->data['conpreuni'] ?? 0) + $this->request->data['gerdesval_tmp'] ?? 0;
            } else
                $total_valor = Util::uticonval_br_us($this->request->data['conproqtd']) * Util::uticonval_br_us($this->request->data['conpreuni'] ?? 0);

            $retorno = $this->documento_conta->conitemod($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['resdocnum_modificar'], $this->request->data['quarto_item'], $this->request->data['geritetit'], Util::convertDataSql($this->request->data['gerdattit']), $total_valor, $this->request->data['convenpon'], $this->request->data['conprocod'] ?? '1', Util::uticonval_br_us($this->request->data['conproqtd']), Util::uticonval_br_us($this->request->data['conpreuni']), $this->request->data['desc_cortesia_tmp'], $this->request->data['gerdestip_tmp'], $this->request->data['gerdesfat_tmp'], $this->request->data['gerdesval_tmp'], $this->request->data['gertipmot_tmp'], $this->request->data['gerusucod_tmp'], $this->request->data['servico_taxa_incide'], $this->request->data['gerobstit_tmp']);
            $this->session->write('retorno', $retorno);
        } else {
            //Verificação do modo de exibição (dialog [post] ou tela [get])
            if ($this->request->is('post')) {
                $empresa_codigo = $this->request->data['empresa_codigo'];
                $documento_numero = $this->request->data['documento_numero'];
                $quarto_item = $this->request->data['quarto_item'];
                $conta_item = $this->request->data['conta_item'];
                $redirect_page = $this->request->data['redirect_page'];
                $this->set('modo_exibicao', false);
            } else if ($this->request->is('get')) {
                $empresa_codigo = $this->request->query['empresa_codigo'];
                $documento_numero = $this->request->query['documento_numero'];
                $quarto_item = $this->request->query['quarto_item'];
                $conta_item = $this->request->query['conta_item'];
                $redirect_page = $this->request->query['redirect_page'];
                $this->set('modo_exibicao', true);
            }

            $result = $this->documento_conta->coniteexi($empresa_codigo, $documento_numero, $quarto_item, $conta_item);
            $documento_contas_table = TableRegistry::get('DocumentoContasTabela');

            $geracever_conitemod = "";
            $geracever_coniteexc = "";

            if ($geracever_conitemod == '0' || $result['referenciado_item'] != 0 || $documento_contas_table->findItensReferenciam($empresa_codigo, $documento_numero, $quarto_item, $conta_item)->count() > 0) {
                $this->set('disabled', 'disabled');
            } else {
                $this->set('disabled', "");
            }

            if ($geracever_coniteexc == '0' || ($result['referenciado_item'] != 0 || $documento_contas_table->findItensReferenciam($empresa_codigo, $documento_numero, $quarto_item, $conta_item)->count() > 0)) {
                $this->set('disabled_exc', 'disabled');
            } else {
                $this->set('disabled_exc', "");
            }

            //verifica a servico_taxa_incide por meio da geraceseq
            $parametros = array('produto_codigo' => $result['produto_codigo'], 'empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                'empresa_codigo' => $empresa_codigo, 'venda_ponto_codigo' => "'" . $result['venda_ponto_codigo'] . "'");
            $servico_taxa_incide = $this->geral->geraceseq('servico_taxa_incide', array('servico_taxa_incide'), $parametros)['servico_taxa_incide'];

            $array_map = array('gerempcod' => $empresa_codigo, 'quarto_item' => $quarto_item, 'geritetit' => $result['conta_item'], 'conprocod' => $result['produto_codigo'], 'gerdattit' => Util::convertDataDMY($result['data']),
                'conproqtd' => $result['produto_qtd'], 'conpreuni' => $result['unitario_preco'], 'produto_nome' => $result['produto_nome'],
                'resdocnum_modificar' => $result['documento_numero'], 'convenpon' => $result['venda_ponto_codigo'], 'servico_taxa_incide' => $result['servico_taxa_incide'],
                'contotpre' => $result['total_valor'], 'condesval' => $result['desconto_valor'], 'concontip' => $result['contabil_tipo'],
                'desc_cortesia_tmp' => $result['desconto_cortesia'], 'gerdesfat_tmp' => $result['desconto_fator'], 'gerdestip_tmp' => $result['desconto_tipo'], 'gerdesval_tmp' => $result['desconto_valor'],
                'gertipmot_tmp' => $result['desconto_cortesia_motivo'], 'gerobstit_tmp' => $result['texto'], 'gerusucod_tmp' => $result['desconto_cortesia_usuario'],
                'coniteref' => $result['referenciado_item'], 'servico_taxa_incide' => $servico_taxa_incide, 'criacao_data' => $result['criacao_data'],
                'quarto_status_codigo' => $result['quarto_status_codigo'], 'conta_editavel_preco' => $result['conta_editavel_preco'], 'estornavel' => $result['estornavel']);

            $this->set($array_map);

            $this->set('convenpon_list', $this->geral->gercamdom('convenpon', $this->session->read('empresa_selecionada')["empresa_codigo"]));
            $this->set('gertipmot_list_desc', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'de'")));
            $this->set('gertipmot_list_cort', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'co'")));
            $this->set('gertipmot_list_acre', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'ac'")));

            $produto_grupo_empresa = TableRegistry::get('ProdutoGrupoEmpresas');
            $produto_tipo_codigo = $produto_grupo_empresa->findByProdutoCodigoEEmpresaGrupoCodigo($this->session->read('empresa_selecionada')['empresa_grupo_codigo'], $result['produto_codigo']);

            $this->set('apenas_editavel_desconto', $produto_tipo_codigo['apenas_editavel_desconto']);
            $this->set('produto_tipo_codigo', $produto_tipo_codigo['produto_tipo_codigo']);

            if ($produto_tipo_codigo['produto_tipo_codigo'] == 'PAG') {
                $pesquisa_pagamento = $this->documento_conta->conpagexi($empresa_codigo, $documento_numero, $quarto_item, $conta_item);
                $array_map = array('empresa_nome' => $pesquisa_pagamento['empresa_nome'], 'resdocnum_modificar' => $documento_numero, 'resdocnum' => $documento_numero, 'rescarvez' => $pesquisa_pagamento['cartao_parcela'],
                    'quarto_item' => $quarto_item, 'geritecon' => $conta_item, 'gerdattit' => $pesquisa_pagamento['data'],
                    'pagante_nome' => $pesquisa_pagamento['pagante_nome'], 'pagante_sobrenome' => $pesquisa_pagamento['pagante_sobrenome'],
                    'gervaltit' => $pesquisa_pagamento['total_valor'], 'respagfor' => $pesquisa_pagamento['pagamento_forma_nome'],
                    'resnomcom' => $pesquisa_pagamento['cartao_nome'], 'rescarnum' => $pesquisa_pagamento['cartao_numero'],
                    'rescarval' => $pesquisa_pagamento['cartao_validade'], 'respagbnc' => $pesquisa_pagamento['banco_numero'],
                    'respagcli' => $pesquisa_pagamento['cliente_codigo'],
                    'respagagc' => $pesquisa_pagamento['agencia_numero'], 'respagcco' => $pesquisa_pagamento['conta_numero']
                    , 'conresdep' => $pesquisa_pagamento['deposito_referencia'], 'pagamento_forma_codigo' => $pesquisa_pagamento['pagamento_forma_codigo'],
                    'transferencia_documento_numero' => $pesquisa_pagamento['transferencia_documento_numero'], 'transferencia_quarto_item' => $pesquisa_pagamento['transferencia_quarto_item']);
                $this->set($array_map);
            }

            $gerusucod_list = $this->geral->gercamdom('gerusucod', $this->session->read('empresa_selecionada')["empresa_grupo_codigo"]);
            $this->set('usuario_criacao_nome', $gerusucod_list[array_search($result['criacao_usuario'], array_column($gerusucod_list, 'valor'))]['rotulo']);
        }
        $this->set('geracever_conitemod', $geracever_conitemod);
        $this->set('geracever_coniteexc', $geracever_coniteexc);
        $this->set($info_tela);
        //Checa as permissões em elementos da tela
        $this->set('ace_conpagexi', $this->geral->gerauuver('documentocontas', 'conpagexi') ? '' : ' disabled ');
        $this->set('ace_condesapl', $this->geral->gerauuver('documentocontas', 'condesapl') ? '' : ' disabled ');
        $this->set('ace_coniteexc', $this->geral->gerauuver('documentocontas', 'coniteexc') ? '' : ' disabled ');

        if (sizeof($this->session->read('historico')) > 0)
            $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
        else
            $this->set('pagina_referencia', '');
        $this->viewBuilder()->setLayout('ajax');
    }

    public function conitecri() {
        if ($this->request->is('post') && isset($this->request->data['ajax'])) {
            //Verifica se o tipo de produto pode ser editar o preço
            $unitario_preco = null;
            $unitario_preco_origem = null;
            $horario_modificacao_tipo = null;
            $horario_modificacao_valor = null;

            if ($this->request->data['conta_editavel_preco'] == 1) {
                $unitario_preco = Util::uticonval_br_us($this->request->data['conpreuni']);
                $unitario_preco_origem = 1;
            }

            if ($this->request->data['horario_modificacao_tipo'] != null && $this->request->data['horario_modificacao_tipo'] != '')
                $horario_modificacao_tipo = $this->request->data['horario_modificacao_tipo'];

            if ($this->request->data['horario_modificacao_valor'] != null && $this->request->data['horario_modificacao_valor'] != '')
                $horario_modificacao_valor = $this->request->data['horario_modificacao_valor'];

            //Monta o vetor de motivos de desconto, se necessário
            $motivo = null;
            //Primeiro verifica se veio motivos a partir do desconto comum
            if ($this->request->data['gertipmot_tmp'] != null && $this->request->data['gertipmot_tmp'] != '') {
                $motivos = array();
                if ($this->request->data['desc_cortesia_tmp'] == 'd')
                    $motivo['motivo_tipo_codigo'] = 'de';
                elseif ($this->request->data['desc_cortesia_tmp'] == 'c')
                    $motivo['motivo_tipo_codigo'] = 'co';
                elseif ($this->request->data['desc_cortesia_tmp'] == 'a')
                    $motivo['motivo_tipo_codigo'] = 'ac';

                $motivo['motivo_codigo'] = $this->request->data['gertipmot_tmp'];
                $motivo['motivo_texto'] = $this->request->data['gerobstit_tmp'];
                array_push($motivos, $motivo);
                //Verifica se foi um desconto geral
            } elseif ($this->request->data['gertipmot'] != null && $this->request->data['gertipmot'] != '') {
                $motivos = array();
                if ($this->request->data['concontip'] == 'C')
                    $motivo['motivo_tipo_codigo'] = 'de';
                else
                    $motivo['motivo_tipo_codigo'] = 'ac';

                $motivo['motivo_codigo'] = $this->request->data['gertipmot'];
                $motivo['motivo_texto'] = $this->request->data['gerobstit'];
                array_push($motivos, $motivo);
            }

            $retorno = $this->documento_conta->conitecri($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['documento_numero'], $this->request->data['quarto_item'], Util::convertDataSql($this->request->data['gerdattit']), $this->request->data['convenpon'], explode("|", $this->request->data['conprocod'])[0], Util::uticonval_br_us($this->request->data['conproqtd']), $this->session->read('acesso_perfil_codigo'), $this->request->data['desc_cortesia_tmp'], $this->request->data['gerdestip_tmp'], $this->request->data['gerdesfat_tmp'], $motivos, $unitario_preco, $unitario_preco_origem, null, null, $horario_modificacao_tipo, $horario_modificacao_valor, $this->request->data['inicial_data'] ?? null, $this->request->data['final_data'] ?? null);

            $this->set($this->request->data);
            $this->session->write('retorno', $retorno);
            $this->autoRender = false;
        } else {
            //Verificação do modo de exibição (dialog [post] ou tela [get])
            if ($this->request->is('post')) {
                $quarto_item = $this->request->data['quarto_item'];
                $documento_numero = $this->request->data['documento_numero'];
                $redirect_page = $this->request->data['redirect_page'];
                $inicial_data = $this->request->data['inicial_data'];
                $final_data = $this->request->data['final_data'];
                $quarto_status_codigo = $this->request->data['quarto_status_codigo'];
                $modo_exibicao = 'dialog';
            } else if ($this->request->is('get')) {
                $quarto_item = $this->request->query['quarto_item'];
                $documento_numero = $this->request->query['documento_numero'];
                $redirect_page = $this->request->query['redirect_page'];
                $inicial_data = $this->request->query['inicial_data'];
                $final_data = $this->request->query['final_data'];
                $quarto_status_codigo = $this->request->query['quarto_status_codigo'];
                $modo_exibicao = 'tela';
            }

            $info_tela = $this->pagesController->montagem_tela('conitecri');
            $this->set($info_tela);
            $this->set('convenpon_list', $this->geral->gercamdom('convenpon', $this->session->read('empresa_selecionada')["empresa_codigo"]));
            $this->set('gertipmot_list_desc', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'de'")));
            $this->set('gertipmot_list_cort', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'co'")));
            $this->set('gertipmot_list_acre', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'ac'")));
            $this->set('gerempcod', $this->session->read('empresa_selecionada')['empresa_codigo']);
            $this->set('quarto_item', $quarto_item);
            $this->set('documento_numero', $documento_numero);
            $this->set('inicial_data', $inicial_data);
            $this->set('final_data', $final_data);
            $this->set('quarto_status_codigo_atual', $quarto_status_codigo);

            $this->set('redirect_page', $redirect_page);
            $this->set('modo_exibicao', $modo_exibicao);
            $this->set('ace_condesapl', $this->geral->gerauuver('documentocontas', 'condesapl') ? '' : ' disabled ');
        }

        $this->viewBuilder()->setLayout('ajax');
    }

    public function conpagcri() {

        if ($this->request->is('post') && isset($this->request->data['ajax'])) {
            $data = $this->request->data;
            $quarto_item = $data['quarto_item_atual'];
            $total_pagamento_formas = $data['total_pagamento_formas'];
            //Monta o array de pagamentos
            $pagamento_dados = array();

            if ($data['pagamento_total']) {
                $total_quartos_itens = $data['total_quartos_itens'];
                $pago_primeiro_quarto = 0;
                for ($i = 1; $i <= $total_pagamento_formas; $i++)
                    $pago_primeiro_quarto += $data['forma_valor_' . $i];

                for ($i = 1; $i <= $total_pagamento_formas; $i++) {
                    //Foi selecionado um pagamento
                    if (isset($data['respagfor_' . $i]) && $data['respagfor_' . $i] != "" && $data['forma_valor_' . $i] != "") {
                        $pagamento_dado['pagamento_forma_codigo'] = explode("|", $data['respagfor_' . $i])[0];
                        $pagamento_dado['contabil_tipo'] = explode("|", $data['respagfor_' . $i])[1];
                        $pagamento_dado['valor'] = $pago_primeiro_quarto;
                        $pagamento_dado['data'] = $data['forma_data_' . $i] ?? "";
                        $pagamento_dado['cartao_nome'] = $data['forma_pagante_nome_' . $i] ?? "";
                        $pagamento_dado['cartao_numero'] = $data['forma_cartao_numero_' . $i] ?? "";
                        $pagamento_dado['cartao_parcela'] = $data['forma_cartao_parcela_' . $i] ?? "";
                        $pagamento_dado['cartao_validade'] = $data['forma_cartao_validade_' . $i] ?? "";
                        $pagamento_dado['deposito_referencia'] = $data['forma_referencia_' . $i] ?? "";
                        $pagamento_dado['banco_numero'] = $data['forma_banco_' . $i] ?? "";
                        $pagamento_dado['agencia_numero'] = $data['forma_agencia_' . $i] ?? "";
                        $pagamento_dado['conta_numero'] = $data['forma_conta_corrente_' . $i] ?? "";
                        $pagamento_dado['conta_dv'] = $data['forma_conta_corrente_dv_' . $i] ?? "";
                        $pagamento_dado['pre_autorizacao'] = isset($data['pre_autorizacao_' . $i]) ? 1 : 0;
                        $pagamento_dado['transferencia_documento_numero'] = $data['transferencia_documento_numero_' . $i] ?? "";
                        $pagamento_dado['transferencia_quarto_item'] = $data['transferencia_quarto_item_' . $i] ?? "";
                    }
                    //Cria o vetor de pagantes ou creditados
                    $pagamento_dado['pagante_codigo'] = $data['pag_codigo_' . $i];
                    $pagamento_dado['pagante_nome'] = $data['pagante_nome_' . $i];
                    $pagamento_dado['pagante_cpf_cnpj'] = $data['pagante_cpf_cnpj_' . $i];

                    array_push($pagamento_dados, $pagamento_dado);
                }
                $retorno = $this->documento_conta->conpagcri($this->session->read('empresa_selecionada')['empresa_codigo'], $data['documento_numero'], 1, null, null, $pagamento_dados);
                $pagamento_dados = array();

                //Realiza a transferencia das contas dos outros quartos para o quarto 1
                for ($i = 2; $i <= $total_quartos_itens; $i++) {
                    $pagamento_dado['pagamento_forma_codigo'] = 7;
                    $pagamento_dado['contabil_tipo'] = 'C';
                    $pagamento_dado['valor'] = $data['a_pagar_' . $i];
                    $pagamento_dado['data'] = $this->geral->geragodet(2);
                    $pagamento_dado['transferencia_documento_numero'] = $data['documento_numero'];
                    $pagamento_dado['transferencia_quarto_item'] = 1;

                    //Cria o vetor de pagantes ou creditados
                    $pagamento_dado['pagante_codigo'] = $data['pag_codigo_1'];
                    $pagamento_dado['pagante_nome'] = $data['pagante_nome_1'];
                    $pagamento_dado['pagante_cpf_cnpj'] = $data['pagante_cpf_cnpj_1'];

                    array_push($pagamento_dados, $pagamento_dado);
                    $retorno = $this->documento_conta->conpagcri($this->session->read('empresa_selecionada')['empresa_codigo'], $data['documento_numero'], $i, null, null, $pagamento_dados);
                    $pagamento_dados = array();
                }
            } else {
                for ($i = 1; $i <= $total_pagamento_formas; $i++) {
                    //Se for crédito para cliente
                    if (isset($data['respagfor_' . $i]) && $data['respagfor_' . $i] == '5') {
                        //TESTAR DEPOIS QUANDO FOR CREDITO PRA CLIENTE
                        /* $arr_pagamento_itens[$indice_pagamentos]['pagamento_forma_codigo'] = $this->request->data['respagfor_' . $quarto_item . '_' . $i];
                          $arr_pagamento_itens[$indice_pagamentos]['credito_data'] = $this->request->data['credito_data_' . $quarto_item . '_' . $i];
                          $arr_pagamento_itens[$indice_pagamentos]['credito_prazo_expiracao'] = $this->request->data['credito_prazo_expiracao_' . $quarto_item . '_' . $i];
                          $arr_pagamento_itens[$indice_pagamentos]['credito_data_expiracao'] = $this->request->data['credito_data_expiracao_' . $quarto_item . '_' . $i];
                          $arr_pagamento_itens[$indice_pagamentos]['valor'] = $this->request->data['forma_valor_' . $quarto_item . '_' . $i]; */
                    } else {
                        //Foi selecionado um pagamento
                        if (isset($data['respagfor_' . $i]) && $data['respagfor_' . $i] != "" && $data['forma_valor_' . $i] != "") {
                            $pagamento_dado['pagamento_forma_codigo'] = explode("|", $data['respagfor_' . $i])[0];
                            $pagamento_dado['contabil_tipo'] = explode("|", $data['respagfor_' . $i])[1];
                            $pagamento_dado['valor'] = $data['forma_valor_' . $i];
                            $pagamento_dado['data'] = $data['forma_data_' . $i] ?? "";
                            $pagamento_dado['cartao_nome'] = $data['forma_pagante_nome_' . $i] ?? "";
                            $pagamento_dado['cartao_numero'] = $data['forma_cartao_numero_' . $i] ?? "";
                            $pagamento_dado['cartao_parcela'] = $data['forma_cartao_parcela_' . $i] ?? "";
                            $pagamento_dado['cartao_validade'] = $data['forma_cartao_validade_' . $i] ?? "";
                            $pagamento_dado['deposito_referencia'] = $data['forma_referencia_' . $i] ?? "";
                            $pagamento_dado['banco_numero'] = $data['forma_banco_' . $i] ?? "";
                            $pagamento_dado['agencia_numero'] = $data['forma_agencia_' . $i] ?? "";
                            $pagamento_dado['conta_numero'] = $data['forma_conta_corrente_' . $i] ?? "";
                            $pagamento_dado['conta_dv'] = $data['forma_conta_corrente_dv_' . $i] ?? "";
                            $pagamento_dado['pre_autorizacao'] = isset($data['pre_autorizacao_' . $i]) ? 1 : 0;
                            $pagamento_dado['transferencia_documento_numero'] = $data['transferencia_documento_numero_' . $i] ?? "";
                            $pagamento_dado['transferencia_quarto_item'] = $data['transferencia_quarto_item_' . $i] ?? "";
                        }
                    }
                    //Cria o vetor de pagantes ou creditados
                    $pagamento_dado['contratante_codigo'] = $data['clicadcod'];
                    $pagamento_dado['pagante_codigo'] = $data['pag_codigo_' . $i];
                    $pagamento_dado['pagante_nome'] = $data['pagante_nome_' . $i];
                    $pagamento_dado['pagante_cpf_cnpj'] = $data['pagante_cpf_cnpj_' . $i];

                    array_push($pagamento_dados, $pagamento_dado);
                }
                $retorno = $this->documento_conta->conpagcri($this->session->read('empresa_selecionada')['empresa_codigo'], $data['documento_numero'], $quarto_item, null, null, $pagamento_dados);
            }

            $this->session->write('retorno', $retorno);
            $this->autoRender = false;
        } else {
            $arr_gertelmon = $this->geral->gertelmon($this->session->read('empresa_grupo_codigo_ativo'), 'conpagcri');
            $rotulos = Util::germonrot($arr_gertelmon);
            $this->set($rotulos);
            $this->set(Util::germonfor($arr_gertelmon));
            $this->set(Util::germonpro($arr_gertelmon));
            $this->set(Util::germonval($arr_gertelmon));

            $info_tela = $this->pagesController->montagem_tela('conpagcri');

            //se estiver efetuando o pagamento em apenas um quarto
            if ($this->request->data['pagamento_total'] == 0) {
                //Chama a resapgval
                $retorno_resapgval = $this->reserva->resapgval($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['documento_numero'], $this->request->data['quarto_item'] ?? null, $this->request->data['data'] ?? null, $this->request->data['evento'] ?? null);

                $partidas_por_quarto_item[$this->request->data['quarto_item']] = $this->reserva->resparexi($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['documento_numero'], $this->request->data['quarto_item']);

                $this->set('somatoria_partida_valor', $retorno_resapgval['a_pagar_total']);
                $this->set('quarto_item', $this->request->data['quarto_item']);
                //se estiver efetuando o pagamento total
            } else {
                //Chama a resapgval
                $retorno_resapgval = $this->reserva->resapgval($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['documento_numero'], null, $this->request->data['data'] ?? null, $this->request->data['evento'] ?? null);

                //Monta a lista de partidas da reserva por quarto item
                foreach (explode("|", $this->request->data['quarto_itens']) as $quarto_item)
                    $partidas_por_quarto_item[$quarto_item] = $this->reserva->resparexi($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['documento_numero'], $quarto_item);

                $this->set('somatoria_partida_valor', $retorno_resapgval['a_pagar_total']);
                $this->set('quarto_item', $this->request->data['quarto_item']);
            }

            $this->set($info_tela);
            $this->set('total_quartos_itens', sizeof(explode("|", $this->request->data['quarto_itens'])));
            $this->set('redirect_page', $this->request->data['redirect_page']);
            $this->set('contratante_codigo', $this->request->data['pagante_codigo']);
            $this->set('pagante_codigo', $this->request->data['pagante_codigo']);
            $this->set('pagante_nome', $this->request->data['pagante_nome']);
            $this->set('pagante_cpf_cnpj', $this->request->data['pagante_cpf_cnpj']);
            $this->set('documento_numero', $this->request->data['documento_numero']);
            $this->set('pagamento_total', $this->request->data['pagamento_total']);
            $this->set('partidas_por_quarto_item', $partidas_por_quarto_item);
            $this->set('var_respagfor', $this->reserva->respagfor($this->session->read('empresa_selecionada')['empresa_codigo'], $this->session->read('venda_canal_codigo'), "0"));
        }

        $this->viewBuilder()->setLayout('ajax');
    }

    public function coniteexc() {
        $info_tela = $this->pagesController->montagem_tela('coniteexc');

        if ($this->request->is('post') && isset($this->request->data['ajax'])) {

            //Monta o vetor de motivo da exclusao
            $motivos = array();
            $motivo = null;
            $motivo['motivo_tipo_codigo'] = 'es';
            $motivo['motivo_codigo'] = $this->request->data['motivo_exclusao'];
            $motivo['motivo_texto'] = $this->request->data['observacao_exclusao'];
            array_push($motivos, $motivo);

            $retorno = $this->documento_conta->coniteexc($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['documento_numero'], $this->request->data['quarto_item'], $this->request->data['conta_item'], $motivos);
            $this->session->write('retorno', $retorno);
            $this->autoRender = false;
        } else {
            $this->set($info_tela);
            $this->set('gertipmot_list_exc', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                        'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'es'")));
            $this->set('conta_item', $this->request->data['conta_item']);
            $this->set('documento_numero', $this->request->data['documento_numero']);
            $this->set('servico_taxa_incide', $this->request->data['servico_taxa_incide']);
            $this->set('quarto_item', $this->request->data['quarto_item']);
            $this->set('pagina_referencia', $this->request->data['pagina_referencia']);

            $this->viewBuilder()->setLayout('ajax');
        }
    }

    public function condesapl() {
        if ($this->request->is('post') && isset($this->request->data['ajax'])) {
            $empresa_codigo = $this->request->data['empresa_codigo'];
            $produto_codigo = explode("|", $this->request->data['produto_codigo'])[0];
            $preco_total = $this->request->data['preco_total'];
            $desconto_cortesia = $this->request->data['desconto_cortesia'];
            $desconto_fator = $this->request->data['desconto_fator'];
            $desconto_tipo = $this->request->data['desconto_tipo'];
            $usuario_autorizacao = $this->request->data['usuario_autorizacao'];
            $senha_autorizacao = $this->request->data['senha_autorizacao'];
            $produto_grupo_empresa = TableRegistry::get('ProdutoGrupoEmpresas');
            $produto_tipo_codigo = $produto_grupo_empresa->findByProdutoCodigoEEmpresaGrupoCodigo($this->session->read('empresa_selecionada')['empresa_grupo_codigo'], $produto_codigo);
            $retorno = $this->documento_conta->condesapl($empresa_codigo, $this->session->read('acesso_perfil_codigo'), $produto_codigo, $produto_tipo_codigo->produto_tipo_codigo, $preco_total, $desconto_cortesia, $desconto_fator, $desconto_tipo, $usuario_autorizacao, $senha_autorizacao);
            echo json_encode($retorno);
            $this->autoRender = false;
        } else {
            $info_tela = $this->pagesController->montagem_tela('condesapl');
            $this->set($info_tela);
            $this->set('gertipmot_list_desc', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'de'")));
            $this->set('gertipmot_list_cort', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'co'")));
            $this->set('gertipmot_list_acre', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'ac'")));
            $this->set('preco_anterior', $this->request->data['preco_anterior']);
            $this->set('preco_posterior', $this->request->data['preco_posterior']);
            $this->set('preco_total', $this->request->data['preco_total']);
            $this->set('desc_cortesia', $this->request->data['desconto_cortesia']);
            $this->set('desconto_tipo', $this->request->data['desconto_tipo']);
            $this->set('desconto_fator', $this->request->data['desconto_fator']);
            $this->set('desconto_valor', $this->request->data['desconto_valor']);
            $this->set('observacao', $this->request->data['observacao']);
            $this->set('usuario_codigo', $this->request->data['usuario_codigo']);
            $this->set('usuario_senha', $this->request->data['usuario_senha']);
            $this->set('motivo_cortesia', $this->request->data['motivo_cortesia']);
            $this->set('motivo_desconto', $this->request->data['motivo_desconto']);
            $this->set('motivo_acrescimo', $this->request->data['motivo_acrescimo']);
            $this->set('tipo_conta', $this->request->data['tipo_conta']);
            $this->set('total_desconto', $this->request->data['total_desconto']);
        }
        $this->viewBuilder()->setLayout('ajax');
    }

    public function conpagpes() {
        $info_tela = $this->pagesController->montagem_tela('conpagpes');
        $historico_busca = $this->pagesController->consomeHistoricoTela('documentocontas/conpagpes');
        $this->request->data = array_merge($this->request->data, $historico_busca);

        if ($this->request->is('post') || sizeof($historico_busca) > 0) {

            $data_intervalo = $this->request->data['gerdattit_inicio'] . "|" . $this->request->data['gerdattit_final'];
            $pesquisa_pagamentos = $this->documento_conta->conpagpes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['resdocnum']
                    , $this->request->data['c_codigo'] ?? null, $this->request->data['clicpfcnp'] ?? null, $this->request->data['cliprinom'] ?? null, $this->request->data['clicadema'] ?? null
                    , $this->request->data['clidocnum'] ?? null, $this->request->data['clidoctip'] ?? null
                    , $this->request->data['clicadpap'] ?? null
                    , $data_intervalo, $this->request->data['forma_valor'] ?? null, $this->request->data['respagfor'] ?? null
                    , $this->request->data['forma_cartao_numero'] ?? null, $this->request->data['respagref'] ?? null
                    , $this->request->data['forma_banco'] ?? null, $this->request->data['forma_agencia'] ?? null
                    , $this->request->data['forma_conta_corrente'] ?? null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);

            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                $this->response->download('export.csv');
                $data = $pesquisa_pagamentos['results'];
                $_serialize = 'data';
                $_extract = ['documento_numero', 'data', 'total_valor', 'nome', 'cpf', 'pagamento_forma_nome', 'cartao_numero',
                    'banco_numero', 'agencia_numero', 'conta_numero', 'deposito_referencia'];
                $_header = [$info_tela['rot_restittit'], $info_tela['rot_gerdattit'], $info_tela['rot_resapgval'],
                    $info_tela['rot_respagnom'], $info_tela['rot_conpagcfj'], $info_tela['rot_respagfor'],
                    $info_tela['rot_rescarnum'], $info_tela['rot_respagbnc'], $info_tela['rot_respagagc'],
                    $info_tela['rot_respagcco'], $info_tela['rot_respagref']];
                $_csvEncoding = "iso-8859-1";
                $_delimiter = ";";
                $this->set(compact('data', '_serialize', '_delimiter', '_header', '_extract', '_csvEncoding'));

                $this->viewBuilder()->className('CsvView.Csv');
            } else {
                $this->set('pesquisa_pagamentos', $pesquisa_pagamentos['results']);
                $this->request->data['pesquisar_pagamentos'] = 'yes';
                $this->set($this->request->data);

                //exibe a paginação
                $paginator = new Paginator(10);
                $this->set('paginacao', $paginator->gera_paginacao($pesquisa_pagamentos['filteredTotal'], $this->request->data['pagina'], 'conpagpes', sizeof($pesquisa_pagamentos['results'])));
            }
        } else
            $this->set('ordenacao_sistema', '1');
        $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
        $this->set('var_respagfor', $this->reserva->respagfor($this->session->read('empresa_selecionada')['empresa_codigo'], $this->session->read('venda_canal_codigo'), "0"));
        $this->set('cliente_papeis_lista', $this->geral->gercamdom('clicadpap'));
        $this->set('pagamento_pesquisa_max', $this->geral->gercnfpes('pagamento_pesquisa_max'));
        $this->set('gerempcod', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function conpagexi() {
        $info_tela = $this->pagesController->montagem_tela('conpagexi');

        $empresa_codigo = trim($this->request->params['pass'][0]);
        $documento_numero = trim($this->request->params['pass'][1]);
        $quarto_item = trim($this->request->params['pass'][2]);
        $conta_item = trim($this->request->params['pass'][3]);
        $pesquisa_pagamento = $this->documento_conta->conpagexi($empresa_codigo, $documento_numero, $quarto_item, $conta_item);
        $array_map = array('empresa_nome' => $pesquisa_pagamento['empresa_nome'], 'resdocnum' => $pesquisa_pagamento['documento_numero'],
            'quarto_item' => $quarto_item, 'geritecon' => $pesquisa_pagamento['conta_item'], 'gerdattit' => $pesquisa_pagamento['data'],
            'pagante_nome' => $pesquisa_pagamento['pagante_nome'], 'pagante_sobrenome' => $pesquisa_pagamento['pagante_sobrenome'],
            'gervaltit' => $pesquisa_pagamento['total_valor'], 'respagfor' => $pesquisa_pagamento['pagamento_forma_nome'],
            'resnomcom' => $pesquisa_pagamento['cartao_nome'], 'rescarnum' => $pesquisa_pagamento['cartao_numero'],
            'rescarval' => $pesquisa_pagamento['cartao_validade'], 'rescarvez' => $pesquisa_pagamento['cartao_parcela'], 'respagbnc' => $pesquisa_pagamento['banco_numero'],
            'respagagc' => $pesquisa_pagamento['agencia_numero'], 'respagcco' => $pesquisa_pagamento['conta_numero']
            , 'conresdep' => $pesquisa_pagamento['deposito_referencia'], 'pagamento_forma_codigo' => $pesquisa_pagamento['pagamento_forma_codigo'],
            'transferencia_documento_numero' => $pesquisa_pagamento['transferencia_documento_numero'], 'transferencia_quarto_item' => $pesquisa_pagamento['transferencia_quarto_item']);
        $this->set($array_map);
        $this->set($info_tela);
        if (sizeof($this->session->read('historico')) > 0)
            $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
        else
            $this->set('pagina_referencia', '');
        $this->viewBuilder()->setLayout('ajax');
    }

//Gestao de creditos do cliente
    public function concreexi() {
        $info_tela = $this->pagesController->montagem_tela('concreexi');
        $historico_busca = $this->pagesController->consomeHistoricoTela('documentocontas/concreexi');
        $this->request->data = array_merge($this->request->data, $historico_busca);
        if ($this->request->is('post') || sizeof($historico_busca) > 0) {

            $pesquisa_creditos = $this->documento_conta->concreexi($this->session->read('empresa_selecionada')['empresa_codigo'], $this->session->read('empresa_grupo_codigo_ativo'), $this->request->data['c_codigo'], null, 3, $this->request->data['tipo_lancamento'] ?? 'todos');

            $this->set('credito_saldo', $pesquisa_creditos['credito_saldo']);
            unset($pesquisa_creditos['credito_saldo']);
            $this->set('pesquisa_creditos', $pesquisa_creditos);

            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                $this->response->download('export.csv');
                $data = $pesquisa_creditos['lancamentos'];
                $_serialize = 'data';
                $_extract = ['data', 'documento_numero', 'expiracao_data', 'valor'];
                $_header = [$info_tela['rot_clicadcod'], $info_tela['rot_resdocnum'], $info_tela['rot_concreexp'], $info_tela['rot_concreexp'],
                    $info_tela['rot_gervaltit'] . " (" . $this->geral->germoeatr() . ")"];
                $_csvEncoding = "iso-8859-1";
                $_delimiter = ";";
                $this->set(compact('data', '_serialize', '_delimiter', '_header', '_extract', '_csvEncoding'));

                $this->viewBuilder()->className('CsvView.Csv');
            } else {
                $clientes_table = TableRegistry::get('Clientes');
                $cliente = $clientes_table->findByClienteCodigoEEmpresaGrupoCodigo($this->session->read('empresa_grupo_codigo_ativo'), $this->request->data['c_codigo']);
                $this->set('cliente_pagamento', $cliente);
                $this->set($this->request->data);
            }
        }

        $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
        $this->set('cliente_papeis_lista', $this->geral->gercamdom('clicadpap'));
        $geracever_conitemod = "";
        if ($this->geral->geracever('conitemod') != "") {
            $geracever_conitemod = "0";
        } else {
            $geracever_conitemod = "1";
        }
        $this->set('geracever_conitemod', $geracever_conitemod);

        $geracever_coniteexc = "";
        if ($this->geral->geracever('coniteexc') != "") {
            $geracever_coniteexc = "0";
        } else {
            $geracever_coniteexc = "1";
        }

        if (sizeof($this->session->read('historico')) > 0)
            $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
        else
            $this->set('pagina_referencia', '');
        $this->set('gerempcod', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $this->set('geracever_coniteexc', $geracever_coniteexc);
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function conpfppes() {
        $info_tela = $this->pagesController->montagem_tela('conpfppes');

        if ($this->request->is('post') && isset($this->request->data['ajax']) || (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == 1)) {
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

            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {

                $pesquisa_contas = $this->documento_conta->conpfppes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['resdocnum'] ?? null, null, $this->request->data['c_codigo'] ?? null, $inicial_data, $final_data, $estadia_data, $this->request->data['resquatip'] ?? null, $this->request->data['resdocsta'] ?? null, $this->request->data['consaldze'] ?? null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo']);

                //Formata os valores
                for ($i = 0; $i < sizeof($pesquisa_contas['results']); $i++) {
                    $pesquisa_contas['results'][$i]['inicial_data'] = Util::convertDataDMY($pesquisa_contas['results'][$i]['inicial_data']);
                    $pesquisa_contas['results'][$i]['final_data'] = Util::convertDataDMY($pesquisa_contas['results'][$i]['final_data']);
                    $pesquisa_contas['results'][$i]['nome'] = $pesquisa_contas['results'][$i]['nome'] . ' ' . $pesquisa_contas['results'][$i]['sobrenome'];
                    $pesquisa_contas['results'][$i]['saldo_a_pagar'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['saldo_a_pagar']);
                    $pesquisa_contas['results'][$i]['total_despesas'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_despesas']);
                    $pesquisa_contas['results'][$i]['total_cartao'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_cartao']);
                    $pesquisa_contas['results'][$i]['total_dinheiro'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_dinheiro']);
                    $pesquisa_contas['results'][$i]['total_transferencia_deposito'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_transferencia_deposito']);
                    $pesquisa_contas['results'][$i]['total_cheque'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_cheque']);
                    $pesquisa_contas['results'][$i]['total_faturado'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_faturado']);
                    $pesquisa_contas['results'][$i]['total_debito'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_debito']);
                    $pesquisa_contas['results'][$i]['total_credito'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_credito']);
                    $pesquisa_contas['results'][$i]['total_transferencia_entre_contas'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_transferencia_entre_contas']);
                }
                $this->response->download('export.csv');
                $data = $pesquisa_contas['results'];
                $_serialize = 'data';
                $_extract = ['quarto_codigo', 'inicial_data', 'final_data', 'documento_numero',  'nome', 'saldo_a_pagar', 'total_despesas', 'total_cartao','total_debito',
                    'total_dinheiro', 'total_transferencia_deposito', 'total_cheque', 'total_faturado', 'total_transferencia_entre_contas', 'total_credito'];
                $_header = [$info_tela['rot_resquacod'], $info_tela['rot_gerchitit'], $info_tela['rot_gerchotit'], $info_tela['rot_resdocnum'], $info_tela['rot_resdochos'], $info_tela['rot_consalpag'], $info_tela['rot_contotdes'],
                   'Cartão crédito', 'Cartão débito', $info_tela['rot_condintit'], $info_tela['rot_contratit'], $info_tela['rot_conchetit'], $info_tela['rot_confattit'], $info_tela['rot_contrctit'], 'Crédito'];
                $_csvEncoding = "iso-8859-1";
                $_delimiter = ";";
                $this->set(compact('data', '_serialize', '_delimiter', '_header', '_extract', '_csvEncoding'));

                $this->viewBuilder()->className('CsvView.Csv');
            } else {

                if (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == 1) {
                    $pagina = null;
                } else {
                    $pagina = $this->request->data['pagina'] ?? 1;
                }

                $pesquisa_contas = $this->documento_conta->conpfppes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['resdocnum'] ?? null, null, $this->request->data['c_codigo'] ?? null, $inicial_data, $final_data, $estadia_data, $this->request->data['resquatip'] ?? null, $this->request->data['resdocsta'] ?? null, $this->request->data['consaldze'] ?? null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $pagina);
                $this->set('pesquisa_contas', $pesquisa_contas['results']);
                $this->set('somas', $pesquisa_contas['somas']);
                $this->set($this->request->data);
                if (!$this->request->data['export_pdf'] == 1) {
                    //exibe a paginação
                    $paginator = new Paginator(10);
                    $this->set('paginacao', $paginator->gera_paginacao($pesquisa_contas['filteredTotal'], $this->request->data['pagina'], 'conpfppes', sizeof($pesquisa_contas['results'])));
                }
            }
        } else {
            $gerdattip = 'estadia';
            $estadia_data['inicial'] = $this->geral->geragodet(1);
            $estadia_data['final'] = Util::addYear($this->geral->geragodet(1), 1);
            $ordenacao_coluna = "inicial_data|final_data|";
            $ordenacao_tipo = "desc|desc|";

            $pesquisa_contas = $this->documento_conta->conpfppes($this->session->read('empresa_selecionada')['empresa_codigo'], null, null, null, null, null, $estadia_data, null, null, null, $ordenacao_coluna, $ordenacao_tipo, 1);

            $this->set('pesquisa_contas', $pesquisa_contas['results']);
            $this->set('somas', $pesquisa_contas['somas']);
            $this->set('gerdattip', $gerdattip);
            $this->set('gerdatini', Util::convertDataDMY($estadia_data['inicial']));
            $this->set('gerdatfin', Util::convertDataDMY($estadia_data['final']));
            $this->set('ordenacao_sistema', '1');
            $this->set('ordenacao_coluna', $ordenacao_coluna);
            $this->set('ordenacao_tipo', $ordenacao_tipo);
            //exibe a paginação
            $paginator = new Paginator(10);
            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_contas['filteredTotal'], 1, 'conpfppes', sizeof($pesquisa_contas['results'])));
        }
        $this->set('gerdomsta_list', $this->geral->gercamdom('resdocsta', 'rs'));
        $resquatip_list = $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $this->set('resquatip_list', $resquatip_list);
        $this->set('gerquacod_list', $this->geral->gercamdom('resquacod', $this->session->read('empresa_selecionada')['empresa_codigo']));
        $reserva_status_list = $this->geral->gercamdom('resdocsta', 'rs');
        $this->set('reserva_status_list', $reserva_status_list);
        $this->set($info_tela);
        if (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == '1') {
            $texto_filtros = "";
            $arr_filtros = array();
            foreach (array_keys($this->request->data) as $chave) {
                if (!empty($this->request->data[$chave])) {
                    switch ($chave) {
                        case 'gerdattip':
                            if ($this->request->data['gerdatini'] != $this->request->data['gerdatfin'])
                                $arr_filtros[] = 'Data de ' . $this->request->data['gerdattip'] . ': ' . $this->request->data['gerdatini'] . ' à ' . $this->request->data['gerdatfin'];
                            else
                                $arr_filtros[] = 'Data de ' . $this->request->data['gerdattip'] . ': ' . $this->request->data['gerdatini'];
                            break;
                        case 'resdocsta':
                            $texto_status = 'Status: ';
                            foreach ($this->request->data['resdocsta'] as $status_item)
                                $texto_status .= $reserva_status_list[array_search($status_item, array_column($reserva_status_list, 'valor'))]['rotulo'] . ', ';

                            $texto_status = substr($texto_status, 0, -2);
                            $arr_filtros[] = $texto_status;
                            break;
                        case 'resquatip':
                            $texto_quarto_tipo = 'Tipo de quarto: ';
                            foreach ($this->request->data['resquatip'] as $quarto_tipo_item)
                                $texto_quarto_tipo .= $resquatip_list[array_search($quarto_tipo_item, array_column($resquatip_list, 'valor'))]['rotulo'] . ', ';

                            $texto_quarto_tipo = substr($texto_quarto_tipo, 0, -2);
                            $arr_filtros[] = $texto_quarto_tipo;
                            break;
                        case 'cliprinom':
                            $arr_filtros[] = 'Cliente: ' . $this->request->data['cliprinom'];
                            break;
                        case 'consaldze':
                            $arr_filtros[] = 'Saldo diferente de zero';
                            break;
                    }
                }
            }

            $texto_filtros = implode('<br />', $arr_filtros);

            $this->set('texto_filtros', $texto_filtros);

            $this->viewBuilder()
                    ->options(['config' => [
                            'filename' => 'impressaoConpfppes'
                        ]
            ]);
            Configure::write('CakePdf', [
                'engine' => [
                    'className' => 'CakePdf.DomPdf'
                ],
                'orientation' => 'landscape',
            ]);
        } else {
            $this->viewBuilder()->setLayout('ajax');
        }
    }

    public function conitepes() {
        $info_tela = $this->pagesController->montagem_tela('conitepes');
        if ($this->request->is('post') && isset($this->request->data['ajax']) || (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == 1)) {
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

            $competencia_data['inicial'] = $this->request->data['condatlan_inicio'] ?? null;
            $competencia_data['final'] = $this->request->data['condatlan_final'] ?? null;

            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                $pesquisa_contas = $this->documento_conta->conitepes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['resdocnum'] ?? null, null, $this->request->data['c_codigo'] ?? null, 
                        'rs', array('1', '2', '3', '4'), $inicial_data, $final_data, $estadia_data, $competencia_data, $this->request->data['resquacod'] ?? null, $this->request->data['progrutip'] ?? null,
                        $this->request->data['conprocod'] ?? null, $this->request->data['resvndcan'] ?? null, $this->request->data['respagagc'] ?? null, $this->request->data['convenpon'] ?? null,
                        $this->request->data['resquatip'] ?? null, null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo']);

                //Formata os valores
                for ($i = 0; $i < sizeof($pesquisa_contas['results']); $i++) {
                    $pesquisa_contas['results'][$i]['inicial_data'] = Util::convertDataDMY($pesquisa_contas['results'][$i]['inicial_data']);
                    $pesquisa_contas['results'][$i]['final_data'] = Util::convertDataDMY($pesquisa_contas['results'][$i]['final_data']);
                    $pesquisa_contas['results'][$i]['documento_numero'] = $pesquisa_contas['results'][$i]['documento_numero'] . '_' . $pesquisa_contas['results'][$i]['quarto_item'];
                    $pesquisa_contas['results'][$i]['data'] = Util::convertDataDMY($pesquisa_contas['results'][$i]['data']);
                    $pesquisa_contas['results'][$i]['produto_qtd'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['produto_qtd']);
                    $pesquisa_contas['results'][$i]['unitario_preco'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['unitario_preco']);
                    $pesquisa_contas['results'][$i]['total_valor'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['total_valor']);
                }
                $this->response->download('export.csv');
                $data = $pesquisa_contas['results'];
                $_serialize = 'data';
                $_extract = ['inicial_data', 'final_data', 'quarto_codigo', 'documento_numero', 'data', 'venda_ponto_codigo', 'produto_tipo_codigo', 'produto_nome',
                    'produto_qtd', 'unitario_preco', 'total_valor'];
                $_header = [$info_tela['rot_gerchitit'], $info_tela['rot_gerchotit'], $info_tela['rot_resquacod'], $info_tela['rot_resdocnum'], $info_tela['rot_gerdattit'], $info_tela['rot_convenpon'],
                    $info_tela['rot_progrutip'], $info_tela['rot_conprocod'], $info_tela['rot_conproqtd'], $info_tela['rot_conpreuni'], $info_tela['rot_gertottit']];
                $_csvEncoding = "iso-8859-1";
                $_delimiter = ";";
                $this->set(compact('data', '_serialize', '_delimiter', '_header', '_extract', '_csvEncoding'));

                $this->viewBuilder()->className('CsvView.Csv');
            } else {
                if (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == 1) {
                    $pagina = null;
                } else {
                    $pagina = $this->request->data['pagina'] ?? 1;
                }

                $pesquisa_contas = $this->documento_conta->conitepes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['resdocnum'] ?? null, null, $this->request->data['c_codigo'] ?? null,
                        'rs', array('1', '2', '3', '4'), $inicial_data, $final_data, $estadia_data, $competencia_data, $this->request->data['resquacod'] ?? null, $this->request->data['progrutip'] ?? null, $this->request->data['conprocod'] ?? null, $this->request->data['resvndcan'] ?? null, $this->request->data['respagagc'] ?? null, $this->request->data['convenpon'] ?? null, $this->request->data['resquatip'] ?? null, null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);
                $this->set('pesquisa_contas', $pesquisa_contas['results']);
                $this->set('somas', $pesquisa_contas['somas']);
                $this->set($this->request->data);
                if (!$this->request->data['export_pdf'] == 1) {
                    //exibe a paginação
                    $paginator = new Paginator(10);
                    $this->set('paginacao', $paginator->gera_paginacao($pesquisa_contas['filteredTotal'], $this->request->data['pagina'], 'conitepes', sizeof($pesquisa_contas['results'])));
                }
            }
        } else {
            $ordenacao_coluna = "inicial_data|final_data|";
            $ordenacao_tipo = "desc|desc|";
            $competencia_data['inicial'] = $this->geral->geragodet(1);
            $competencia_data['final'] = $this->geral->geragodet(1);
            $pesquisa_contas = $this->documento_conta->conitepes($this->session->read('empresa_selecionada')['empresa_codigo'], null, null, null, 'rs', array('1', '2', '3', '4'), null, null, null, $competencia_data, null, null, null, null, null, null, null, null, $ordenacao_coluna, $ordenacao_tipo, 1);
            $this->set('pesquisa_contas', $pesquisa_contas['results']);
            $this->set('somas', $pesquisa_contas['somas']);

            //exibe a paginação
            $paginator = new Paginator(10);
            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_contas['filteredTotal'], 1, 'conitepes', sizeof($pesquisa_contas['results'])));
            $this->set('ordenacao_sistema', '1');
            $this->set('condatlan_inicio', Util::convertDataDMY($competencia_data['inicial']));
            $this->set('condatlan_final', Util::convertDataDMY($competencia_data['final']));
            $this->set('ordenacao_coluna', $ordenacao_coluna);
            $this->set('ordenacao_tipo', $ordenacao_tipo);
        }
        $this->set('gerquacod_list', $this->geral->gercamdom('resquacod', $this->session->read('empresa_selecionada')['empresa_codigo']));
        $resquatip_list = $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $this->set('resquatip_list', $resquatip_list);
        $this->set('reserva_status_list', $this->geral->gercamdom('resdocsta', 'rs'));
        $procadtip_list = $this->geral->gercamdom('procadtip', $this->session->read('empresa_selecionada')['empresa_grupo_codigo']);
        $this->set('procadtip_list', $procadtip_list);
        $this->set('resvndcan_list', $this->geral->gercamdom('resvndcan'));
        $provenpon_list = $this->geral->gercamdom('provenpon', $this->session->read('empresa_selecionada')['empresa_grupo_codigo']);
        $this->set('provenpon_list', $provenpon_list);
        $dominio_agencia_viagem = $this->geral->gercamdom('resviaage');
        $elemento_ds = $dominio_agencia_viagem[array_search('ds', array_column($dominio_agencia_viagem, 'valor'))];
        $elemento_da = $dominio_agencia_viagem[array_search('da', array_column($dominio_agencia_viagem, 'valor'))];
        $dominio_agencia_viagem[array_search('ds', array_column($dominio_agencia_viagem, 'valor'))] = $dominio_agencia_viagem[0];
        $dominio_agencia_viagem[array_search('da', array_column($dominio_agencia_viagem, 'valor'))] = $dominio_agencia_viagem[1];
        $dominio_agencia_viagem[0] = $elemento_ds;
        $dominio_agencia_viagem[1] = $elemento_da;
        $this->set('respagagc_list', $dominio_agencia_viagem);
        $this->set($info_tela);
        if (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == '1') {
            $texto_filtros = "";
            $arr_filtros = array();
            foreach (array_keys($this->request->data) as $chave) {
                if (!empty($this->request->data[$chave])) {
                    switch ($chave) {
                        case 'condatlan_inicio':
                            if ($this->request->data['condatlan_inicio'] != $this->request->data['condatlan_final'])
                                $arr_filtros[] = 'Data de lançamento: ' . $this->request->data['condatlan_inicio'] . ' à ' . $this->request->data['condatlan_final'];
                            else
                                $arr_filtros[] = 'Data de lançamento: ' . $this->request->data['condatlan_inicio'];
                            break;
                        case 'progrutip':
                            $arr_filtros[] = 'Tipo de produto: ' . $procadtip_list[array_search($this->request->data['progrutip'], array_column($procadtip_list, 'valor'))]['rotulo'];
                            break;
                        case 'conpronom':
                            $arr_filtros[] = 'Produto: ' . $this->request->data['conpronom'];
                            break;
                        case 'convenpon':
                            $arr_filtros[] = 'Ponto de venda: ' . $provenpon_list[array_search($this->request->data['convenpon'], array_column($provenpon_list, 'valor'))]['rotulo'];
                            break;
                        case 'gerdattip':
                            if ($this->request->data['gerdatini'] != $this->request->data['gerdatfin'])
                                $arr_filtros[] = 'Data de ' . $this->request->data['gerdattip'] . ': ' . $this->request->data['gerdatini'] . ' à ' . $this->request->data['gerdatfin'];
                            else
                                $arr_filtros[] = 'Data de ' . $this->request->data['gerdattip'] . ': ' . $this->request->data['gerdatini'];
                            break;
                        case 'resquatip':
                            $texto_quarto_tipo = 'Tipo de quarto: ';
                            foreach ($this->request->data['resquatip'] as $quarto_tipo_item)
                                $texto_quarto_tipo .= $resquatip_list[array_search($quarto_tipo_item, array_column($resquatip_list, 'valor'))]['rotulo'] . ', ';

                            $texto_quarto_tipo = substr($texto_quarto_tipo, 0, -2);
                            $arr_filtros[] = $texto_quarto_tipo;
                            break;
                        case 'cliprinom':
                            $arr_filtros[] = 'Cliente: ' . $this->request->data['cliprinom'];
                            break;
                        case 'respagagc':
                            $arr_filtros[] = 'Agência: ' . $dominio_agencia_viagem[array_search($this->request->data['respagagc'], array_column($dominio_agencia_viagem, 'valor'))]['rotulo'];
                            break;
                    }
                }
            }

            $texto_filtros = implode('<br />', $arr_filtros);

            $this->set('texto_filtros', $texto_filtros);

            $this->viewBuilder()
                    ->options(['config' => [
                            'filename' => 'impressaoReserva'
                        ]
            ]);
        } else {
            $this->viewBuilder()->setLayout('ajax');
        }
    }

    public function conihopes() {
        $info_tela = $this->pagesController->montagem_tela('conihopes');

        if ($this->request->is('post') && isset($this->request->data['ajax']) || (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == 1)) {
            $competencia_data['inicial'] = $this->request->data['condatlan_inicio'] ?? null;
            $competencia_data['final'] = $this->request->data['condatlan_final'] ?? null;

            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                $pesquisa_contas = $this->documento_conta->conitepes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['resdocnum'] ?? null, null, $this->request->data['c_codigo'] ?? null,
                        'rs', array('1', '2', '3', '4'), null, null, null, $competencia_data, $this->request->data['resquacod'] ?? null, $this->request->data['progrutip'] ?? null, $this->request->data['conprocod'] ?? null, null, null, null, null, 1, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo']);

                //Formata os valores
                for ($i = 0; $i < sizeof($pesquisa_contas['results']); $i++) {
                    $pesquisa_contas['results'][$i]['inicial_data'] = Util::convertDataDMY($pesquisa_contas['results'][$i]['inicial_data']);
                    $pesquisa_contas['results'][$i]['final_data'] = Util::convertDataDMY($pesquisa_contas['results'][$i]['final_data']);
                    $pesquisa_contas['results'][$i]['produto_qtd'] = $this->geral->gersepatr($pesquisa_contas['results'][$i]['produto_qtd']);
                }
                $this->response->download('export.csv');
                $data = $pesquisa_contas['results'];
                $_serialize = 'data';
                $_extract = ['quarto_codigo', 'hospede', 'inicial_data', 'final_data', 'produto_nome', 'produto_qtd'];
                $_header = [$info_tela['rot_resquacod'], $info_tela['rot_resdochos'], $info_tela['rot_gerchitit'], $info_tela['rot_gerchotit'], $info_tela['rot_conprocod'], $info_tela['rot_conproqtd']];
                $_csvEncoding = "iso-8859-1";
                $_delimiter = ";";
                $this->set(compact('data', '_serialize', '_delimiter', '_header', '_extract', '_csvEncoding'));

                $this->viewBuilder()->className('CsvView.Csv');

                $this->viewBuilder()->className('CsvView.Csv');
            } else {
                if (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == 1) {
                    $pagina = null;
                } else {
                    $pagina = $this->request->data['pagina'] ?? 1;
                }

                $pesquisa_contas = $this->documento_conta->conitepes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['resdocnum'] ?? null, null, $this->request->data['c_codigo'] ?? null,
                        'rs', array('1', '2', '3', '4'), null, null, null, $competencia_data, $this->request->data['resquacod'] ?? null, $this->request->data['progrutip'] ?? null, $this->request->data['conprocod'] ?? null, null, null, null, null, 1, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $pagina);

                $this->set('pesquisa_contas', $pesquisa_contas['results']);
                $this->set('somas', $pesquisa_contas['somas']);
                $this->set($this->request->data);

                if (!$this->request->data['export_pdf'] == 1) {
                    $paginator = new Paginator(10);
                    $this->set('paginacao', $paginator->gera_paginacao($pesquisa_contas['filteredTotal'], $this->request->data['pagina'], 'conihopes', sizeof($pesquisa_contas['results'])));
                }
            }
        } else {
            $competencia_data['inicial'] = $this->geral->geragodet(1);
            $competencia_data['final'] = $this->geral->geragodet(1);
            $ordenacao_coluna = "quarto_codigo|";
            $ordenacao_tipo = "asc|";
            $pesquisa_contas = $this->documento_conta->conitepes($this->session->read('empresa_selecionada')['empresa_codigo'], null, null, null, 'rs', array('1', '2', '3', '4'), null, null, null, $competencia_data, null, null, null, null,
                    null, null, null, 1, $ordenacao_coluna, $ordenacao_tipo, 1);

            $this->set('pesquisa_contas', $pesquisa_contas['results']);
            $this->set('somas', $pesquisa_contas['somas']);
            //exibe a paginação
            $paginator = new Paginator(10);
            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_contas['filteredTotal'], 1, 'conihopes', sizeof($pesquisa_contas['results'])));
            $this->set('condatlan_inicio', Util::convertDataDMY($competencia_data['inicial']));
            $this->set('condatlan_final', Util::convertDataDMY($competencia_data['final']));
            $this->set('ordenacao_sistema', '1');
            $this->set('ordenacao_coluna', $ordenacao_coluna);
            $this->set('ordenacao_tipo', $ordenacao_tipo);
        }
        $this->set('gerquacod_list', $this->geral->gercamdom('resquacod', $this->session->read('empresa_selecionada')['empresa_codigo']));
        $this->set('reserva_status_list', $this->geral->gercamdom('resdocsta', 'rs'));
        $procadtip_list = $this->geral->gercamdom('procadtip', $this->session->read('empresa_selecionada')['empresa_grupo_codigo']);
        $this->set('procadtip_list', $procadtip_list);
        $this->set($info_tela);
        if (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == '1') {
            $texto_filtros = "";
            $arr_filtros = array();
            foreach (array_keys($this->request->data) as $chave) {
                if (!empty($this->request->data[$chave])) {
                    switch ($chave) {
                        case 'condatlan_inicio':
                            if ($this->request->data['condatlan_inicio'] != $this->request->data['condatlan_final'])
                                $arr_filtros[] = 'Data de lançamento: ' . $this->request->data['condatlan_inicio'] . ' à ' . $this->request->data['condatlan_final'];
                            else
                                $arr_filtros[] = 'Data de lançamento: ' . $this->request->data['condatlan_inicio'];
                            break;
                        case 'progrutip':
                            $arr_filtros[] = 'Tipo de produto: ' . $procadtip_list[array_search($this->request->data['progrutip'], array_column($procadtip_list, 'valor'))]['rotulo'];
                            break;
                        case 'conpronom':
                            $arr_filtros[] = 'Produto: ' . $this->request->data['conpronom'];
                            break;
                    }
                }
            }

            $texto_filtros = implode('<br />', $arr_filtros);

            $this->set('texto_filtros', $texto_filtros);

            $this->viewBuilder()
                    ->options(['config' => [
                            'filename' => 'impressaoReserva'
                        ],
            ]);
        } else {
            $this->viewBuilder()->setLayout('ajax');
        }
    }

}
