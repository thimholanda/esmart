<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Utility\Util;
use App\Utility\Paginator;
use App\Model\Entity\Reserva;
use App\Model\Entity\Estadia;
use App\Model\Entity\Servico;
use Cake\ORM\TableRegistry;
use App\Model\Entity\DocumentoConta;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;
use App\Controller\PagesController;

class ReservasController extends AppController {

    private $servico;
    private $reserva;
    private $estadia;
    private $documento_conta;
    private $pagesController;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->reserva = new Reserva();
        $this->servico = new Servico();
        $this->estadia = new Estadia();
        $this->documento_conta = new DocumentoConta();
        $this->connection = ConnectionManager::get('default');
        $this->pagesController = new PagesController();
    }

    public function rescriini() {
        $info_tela = $this->pagesController->montagem_tela('rescriini');

        // Faz a busca do maximo de adultos e crianças
        $max_adultos = $this->reserva->resadumax($this->session->read('empresa_selecionada')["empresa_codigo"]);
        //Verifica se não está voltando da resquatar
        if (!isset($this->request->data['resquatar_volta'])) {
            //Inicializa valores padr�es
            $m_a = $info_tela['padrao_valor_resaduqtd'];
            $m_c = $info_tela['padrao_valor_rescriqtd'];
            $m_e = $this->session->read('empresa_selecionada')["empresa_codigo"] ?? $info_tela['padrao_valor_gerempcod'] ?? "";

            //Carrega os dados de separador e linguagem
            if ($m_e != "")
                $this->geral->gerempsel($m_e);

            if ($m_c == "")
                $m_c = "0";
            if ($m_a == "")
                $m_a = "2";
            if ($m_e == "") {
                $m_e = $this->session->read('empresa_selecionada')["empresa_codigo"];
            }

            $max_criancas = $this->reserva->rescrimax($this->session->read('empresa_selecionada')["empresa_codigo"], $m_a);

            $this->set('var_max_crianca', $max_criancas);
            $m_inicial_data_tmp = $info_tela['padrao_valor_resentdat'];
            $m_final_data_tmp = $info_tela['padrao_valor_ressaidat'];

            if (empty($m_inicial_data_tmp))
                $m_inicial_data_tmp = $this->geral->geratudat();

            if (!empty($m_inicial_data_tmp) && empty($m_final_data_tmp))
                $m_final_data_tmp = Util::convertDataDMY(Util::addDate(Util::convertDataSQL($m_inicial_data_tmp), "1"));

            if (!empty($m_inicial_data_tmp))
                $m_inicial_data = $m_inicial_data_tmp;
            if (!empty($m_final_data_tmp))
                $m_final_data = $m_final_data_tmp;

            $this->set('m_a', $m_a);
            $this->set('m_c', $m_c);
            $this->set('m_e', $m_e);
            $this->set('m_inicial_data', $m_inicial_data);
            $this->set('m_final_data', $m_final_data);
            $this->set('resquaqtd', $info_tela['padrao_valor_resaduqtd'] ?? 1);

            if ($this->session->check('historico') && sizeof($this->session->read('historico')) > 0)
                $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
            else
                $this->set('pagina_referencia', '');

            //se está voltando da resquatar
        }else {
            $m_a = array();
            $m_c = array();
            $max_criancas = array();
            $idade_criancas = array();

            for ($quarto_item = 1; $quarto_item <= $this->request->data['resquaqtd']; $quarto_item++) {
                $m_a[$quarto_item] = $this->request->data['resaduqtd_' . $quarto_item];
                $m_c[$quarto_item] = $this->request->data['rescriqtd_' . $quarto_item];
                $max_criancas[$quarto_item] = $this->reserva->rescrimax($this->session->read('empresa_selecionada')["empresa_codigo"], $m_a[$quarto_item]);

                $crianca_idades = array();
                for ($j = 0; $j < $m_c[$quarto_item]; $j++) {
                    array_push($crianca_idades, $this->request->data['crianca_idade_' . $quarto_item . '_' . $j]);
                }
                $idade_criancas[$quarto_item]['idades'] = $crianca_idades;
            }

            $this->set('var_max_crianca', $max_criancas);
            $this->set('idade_criancas', $idade_criancas);
            $this->set('m_a', $m_a);
            $this->set('m_c', $m_c);
            $this->set('m_e', $this->session->read('empresa_selecionada')["empresa_codigo"]);
            $this->set('m_inicial_data', $this->request->data['resentdat']);
            $this->set('m_final_data', $this->request->data['ressaidat']);
            $this->set('resquaqtd', $this->request->data['resquaqtd']);
            $this->set('resquatar_volta', $this->request->data['resquatar_volta']);
            $this->set('pagina_referencia', $this->request->data['pagina_referencia']);
        }

        $this->set('var_max_adulto', $max_adultos);
        $this->set('fuso_horario', $this->session->read('empresa_selecionada')['horario_fuso']);
        $this->set('inicial_padrao_horario', $this->session->read('inicial_padrao_horario'));
        $this->set('final_padrao_horario', $this->session->read('final_padrao_horario'));
        $this->set('session_acesso', $this->session->read('empresa_dados'));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function resquatar() {
        $info_tela = $this->pagesController->montagem_tela('resquatar');
        $this->session->delete('total_descontos');

        if (!(isset($this->request->data['back_page']))) {
            $this->geral->gerpagsal('reservas/rescriini', $this->request->data);
        }

        //Está voltando da resadisel
        if (array_key_exists('back_page', $this->request->data) && $this->request->data['back_page'] == 'reservas/resquatar') {
            $dados_historico = $this->geral->gerconhis();
            foreach ($dados_historico as $key => $value) {
                if ($key == 'data')
                    foreach ($value as $key2 => $data) {
                        $this->request->data[$key2] = $data;
                    }
            }
        }

        //Verifica as permissões de acesso
        $geracever_resquatar = $this->geral->geracever('resquatar');

        //Deve-se imprimir a string de erro de acesso
        if ($geracever_resquatar != "") {
            $this->session->write('geracever_resquatar', $geracever_resquatar);
            $this->response = $this->redirect('/documentos/rescriini');
            $this->response->send();
            die();
        }

        $inicial_data = Util::convertDataSql($this->request->data['resentdat']);
        $final_data = Util::convertDataSql($this->request->data['ressaidat']);
        $empresa_codigo = $this->session->read('empresa_selecionada')["empresa_codigo"];
        $gerdatdet = $this->geral->gerdatdet($inicial_data, $final_data);
        $quarto_qtd = $this->request->data['resquaqtd'];


        //Se não estiver voltando da resadisel
        if (!isset($this->request->data['resadisel_volta']) && !isset($this->request->data['rescliide_volta'])) {

            //Monta o vetor de ocupacao           
            $quarto_ocupacao = array();
            $ultimo_indice_idades = 0;
            for ($i = 1; $i <= $quarto_qtd; $i++) {
                $quarto_ocupacao[$i]['resaduqtd'] = $this->request->data['resaduqtd'][$i - 1];
                $crianca_qtd = $this->request->data['rescriqtd'][$i - 1];
                $quarto_ocupacao[$i]['crianca_idade'] = array_slice($this->request->data['crianca_idade'] ?? array(), $ultimo_indice_idades, $crianca_qtd);
                $ultimo_indice_idades += $crianca_qtd;
                $this->request->data['resaduqtd_' . $i] = $quarto_ocupacao[$i]['resaduqtd'];
                $this->request->data['rescriqtd_' . $i] = $this->request->data['rescriqtd'][$i - 1];
                for ($j = 0; $j < sizeof($quarto_ocupacao[$i]['crianca_idade']); $j++) {
                    $this->request->data['crianca_idade_' . $i . '_' . $j] = $quarto_ocupacao[$i]['crianca_idade'][$j];
                }
            }
            $this->set('final_quarto_tipo', $this->reserva->restipdet($empresa_codigo, $inicial_data, $final_data, $quarto_ocupacao));

            $this->set('dias_estadia', $gerdatdet[0]['data_quantidade']);
            $this->set('quarto_ocupacao', $quarto_ocupacao);
            $this->set('resaduqtd', $this->request->data['resaduqtd']);
            $this->set('rescriqtd', $this->request->data['rescriqtd']);
            $this->set('total_preco', 0);
            //Se estiver voltando da resadisel
        } else {
            if (isset($this->request->data['rescliide_volta']))
            //Remove o documento temporario
                $this->reserva->restemexc($empresa_codigo, $this->request->data['documento_numero']);
            //Monta o vetor de ocupacao           
            $quarto_ocupacao = array();
            $adulto_qtd_array = array();
            $crianca_qtd_array = array();
            $crianca_idade_array = array();
            for ($i = 1; $i <= $quarto_qtd; $i++) {
                //se o quarto nao tiver sido removido
                $quarto_ocupacao[$i]['resaduqtd'] = $this->request->data['resaduqtd_' . $i];
                array_push($adulto_qtd_array, $this->request->data['resaduqtd_' . $i]);
                $crianca_qtd = $this->request->data['rescriqtd_' . $i];
                array_push($crianca_qtd_array, $this->request->data['rescriqtd_' . $i]);
                $crianca_idade = array();
                for ($j = 0; $j < $crianca_qtd; $j++) {
                    array_push($crianca_idade, $this->request->data['crianca_idade_' . $i . '_' . $j]);
                    array_push($crianca_idade_array, $this->request->data['crianca_idade_' . $i . '_' . $j]);
                }

                $quarto_ocupacao[$i]['crianca_idade'] = $crianca_idade;
            }

            $this->set('final_quarto_tipo', $this->reserva->restipdet($empresa_codigo, $inicial_data, $final_data, $quarto_ocupacao));
            $this->set('dias_estadia', $gerdatdet[0]['data_quantidade']);
            $this->set('quarto_ocupacao', $quarto_ocupacao);
            $this->set('resaduqtd', $adulto_qtd_array);
            $this->set('rescriqtd', $crianca_qtd_array);
            $this->set('crianca_idade', $crianca_idade_array);
            $this->set('total_preco', $this->request->data['total_original']);
            $this->set('resadisel_volta', 1);
        }

        $this->set('resentdat', Util::convertDataDMY($inicial_data));
        $this->set('ressaidat', Util::convertDataDMY($final_data));
        $this->set('inicial_data_completa', Util::convertDataDMY($inicial_data) . ' ' . $this->session->read('inicial_padrao_horario'));
        $this->set('final_data_completa', Util::convertDataDMY($final_data) . ' ' . $this->session->read('final_padrao_horario'));
        $this->set('inicial_padrao_horario', $this->session->read('inicial_padrao_horario'));
        $this->set('final_padrao_horario', $this->session->read('final_padrao_horario'));
        $this->set('resquaqtd', $this->request->data['resquaqtd']);
        $this->set('empresa_nome_fantasia', $this->session->read('empresa_selecionada')['empresa_nome_fantasia']);
        $this->set('tarifa_manual_entrada', $this->session->read('empresa_selecionada')['tarifa_manual_entrada']);
        $this->set('datas', $gerdatdet['datas']);
        $this->set($info_tela);
        $this->set('pagina_referencia', $this->request->data['pagina_referencia'] ?? '');
        $this->viewBuilder()->setLayout('ajax');
    }

//Valida o total de di�rias (ajax)
    function resvaldia() {
        $resvaldia = $this->reserva->resvaldia($this->request->data['resentdat'], $this->request->data['ressaidat']);
        if (!$resvaldia) {
            echo "erro";
        }
        $this->autoRender = false;
    }

    public function resadisel() {
        $info_tela = $this->pagesController->montagem_tela('resadisel');
        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];

        //Percorre cada quarto item
        $quarto_qtd = $this->request->data['resquaqtd'];
        $dias_estadia = $this->request->data['dias_estadia'];

        $var_resadimax_arr = array();
        $adicional_preco_arr = array();
        $adicional_preco = array();
        $adicionais_itens_inclusos = array();
        if (isset($this->request->data['rescliide_volta']))
            $this->reserva->restemexc($empresa_codigo, $this->request->data['documento_numero']);
        for ($quarto_item = 1; $quarto_item <= $quarto_qtd; $quarto_item++) {
            //se o quarto nao tiver sido removido
            if ($this->request->data['quarto_item_removido_' . $quarto_item] != 1 && $this->request->data['quarto_item_sem_tarifas_' . $quarto_item] != 1) {
                //busca a tarifa tipo codigo para cada quarto item
                $tarifa_tipo_codigo = $this->request->data['tarifa_tipo_codigo_' . $quarto_item];
                $var_resadipro[$quarto_item] = $this->reserva->resadipro($empresa_codigo, $tarifa_tipo_codigo);
                $adicionais_itens_inclusos[$quarto_item] = array();
                foreach ($var_resadipro[$quarto_item] as $adicional) {
                    if ($adicional['incluido'] == 1)
                        array_push($adicionais_itens_inclusos[$quarto_item], $adicional);
                }
                $indice = 0;
                $var_numero_cell = 0;
                $adicional_total_preco = 0;
                $adulto_qtd[$quarto_item] = $this->request->data['resaduqtd_' . $quarto_item];
                $crianca_qtd[$quarto_item] = $this->request->data['rescriqtd_' . $quarto_item];

                for ($j = 0; $j < sizeof($var_resadipro[$quarto_item]); $j++) {
                    //geraceseq para a descrição do produto
                    $parametros = array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $empresa_codigo, 'produto_codigo' => $var_resadipro[$quarto_item][$j]['adicional_codigo']);
                    $var_resadipro[$quarto_item][$j]['descricao'] = $this->geral->geraceseq('produto_descricao', array('descricao'), $parametros)['descricao'];

                    //geraceseq para o preço do produto
                    $parametros = array('produto_codigo' => $var_resadipro[$quarto_item][$j]['adicional_codigo'],
                        'empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                        'empresa_codigo' => $empresa_codigo, 'venda_ponto_codigo' => "''");

                    $var_resadipro[$quarto_item][$j]['preco'] = $this->geral->geraceseq('produto_preco', array('preco'), $parametros)['preco'];
                    $var_resadipro[$quarto_item][$j]['servico_taxa_incide'] = $this->geral->geraceseq('servico_taxa_incide', array('servico_taxa_incide'), $parametros)['servico_taxa_incide'];
                    $horario_modificacao = $this->geral->geraceseq('horario_modificacao_tipo', array('horario_modificacao_tipo', 'horario_modificacao_valor'), $parametros);

                    if (is_array($horario_modificacao) && sizeof($horario_modificacao) > 0 && array_key_exists('horario_modificacao_tipo', $horario_modificacao[0]))
                        $var_resadipro[$quarto_item][$j]['horario_modificacao'] = $horario_modificacao[0];

                    ++$var_numero_cell;
                    //calcula o max de adicional para cada adicional
                    $var_resadimax_arr[$quarto_item][$indice] = $this->reserva->resadimax($adulto_qtd[$quarto_item], $crianca_qtd[$quarto_item], $dias_estadia, $var_resadipro[$quarto_item][$j]["variavel_fator_codigo"], $var_resadipro[$quarto_item][$j]["max_qtd"]);

                    $adicional_preco_arr[$quarto_item][$indice] = $this->reserva->resadipre($adulto_qtd[$quarto_item], $crianca_qtd[$quarto_item], $dias_estadia, $var_resadipro[$quarto_item][$j]["adicional_codigo"], ($this->request->data["qtd_$var_numero_cell"] ?? 0), $var_resadipro[$quarto_item][$j]["fixo_fator_codigo"], $var_resadipro[$quarto_item][$j]["preco"]);
                    $adicional_preco[$quarto_item][$indice] = $adicional_preco_arr[$quarto_item][$indice];
                    $adicional_total_preco += $adicional_preco[$quarto_item][$indice];
                    $adicional_preco[$quarto_item][$indice] = $this->geral->gersepatr($adicional_preco[$quarto_item][$indice]);
                    $var_resadipro[$quarto_item][$j]['preco'] = $this->geral->gersepatr($var_resadipro[$quarto_item][$j]['preco']);
                    $indice++;
                }
            }
        }

        //Verifica se existem adicionais para os tipos de tarifas encontrados, caso não existam, pula a resadisel
        $existem_adicionais = false;
        foreach ($var_resadipro as $adicional_proposto)
            if (sizeof($adicional_proposto) > 0)
                $existem_adicionais = true;

        $this->set('var_resadipro', $var_resadipro);
        $this->set('adicionais_itens_inclusos', $adicionais_itens_inclusos);
        $this->set('var_resadimax_arr', $var_resadimax_arr);
        $this->set('adicional_preco_arr', $adicional_preco_arr);
        $this->set('adicional_preco', $adicional_preco);
        $this->set($info_tela);
        $this->set('resaduqtd', $adulto_qtd);
        $this->set('rescriqtd', $crianca_qtd);
        $this->set('existem_adicionais', $existem_adicionais);

        unset($this->request->data['form_validator_function']);

        $this->set($this->request->data);
        if (!$existem_adicionais) {
            $this->setAction('rescliide');
        }

        $this->viewBuilder()->setLayout('ajax');
    }

    public function rescliide() {
        $info_tela = $this->pagesController->montagem_tela('rescliide');

        if (!(isset($this->request->data['back_page']))) {
            $this->geral->gerpagsal('reservas/resadisel', $this->request->data);
        }
        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];

        //Percorre cada quarto item
        $quarto_qtd = $this->request->data['resquaqtd'];
        $dias_estadia = $this->request->data['dias_estadia'];
        $inicial_data_sem_horario = $this->request->data['resentdat'];
        $final_data_sem_horario = $this->request->data['ressaidat'];
        $inicial_data_completa = Util::convertDataSql($this->request->data['inicial_data_completa']);
        $final_data_completa = Util::convertDataSql($this->request->data['final_data_completa']);

        $estadia_data = $this->geral->gerdatdet(Util::convertDataSql($inicial_data_sem_horario), Util::convertDataSql($final_data_sem_horario));

        //monta a lista de quarto tipos
        $quarto_tipo_array = array();
        for ($quarto_item = 1; $quarto_item <= $quarto_qtd; $quarto_item++) {
            if ($this->request->data['quarto_item_sem_tarifas_' . $quarto_item] != 1 && $this->request->data['quarto_item_removido_' . $quarto_item] != 1)
                array_push($quarto_tipo_array, $this->request->data['quarto_tipo_codigo_' . $quarto_item]);
        }

        //Cria os documentos temporários na tabela documento
        $retorno_resdoctem = $this->reserva->resdoctem($empresa_codigo, $inicial_data_completa, $final_data_completa, $estadia_data['datas'], $quarto_tipo_array, null, 'c');

        $documento_numero = $retorno_resdoctem['documento_numero'] ?? null;

        //Deu erro na resdoctem
        if ($documento_numero == null) {
            $path = Router::url('/', true);
            echo "<script type='text/javascript'>" . $retorno_resdoctem['mensagem']['mensagem'] . "window.location.href = '" . $path . "/'; </script>";
        } else {
            $var_respagreg = array();
            for ($quarto_item = 1; $quarto_item <= $quarto_qtd; $quarto_item++) {
                if ($this->request->data['quarto_item_sem_tarifas_' . $quarto_item] != 1 && $this->request->data['quarto_item_removido_' . $quarto_item] != 1) {
                    $var_respagpar[$quarto_item] = $this->reserva->respagpar($empresa_codigo, $this->request->data['tarifa_tipo_codigo_' . $quarto_item], $this->request->data['tarifa_valor_' . $quarto_item] +
                            (isset($this->request->data['total_adicionais_' . $quarto_item]) ? $this->request->data['total_adicionais_' . $quarto_item] : 0), $dias_estadia, $estadia_data['datas'], $this->geral->geragodet(1));

                    for ($i = 0; $i < sizeof($var_respagpar[$quarto_item]); $i++) {
                        $var_respagpar[$quarto_item][$i]["tarifa_variacao"] = $this->geral->gersepatr($var_respagpar[$quarto_item][$i]["tarifa_variacao"]);
                        $var_respagpar[$quarto_item][$i]["partida_valor"] = $this->geral->gersepatr($var_respagpar[$quarto_item][$i]["partida_valor"]);
                    }

                    $var_rescandet[$quarto_item] = $this->reserva->rescandet($empresa_codigo, $this->request->data['tarifa_tipo_codigo_' . $quarto_item]);
                    $var_rescnfdet[$quarto_item] = $this->reserva->rescnfdet($empresa_codigo, $this->request->data['tarifa_tipo_codigo_' . $quarto_item]);

                    for ($i = 0; $i < sizeof($var_rescnfdet[$quarto_item]); $i++) {
                        if ($var_rescnfdet[$quarto_item][$i]['evento'] == 1) {
                            $data_confirmacao = Util::somaHora($inicial_data_completa, $var_rescnfdet[$quarto_item][$i]['tempo_hora']);
                            //se a data de cancelamento estiver no passado
                            if (Util::comparaDatas($this->geral->geragodet(2), $data_confirmacao) == 1) {
                                unset($var_rescnfdet[$quarto_item][$i]);
                                $var_rescnfdet[$quarto_item] = array_values($var_rescnfdet[$quarto_item]);
                            }
                        } elseif ($var_rescnfdet[$quarto_item][$i]['evento'] == 2) {
                            $data_confirmacao = Util::somaHora($final_data_completa, $var_rescnfdet[$quarto_item][$i]['tempo_hora']);
                            //se a data de cancelamento estiver no passado
                            if (Util::comparaDatas($this->geral->geragodet(2), $data_confirmacao) == 1) {
                                unset($var_rescnfdet[$quarto_item][$i]);
                                $var_rescnfdet[$quarto_item] = array_values($var_rescnfdet[$quarto_item]);
                            }
                        }
                    }
                    //Monta as opções relacionadas a prazo e valor do cancelamento
                    $array_rescandet[$quarto_item] = array();
                    if (count($var_rescandet[$quarto_item]) > 0) {
                        $array_rescandet[$quarto_item] = $var_rescandet[$quarto_item][0];
                    }

                    $var_rescansel[$quarto_item] = $this->reserva->rescansel($empresa_codigo, $array_rescandet[$quarto_item], $quarto_item, $this->request->data['tarifa_valor_' . $quarto_item] + (isset($this->request->data['total_adicionais_' . $quarto_item]) ? $this->request->data['total_adicionais_' . $quarto_item] : 0), ($this->request->data['tarifa_valor_' . $quarto_item] / $dias_estadia), Util::convertDataSql($this->request->data['resentdat']), Util::convertDataSql($this->request->data['ressaidat']), $this->geral->geragodet(1)) ?? "";
                    //Monta as opções relacionadas à confirmação{
                    $array_rescnfdet[$quarto_item] = array();
                    if (count($var_rescnfdet[$quarto_item]) > 0) {
                        $array_rescnfdet[$quarto_item] = $var_rescnfdet[$quarto_item][0];
                    }

                    $var_rescnfsel[$quarto_item] = $this->reserva->rescnfsel($array_rescnfdet[$quarto_item], $quarto_item, Util::convertDataSql($this->request->data['resentdat']), Util::convertDataSql($this->request->data['ressaidat']), null, $this->geral->geragodet(1));

                    //Monta as opções da forma de pagamento
                    if ($var_rescnfsel[$quarto_item]['html'] == "(forma de pagamento)") {
                        $var_respagfor[$quarto_item] = $this->reserva->respagfor($empresa_codigo, $this->session->read('venda_canal_codigo'), "0");
                        if (count($var_respagfor[$quarto_item]) > 0) {
                            $var_respagreg[$quarto_item] = $this->reserva->respagreg($var_respagfor[$quarto_item][0], '', '', "0.00");
                        }
                    }
                }
            }

            $this->set('var_respagreg', $var_respagreg);
            $this->set('var_rescansel', $var_rescansel);
            $this->set('var_rescnfsel', $var_rescnfsel);
            $this->set('var_rescandet', $var_rescandet);
            $this->set('var_rescnfdet', $var_rescnfdet);
            $this->set('documento_numero', $documento_numero);
            $this->set('var_respagpar', $var_respagpar);
            $this->set('var_respagfor', $this->reserva->respagfor($empresa_codigo, $this->session->read('venda_canal_codigo'), "0"));
            $this->set('doc_err', $this->reserva->doc_err());
            $this->set('empresa_grupo_codigo', $this->session->read("empresa_grupo_codigo_ativo"));
            $this->set('reserva_expiracao', $this->session->read('empresa_selecionada')['reserva_expiracao']);
            $this->set('reserva_intervalo', $this->session->read('empresa_selecionada')['reserva_intervalo']);
            $this->set('cliente_univoco_campo', $this->session->read('cliente_univoco_campo'));
            $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
            $this->set('moeda_simbolo', $this->geral->germoeatr());
            $this->set('dominio_ddi_lista', $this->geral->gercamdom('clicelddi'));
            $this->set('empresa_codigo', $empresa_codigo);
            unset($this->request->data['documento_numero']);
        }

        $this->set($this->request->data);
        $this->set($info_tela);
        $dominio_agencia_viagem = $this->geral->gercamdom('resviaage');
        $elemento_ds = $dominio_agencia_viagem[array_search('ds', array_column($dominio_agencia_viagem, 'valor'))];
        $elemento_da = $dominio_agencia_viagem[array_search('da', array_column($dominio_agencia_viagem, 'valor'))];
        $dominio_agencia_viagem[array_search('ds', array_column($dominio_agencia_viagem, 'valor'))] = $dominio_agencia_viagem[0];
        $dominio_agencia_viagem[array_search('da', array_column($dominio_agencia_viagem, 'valor'))] = $dominio_agencia_viagem[1];
        $dominio_agencia_viagem[0] = $elemento_ds;
        $dominio_agencia_viagem[1] = $elemento_da;
        $this->set('dominio_agencia_viagem', $dominio_agencia_viagem);
        $this->set('ddi_padrao', $this->session->read('ddi_padrao'));
        $this->viewBuilder()->setLayout('ajax');
    }

    public function resdocmod() {
        if ($this->request->is('post') && !isset($this->request->data['export_pdf'])) {
            //Pega todos os dados do formulário da rescliide
            $resdocmod = array();
            if ($this->request->data['data'] ?? "" != "") {
                parse_str($this->request->data['data'], $resdocmod);
            }

            //Mapeia os dados do documento
            $documento_dados = array();
            //Mapeia os dados do contratante
            $contratante_dados = array();

            //Faz o mapeamento das outras informações para cada quarto
            $quarto_item_dados = array();
            for ($quarto_item = 1; $quarto_item <= $resdocmod['resquaqtd']; $quarto_item++) {

                //Precisa verificar se o status da reserva era preliminar, para poder setar o novo status como confirmada dependendo do tipo de confirmação
                if ($resdocmod['resdocsta_' . $quarto_item] < 2) {
                    if ($resdocmod['reserva_confirmacao_tipo_' . $quarto_item] == 0)
                        $quarto_item_dados[$quarto_item]['reserva_dados']['quarto_status_codigo'] = 1;
                    else if ($resdocmod['reserva_confirmacao_tipo_' . $quarto_item] == 1)
                        $quarto_item_dados[$quarto_item]['reserva_dados']['quarto_status_codigo'] = 2;
                } else
                    $quarto_item_dados[$quarto_item]['reserva_dados']['quarto_status_codigo'] = $resdocmod['resdocsta_' . $quarto_item];

                $quarto_item_dados[$quarto_item]['reserva_dados']['reserva_confirmacao_tipo'] = $resdocmod['reserva_confirmacao_tipo_' . $quarto_item];
                $quarto_item_dados[$quarto_item]['reserva_dados']['confirmacao_limite'] = $resdocmod['limite_data_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['texto'] = $resdocmod['resmsghot_' . $quarto_item] == null ? ' ' : $resdocmod['resmsghot_' . $quarto_item];
                $quarto_item_dados[$quarto_item]['reserva_dados']['camareira_texto'] = $resdocmod['resmsgcam_' . $quarto_item] == null ? ' ' : $resdocmod['resmsgcam_' . $quarto_item];
                $quarto_item_dados[$quarto_item]['reserva_dados']['confirmacao_codigo'] = explode("|", $resdocmod['rescnfdet_' . $quarto_item])[1];
                $quarto_item_dados[$quarto_item]['reserva_dados']['info_diarias'] = array();

                $quarto_item_dados[$quarto_item]['reserva_dados']['reserva_data'] = $rescliide['resresdat'] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['agencia_codigo'] = $resdocmod['resviaage'] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['externo_documento_numero'] = $resdocmod['docnumage'] ?? null;

                //Mapeamento das informaçoes de adicionais
                $quarto_item_dados[$quarto_item]['adicional_dados']['produto_codigo'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['adicional_qtd'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['fixo_fator_codigo'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['fator_fixo_valor'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['lancamento_tempo'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['unitario_preco'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['adicional_total'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_tipo'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_valor'] = array();
            }

            //Mapeia as informações de pagamento
            //Indica que teve quartos confirmados
            if ($resdocmod['total_pagamento_formas'] > 0) {
                //Busca o primeiro quarto item com confirmacao
                $primeiro_quarto_item = 0;
                for ($quarto_item = 1; $quarto_item <= $resdocmod['resquaqtd']; $quarto_item++) {
                    //Indica que é confirmada com pagamento direto
                    if ($resdocmod['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $resdocmod['reserva_valor_tipo_' . $quarto_item] == 1) {
                        $primeiro_quarto_item = $quarto_item;
                        break;
                    }
                }
                //Monta um vetor de pagamentos, baseado no total de formas de pagamentos
                $pagamento_dados = array();

                for ($i = 1; $i <= $resdocmod['total_pagamento_formas']; $i++) {
                    //Foi selecionado um pagamento
                    if (isset($resdocmod['respagfor_' . $i]) && $resdocmod['respagfor_' . $i] != "" && $resdocmod['forma_valor_' . $i] != "") {
                        $pagamento_dado['pagamento_forma_codigo'] = explode("|", $resdocmod['respagfor_' . $i])[0];
                        $pagamento_dado['contabil_tipo'] = explode("|", $resdocmod['respagfor_' . $i])[1];
                        $pagamento_dado['valor'] = $resdocmod['forma_valor_' . $i];
                        $pagamento_dado['data'] = $resdocmod['forma_data_' . $i];
                        $pagamento_dado['cartao_nome'] = $resdocmod['forma_pagante_nome_' . $i] ?? "";
                        $pagamento_dado['cartao_numero'] = $resdocmod['forma_cartao_numero_' . $i] ?? "";
                        $pagamento_dado['cartao_validade'] = $resdocmod['forma_cartao_validade_' . $i] ?? "";
                        $pagamento_dado['cartao_parcela'] = $resdocmod['forma_cartao_parcela_' . $i] ?? "";
                        $pagamento_dado['deposito_referencia'] = $resdocmod['forma_referencia_' . $i] ?? "";
                        $pagamento_dado['banco_numero'] = $resdocmod['forma_banco_' . $i] ?? "";
                        $pagamento_dado['agencia_numero'] = $resdocmod['forma_agencia_' . $i] ?? "";
                        $pagamento_dado['conta_numero'] = $resdocmod['forma_conta_corrente_' . $i] ?? "";
                        $pagamento_dado['conta_dv'] = $resdocmod['forma_conta_corrente_dv_' . $i] ?? "";
                        //Determina que não é pre autorizado
                        $pagamento_dado['pre_autorizacao'] = 0;
                        //Cria o vetor de pagantes ou creditados
                        $pagamento_dado['pagante_codigo'] = $resdocmod['pag_codigo_' . $i];
                        $pagamento_dado['pagante_nome'] = $resdocmod['pagante_nome_' . $i];
                        $pagamento_dado['pagante_cpf_cnpj'] = $resdocmod['pagante_cpf_cnpj_' . $i];

                        array_push($pagamento_dados, $pagamento_dado);
                    }
                }
                $quarto_item_dados[$primeiro_quarto_item]['pagamento_dados'] = $pagamento_dados;

                $pagamento_dados = array();
                //Realiza a transferencia das contas dos outros quartos (confirmados) para o quarto 1
                for ($quarto_item = $primeiro_quarto_item + 1; $quarto_item <= $resdocmod['resquaqtd']; $quarto_item++) {
                    //Indica que é confirmada com pagamento direto
                    if ($resdocmod['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $resdocmod['reserva_valor_tipo_' . $quarto_item] == 1) {

                        $pagamento_dado['pagamento_forma_codigo'] = 7;
                        $pagamento_dado['contabil_tipo'] = 'C';
                        $pagamento_dado['valor'] = $resdocmod['valor_parcela1_' . $quarto_item . '_' . $resdocmod['prazo_' . $quarto_item]];
                        $pagamento_dado['data'] = $this->geral->geragodet(2);
                        $pagamento_dado['transferencia_documento_numero'] = $resdocmod['documento_numero'];
                        $pagamento_dado['transferencia_quarto_item'] = $primeiro_quarto_item;
                        //Determina que não é pre autorizado
                        $pagamento_dado['pre_autorizacao'] = 0;
                        //Cria o vetor de pagantes ou creditados
                        $pagamento_dado['pagante_codigo'] = $resdocmod['pag_codigo_1'];
                        $pagamento_dado['pagante_nome'] = $resdocmod['pagante_nome_1'];
                        $pagamento_dado['pagante_cpf_cnpj'] = $resdocmod['pagante_cpf_cnpj_1'];

                        array_push($pagamento_dados, $pagamento_dado);

                        $quarto_item_dados[$quarto_item]['pagamento_dados'] = $pagamento_dados;
                        $pagamento_dados = array();
                    }
                }
                //Se forem todas não confirmadas
            }
            //Verifica se alguma possui pagamento pre autorizado
            //Busca o primeiro quarto item com confirmacao pre autorizada
            $primeiro_quarto_item = 0;
            for ($quarto_item = 1; $quarto_item <= $resdocmod['resquaqtd']; $quarto_item++) {
                //Indica que é confirmada com pagamento pre
                if ($resdocmod['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $resdocmod['reserva_valor_tipo_' . $quarto_item] == 2) {
                    $primeiro_quarto_item = $quarto_item;
                    break;
                }
            }
            //Se existe algum pagamento pre autorizado
            if ($primeiro_quarto_item != 0) {
                $pagamento_dados = array();
                if ($resdocmod['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $resdocmod['reserva_valor_tipo_' . $quarto_item] == 2) {
                    $pagamento_dado['pagamento_forma_codigo'] = 1;
                    $pagamento_dado['contabil_tipo'] = 'D';
                    $pagamento_dado['valor'] = $resdocmod['pre_forma_valor'];
                    $pagamento_dado['data'] = $this->geral->geragodet(2);
                    $pagamento_dado['cartao_nome'] = $resdocmod['pre_forma_pagante_nome'];
                    $pagamento_dado['cartao_numero'] = $resdocmod['pre_forma_cartao_numero'];
                    $pagamento_dado['cartao_validade'] = $resdocmod['pre_forma_cartao_validade'];
                    $pagamento_dado['cartao_parcela'] = $resdocmod['pre_forma_cartao_parcela'];
                    $pagamento_dado['deposito_referencia'] = "";
                    $pagamento_dado['banco_numero'] = "";
                    $pagamento_dado['agencia_numero'] = "";
                    $pagamento_dado['conta_numero'] = "";
                    $pagamento_dado['conta_dv'] = "";
                    //Determina que é pre autorizado
                    $pagamento_dado['pre_autorizacao'] = 1;
                    //Cria o vetor de pagantes ou creditados
                    $pagamento_dado['pagante_codigo'] = $resdocmod['pre_pag_codigo'];
                    $pagamento_dado['pagante_nome'] = $resdocmod['pre_pagante_nome'];
                    $pagamento_dado['pagante_cpf_cnpj'] = $resdocmod['pre_pagante_cpf_cnpj'];

                    array_push($pagamento_dados, $pagamento_dado);
                    $quarto_item_dados[$primeiro_quarto_item]['pagamento_dados'] = $pagamento_dados;
                }
            }

            $retorno = $this->reserva->resdocmod($resdocmod['empresa_codigo'], $resdocmod['documento_numero'], 3, $documento_dados, $contratante_dados, $quarto_item_dados);
            echo json_encode($retorno);
            $this->session->write('retorno_footer', $retorno['mensagem']['mensagem']);
            $this->autoRender = false;
        } else {
            //Busca a empresa passado por parametro
            $doc_numero = trim($this->request->params['pass'][0]);
            $empresa = trim($this->request->params['pass'][1]);
            $quarto_item_selecionado = trim($this->request->params['pass'][2] ?? 0);
            
            $historico_busca = $this->pagesController->consomeHistoricoTela('reservas/resdocmod/' . $doc_numero . '/' . $empresa . '/' . $quarto_item_selecionado);
            $this->request->data = array_merge($this->request->data, $historico_busca);
            
            if (isset($this->request->data['tab-atual'])) {
                $this->set('tab_atual', $this->request->data['tab-atual']);
            }
            //Verifica a permissão para editar
            $acesso = $this->geral->geracever('resdocmod');
            $disabled = "";
            if ($acesso != "") {
                //$disabled = "disabled";
            }
            $this->set('disabled', $disabled);

            $resdocpes = $this->reserva->resdocpes($empresa, "rs", $doc_numero)['results'];
            $this->set('documento_numero', $doc_numero);
            //Adiciona o quarto item como chave no vetor
            $retorno_resdocpes = array();

            foreach ($resdocpes as $resdocpes_item)
                $retorno_resdocpes[$resdocpes_item['quarto_item']] = $resdocpes_item;

            //Busca os adicionais
            $documento_contas = TableRegistry::get('DocumentoContasTabela');
            $adicionais = $documento_contas->findAdicionaisByDocumentoNumeroEEmpresaCodigo($empresa, $doc_numero);
            $this->set('adicionais', $adicionais);
            //Busca as partidas para calcular a primeira parcela de cada quarto item
            $documento_partida = TableRegistry::get('DocumentoPartidas');
            $partidas = $documento_partida->findPartidasByDocumentoNumeroEEmpresaCodigo($empresa, $doc_numero);
            $partidas_por_quarto_item = array();
            foreach ($partidas as $partida) {
                $partidas_por_quarto_item[$partida['quarto_item']][] = $partida;
            }

            $this->set('partidas_por_quarto_item', $partidas_por_quarto_item);

            $var_respagreg = $sel_respagfor = $var_respagfor = $var_rescnfsel = $var_rescnfdet = $data_quantidade = $confirmacao_tipo = $sel_name_rescnfdet = $diarias = array();

            foreach ($retorno_resdocpes as $quarto_item) {

                $estadia_data[$quarto_item['quarto_item']] = $this->geral->gerdatdet($retorno_resdocpes[$quarto_item['quarto_item']]['inicial_data'], $retorno_resdocpes[$quarto_item['quarto_item']]['final_data']);
                $data_quantidade[$quarto_item['quarto_item']] = $estadia_data[$quarto_item['quarto_item']][0]['data_quantidade'];
                $diarias[$quarto_item['quarto_item']] = $estadia_data[$quarto_item['quarto_item']]['datas'];

                $var_rescnfdet[$quarto_item['quarto_item']] = $this->reserva->rescnfdet($empresa, $quarto_item['tipo_tarifa_codigo']);

                //Monta as opções relacionadas à confirmação{
                $array_rescnfdet[$quarto_item['quarto_item']] = array();

                foreach ($var_rescnfdet[$quarto_item['quarto_item']] as $ky => $value) {
                    if ($value["reserva_confirmacao_codigo"] == $quarto_item['confirmacao_codigo']) {
                        $array_rescnfdet[$quarto_item['quarto_item']] = $value;
                        $sel_name_rescnfdet[$quarto_item['quarto_item']] = $value["reserva_confirmacao_nome"];
                        $confirmacao_tipo[$quarto_item['quarto_item']] = $value["reserva_confirmacao_tipo"];
                    }
                }
                if (sizeof($array_rescnfdet[$quarto_item['quarto_item']]) > 0) {
                    $var_rescnfsel[$quarto_item['quarto_item']] = $this->reserva->rescnfsel($array_rescnfdet[$quarto_item['quarto_item']], $quarto_item['quarto_item'], $quarto_item['inicial_data'], $quarto_item['final_data'], $quarto_item['confirmacao_limite'], $quarto_item['reserva_data']) ?? "";

                    //Monta as opções da forma de pagamento
                    if ($var_rescnfsel[$quarto_item['quarto_item']]['html'] == "(forma de pagamento)") {
                        $var_respagfor[$quarto_item['quarto_item']] = $this->reserva->respagfor($empresa, $quarto_item['venda_canal_codigo'], "0");
                        if (count($var_respagfor[$quarto_item['quarto_item']]) > 0) {
                            foreach ($var_respagfor[$quarto_item['quarto_item']] AS $item_respafor) {
                                if ($item_respafor['pagamento_forma_codigo'] == $quarto_item['pagamento_forma_codigo'])
                                    $sel_respagfor[$quarto_item['quarto_item']] = $item_respafor['pagamento_forma_codigo']; // Força a seleção do primeiro item da lista.        
                            }
                            $var_respagreg[$quarto_item['quarto_item']] = $this->reserva->respagreg($quarto_item['pagamento_forma_codigo'], $quarto_item['nome'] . ' ', $quarto_item['sobrenome'], ($this->geral->gersepatr($quarto_item['planejado_valor'] ?? "0.00")), $disabled, null, $pagamento_dados ?? null, null, null, null, $quarto_item['reserva_data']);
                        }
                    }
                }
            }
            $this->set('var_respagreg', $var_respagreg);
            $this->set('sel_respagfor', $sel_respagfor);
            $this->set('var_respagfor', $var_respagfor);
            $this->set('var_rescnfsel', $var_rescnfsel);
            $this->set('var_rescnfdet', $var_rescnfdet);
            $this->set('data_quantidade', $data_quantidade);
            $this->set('quarto_item_selecionado', $quarto_item_selecionado);
            $this->set('diarias', $diarias);
            $this->set('confirmacao_tipo', $confirmacao_tipo);
            $this->set('sel_name_rescnfdet', $sel_name_rescnfdet);
            $this->set('cancelamento_motivos', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'ca'")));

            //TODO fazer pra varios quartos
            $cabecalho_conta = array();
            $this->set('cabecalho_conta', $cabecalho_conta);
            //Monta a lista de partidas da reserva
            $partidas_lista = $this->reserva->resparexi($this->session->read('empresa_selecionada')['empresa_codigo'], $doc_numero, 1);
            $this->set('partidas_lista', $partidas_lista);
            $var_respagfor = $this->reserva->respagfor($this->session->read('empresa_selecionada')['empresa_codigo'], $this->session->read('venda_canal_codigo'), "0");
            $this->set('var_respagfor', $var_respagfor);

            $info_tela = $this->pagesController->montagem_tela('resdocmod');

            $this->set('moeda_simbolo', $this->geral->germoeatr());

            $geracever_conitecri = "";
            if ($this->geral->geracever('conitecri') != "") {
                $geracever_conitecri = "0";
            } else {
                $geracever_conitecri = "1";
            }

            $this->set('geracever_conitecri', $geracever_conitecri);

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
            $this->set('geracever_coniteexc', $geracever_coniteexc);

//Verifica a permissão para editar cliente
            $dis_clicadmod = "";
            if ($this->geral->geracever('clicadmod') != "") {
                $dis_clicadmod = "disabled";
            }
            $this->set('dis_clicadmod', $dis_clicadmod);
            if ($this->session->check('tab-atual')) {
                $this->set('tab_atual', $this->session->read('tab-atual'));
                $this->session->delete('tab-atual');
            }

            $pesquisa_contas = $this->documento_conta->conresexi($empresa, $doc_numero);
            $contas_quarto_item = array();
            foreach ($pesquisa_contas as $value) {
                $contas_quarto_item[$value['quarto_item']][] = $value;
            }
            ksort($contas_quarto_item);

            //Checa as permissões em elementos da tela
            $this->set('ace_estchicri', $this->geral->gerauuver('estadias', 'estchicri') ? '' : ' disabled ');
            $this->set('ace_gerlogexi', $this->geral->gerauuver('geral', 'gerlogexi') ? '' : ' disabled ');
            $this->set('ace_resdocmod', $this->geral->gerauuver('reservas', 'resdocmod') ? '' : ' disabled ');
            $this->set('ace_resdoccan', $this->geral->gerauuver('reservas', 'resdoccan') ? '' : ' disabled ');
            $this->set('reserva_dados', $retorno_resdocpes);
            $this->set('contas_quarto_item', $contas_quarto_item);
            $this->set('dominio_agencia_viagem', $this->geral->gercamdom('resviaage'));
            $dominio_agencia_viagem = $this->geral->gercamdom('resviaage');
            $elemento_ds = $dominio_agencia_viagem[array_search('ds', array_column($dominio_agencia_viagem, 'valor'))];
            $elemento_da = $dominio_agencia_viagem[array_search('da', array_column($dominio_agencia_viagem, 'valor'))];
            $dominio_agencia_viagem[array_search('ds', array_column($dominio_agencia_viagem, 'valor'))] = $dominio_agencia_viagem[0];
            $dominio_agencia_viagem[array_search('da', array_column($dominio_agencia_viagem, 'valor'))] = $dominio_agencia_viagem[1];
            $dominio_agencia_viagem[0] = $elemento_ds;
            $dominio_agencia_viagem[1] = $elemento_da;
            $this->set('dominio_agencia_viagem', $dominio_agencia_viagem);

            $agencias = array();
            foreach ($dominio_agencia_viagem as $agencia)
                $agencias[$agencia['valor']] = $agencia['rotulo'];

            $this->set('agencias', $agencias);
            $this->set($info_tela);
            if (sizeof($this->session->read('historico')) > 0)
                $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
            else
                $this->set('pagina_referencia', '');

            if (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == '1') {
                $this->viewBuilder()
                        ->options(['config' => [
                                'orientation' => 'portrait',
                                'filename' => 'impressaoReserva'
                            ]
                ]);
            } else {
                $this->viewBuilder()->setLayout('ajax');
            }
        }
    }

    public function resdocpes() {
        $info_tela = $this->pagesController->montagem_tela('resdocpes');
        $historico_busca = $this->pagesController->consomeHistoricoTela('reservas/resdocpes');
        $this->request->data = array_merge($this->request->data, $historico_busca);
        if ($this->request->is('post') || sizeof($historico_busca) > 0) {
            $documento_numero = $this->request->data['resdocnum'] ?? "";

            $quarto_tipo_codigo = $this->request->data['resquatip'] ?? "";
            $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'] ?? $this->session->read('empresa_selecionada')['empresa_codigo'];
            $resdocexc = $this->request->data['resdocexc'] ?? 0;
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

            $documento_status_codigo = array();
            if (array_key_exists('gerdocsta', $this->request->data)) {
                if (!is_array($this->request->data['gerdocsta']))
                    $this->request->data['gerdocsta'] = explode(',', $this->request->data['gerdocsta']);
                $documento_status_codigo = $this->request->data['gerdocsta'];
            }

            $quarto_codigo = array();
            if (array_key_exists('resquacod', $this->request->data)) {
                if (!is_array($this->request->data['resquacod']))
                    $this->request->data['resquacod'] = explode(',', $this->request->data['resquacod']);
                $quarto_codigo = $this->request->data['resquacod'];
            }


            $dias_max_expiracao = $this->request->data['dias_expiracao_max'] ?? "";
            $horas_max_expiracao = $this->request->data['horas_expiracao_max'] ?? "";

            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                $pesquisa_reservas = $this->reserva->resdocpes($empresa_codigo, "rs", $documento_numero, $this->request->data['quarto_item'] ?? null, $this->request->data['c_codigo'] ?? null, $documento_status_codigo, $resdocexc, $dias_max_expiracao, $horas_max_expiracao, $inicial_data, $final_data, $estadia_data, $criacao_data, $quarto_codigo, $quarto_tipo_codigo, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], null);

                $this->response->download('export.csv');
                $data = $pesquisa_reservas['results'];
                $_serialize = 'data';
                $_extract = ['documento_numero', 'inicial_data', 'final_data', 'quarto_tipo_nome', 'quarto_codigo', 'nome', 'documento_status_nome', 'confirmacao_limite'];
                $_header = [$info_tela['rot_resdocnum'], $info_tela['rot_resentdat'], $info_tela['rot_ressaidat'], $info_tela['rot_resquatip'], $info_tela['rot_resquacod'], $info_tela['rot_cliclicon'], $info_tela['rot_resdocsta'], $info_tela['rot_rescnflim']];
                $_csvEncoding = "iso-8859-1";
                $_delimiter = ";";
                $this->set(compact('data', '_serialize', '_delimiter', '_header', '_extract', '_csvEncoding'));

                $this->viewBuilder()->className('CsvView.Csv');
            } else  //Se tiver exportando para pdf, não passa a paginação
            if (isset($this->request->data['export_pdf']) && $this->request->data['export_pdf'] == '1') {
                $pesquisa_reservas = $this->reserva->resdocpes($empresa_codigo, "rs", $documento_numero, $this->request->data['quarto_item'] ?? null, $this->request->data['c_codigo'] ?? null, $documento_status_codigo, $resdocexc, $dias_max_expiracao, $horas_max_expiracao, $inicial_data, $final_data, $estadia_data, $criacao_data, $quarto_codigo, $quarto_tipo_codigo, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], null);

                $indice = 0;
                $string_html = "";
                foreach ($pesquisa_reservas['results'] as $value) {
                    if ($indice < 400)
                        $string_html .= "<tr>
                    <td style='padding:5px;'>" . $value['documento_numero'] . "</td>
                    <td style='padding:5px;'>" . $value['quarto_codigo'] . "</td>
                    <td style='padding:5px;'>" . $value['quarto_tipo_nome'] . "</td>
                    <td style='padding:5px;'>" . Util::convertDataDMY($value['inicial_data']) . "</td>
                    <td style='padding:5px;'>" . Util::convertDataDMY($value['final_data']) . "</td>
                    <td style='padding:5px;'>" . $value['nome'] . ' ' . $value['sobrenome'] . "</td>
                    <td style='padding:5px;'>" . $value['documento_status_nome'] . "</td>
                    <td style='padding:5px;'>" . Util::convertDataDMY($value['confirmacao_limite'], 'd/m/Y H:i') . "</td>
                    <td style='padding:5px;'>" . $value['day_diff'] . "d " . $value['hour_diff'] . "h" . "</td>                
                </tr>";
                    $indice++;
                }

                $this->set('pesquisa_reservas', $string_html);
                $this->set($this->request['data']);
            } else {

                $pesquisa_reservas = $this->reserva->resdocpes($empresa_codigo, "rs", $documento_numero, $this->request->data['quarto_item'] ?? null, $this->request->data['c_codigo'] ?? null, $documento_status_codigo, $resdocexc, $dias_max_expiracao, $horas_max_expiracao, $inicial_data, $final_data, $estadia_data, $criacao_data, $quarto_codigo, $quarto_tipo_codigo, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);
                $this->set('pesquisa_reservas', $pesquisa_reservas);
                $this->set('exibir_max_exp', $documento_status_codigo == '1' ? '1' : '0');
                $this->set('empresa_codigo', $empresa_codigo);
                $this->set($this->request->data);
                //exibe a paginação
                $paginator = new Paginator(10);
                $this->set('paginacao', $paginator->gera_paginacao($pesquisa_reservas['filteredTotal'], $this->request->data['pagina'], 'resdocpes', sizeof($pesquisa_reservas['results'])));
            }
        } else {

            if ($info_tela['padrao_valor_gerdocsta'] != '')
                $gerdocsta = explode("|", $info_tela['padrao_valor_gerdocsta']);
            else
                $gerdocsta = ['1', '2', '3', '4'];

            if ($info_tela['padrao_valor_resquatip'] != '')
                $resquatip = explode("|", $info_tela['padrao_valor_resquatip']);
            else
                $resquatip = null;

            if ($info_tela['padrao_valor_gerdattip'] != '')
                $gerdattip = $info_tela['padrao_valor_gerdattip'];
            else
                $gerdattip = 'entrada';

            $ordenacao_coluna = "inicial_data|quarto_tipo_nome|quarto_codigo|";
            $ordenacao_tipo = "asc|asc|asc|";

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

            $pesquisa_reservas = $this->reserva->resdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], "rs", null, null, null, $gerdocsta, null, null, null, $inicial_data, $final_data, $estadia_data, $criacao_data, null, $resquatip, $ordenacao_coluna, $ordenacao_tipo, 1);
//exibe a paginação
            $paginator = new Paginator(10);
            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_reservas['filteredTotal'], 1, 'resdocpes', sizeof($pesquisa_reservas['results'])));

            $this->set('pesquisa_reservas', $pesquisa_reservas);

            $this->set('gerdocsta', $gerdocsta);

            $this->set('resquatip', $resquatip);
            $this->set('gerdattip', $gerdattip);
            $this->set('ordenacao_coluna', $ordenacao_coluna);
            $this->set('ordenacao_tipo', $ordenacao_tipo);
            $this->set('ordenacao_sistema', '1');

            $this->set('exibir_max_exp', '0');
            $this->set('dias_max_expiracao', '');
            $this->set('horas_max_expiracao', '');
        }

        $this->set('reserva_status_list', $this->geral->gercamdom('resdocsta', 'rs'));
        //$this->set('cliente_papeis_lista', $this->geral->gercamdom('clicadpap'));

        $this->set('logo_empresa', $this->session->read('empresa_selecionada')['logo']);
        $this->set('mm', $this->session->read('empresa_selecionada')['horario_fuso']);
        //Busca a lista de quartos
        $dados_quartos = $this->connection->execute("SELECT q.quarto_codigo, qt.quarto_tipo_curto_nome FROM quartos q INNER JOIN quarto_tipos qt ON q.empresa_codigo = qt.empresa_codigo"
                        . " AND q.quarto_tipo_codigo = qt.quarto_tipo_codigo WHERE q.empresa_codigo = :empresa_codigo ORDER BY q.quarto_codigo", ['empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo']])->fetchAll("assoc");
        $quarto_por_tipo = array();
        foreach ($dados_quartos as $quarto_dado) {
            $quarto_por_tipo[$quarto_dado['quarto_codigo']] = $quarto_dado['quarto_tipo_curto_nome'];
        }


        $this->set('quarto_por_tipo', $quarto_por_tipo);
        $this->set('resquatip_list', $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo']));
        //Monta os motivos de cancelamento
        $this->set('cancelamento_motivos', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'ca'")));

        //Checa as permissões em elementos da tela
        $this->set('ace_resdoccan', $this->geral->gerauuver('reservas', 'resdoccan') ? '' : ' disabled ');
        $this->set('ace_resexpsel', $this->geral->gerauuver('reservas', 'resexpsel') ? '' : ' disabled ');
        $this->set('ace_clicadpes', $this->geral->gerauuver('clientes', 'clicadpes') ? '' : ' disabled ');
        $this->set('ace_estchicri', $this->geral->gerauuver('estadias', 'estchicri') ? '' : ' disabled ');
        $this->set('reserva_pesquisa_max', $this->session->read('reserva_pesquisa_max'));
        $this->set('gerdatatu', Util::convertDataDMY($this->geral->geragodet(1)));
        $gerdomsta_list = $this->geral->gercamdom('resdocsta', 'rs');
        //Ordena os valores de status de acordo com seu codigo
        usort($gerdomsta_list, function($a, $b) {
            return $a['valor'] <=> $b['valor'];
        });
        $this->set('gerdomsta_list', $gerdomsta_list);
        $this->set($info_tela);
        if (sizeof($this->session->read('historico')) > 0)
            $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
        else
            $this->set('pagina_referencia', '');

        if (!isset($this->request->data['export_pdf']) || !$this->request->data['export_pdf'] == '1')
            $this->viewBuilder()->setLayout('ajax');
    }

    /*
     * Primeira validação da reserva feita por ajax
     */

    public function resdocval1() {
        $empresa_codigo = $this->request->data['empresa_codigo'];
        $reserva_confirmacao_tipo = $this->request->data['reserva_confirmacao_tipo'] ?? null;
        $valor_tipo = $this->request->data['valor_tipo'] ?? 0;
        $informado_valor = $this->request->data['informado_valor'] ?? null;
        $total_preco = $this->request->data['total_preco'] ?? null;
        $partida_valor = $this->request->data['partida_valor1'] ?? null;

        echo $this->reserva->resdocval1($empresa_codigo, $reserva_confirmacao_tipo, $valor_tipo, $informado_valor, $total_preco, $partida_valor);
        $this->autoRender = false;
    }

    /*
     * Segunda valida��o da reserva feita por ajax
     */

    public function resdocval2() {
        echo $this->reserva->resdocval2();
        $this->autoRender = false;
    }

    /*
     * Fun��o que guarda a reserva feita por ajax
     */

    public function resdocsal() {
        //Pega todos os dados do formulário da rescliide
        $rescliide = array();
        if ($this->request->data['data'] ?? "" != "") {
            parse_str($this->request->data['data'], $rescliide);
        }
        unset($rescliide['form_validator_function']);

        //Mapeia os dados do documento
        $documento_dados = array();
        $documento_dados['documento_numero'] = $rescliide['documento_numero'];
        $documento_dados['venda_canal_codigo'] = $rescliide['venda_canal_codigo'];
        $documento_dados['venda_ponto_codigo'] = $rescliide['venda_ponto_codigo'];
        $documento_dados['inicial_data'] = $rescliide['inicial_data_completa_original'];
        $documento_dados['final_data'] = $rescliide['final_data_completa_original'];
        $documento_dados['texto'] = "";


        $contratante_dados['contratante_codigo'] = $rescliide['c_codigo'];
        $contratante_dados['contratante_nome'] = $rescliide['cliprinom'];
        $contratante_dados['contratante_sobrenome'] = $rescliide['clisobnom'];
        $contratante_dados['contratante_email'] = $rescliide['clicadema'];
        $contratante_dados['contratante_cpfcnpj'] = $rescliide['clicpfcnp'] ?? null;
        $contratante_dados['contratante_tel_ddi'] = $rescliide['clitelddi'];
        $contratante_dados['contratante_tel_numero'] = $rescliide['clitelnum'];
        $contratante_dados['contratante_cel_ddi'] = $rescliide['clicelddi'];
        $contratante_dados['contratante_cel_numero'] = $rescliide['clicelnum'];
        $contratante_dados['contratante_modificado'] = $rescliide['c_has_changed'];

        //Faz o mapeamento das outras informações para cada quarto
        $quarto_item_dados = array();
        for ($quarto_item = 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++) {
            if ($rescliide['quarto_item_sem_tarifas_' . $quarto_item] != 1 && $rescliide['quarto_item_removido_' . $quarto_item] != 1) {
                if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 0)
                    $quarto_item_dados[$quarto_item]['reserva_dados']['quarto_status_codigo'] = 1;
                else if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1)
                    $quarto_item_dados[$quarto_item]['reserva_dados']['quarto_status_codigo'] = 2;
                $quarto_item_dados[$quarto_item]['reserva_dados']['reserva_confirmacao_tipo'] = $rescliide['reserva_confirmacao_tipo_' . $quarto_item];
                $quarto_item_dados[$quarto_item]['reserva_dados']['total_preco'] = $rescliide['total_quarto_item_' . $quarto_item];
                $quarto_item_dados[$quarto_item]['reserva_dados']['confirmacao_limite'] = $rescliide['confirmacao_limite_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['cancelamento_limite'] = $rescliide['cancelamento_limite_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['cancelamento_valor'] = $rescliide['cancelamento_valor_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['texto'] = $rescliide['resmsghot_' . $quarto_item];
                $quarto_item_dados[$quarto_item]['reserva_dados']['camareira_texto'] = $rescliide['resmsgcam_' . $quarto_item];
                $quarto_item_dados[$quarto_item]['reserva_dados']['confirmacao_codigo'] = $rescliide['rescnfdet_' . $quarto_item];
                $quarto_item_dados[$quarto_item]['reserva_dados']['cancelamento_codigo'] = $rescliide['rescandet_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['tipo_tarifa_codigo'] = $rescliide['tarifa_tipo_codigo_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['adulto_qtd'] = $rescliide['resaduqtd_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_qtd'] = $rescliide['rescriqtd_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['adulto_qtd_ajustada'] = $rescliide['resaduqtd_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_qtd_ajustada'] = $rescliide['rescriqtd_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_idade'] = "";
                //Salva as idades das crianças
                if ($rescliide['rescriqtd_' . $quarto_item] > 0) {
                    for ($i = 0; $i < $rescliide['rescriqtd_' . $quarto_item]; $i++)
                        $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_idade'] .= $rescliide['crianca_idade_' . $quarto_item . '_' . $i] . ',';
                    $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_idade'] = substr($quarto_item_dados[$quarto_item]['reserva_dados']['crianca_idade'], 0, -1);
                }

                $quarto_item_dados[$quarto_item]['reserva_dados']['info_diarias'] = array();
                for ($i = 1; $i <= $rescliide['dias_estadia']; $i++) {
                    array_push($quarto_item_dados[$quarto_item]['reserva_dados']['info_diarias'], $rescliide['info_diarias_' . $quarto_item . '_' . $rescliide['quarto_tipo_codigo_' . $quarto_item] . '_' . $rescliide['tarifa_tipo_codigo_' . $quarto_item] . '_' . $i]);
                }
                $quarto_item_dados[$quarto_item]['reserva_dados']['info_conta_adicionais'] = $rescliide['info_conta_adicionais_' . $quarto_item] ?? null;
                $quarto_item_dados[$quarto_item]['reserva_dados']['reserva_data'] = $rescliide['resresdat'];
                $quarto_item_dados[$quarto_item]['reserva_dados']['agencia_codigo'] = $rescliide['resviaage'];
                $quarto_item_dados[$quarto_item]['reserva_dados']['externo_documento_numero'] = $rescliide['docnumage'];
                //Mapeamento das partidas
                $quarto_item_dados[$quarto_item]['partida_dados']['partida_item'] = array();
                $quarto_item_dados[$quarto_item]['partida_dados']['partida_liquidacao_data'] = array();
                $quarto_item_dados[$quarto_item]['partida_dados']['partida_valor'] = array();
                $quarto_item_dados[$quarto_item]['partida_dados']['pagamento_requerido_evento'] = array();
                for ($i = 1; $i <= $rescliide['total_partidas_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item]]; $i++) {
                    array_push($quarto_item_dados[$quarto_item]['partida_dados']['partida_item'], $rescliide['partida_item_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item] . '_' . $i]);
                    array_push($quarto_item_dados[$quarto_item]['partida_dados']['partida_liquidacao_data'], $rescliide['partida_data_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item] . '_' . $i]);
                    array_push($quarto_item_dados[$quarto_item]['partida_dados']['partida_valor'], $rescliide['partida_valor_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item] . '_' . $i]);
                    array_push($quarto_item_dados[$quarto_item]['partida_dados']['pagamento_requerido_evento'], $rescliide['partida_pagamento_requerido_evento_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item] . '_' . $i]);
                }

                $quarto_item_dados[$quarto_item]['reserva_dados']['pagamento_prazo_codigo'] = $rescliide['prazo_' . $quarto_item] ?? null;

                //Mapeamento dos hóspedes de cada quarto
                $total_hospedes = $rescliide['resaduqtd_' . $quarto_item] + $rescliide['rescriqtd_' . $quarto_item];
                $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_codigo'] = array();
                $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_codigo_antigo'] = array();
                $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_email'] = array();
                $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_nome'] = array();
                $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_sobrenome'] = array();
                $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_modificado'] = array();
                $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_novo_cliente'] = array();

                for ($i = 1; $i <= $total_hospedes; $i++) {
                    array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_codigo'], $rescliide['h_codigo_' . $quarto_item . '_' . $i]);
                    array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_codigo_antigo'], $rescliide['h_codigo_antigo_' . $quarto_item . '_' . $i]);
                    array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_email'], $rescliide['h_email_' . $quarto_item . '_' . $i] ?? '');
                    array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_nome'], $rescliide['h_nome_' . $quarto_item . '_' . $i] ?? '');
                    array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_sobrenome'], $rescliide['h_sobrenome_' . $quarto_item . '_' . $i] ?? '');
                    array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_modificado'], $rescliide['h_has_changed_' . $quarto_item . '_' . $i]);
                    //Verifica se o primeiro hóspede é o contratante ou um novo cliente a ser criado
                    if ($i == 1) {
                        if (isset($rescliide['hospede_mesmo_contratante_' . $quarto_item]))
                            array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_novo_cliente'], 0);
                        else
                            array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_novo_cliente'], 1);
                    } else
                        array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_novo_cliente'], 1);
                }

                //Mapeamento das informaçoes de adicionais
                $quarto_item_dados[$quarto_item]['adicional_dados']['produto_codigo'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['adicional_qtd'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['fixo_fator_codigo'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['fator_fixo_valor'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['lancamento_tempo'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['unitario_preco'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['adicional_total'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['adicional_desconto'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_tipo'] = array();
                $quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_valor'] = array();

                for ($i = 1; $i <= $rescliide['adicional_item_qtd_' . $quarto_item]; $i++) {
                    if ($rescliide['adicional_qtd_' . $quarto_item . '_' . $i] > 0) {
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['produto_codigo'], $rescliide['produto_codigo_' . $quarto_item . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['adicional_qtd'], $rescliide['adicional_qtd_' . $quarto_item . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['fixo_fator_codigo'], $rescliide['adicional_fixo_fator_codigo_' . $quarto_item . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['fator_fixo_valor'], $rescliide['adicional_fator_fixo_valor_' . $quarto_item . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['lancamento_tempo'], $rescliide['lancamento_tempo_' . $quarto_item . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['unitario_preco'], $rescliide['preco_' . $quarto_item . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['adicional_total'], $rescliide['adicional_total_' . $quarto_item . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_tipo'], $rescliide['horario_modificacao_tipo_' . $quarto_item . '_' . $i] ?? null);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_valor'], $rescliide['horario_modificacao_valor_' . $quarto_item . '_' . $i] ?? null);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['adicional_desconto'], $rescliide['adicional_desconto_tmp_' . $quarto_item . '_' . $i]);
                    }
                }
            }
        }
        //Mapeia as informações de pagamento
        //Indica que teve quartos confirmados
        if ($rescliide['total_pagamento_formas'] > 0) {
            //Busca o primeiro quarto item com confirmacao
            $primeiro_quarto_item = 0;
            for ($quarto_item = 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++) {
                if ($rescliide['quarto_item_sem_tarifas_' . $quarto_item] != 1 && $rescliide['quarto_item_removido_' . $quarto_item] != 1) {
                    //Indica que é confirmada com pagamento direto
                    if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $rescliide['reserva_valor_tipo_' . $quarto_item] == 1) {
                        $primeiro_quarto_item = $quarto_item;
                        break;
                    }
                }
            }
            //Monta um vetor de pagamentos, baseado no total de formas de pagamentos
            $pagamento_dados = array();

            for ($i = 1; $i <= $rescliide['total_pagamento_formas']; $i++) {
                //Foi selecionado um pagamento
                if (isset($rescliide['respagfor_' . $i]) && $rescliide['respagfor_' . $i] != "" && $rescliide['forma_valor_' . $i] != "") {
                    $pagamento_dado['pagamento_forma_codigo'] = explode("|", $rescliide['respagfor_' . $i])[0];
                    $pagamento_dado['contabil_tipo'] = explode("|", $rescliide['respagfor_' . $i])[1];
                    $pagamento_dado['valor'] = $rescliide['forma_valor_' . $i];
                    $pagamento_dado['data'] = $rescliide['forma_data_' . $i];
                    $pagamento_dado['cartao_nome'] = $rescliide['forma_pagante_nome_' . $i] ?? "";
                    $pagamento_dado['cartao_numero'] = $rescliide['forma_cartao_numero_' . $i] ?? "";
                    $pagamento_dado['cartao_validade'] = $rescliide['forma_cartao_validade_' . $i] ?? "";
                    $pagamento_dado['cartao_parcela'] = $rescliide['forma_cartao_parcela_' . $i] ?? "";
                    $pagamento_dado['deposito_referencia'] = $rescliide['forma_referencia_' . $i] ?? "";
                    $pagamento_dado['banco_numero'] = $rescliide['forma_banco_' . $i] ?? "";
                    $pagamento_dado['agencia_numero'] = $rescliide['forma_agencia_' . $i] ?? "";
                    $pagamento_dado['conta_numero'] = $rescliide['forma_conta_corrente_' . $i] ?? "";
                    $pagamento_dado['conta_dv'] = $rescliide['forma_conta_corrente_dv_' . $i] ?? "";
                    //Determina que não é pre autorizado
                    $pagamento_dado['pre_autorizacao'] = 0;
                    //Cria o vetor de pagantes ou creditados
                    $pagamento_dado['pagante_codigo'] = $rescliide['pag_codigo_' . $i];
                    $pagamento_dado['pagante_igual_contratante'] = $rescliide['pagante_igual_contratante_' . $i];
                    $pagamento_dado['pagante_nome'] = $rescliide['pagante_nome_' . $i];
                    $pagamento_dado['pagante_cpf_cnpj'] = $rescliide['pagante_cpf_cnpj_' . $i];

                    array_push($pagamento_dados, $pagamento_dado);
                }
            }
            $quarto_item_dados[$primeiro_quarto_item]['pagamento_dados'] = $pagamento_dados;

            $pagamento_dados = array();
            //Realiza a transferencia das contas dos outros quartos (confirmados) para o quarto 1
            for ($quarto_item = $primeiro_quarto_item + 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++) {
                if ($rescliide['quarto_item_sem_tarifas_' . $quarto_item] != 1 && $rescliide['quarto_item_removido_' . $quarto_item] != 1) {
                    //Indica que é confirmada com pagamento direto
                    if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $rescliide['reserva_valor_tipo_' . $quarto_item] == 1) {

                        $pagamento_dado['pagamento_forma_codigo'] = 7;
                        $pagamento_dado['contabil_tipo'] = 'C';
                        $pagamento_dado['valor'] = $rescliide['valor_parcela1_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item]];
                        $pagamento_dado['data'] = $this->geral->geragodet(2);
                        $pagamento_dado['transferencia_documento_numero'] = $rescliide['documento_numero'];
                        $pagamento_dado['transferencia_quarto_item'] = $primeiro_quarto_item;
                        //Determina que não é pre autorizado
                        $pagamento_dado['pre_autorizacao'] = 0;
                        //Cria o vetor de pagantes ou creditados
                        $pagamento_dado['pagante_codigo'] = $rescliide['pag_codigo_1'];
                        $pagamento_dado['pagante_igual_contratante'] = $rescliide['pagante_igual_contratante_1'];
                        $pagamento_dado['pagante_nome'] = $rescliide['pagante_nome_1'];
                        $pagamento_dado['pagante_cpf_cnpj'] = $rescliide['pagante_cpf_cnpj_1'];

                        array_push($pagamento_dados, $pagamento_dado);

                        $quarto_item_dados[$quarto_item]['pagamento_dados'] = $pagamento_dados;
                        $pagamento_dados = array();
                    }
                }
            }
            //Se forem todas não confirmadas
        }
        //Verifica se alguma possui pagamento pre autorizado
        //Busca o primeiro quarto item com confirmacao pre autorizada
        $primeiro_quarto_item = 0;
        for ($quarto_item = 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++) {
            if ($rescliide['quarto_item_sem_tarifas_' . $quarto_item] != 1 && $rescliide['quarto_item_removido_' . $quarto_item] != 1) {
                //Indica que é confirmada com pagamento pre
                if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $rescliide['reserva_valor_tipo_' . $quarto_item] == 2) {
                    $primeiro_quarto_item = $quarto_item;
                    break;
                }
            }
        }
        //Se existe algum pagamento pre autorizado
        if ($primeiro_quarto_item != 0) {
            $pagamento_dados = array();
            if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $rescliide['reserva_valor_tipo_' . $quarto_item] == 2) {
                $pagamento_dado['pagamento_forma_codigo'] = 1;
                $pagamento_dado['contabil_tipo'] = 'D';
                $pagamento_dado['valor'] = $rescliide['pre_forma_valor'];
                $pagamento_dado['data'] = $rescliide['pre_forma_data'];
                $pagamento_dado['cartao_nome'] = $rescliide['pre_forma_pagante_nome'];
                $pagamento_dado['cartao_numero'] = $rescliide['pre_forma_cartao_numero'];
                $pagamento_dado['cartao_validade'] = $rescliide['pre_forma_cartao_validade'];
                $pagamento_dado['cartao_parcela'] = $rescliide['pre_forma_cartao_parcela'];
                $pagamento_dado['deposito_referencia'] = "";
                $pagamento_dado['banco_numero'] = "";
                $pagamento_dado['agencia_numero'] = "";
                $pagamento_dado['conta_numero'] = "";
                $pagamento_dado['conta_dv'] = "";
                //Determina que é pre autorizado
                $pagamento_dado['pre_autorizacao'] = 1;
                //Cria o vetor de pagantes ou creditados
                $pagamento_dado['pagante_codigo'] = $rescliide['pre_pag_codigo'];
                $pagamento_dado['pagante_igual_contratante'] = $rescliide['pre_pagante_igual_contratante'];
                $pagamento_dado['pagante_nome'] = $rescliide['pre_pagante_nome'];
                $pagamento_dado['pagante_cpf_cnpj'] = $rescliide['pre_pagante_cpf_cnpj'];

                array_push($pagamento_dados, $pagamento_dado);
                $quarto_item_dados[$primeiro_quarto_item]['pagamento_dados'] = $pagamento_dados;
            }
        }

        $retorno_resdoccri = $this->reserva->resdoccri($rescliide['empresa_codigo'] ?? $this->session->read("empresa_selecionada")["empresa_codigo"], $rescliide['documento_numero'], $documento_dados, $contratante_dados, $quarto_item_dados);

        if ($retorno_resdoccri['retorno'] == 1) {
            //Faz a criaçao das fnrhs
            $quarto_item_vetor = array();
            for ($quarto_item = 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++)
                if ($rescliide['quarto_item_sem_tarifas_' . $quarto_item] != 1 && $rescliide['quarto_item_removido_' . $quarto_item] != 1)
                    array_push($quarto_item_vetor, $quarto_item);

            $this->estadia->estfnrcri($rescliide['empresa_codigo'] ?? $this->session->read("empresa_selecionada")["empresa_codigo"], $rescliide['documento_numero'], $quarto_item_vetor, array());
        }
        echo json_encode($retorno_resdoccri);
        $this->autoRender = false;
    }

    public function respdrcri() {

        /*  $historico_busca = $this->pagesController->consomeHistoricoTela('reservas/respaiatu');
          $this->request->data = array_merge($this->request->data, $historico_busca); */

        $inicial_data = $this->request->data['inicial_data'];
        $final_data = $this->request->data['final_data'];
        $empresa_codigo = $this->session->read('empresa_selecionada')["empresa_codigo"];

        //Está submetendo o form para criação da reserva
        if ($this->request->is('post') && isset($this->request->data['ajax'])) {

            $inicial_data_completa = Util::convertDataHoraSql($this->request->data['inicial_data_completa_original']);
            $final_data_completa = Util::convertDataHoraSql($this->request->data['final_data_completa_original']);

            $estadia_data = $this->geral->gerdatdet(explode(" ", $inicial_data_completa)[0], explode(" ", $final_data_completa)[0]);

            $quarto_tipo_array = array();
            $quarto_codigo_array = array();
            for ($i = 1; $i <= $this->request->data['resquaqtd']; $i++) {
                array_push($quarto_tipo_array, $this->request->data['quarto_tipo_codigo_' . $i]);
                array_push($quarto_codigo_array, $this->request->data['quarto_codigo_' . $i]);
            }

            //Inicialmente chama a resdoctem
            $retorno_resdoctem = $this->reserva->resdoctem($empresa_codigo, $inicial_data_completa, $final_data_completa, $estadia_data['datas'], $quarto_tipo_array, $quarto_codigo_array, 'p');
            $documento_numero = $retorno_resdoctem['documento_numero'] ?? null;

            //Deu erro na resdoctem
            if ($documento_numero == null) {
                $this->session->write('retorno_alert', $retorno_resdoctem['mensagem']['mensagem']);
                exit();
            } else {

                //Chama a resdoccri para criação
                //Pega todos os dados do formulário da respdrcri
                $rescliide = $this->request->data;

                unset($rescliide['form_validator_function']);

                //Mapeia os dados do documento
                $documento_dados = array();
                $documento_dados['documento_numero'] = $documento_numero;
                $documento_dados['venda_canal_codigo'] = $rescliide['venda_canal_codigo'];
                $documento_dados['venda_ponto_codigo'] = $rescliide['venda_ponto_codigo'];
                $documento_dados['inicial_data'] = $inicial_data_completa;
                $documento_dados['final_data'] = $final_data_completa;
                $documento_dados['texto'] = "";

                //Mapeia os dados do contratante
                $contratante_dados['contratante_codigo'] = $rescliide['c_codigo'];
                $contratante_dados['contratante_nome'] = $rescliide['cliprinom'];
                $contratante_dados['contratante_sobrenome'] = $rescliide['clisobnom'];
                $contratante_dados['contratante_email'] = $rescliide['clicadema'];
                $contratante_dados['contratante_cpfcnpj'] = $rescliide['clicpfcnp'] ?? null;
                $contratante_dados['contratante_tel_ddi'] = $rescliide['clitelddi'];
                $contratante_dados['contratante_tel_numero'] = $rescliide['clitelnum'];
                $contratante_dados['contratante_cel_ddi'] = $rescliide['clicelddi'];
                $contratante_dados['contratante_cel_numero'] = $rescliide['clicelnum'];
                $contratante_dados['contratante_modificado'] = $rescliide['c_has_changed'];

                //Faz o mapeamento das outras informações para cada quarto
                $quarto_item_dados = array();
                $indice_idade = 0;
                for ($quarto_item = 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++) {
                    if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 0)
                        $quarto_item_dados[$quarto_item]['reserva_dados']['quarto_status_codigo'] = 1;
                    else if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1)
                        $quarto_item_dados[$quarto_item]['reserva_dados']['quarto_status_codigo'] = 2;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['reserva_confirmacao_tipo'] = $rescliide['reserva_confirmacao_tipo_' . $quarto_item];
                    $quarto_item_dados[$quarto_item]['reserva_dados']['total_preco'] = $rescliide['total_quarto_item_' . $quarto_item];
                    $quarto_item_dados[$quarto_item]['reserva_dados']['confirmacao_limite'] = $rescliide['confirmacao_limite_' . $quarto_item] ?? null;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['cancelamento_limite'] = $rescliide['cancelamento_limite_' . $quarto_item] ?? null;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['cancelamento_valor'] = $rescliide['cancelamento_valor_' . $quarto_item] ?? null;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['texto'] = $rescliide['resmsghot_' . $quarto_item];
                    $quarto_item_dados[$quarto_item]['reserva_dados']['camareira_texto'] = $rescliide['resmsgcam_' . $quarto_item];
                    $quarto_item_dados[$quarto_item]['reserva_dados']['confirmacao_codigo'] = $rescliide['rescnfdet_' . $quarto_item];
                    $quarto_item_dados[$quarto_item]['reserva_dados']['cancelamento_codigo'] = $rescliide['rescandet_' . $quarto_item] ?? null;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['tipo_tarifa_codigo'] = $rescliide['tarifa_tipo_codigo_' . $quarto_item] ?? null;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['adulto_qtd'] = $rescliide['resaduqtd'][$quarto_item - 1] ?? null;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_qtd'] = $rescliide['rescriqtd'][$quarto_item - 1] ?? null;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['adulto_qtd_ajustada'] = $rescliide['resaduqtd'][$quarto_item - 1] ?? null;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_qtd_ajustada'] = $rescliide['rescriqtd'][$quarto_item - 1] ?? null;

                    $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_idade'] = "";
                    //Salva as idades das crianças
                    if ($rescliide['rescriqtd'][$quarto_item - 1] > 0) {
                        for ($i = $indice_idade; $i < $rescliide['rescriqtd'][$quarto_item - 1] + $indice_idade; $i++)
                            $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_idade'] .= $rescliide['crianca_idade'][$i] . ',';

                        $quarto_item_dados[$quarto_item]['reserva_dados']['crianca_idade'] = substr($quarto_item_dados[$quarto_item]['reserva_dados']['crianca_idade'], 0, -1);
                        $indice_idade = $i;
                    }

                    $quarto_item_dados[$quarto_item]['reserva_dados']['info_diarias'] = array();
                    for ($i = 1; $i <= $rescliide['dias_estadia']; $i++) {
                        array_push($quarto_item_dados[$quarto_item]['reserva_dados']['info_diarias'], $rescliide['info_diarias_' . $quarto_item . '_' . $rescliide['quarto_tipo_codigo_' . $quarto_item] . '_' . $rescliide['tarifa_tipo_codigo_' . $quarto_item] . '_' . $i]);
                    }
                    $quarto_item_dados[$quarto_item]['reserva_dados']['info_conta_adicionais'] = $rescliide['info_conta_adicionais_' . $quarto_item] ?? null;
                    $quarto_item_dados[$quarto_item]['reserva_dados']['reserva_data'] = $rescliide['resresdat'];
                    $quarto_item_dados[$quarto_item]['reserva_dados']['agencia_codigo'] = $rescliide['resviaage'];
                    $quarto_item_dados[$quarto_item]['reserva_dados']['externo_documento_numero'] = $rescliide['docnumage'];

                    //Mapeamento das partidas
                    $quarto_item_dados[$quarto_item]['partida_dados']['partida_item'] = array();
                    $quarto_item_dados[$quarto_item]['partida_dados']['partida_liquidacao_data'] = array();
                    $quarto_item_dados[$quarto_item]['partida_dados']['partida_valor'] = array();
                    $quarto_item_dados[$quarto_item]['partida_dados']['pagamento_requerido_evento'] = array();
                    for ($i = 1; $i <= $rescliide['total_partidas_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item]]; $i++) {
                        array_push($quarto_item_dados[$quarto_item]['partida_dados']['partida_item'], $rescliide['partida_item_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item] . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['partida_dados']['partida_liquidacao_data'], $rescliide['partida_data_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item] . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['partida_dados']['partida_valor'], $rescliide['partida_valor_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item] . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['partida_dados']['pagamento_requerido_evento'], $rescliide['partida_pagamento_requerido_evento_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item] . '_' . $i]);
                    }

                    $quarto_item_dados[$quarto_item]['reserva_dados']['pagamento_prazo_codigo'] = $rescliide['prazo_' . $quarto_item] ?? null;

                    //Mapeamento dos hóspedes de cada quarto
                    $total_hospedes = $rescliide['resaduqtd'][$quarto_item - 1] + $rescliide['rescriqtd'][$quarto_item - 1];
                    $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_codigo'] = array();
                    $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_codigo_antigo'] = array();
                    $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_email'] = array();
                    $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_nome'] = array();
                    $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_sobrenome'] = array();
                    $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_modificado'] = array();
                    $quarto_item_dados[$quarto_item]['hospede_dados']['hospede_novo_cliente'] = array();

                    for ($i = 1; $i <= $total_hospedes; $i++) {
                        array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_codigo'], $rescliide['h_codigo_' . $quarto_item . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_codigo_antigo'], $rescliide['h_codigo_antigo_' . $quarto_item . '_' . $i]);
                        array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_email'], $rescliide['h_email_' . $quarto_item . '_' . $i] ?? '');
                        array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_nome'], $rescliide['h_nome_' . $quarto_item . '_' . $i] ?? '');
                        array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_sobrenome'], $rescliide['h_sobrenome_' . $quarto_item . '_' . $i] ?? '');
                        array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_modificado'], $rescliide['h_has_changed_' . $quarto_item . '_' . $i]);
                        //Verifica se o primeiro hóspede é o contratante ou um novo cliente a ser criado
                        if ($i == 1) {
                            if (isset($rescliide['hospede_mesmo_contratante_' . $quarto_item]))
                                array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_novo_cliente'], 0);
                            else
                                array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_novo_cliente'], 1);
                        } else
                            array_push($quarto_item_dados[$quarto_item]['hospede_dados']['hospede_novo_cliente'], 1);
                    }

                    //Mapeamento das informaçoes de adicionais
                    $quarto_item_dados[$quarto_item]['adicional_dados']['produto_codigo'] = array();
                    $quarto_item_dados[$quarto_item]['adicional_dados']['adicional_qtd'] = array();
                    $quarto_item_dados[$quarto_item]['adicional_dados']['fixo_fator_codigo'] = array();
                    $quarto_item_dados[$quarto_item]['adicional_dados']['fator_fixo_valor'] = array();
                    $quarto_item_dados[$quarto_item]['adicional_dados']['lancamento_tempo'] = array();
                    $quarto_item_dados[$quarto_item]['adicional_dados']['unitario_preco'] = array();
                    $quarto_item_dados[$quarto_item]['adicional_dados']['adicional_total'] = array();
                    $quarto_item_dados[$quarto_item]['adicional_dados']['adicional_desconto'] = array();
                    $quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_tipo'] = array();
                    $quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_valor'] = array();

                    for ($i = 1; $i <= $rescliide['adicional_item_qtd_' . $quarto_item]; $i++) {
                        if (isset($rescliide['adicional_qtd_' . $quarto_item . '_' . $i]) && $rescliide['adicional_qtd_' . $quarto_item . '_' . $i] > 0) {
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['produto_codigo'], $rescliide['produto_codigo_' . $quarto_item . '_' . $i]);
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['adicional_qtd'], $rescliide['adicional_qtd_' . $quarto_item . '_' . $i]);
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['fixo_fator_codigo'], $rescliide['adicional_fixo_fator_codigo_' . $quarto_item . '_' . $i]);
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['fator_fixo_valor'], $rescliide['adicional_fator_fixo_valor_' . $quarto_item . '_' . $i]);
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['lancamento_tempo'], $rescliide['lancamento_tempo_' . $quarto_item . '_' . $i]);
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['unitario_preco'], $rescliide['preco_' . $quarto_item . '_' . $i]);
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['adicional_total'], $rescliide['adicional_total_' . $quarto_item . '_' . $i]);
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['adicional_desconto'], $rescliide['adicional_desconto_tmp_' . $quarto_item . '_' . $i]);
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_tipo'], $rescliide['horario_modificacao_tipo_' . $quarto_item . '_' . $i] ?? null);
                            array_push($quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_valor'], $rescliide['horario_modificacao_valor_' . $quarto_item . '_' . $i] ?? null);
                        }
                    }

                    //Verifica a existencia de adicionais inclusos na tarifa
                    $adicionais_itens = $this->reserva->resadipro($empresa_codigo, $rescliide['tarifa_tipo_codigo_' . $quarto_item]);


                    $adicionais_itens_inclusos = array();
                    foreach ($adicionais_itens as $adicional) {
                        if ($adicional['incluido'] == 1)
                            array_push($adicionais_itens_inclusos, $adicional);
                    }
                    foreach ($adicionais_itens_inclusos as $adicional_incluso) {

                        //calcula o fator_fixo_valor
                        switch ($adicional_incluso['variavel_fator_codigo']) {
                            case 1:
                            case 2:
                                $var_fator_fixo_valor = 0;
                                for ($i = 0; $i <= $adicional_incluso['max_qtd']; $i++) // verificar
                                    $var_fator_fixo_valor++;
                                break;
                            case 3:
                                $var_fator_fixo_valor = $rescliide['resaduqtd'][$quarto_item - 1] + $rescliide['rescriqtd'][$quarto_item - 1];
                                break;
                            case 4:
                                $var_fator_fixo_valor = $rescliide['resaduqtd'][$quarto_item - 1];
                                break;
                            case 5:
                                $var_fator_fixo_valor = $rescliide['dias_estadia'];
                                break;
                            case 6:
                                $var_fator_fixo_valor = ($rescliide['resaduqtd'][$quarto_item - 1] + $rescliide['rescriqtd'][$quarto_item - 1]) * $rescliide['dias_estadia'];
                                break;
                            case 7:
                                $var_fator_fixo_valor = $rescliide['resaduqtd'][$quarto_item - 1] * $rescliide['dias_estadia'];
                                break;
                            default:
                        }

                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['produto_codigo'], $adicional_incluso['adicional_codigo']);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['adicional_qtd'], $var_fator_fixo_valor);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['fixo_fator_codigo'], $adicional_incluso['fixo_fator_codigo']);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['fator_fixo_valor'], $var_fator_fixo_valor);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['lancamento_tempo'], $adicional_incluso['lancamento_tempo']);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['unitario_preco'], 0);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['adicional_total'], 0);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['adicional_desconto'], 'd|0.00|p|0.00|||');
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_tipo'], null);
                        array_push($quarto_item_dados[$quarto_item]['adicional_dados']['horario_modificacao_valor'], null);
                    }
                }
                //Mapeia as informações de pagamento
                //Indica que teve quartos confirmados
                if ($rescliide['total_pagamento_formas'] > 0) {
                    //Busca o primeiro quarto item com confirmacao
                    $primeiro_quarto_item = 0;
                    for ($quarto_item = 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++) {
                        //Indica que é confirmada com pagamento direto
                        if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $rescliide['reserva_valor_tipo_' . $quarto_item] == 1) {
                            $primeiro_quarto_item = $quarto_item;
                            break;
                        }
                    }
                    //Monta um vetor de pagamentos, baseado no total de formas de pagamentos
                    $pagamento_dados = array();

                    for ($i = 1; $i <= $rescliide['total_pagamento_formas']; $i++) {
                        //Foi selecionado um pagamento
                        if (isset($rescliide['respagfor_' . $i]) && $rescliide['respagfor_' . $i] != "" && $rescliide['forma_valor_' . $i] != "") {
                            $pagamento_dado['pagamento_forma_codigo'] = explode("|", $rescliide['respagfor_' . $i])[0];
                            $pagamento_dado['contabil_tipo'] = explode("|", $rescliide['respagfor_' . $i])[1];
                            $pagamento_dado['valor'] = $rescliide['forma_valor_' . $i];
                            $pagamento_dado['data'] = $rescliide['forma_data_' . $i];
                            $pagamento_dado['cartao_nome'] = $rescliide['forma_pagante_nome_' . $i] ?? "";
                            $pagamento_dado['cartao_numero'] = $rescliide['forma_cartao_numero_' . $i] ?? "";
                            $pagamento_dado['cartao_validade'] = $rescliide['forma_cartao_validade_' . $i] ?? "";
                            $pagamento_dado['cartao_parcela'] = $rescliide['forma_cartao_parcela_' . $i] ?? "";
                            $pagamento_dado['deposito_referencia'] = $rescliide['forma_referencia_' . $i] ?? "";
                            $pagamento_dado['banco_numero'] = $rescliide['forma_banco_' . $i] ?? "";
                            $pagamento_dado['agencia_numero'] = $rescliide['forma_agencia_' . $i] ?? "";
                            $pagamento_dado['conta_numero'] = $rescliide['forma_conta_corrente_' . $i] ?? "";
                            $pagamento_dado['conta_dv'] = $rescliide['forma_conta_corrente_dv_' . $i] ?? "";
                            //Determina que não é pre autorizado
                            $pagamento_dado['pre_autorizacao'] = 0;
                            //Cria o vetor de pagantes ou creditados
                            $pagamento_dado['pagante_codigo'] = $rescliide['pag_codigo_' . $i];
                            $pagamento_dado['pagante_igual_contratante'] = $rescliide['pagante_igual_contratante_' . $i];
                            $pagamento_dado['pagante_nome'] = $rescliide['pagante_nome_' . $i];
                            $pagamento_dado['pagante_cpf_cnpj'] = $rescliide['pagante_cpf_cnpj_' . $i];

                            array_push($pagamento_dados, $pagamento_dado);
                        }
                    }
                    $quarto_item_dados[$primeiro_quarto_item]['pagamento_dados'] = $pagamento_dados;

                    $pagamento_dados = array();
                    //Realiza a transferencia das contas dos outros quartos (confirmados) para o quarto 1
                    for ($quarto_item = $primeiro_quarto_item + 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++) {
                        //Indica que é confirmada com pagamento direto
                        if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $rescliide['reserva_valor_tipo_' . $quarto_item] == 1) {

                            $pagamento_dado['pagamento_forma_codigo'] = 7;
                            $pagamento_dado['contabil_tipo'] = 'C';
                            $pagamento_dado['valor'] = $rescliide['valor_parcela1_' . $quarto_item . '_' . $rescliide['prazo_' . $quarto_item]];
                            $pagamento_dado['data'] = $this->geral->geragodet(2);
                            $pagamento_dado['transferencia_documento_numero'] = $documento_numero;
                            $pagamento_dado['transferencia_quarto_item'] = $primeiro_quarto_item;
                            //Determina que não é pre autorizado
                            $pagamento_dado['pre_autorizacao'] = 0;
                            //Cria o vetor de pagantes ou creditados
                            $pagamento_dado['pagante_codigo'] = $rescliide['pag_codigo_1'];
                            $pagamento_dado['pagante_igual_contratante'] = $rescliide['pagante_igual_contratante_1'];
                            $pagamento_dado['pagante_nome'] = $rescliide['pagante_nome_1'];
                            $pagamento_dado['pagante_cpf_cnpj'] = $rescliide['pagante_cpf_cnpj_1'];

                            array_push($pagamento_dados, $pagamento_dado);

                            $quarto_item_dados[$quarto_item]['pagamento_dados'] = $pagamento_dados;
                            $pagamento_dados = array();
                        }
                    }
                    //Se forem todas não confirmadas
                }
                //Verifica se alguma possui pagamento pre autorizado
                //Busca o primeiro quarto item com confirmacao pre autorizada
                $primeiro_quarto_item = 0;
                for ($quarto_item = 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++) {
                    //Indica que é confirmada com pagamento pre
                    if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $rescliide['reserva_valor_tipo_' . $quarto_item] == 2) {
                        $primeiro_quarto_item = $quarto_item;
                        break;
                    }
                }
                //Se existe algum pagamento pre autorizado
                if ($primeiro_quarto_item != 0) {
                    $pagamento_dados = array();
                    if ($rescliide['reserva_confirmacao_tipo_' . $quarto_item] == 1 && $rescliide['reserva_valor_tipo_' . $quarto_item] == 2) {
                        $pagamento_dado['pagamento_forma_codigo'] = 1;
                        $pagamento_dado['contabil_tipo'] = 'D';
                        $pagamento_dado['valor'] = $rescliide['pre_forma_valor'];
                        $pagamento_dado['data'] = $rescliide['pre_forma_data'];
                        $pagamento_dado['cartao_nome'] = $rescliide['pre_forma_pagante_nome'];
                        $pagamento_dado['cartao_numero'] = $rescliide['pre_forma_cartao_numero'];
                        $pagamento_dado['cartao_validade'] = $rescliide['pre_forma_cartao_validade'];
                        $pagamento_dado['cartao_parcela'] = $rescliide['pre_forma_cartao_parcela'];
                        $pagamento_dado['deposito_referencia'] = "";
                        $pagamento_dado['banco_numero'] = "";
                        $pagamento_dado['agencia_numero'] = "";
                        $pagamento_dado['conta_numero'] = "";
                        $pagamento_dado['conta_dv'] = "";
                        //Determina que é pre autorizado
                        $pagamento_dado['pre_autorizacao'] = 1;
                        //Cria o vetor de pagantes ou creditados
                        $pagamento_dado['pagante_codigo'] = $rescliide['pre_pag_codigo'];
                        $pagamento_dado['pagante_igual_contratante'] = $rescliide['pre_pagante_igual_contratante'];
                        $pagamento_dado['pagante_nome'] = $rescliide['pre_pagante_nome'];
                        $pagamento_dado['pagante_cpf_cnpj'] = $rescliide['pre_pagante_cpf_cnpj'];

                        array_push($pagamento_dados, $pagamento_dado);
                        $quarto_item_dados[$primeiro_quarto_item]['pagamento_dados'] = $pagamento_dados;
                    }
                }
                $retorno = $this->reserva->resdoccri($rescliide['empresa_codigo'] ?? $this->session->read("empresa_selecionada")["empresa_codigo"], $documento_numero, $documento_dados, $contratante_dados, $quarto_item_dados);

                if ($retorno['retorno'] == 1) {
                    //Faz a criaçao das fnrhs
                    $quarto_item_vetor = array();
                    for ($quarto_item = 1; $quarto_item <= $rescliide['resquaqtd']; $quarto_item++)
                        array_push($quarto_item_vetor, $quarto_item);

                    $this->estadia->estfnrcri($rescliide['empresa_codigo'] ?? $this->session->read("empresa_selecionada")["empresa_codigo"], $documento_numero, $quarto_item_vetor, array());
                }
            }

            $this->autoRender = false;
        } else {
            $info_tela = $this->pagesController->montagem_tela('rescliide');

            $arr_gertelmon = $this->geral->gertelmon($this->session->read('empresa_grupo_codigo_ativo'), 'rescriini');
            $padrao_valores = Util::germonpad($arr_gertelmon);
            $adultos_padrao = 1;
            $criancas_padrao = 0;

            if ($padrao_valores['campo_padrao_valor_resaduqtd'] == 1)
                $adultos_padrao = $padrao_valores['padrao_valor_resaduqtd'];

            if ($padrao_valores['campo_padrao_valor_resaduqtd'] == 1)
                $criancas_padrao = $padrao_valores['padrao_valor_rescriqtd'];
            $inicial_data_completa = $inicial_data . ' ' . $this->session->read('inicial_padrao_horario');
            $final_data_completa = $final_data . ' ' . $this->session->read('final_padrao_horario');
            $quarto_qtd = sizeof($this->request->data['quarto_codigo']);

            $quarto_e_tipo_codigos = array();
            $quarto_tipo_array = array();
            $quarto_codigo_array = array();
            for ($i = 0; $i < $quarto_qtd; $i++) {
                $quarto_e_tipo_codigos[$this->request->data['quarto_codigo'][$i]] = $this->request->data['quarto_tipo_codigo'][$i];
                array_push($quarto_tipo_array, $this->request->data['quarto_tipo_codigo'][$i]);
                array_push($quarto_codigo_array, $this->request->data['quarto_codigo'][$i]);
            }


            //Busca os tipos de tarifas de acordo com os tipos de quartos
            $quarto_item = 1;
            $quarto_item_removido_array = array();
            $quarto_item_sem_tarifas_array = array();
            foreach ($quarto_e_tipo_codigos as $quarto_codigo => $quarto_tipo_codigo) {

                // Faz a busca do maximo de adultos e crianças
                $var_max_adultos[$quarto_tipo_codigo] = $this->reserva->resadumax($empresa_codigo, $quarto_tipo_codigo);
                $var_max_criancas[$quarto_tipo_codigo] = $this->reserva->rescrimax($empresa_codigo, 1, $quarto_tipo_codigo);

                $estadia_data = $this->geral->gerdatdet($inicial_data, $final_data);

                //Busca a variavel de acesso sequencia do tipo de quarto (melhorar)
                $acesso_sequencia = $this->connection->execute("SELECT acesso_sequencia_codigo FROM quarto_tipos WHERE "
                                . "empresa_codigo=:empresa_codigo AND quarto_tipo_codigo= :quarto_tipo_codigo", ['empresa_codigo' => $empresa_codigo, 'quarto_tipo_codigo' => $quarto_tipo_codigo])->fetchAll("assoc")[0]['acesso_sequencia_codigo'];

                $restartip = $this->reserva->restartip($empresa_codigo, $quarto_tipo_codigo, $estadia_data['datas'], 1, $acesso_sequencia);

                if (sizeof($restartip) > 0) {

                    $tarifa_tipos[$quarto_tipo_codigo] = $restartip[$quarto_tipo_codigo];
                    //Marca que nenhum quarto foi removido ou está sem tarifa
                    $quarto_item_removido_array[$quarto_item] = '0';
                    $quarto_item_sem_tarifas_array[$quarto_item] = '0';
                    //Inicializa a quantidade de adultos e criancas com 0
                    $this->request->data['resaduqtd_' . $quarto_item] = 1;
                    $this->request->data['rescriqtd_' . $quarto_item] = 0;
                    //Busca o nome dos tipos de quartos selecionados
                    $quarto_tipos = $this->geral->gercamdom('resquatip', $empresa_codigo);

                    foreach ($quarto_tipos as $quarto_tipo) {
                        if ($quarto_tipo['valor'] == $quarto_tipo_codigo)
                            $this->request->data['quarto_tipo_nome_' . $quarto_item] = $quarto_tipo['rotulo'];
                    }

                    //Verifica se existe apenas uma tarifa codigo, nesse caso ela ja vem pre-selecionada e deve então buscar os outros campos dependentes
                    $adicionais_itens[$quarto_codigo] = array();
                    $pagamento_prazo_itens[$quarto_codigo] = array();
                    $cancelamento_itens[$quarto_codigo] = array();
                    $confirmacao_itens[$quarto_codigo] = array();

                    if (sizeof($tarifa_tipos[$quarto_tipo_codigo]) == 1) {
                        $tarifa_tipo_codigo = array_keys($tarifa_tipos[$quarto_tipo_codigo])[0];
                        //Seta os nomes das tarifas no carrinho
                        $total_preco = 0;

                        $this->request->data['tarifa_nome_' . $quarto_item] = $tarifa_tipos[$quarto_tipo_codigo][$tarifa_tipo_codigo]['tarifa_tipo_nome'];
                        $this->request->data['tarifa_valor_' . $quarto_item] = $tarifa_tipos[$quarto_tipo_codigo][$tarifa_tipo_codigo]['total_tarifa'];
                        $total_preco += $tarifa_tipos[$quarto_tipo_codigo][$tarifa_tipo_codigo]['total_tarifa'];
                        //Adicionais
                        $adicionais_itens[$quarto_codigo] = $this->reserva->resadipro($empresa_codigo, $tarifa_tipos[$quarto_tipo_codigo][$tarifa_tipo_codigo]['tarifa_tipo_codigo']);
                        $adicionais_itens_inclusos[$quarto_item] = array();
                        foreach ($adicionais_itens[$quarto_codigo] as $adicional) {
                            if ($adicional['incluido'] == 1)
                                array_push($adicionais_itens_inclusos[$quarto_item], $adicional);
                        }

                        $var_numero_cell = 0;

                        for ($j = 0; $j < sizeof($adicionais_itens[$quarto_codigo]); $j++) {
                            //geraceseq para a descrição do produto
                            $parametros = array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $empresa_codigo,
                                'produto_codigo' => $adicionais_itens[$quarto_codigo][$j]['adicional_codigo']);
                            $adicionais_itens[$quarto_codigo][$j]['descricao'] = $this->geral->geraceseq('produto_descricao', array('descricao'), $parametros)['descricao'];

                            //geraceseq para o preço do produto
                            $parametros = array('produto_codigo' => $adicionais_itens[$quarto_codigo][$j]['adicional_codigo'],
                                'empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                                'empresa_codigo' => $empresa_codigo, 'venda_ponto_codigo' => "''");

                            $adicionais_itens[$quarto_codigo][$j]['preco'] = $this->geral->geraceseq('produto_preco', array('preco'), $parametros)['preco'];
                            $adicionais_itens[$quarto_codigo][$j]['servico_taxa_incide'] = $this->geral->geraceseq('servico_taxa_incide', array('servico_taxa_incide'), $parametros)['servico_taxa_incide'];

                            ++$var_numero_cell;
                        }

                        //Pagamentos prazos
                        $pagamento_prazo_itens[$quarto_codigo] = $this->reserva->respagpar($empresa_codigo, $tarifa_tipos[$quarto_tipo_codigo][$tarifa_tipo_codigo]['tarifa_tipo_codigo'], $tarifa_tipos[$quarto_tipo_codigo][$tarifa_tipo_codigo]['total_tarifa'], sizeof($estadia_data['datas']), $estadia_data['datas'], $this->geral->geragodet(1));

                        for ($i = 0; $i < sizeof($pagamento_prazo_itens[$quarto_codigo]); $i++) {
                            $pagamento_prazo_itens[$quarto_codigo][$i]["tarifa_variacao"] = $this->geral->gersepatr($pagamento_prazo_itens[$quarto_codigo][$i]["tarifa_variacao"]);
                            $pagamento_prazo_itens[$quarto_codigo][$i]["partida_valor"] = $this->geral->gersepatr($pagamento_prazo_itens[$quarto_codigo][$i]["partida_valor"]);
                        }

                        //Cancelamentos
                        $cancelamento_itens[$quarto_codigo] = $this->reserva->rescandet($empresa_codigo, $tarifa_tipos[$quarto_tipo_codigo][$tarifa_tipo_codigo]['tarifa_tipo_codigo']);

                        //Confirmações
                        $confirmacao_itens[$quarto_codigo] = $this->reserva->rescnfdet($empresa_codigo, $tarifa_tipos[$quarto_tipo_codigo][$tarifa_tipo_codigo]['tarifa_tipo_codigo']);

                        for ($i = 0; $i < sizeof($confirmacao_itens[$quarto_codigo]); $i++) {
                            if ($confirmacao_itens[$quarto_codigo][$i]['evento'] == 1) {
                                $data_confirmacao = Util::somaHora($inicial_data_completa, $confirmacao_itens[$quarto_codigo][$i]['tempo_hora']);
                                //se a data de cancelamento estiver no passado
                                if (Util::comparaDatas($this->geral->geragodet(2), $data_confirmacao) == 1) {
                                    unset($confirmacao_itens[$quarto_codigo][$i]);
                                    $confirmacao_itens[$quarto_codigo] = array_values($confirmacao_itens[$quarto_codigo]);
                                }
                            } elseif ($confirmacao_itens[$quarto_codigo][$i]['evento'] == 2) {
                                $data_confirmacao = Util::somaHora($final_data_completa, $confirmacao_itens[$quarto_codigo][$i]['tempo_hora']);
                                //se a data de cancelamento estiver no passado
                                if (Util::comparaDatas($this->geral->geragodet(2), $data_confirmacao) == 1) {
                                    unset($confirmacao_itens[$quarto_codigo][$i]);
                                    $confirmacao_itens[$quarto_codigo] = array_values($confirmacao_itens[$quarto_codigo]);
                                }
                            }
                        }
                        $this->set('total_preco', $total_preco);
                    } else
                        $this->set('total_preco', 0);

                    $quarto_item++;
                } else {
                    echo 0;
                    $this->autoRender = false;
                }
            }
            $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
            $this->set('moeda_simbolo', $this->geral->germoeatr());
            $this->set('dominio_ddi_lista', $this->geral->gercamdom('clicelddi'));
            $this->set('var_max_adultos', $var_max_adultos);
            $this->set('var_max_criancas', $var_max_criancas);
            $this->set('quarto_qtd', $quarto_qtd);
            $this->set('quarto_e_tipo_codigos', $quarto_e_tipo_codigos);
            $this->set('inicial_data', $inicial_data);
            $this->set('final_data', $final_data);

            $this->set('tarifa_tipos', $tarifa_tipos);
            $this->set('cliente_univoco_campo', $this->session->read('cliente_univoco_campo'));
            $this->set('dias_estadia', $estadia_data[0]['data_quantidade']);
            $this->set('quarto_item_removido_array', $quarto_item_removido_array);
            $this->set('quarto_item_sem_tarifas_array', $quarto_item_sem_tarifas_array);

            $this->set('inicial_padrao_horario', $this->session->read('inicial_padrao_horario'));
            $this->set('final_padrao_horario', $this->session->read('final_padrao_horario'));
            $this->set('inicial_data_completa', $inicial_data_completa);
            $this->set('final_data_completa', $final_data_completa);
            $this->set('ddi_padrao', $this->session->read('ddi_padrao'));
            $this->set('adultos_padrao', $adultos_padrao);
            $this->set('criancas_padrao', $criancas_padrao);
            $dominio_agencia_viagem = $this->geral->gercamdom('resviaage');
            $elemento_ds = $dominio_agencia_viagem[array_search('ds', array_column($dominio_agencia_viagem, 'valor'))];
            $elemento_da = $dominio_agencia_viagem[array_search('da', array_column($dominio_agencia_viagem, 'valor'))];
            $dominio_agencia_viagem[array_search('ds', array_column($dominio_agencia_viagem, 'valor'))] = $dominio_agencia_viagem[0];
            $dominio_agencia_viagem[array_search('da', array_column($dominio_agencia_viagem, 'valor'))] = $dominio_agencia_viagem[1];
            $dominio_agencia_viagem[0] = $elemento_ds;
            $dominio_agencia_viagem[1] = $elemento_da;
            $this->set('dominio_agencia_viagem', $dominio_agencia_viagem);
            $this->set('var_respagfor', $this->reserva->respagfor($empresa_codigo, $this->session->read('venda_canal_codigo'), "0"));
            $this->set($this->request->data);

            $this->viewBuilder()->setLayout('ajax');
            $this->set('adicionais_itens', $adicionais_itens);
            $this->set('adicionais_itens_inclusos', $adicionais_itens_inclusos);
            $this->set('pagina', 'respdrcri');
            $this->set('pagamento_prazo_itens', $pagamento_prazo_itens);
            $this->set('cancelamento_itens', $cancelamento_itens);
            $this->set('confirmacao_itens', $confirmacao_itens);
            $this->set('tarifa_manual_entrada', $this->session->read('empresa_selecionada')['tarifa_manual_entrada']);
            $this->set('reserva_expiracao', $this->session->read('empresa_selecionada')['reserva_expiracao']);
            $this->set('reserva_intervalo', $this->session->read('empresa_selecionada')['reserva_intervalo']);
            $this->set($info_tela);
        }
    }

    /*
     * Remove a reserva temporária quando expira o tempo (AJAX)
     */

    public function restemexc() {
        $this->reserva->restemexc($this->request->data['empresa_codigo'], $this->request->data['documento_numero']);
        $this->autoRender = false;
    }

    /*
     * Atualiza o tempo em Documento Sessao a cada intervalo em rescliide
     */

    public function ajaxatutmpses() {
        $this->reserva->atutmpses($this->request->data['empresa_codigo'], $this->request->data['documento_numero']);
        $this->autoRender = false;
    }

    /*
     * Busca Reservas Expiradas
     */

    public function resexpsel() {
        echo $this->reserva->resexpsel($this->request->data['empresa_codigo']);
        $this->autoRender = false;
    }

    /*
     * Cancela reservas marcadas (Ajax)
     * Nesse caso consideramos que todas as reservas são preliminares
     */

    public function resexpcan() {
        $reservas_a_cancelar = explode(",", $this->request->data['documentos_a_cancelar']);
        $quantidade_modificada = 0;
        for ($i = 0; $i < sizeof($reservas_a_cancelar); $i++) {
            $this->reserva->resdoccan($this->request->data['empresa_codigo'], $reservas_a_cancelar[$i]);
            $quantidade_modificada++;
        }
        $retorno = $this->geral->germencri($this->request->data['empresa_codigo'], 24, 1, $quantidade_modificada)['mensagem'];
        echo $retorno;
        $this->autoRender = false;
    }

    /*
     * Exibe a tela de conta quando vai cancelar a reserva
     */

    public function resdoccan() {
        $info_tela = $this->pagesController->montagem_tela('resdoccan');
        $empresa_codigo = $this->request->data['empresa_codigo'] ?? $this->request->params['pass'][0] ?? $this->request->query['gerempcod'] ?? 0;
        $documento_numero = $this->request->data['documento_numero'] ?? $this->request->params['pass'][2] ?? $this->request->query['resdocnum'] ?? 0;
        $quarto_item = $this->request->data['quarto_item'] ?? $this->request->params['pass'][3] ?? $this->request->query['quarto_item'] ?? 0;
        $opened_acordions = $this->request->data['opened_acordions'] ?? $this->request->params['pass'][4] ?? $this->request->query['opened_acordions'] ?? '';
        $ocultar_estornados = $this->request->query['ocultar_estornados'] ?? '';
        $reserva_dados = $this->reserva->resdocpes($empresa_codigo, "rs", $documento_numero);
        //Busca os itens de conta referente a essa reserva
        $pesquisa_contas = $this->documento_conta->conresexi($empresa_codigo, $documento_numero);
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

        //Busca as informações do item multa de cancelamento, onde automatica_criacao_codigo for 4
        $multa_cancelamento = $this->connection->execute("SELECT descricao, produto_codigo FROM produto_empresa_grupos WHERE empresa_grupo_codigo=:empresa_grupo_codigo AND produto_codigo = :produto_codigo", ['empresa_grupo_codigo' => $this->session->read("empresa_selecionada")['empresa_grupo_codigo'], 'produto_codigo' => $this->session->read('multa_cancelamento_codigo')])->fetchAll("assoc");

        foreach ($reserva_dados['results'] as $quarto_item_dados) {
            $multa_cancelamento_item[$quarto_item_dados['quarto_item']]['produto_codigo'] = $multa_cancelamento[0]['descricao'];
            $multa_cancelamento_item[$quarto_item_dados['quarto_item']]['produto_qtd'] = 1;
            $multa_cancelamento_item[$quarto_item_dados['quarto_item']]['total_valor'] = $quarto_item_dados['cancelamento_valor'];
            $multa_cancelamento_item[$quarto_item_dados['quarto_item']]['data'] = $quarto_item_dados['cancelamento_limite'];
            $multa_cancelamento_item[$quarto_item_dados['quarto_item']]['multa_cancelamento'] = 1;
        }

        //Cria um item virtual para multa de cancelamento
        $this->set('multa_cancelamento_item', $multa_cancelamento_item);
        $this->set('quarto_itens', $quarto_itens);
        $this->set('contas_quarto_item', $contas_quarto_item);
        $this->set('pesquisa_contas', $pesquisa_contas);
        $this->set('cabecalho_conta', $reserva_dados['results']);
        $this->set('documento_numero_selecionado', $documento_numero);
        $this->set('quarto_item_selecionado', $quarto_item);
        $this->set('opened_acordions', $opened_acordions . '|');
        $this->set('ocultar_estornados', $ocultar_estornados);
        $this->set('cancelamento_limite', $this->request->data['cancelamento_limite'] ?? '');
        $this->set('cancelamento_valor', $this->request->data['cancelamento_valor'] ?? '');
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function cliconaut() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $search = $this->request->query('search');
            $results = $this->cliente->cliconaut($search);
            $resultArr = array();
            foreach ($results as $result) {
                $resultArr[] = array('label' => $result['nome'] . ' ' . $result['sobrenome'], 'value' => $result['nome'], 'c_codigo' => $result['cliente_codigo']
                    , 'c_nome' => $result['nome'], 'c_sobrenome' => $result['sobrenome']
                    , 'c_email' => $result['email'], 'c_documento_tipo' => $result['cliente_documento_tipo']
                    , 'c_documento_numero' => $result['cliente_documento_numero'], 'c_documento_orgao' => $result['cliente_documento_orgao']
                    , 'c_cpf_numero' => $result['cpf'], 'c_cel_ddi' => $result['cel_ddi']
                    , 'c_cel_numero' => $result['cel_numero']
                    , 'c_tel_ddi' => $result['tel_ddi'], 'c_tel_numero' => $result['tel_numero']);
            }
            $this->set('resultArr', $resultArr);
            echo json_encode($resultArr);
        }
    }

    public function resadipre() {
        echo $this->reserva->resadipre($this->request->data['resaduqtd'], $this->request->data['rescriqtd'], $this->request->data['diarias_qtd'], $this->request->data['adicional_codigo'], $this->request->data['quantidade'], $this->request->data['fixo_fator_codigo'], $this->request->data['preco']);
        $this->autoRender = false;
    }

    public function rescliexi() {
        $retorno = "";
        $clientes = $this->reserva->rescliexi($this->request->data['empresa_codigo'], $this->request->data['documento_numero'], $this->request->data['papel_codigo']);
        foreach ($clientes as $cliente) {
            $retorno .= $cliente->cliente_codigo . "|";
        }
        echo $retorno;
        $this->autoRender = false;
    }

    /*
     * Exibe o painel de ocupação de reservas
     */

    public function respaiatu() {
        $info_tela = $this->pagesController->montagem_tela('respaiatu');
        $historico_busca = $this->pagesController->consomeHistoricoTela('reservas/respaiatu');
        $this->request->data = array_merge($this->request->data, $historico_busca);


        //Se consumiu um item do histórico, deve zerar a janela, para que não seja recalculado o periodo
        if (sizeof($historico_busca) > 0)
            $this->request->data['janela_atual'] = 0;

        if (isset($this->request->data['respaiper']))
            $periodo = $this->request->data['respaiper'];
        elseif ($info_tela['padrao_valor_respaiper'] != null && $info_tela['padrao_valor_respaiper'] != "")
            $periodo = $info_tela['padrao_valor_respaiper'];
        else
            $periodo = 14;
        $janela = $this->request->data['janela_atual'] ?? 0;

        $inicio_data = Util::convertDataSQL($this->request->data['respaidat'] ?? $this->geral->geragodet(1));

        //Faz a configuração das datas iniciais e finais de acordo com o periodo escolhido
        //Se for diferente os periodos de semanas
        if ($periodo != 30) {
            //Transforma a inicial data como sendo a segunda feira da semana da data
            $dayofweek = date('w', strtotime($inicio_data));
            //Se for domingo, não coloca como a segunda feira da semana, mas sim da semana anterior
            if ($dayofweek == 0)
                $dayofweek = 7;
            $inicial_data = date('Y-m-d', strtotime(('-' . ($dayofweek - 1)) . ' days ', strtotime($inicio_data)));
            $final_data = date('Y-m-d', strtotime(Util::somaDias($inicial_data, $periodo - 1, 1)));
            //Se for igual a um mes
        } else {
            $inicial_data = date('Y-m-01', strtotime($inicio_data));
            $final_data = date('Y-m-t', strtotime($inicio_data));
        }

        //Se estiver mudando a janela pela setinha por semana
        if ($janela >= 0)
            $multiplicador_janela = 1;
        else if ($janela < 0)
            $multiplicador_janela = -1;
        if ($janela != 0 && $periodo != 30) {
            $inicial_data = date('Y-m-d', strtotime(Util::somaDias($inicial_data, $multiplicador_janela * 7, 0)));
            $final_data = date('Y-m-d', strtotime(Util::somaDias($final_data, $multiplicador_janela * 7, 0)));

            //Se estiver mudando por mes
        } elseif ($janela != 0 && $periodo == 30) {
            $inicial_data = date('Y-m-01', strtotime('-' . (-1) * $multiplicador_janela . ' month', strtotime($inicio_data)));
            $final_data = date('Y-m-t', strtotime('-' . (-1) * $multiplicador_janela . ' month', strtotime($inicio_data)));
        }

        $gerdatdet = $this->geral->gerdatdet($inicial_data, Util::somaDias($final_data, 1, 0));
        $feriados = $this->geral->gerferdet($this->session->read('empresa_selecionada')['empresa_codigo'], $inicial_data, $final_data);
        $dias_nao_uteis = array();

        foreach ($gerdatdet['datas'] as $key => $data) {
            if ((date('N', strtotime($data)) >= 6) || (array_search($data, array_column($feriados, 'data')) !== false))
                array_push($dias_nao_uteis, $data);
        }

        //calcula os meses existentes entre as datas para exibição na tela
        $inicio = strtotime($inicial_data);
        $fim = date('m', strtotime($final_data));

        do {
            $mes = date('m', $inicio);
            //calcula quantas datas estão em cada mês
            $data_qtd = 0;
            foreach ($gerdatdet['datas'] as $data) {
                if (date('m', strtotime($data)) == $mes)
                    $data_qtd++;
            }

            $meses[] = array('mes' => Util::getNomeMes($mes), 'data_qtd' => $data_qtd);
            $inicio = strtotime('+1 month', $inicio);
        } while ($mes != $fim);

        $respaiatu = $this->reserva->respaiatu($this->session->read('empresa_selecionada')['empresa_codigo'] ?? 1, $inicial_data, $final_data);

        $gerqtppes = $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo'] ?? 1);
        $resviaage = $this->geral->gercamdom('resviaage');

        $quarto_tipos = array();
        foreach ($gerqtppes as $quarto_tipo)
            $quarto_tipos[$quarto_tipo['valor']] = $quarto_tipo['rotulo'];

        $agencias = array();
        foreach ($resviaage as $agencia)
            $agencias[$agencia['valor']] = $agencia['rotulo'];

        $this->set('quarto_tipos', $quarto_tipos);
        $this->set('agencias', $agencias);

        $quartos = $this->connection->execute("SELECT quarto_codigo, quarto_tipo_codigo FROM quartos WHERE empresa_codigo=:empresa_codigo AND excluido<>1", ['empresa_codigo' => $this->session->read("empresa_selecionada")['empresa_codigo'] ?? 1])->fetchAll("assoc");
        $quarto = array();
        foreach ($quartos as $quarto_item)
            $quarto[$quarto_item['quarto_tipo_codigo']][] = $quarto_item['quarto_codigo'];

        foreach ($quarto as $quarto_tipo_codigo => $quarto_codigo) {
            for ($i = 0; $i < sizeof($quarto_codigo); $i++)
                if (!isset($respaiatu['respaiatu_dados'][$quarto_tipo_codigo][$quarto_codigo[$i]]))
                    $respaiatu['respaiatu_dados'][$quarto_tipo_codigo][strval($quarto_codigo[$i])] = array();
        }
        $this->set('quartos', $quarto);
        $this->set($info_tela);
        $this->set('datas', $gerdatdet['datas']);
        $this->set('reservas_pendentes_alocacao', $this->estadia->estquapen($this->session->read('empresa_selecionada')['empresa_codigo'] ?? 1, $inicial_data, $final_data));
        $this->set('empresa_codigo', $this->session->read('empresa_selecionada')['empresa_codigo'] ?? 1);
        $this->set('respaidat', Util::convertDataDMY($inicial_data));
        $this->set('respaiper', $periodo);
        $this->set('meses', $meses);
        $this->set('respaiper_list', $this->geral->gercamdom('respaiper'));
        $this->set('respaiatu_dados', json_encode($respaiatu['respaiatu_dados'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->set('cancelamento_motivos', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'], 'motivo_tipo_codigo' => "'ca'")));
        $this->set('reservado_tipo_quarto', $respaiatu['reservado_tipo_quarto']);
        $this->set('disponivel_quarto_tipo', $respaiatu['disponivel_quarto_tipo']);
        $this->set('ocupacao_diaria', $respaiatu['ocupacao_diaria']);
        $this->set('dias_nao_uteis', $dias_nao_uteis);
        $this->set('ocupacao_media', $respaiatu['ocupacao_media']);
        $this->set('janela_atual', $this->request->data['janela_atual'] ?? 0);
        $this->set('quarto_tipo_nomes_curtos', json_encode($this->geral->gercamdom('gerquatic', $this->session->read('empresa_selecionada')['empresa_codigo'])));
        $this->set('quarto_tipo_nomes_curtos_array', $this->geral->gercamdom('gerquatic', $this->session->read('empresa_selecionada')['empresa_codigo']));
        if (isset($this->request->data['gerordatr']))
            $this->set('gerordatr', $this->request->data['gerordatr']);
        elseif ($info_tela['padrao_valor_gerordatr'] != '') {
            $this->set('gerordatr', $info_tela['padrao_valor_gerordatr']);
        } else
            $this->set('gerordatr', "asc");

        $this->viewBuilder()->setLayout('ajax');
    }

    public function respadatu() {
        $periodo = $this->request->data['respaiper'] ?? 14;
        $janela = $this->request->data['janela_atual'] ?? 0;

        $inicio_data = Util::convertDataSQL($this->request->data['respaidat'] ?? $this->geral->geragodet(1));

        //Faz a configuração das datas iniciais e finais de acordo com o periodo escolhido
        //Se for diferente os periodos de semanas
        if ($periodo != 30) {
            //Transforma a inicial data como sendo a segunda feira da semana da data
            $dayofweek = date('w', strtotime($inicio_data));
            $inicial_data = date('Y-m-d', strtotime(('-' . ($dayofweek - 1)) . ' days ', strtotime($inicio_data)));
            $final_data = date('Y-m-d', strtotime(Util::somaDias($inicial_data, $periodo - 1, 1)));
            //Se for igual a um mes
        } else {
            $inicial_data = date('Y-m-01', strtotime($inicio_data));
            $final_data = date('Y-m-t', strtotime($inicio_data));
        }

        //Se estiver mudando a janela pela setinha por semana
        if ($janela >= 0)
            $multiplicador_janela = 1;
        else if ($janela < 0)
            $multiplicador_janela = -1;
        if ($janela != 0 && $periodo != 30) {
            $inicial_data = date('Y-m-d', strtotime(Util::somaDias($inicial_data, $multiplicador_janela * 7, 0)));
            $final_data = date('Y-m-d', strtotime(Util::somaDias($final_data, $multiplicador_janela * 7, 0)));

            //Se estiver mudando por mes
        } elseif ($janela != 0 && $periodo == 30) {
            $inicial_data = date('Y-m-01', strtotime('-' . (-1) * $multiplicador_janela . ' month', strtotime($inicio_data)));
            $final_data = date('Y-m-t', strtotime('-' . (-1) * $multiplicador_janela . ' month', strtotime($inicio_data)));
        }

        $gerdatdet = $this->geral->gerdatdet($inicial_data, Util::somaDias($final_data, 1, 0));
        $feriados = $this->geral->gerferdet($inicial_data, $final_data);
        //calcula os meses existentes entre as datas para exibição na tela
        $inicio = strtotime($inicial_data);
        $fim = date('m', strtotime($final_data));

        do {
            $mes = date('m', $inicio);
            //calcula quantas datas estão em cada mês
            $data_qtd = 0;
            foreach ($gerdatdet['datas'] as $data) {
                if (date('m', strtotime($data)) == $mes)
                    $data_qtd++;
            }

            $meses[] = array('mes' => Util::getNomeMes($mes), 'data_qtd' => $data_qtd);
            $inicio = strtotime('+1 month', $inicio);
        } while ($mes != $fim);

        $respaiatu = $this->reserva->respaiatu($this->session->read('empresa_selecionada')['empresa_codigo'] ?? 1, $inicial_data, $final_data);

        $quartos = $this->connection->execute("SELECT quarto_codigo, quarto_tipo_codigo FROM quartos WHERE empresa_codigo=:empresa_codigo AND excluido<>1", ['empresa_codigo' => $this->session->read("empresa_selecionada")['empresa_codigo'] ?? 1])->fetchAll("assoc");
        $quarto = array();
        foreach ($quartos as $quarto_item)
            $quarto[$quarto_item['quarto_tipo_codigo']][] = $quarto_item['quarto_codigo'];

        foreach ($quarto as $quarto_tipo_codigo => $quarto_codigo) {
            for ($i = 0; $i < sizeof($quarto_codigo); $i++)
                if (!isset($respaiatu['respaiatu_dados'][$quarto_tipo_codigo][$quarto_codigo[$i]]))
                    $respaiatu['respaiatu_dados'][$quarto_tipo_codigo][strval($quarto_codigo[$i])] = array();
        }
        $retorno['reservas_pendentes_alocacao'] = $this->estadia->estquapen($this->session->read('empresa_selecionada')['empresa_codigo'] ?? 1, $inicial_data, $final_data);
        $retorno['respaiatu_dados'] = $respaiatu['respaiatu_dados'];
        $retorno['reservado_tipo_quarto'] = $respaiatu['reservado_tipo_quarto'];


        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->autoRender = false;
    }

    /*
     * Reserva estrutura painel atualizar
     * Responsável por retornar a estrutura (quartos e datas) utilizadas na montagem da tabela da respaiatu
     * @return lista de quartos, tipos e datas
     */

    public function respaeatu() {
        $info_tela = $this->pagesController->montagem_tela('respaiatu');

        $inicio_data = Util::convertDataSQL($this->request->data['respaidat'] ?? $this->geral->geragodet(1));
        $periodo = $this->request->data['respaiper'] ?? 14;
        $janela = $this->request->data['janela_atual'] ?? 0;
        //Faz a configuração das datas iniciais e finais de acordo com o periodo escolhido
        //Se for diferente os periodos de semanas
        if ($periodo != 30) {
            //Transforma a inicial data como sendo a segunda feira da semana da data
            $dayofweek = date('w', strtotime($inicio_data));
            $inicial_data = date('Y-m-d', strtotime(('-' . ($dayofweek - 1)) . ' days ', strtotime($inicio_data)));
            $final_data = date('Y-m-d', strtotime(Util::somaDias($inicial_data, $periodo - 1, 1)));
            //Se for igual a um mes
        } else {
            $inicial_data = date('Y-m-01', strtotime($inicio_data));
            $final_data = date('Y-m-t', strtotime($inicio_data));
        }

        //Se estiver mudando a janela pela setinha por semana
        if ($janela >= 0)
            $multiplicador_janela = 1;
        else if ($janela < 0)
            $multiplicador_janela = -1;
        if ($janela != 0 && $periodo != 30) {
            $inicial_data = date('Y-m-d', strtotime(Util::somaDias($inicial_data, $multiplicador_janela * 7, 0)));
            $final_data = date('Y-m-d', strtotime(Util::somaDias($final_data, $multiplicador_janela * 7, 0)));

            //Se estiver mudando por mes
        } elseif ($janela != 0 && $periodo == 30) {
            $inicial_data = date('Y-m-01', strtotime('-' . (-1) * $multiplicador_janela . ' month', strtotime($inicio_data)));
            $final_data = date('Y-m-t', strtotime('-' . (-1) * $multiplicador_janela . ' month', strtotime($inicio_data)));
        }

        $gerdatdet = $this->geral->gerdatdet($inicial_data, Util::somaDias($final_data, 1, 0));
        $feriados = $this->geral->gerferdet($inicial_data, $final_data);
        $dias_nao_uteis = array();

        foreach ($gerdatdet['datas'] as $key => $data) {
            if ((date('N', strtotime($data)) >= 6) || (array_search($data, array_column($feriados, 'data')) !== false))
                array_push($dias_nao_uteis, $data);
        }

        //calcula os meses existentes entre as datas para exibição na tela
        $inicio = strtotime($inicial_data);
        $fim = date('m', strtotime($final_data));

        do {
            $mes = date('m', $inicio);
            //calcula quantas datas estão em cada mês
            $data_qtd = 0;
            foreach ($gerdatdet['datas'] as $data) {
                if (date('m', strtotime($data)) == $mes)
                    $data_qtd++;
            }

            $meses[] = array('mes' => Util::getNomeMes($mes), 'data_qtd' => $data_qtd);
            $inicio = strtotime('+1 month', $inicio);
        } while ($mes != $fim);

        $gerqtppes = $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo'] ?? 1);

        $quarto_tipos = array();
        foreach ($gerqtppes as $quarto_tipo)
            $quarto_tipos[$quarto_tipo['valor']] = $quarto_tipo['rotulo'];

        $quartos = $this->connection->execute("SELECT quarto_codigo, quarto_tipo_codigo FROM quartos WHERE empresa_codigo=:empresa_codigo AND excluido<>1", ['empresa_codigo' => $this->session->read("empresa_selecionada")['empresa_codigo'] ?? 1])->fetchAll("assoc");
        $quarto = array();
        foreach ($quartos as $quarto_item)
            $quarto[$quarto_item['quarto_tipo_codigo']][] = $quarto_item['quarto_codigo'];

        foreach ($quarto as $quarto_tipo_codigo => $quarto_codigo) {
            for ($i = 0; $i < sizeof($quarto_codigo); $i++)
                if (!isset($respaiatu['respaiatu_dados'][$quarto_tipo_codigo][$quarto_codigo[$i]]))
                    $respaiatu['respaiatu_dados'][$quarto_tipo_codigo][strval($quarto_codigo[$i])] = array();
        }

        $resviaage = $this->geral->gercamdom('resviaage');
        $agencias = array();
        foreach ($resviaage as $agencia)
            $agencias[$agencia['valor']] = $agencia['rotulo'];

        $this->set($info_tela);
        $this->set('agencias', $agencias);
        $this->set('respaidat', Util::convertDataDMY($inicial_data));
        $this->set('respaiper', $periodo);
        $this->set('respaiper_list', $this->geral->gercamdom('respaiper'));
        $this->set('meses', $meses);
        $this->set('dias_nao_uteis', $dias_nao_uteis);
        $this->set('tela_nome', $arr_gertelmon[0]['tela_nome']);
        $this->set('quartos', json_encode($quarto));
        $this->set('quarto_tipos', json_encode($quarto_tipos));
        $this->set('datas', json_encode($gerdatdet['datas']));
        $this->set('quarto_tipo_nomes_curtos', json_encode($this->geral->gercamdom('gerquatic', $this->session->read('empresa_selecionada')['empresa_codigo'])));
        $this->set('quarto_tipo_nomes_curtos_array', $this->geral->gercamdom('gerquatic', $this->session->read('empresa_selecionada')['empresa_codigo']));
        $this->set('empresa_codigo', $this->session->read('empresa_selecionada')['empresa_codigo'] ?? 1);
        $this->set('janela_atual', $this->request->data['janela_atual'] ?? 0);

        if (isset($this->request->data['ordenacao_tipo']))
            $this->set('ordenacao_tipo', $this->request->data['ordenacao_tipo']);
        else
            $this->set('ordenacao_tipo', "asc");

        $this->viewBuilder()->setLayout('ajax');
    }

    public function resblocri() {
        $info_tela = $this->pagesController->montagem_tela('resblocri');

        if ($this->request->is('post')) {
            $this->set($this->request->data);

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

            $retorno = $this->servico->serdoccri($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['serdoctip'], $this->request->data['serinidat'], $this->request->data['serfindat'], $this->request->data['serquacod'], $this->request->data['serdocsta'] ?? null, $this->request->data['serdocmot'] ?? null, $this->request->data['serdoctxt'] ?? null);
            //$this->session->write('retorno', $retorno);
            $this->session->write('retorno_footer', $retorno['mensagem']['mensagem']);
            if (isset($this->request->data['url_redirect_after']))
                $this->autoRender = false;
        }else {
            $this->set('serinidat', $this->geral->geratudat());
            $this->set('serfindat', Util::convertDataDMY(Util::addDate(Util::convertDataSQL($this->geral->geratudat()), "1")));
            //Seta o ativo como padrao
            $this->set('serdocsta', 1);
        }

        //Busca a lista de quartos
        $dados_quartos = $this->connection->execute("SELECT q.quarto_codigo, qt.quarto_tipo_curto_nome FROM quartos q INNER JOIN quarto_tipos qt ON q.empresa_codigo = qt.empresa_codigo"
                        . " AND q.quarto_tipo_codigo = qt.quarto_tipo_codigo WHERE q.empresa_codigo = :empresa_codigo ORDER BY q.quarto_codigo", ['empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo']])->fetchAll("assoc");
        $quarto_por_tipo = array();
        foreach ($dados_quartos as $quarto_dado) {
            $quarto_por_tipo[$quarto_dado['quarto_codigo']] = $quarto_dado['quarto_tipo_curto_nome'];
        }
        $this->set('quarto_por_tipo', $quarto_por_tipo);
        $this->set('gerdomsta_list', $this->geral->gercamdom('resdocsta', 'bc'));
        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];
        $this->set('resblomot_list', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                    'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'],
                    'motivo_tipo_codigo' => "'bc'")));
        $this->set('inicial_padrao_horario', $this->session->read('inicial_padrao_horario'));
        $this->set('final_padrao_horario', $this->session->read('final_padrao_horario'));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function resblopes($pdf = null) {

        if (!isset($pdf)) {
            $pdf = $this->request->data['pdf'] ?? null;
        }
        $info_tela = $this->pagesController->montagem_tela('resblopes');
        $historico_busca = $this->pagesController->consomeHistoricoTela('reservas/resblopes');
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
            if (array_key_exists('gerdocsta', $this->request->data))
                $gerdocsta = $this->request->data['gerdocsta'];
            $resquatip = array();
            if (array_key_exists('resquatip', $this->request->data))
                $resquatip = $this->request->data['resquatip'];
            $resquacod = array();
            if (array_key_exists('resquacod', $this->request->data))
                $resquacod = $this->request->data['resquacod'];


            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                $pesquisa_servicos = $this->servico->serdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['serdocnum'] ?? null, array($this->request->data['serdoctip']), $gerdocsta, $inicial_data, $final_data, $estadia_data, $criacao_data, $resquacod, $resquatip, $this->request->data['germottit'] ?? null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], null);

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
                $pesquisa_servicos = $this->servico->serdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['serdocnum'] ?? null, array($this->request->data['serdoctip']), $gerdocsta, $inicial_data, $final_data, $estadia_data, $criacao_data, $resquacod, $resquatip, $this->request->data['germottit'] ?? null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);
                $this->set('pesquisa_servicos', $pesquisa_servicos['results']);
                $this->request->data['pesquisar_servicos'] = 'yes';

                $this->set($this->request->data);

                //exibe a paginação
                $paginator = new Paginator(10);
                $this->set('paginacao', $paginator->gera_paginacao($pesquisa_servicos['filteredTotal'], $this->request->data['pagina'], 'resblopes', sizeof($pesquisa_servicos['results'])));
            }
        } else {

            if ($info_tela['padrao_valor_gerdocsta'] != '')
                $gerdocsta = explode("|", $info_tela['padrao_valor_gerdocsta']);
            else
                $gerdocsta = ['1'];

            if ($info_tela['padrao_valor_resquatip'] != '')
                $resquatip = explode("|", $info_tela['padrao_valor_resquatip']);
            else
                $resquatip = null;

            if ($info_tela['padrao_valor_gerdattip'] != '')
                $gerdattip = $info_tela['padrao_valor_gerdattip'];
            else
                $gerdattip = 'entrada';

            if ($info_tela['padrao_valor_germottit'] != '')
                $germottit = explode("|", $info_tela['padrao_valor_germottit']);
            else
                $germottit = null;

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
            $this->set('germottit', $germottit);
            $this->set('ordenacao_coluna', $ordenacao_coluna);
            $this->set('ordenacao_tipo', $ordenacao_tipo);
            $this->set('ordenacao_sistema', '1');


            //Execuçao automática
            $pesquisa_servicos = $this->servico->serdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], null, array('bc'), $gerdocsta, $inicial_data, $final_data, $estadia_data, $criacao_data, null, $resquatip, $germottit, $ordenacao_coluna, $ordenacao_tipo, 1);
            $this->set('pesquisa_servicos', $pesquisa_servicos['results']);
            $this->set('pesquisar_servicos', 'yes');


            //exibe a paginação
            $paginator = new Paginator(10);
            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_servicos['filteredTotal'], 1, 'serdocpes', sizeof($pesquisa_servicos['results'])));
        }

        $lista_quarto_codigo = $this->geral->gercamdom('resquacod', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $lista_quarto_tipo = $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo']);
        $lista_documento_tipo = $this->geral->gercamdom('gerdoctip', "rs,ct", "<>");

        if (isset($pdf) && $pdf == 'bloqueio') {
            $this->viewBuilder()->options([
                'pdfConfig' => [
                    'orientation' => 'portrait',
                    'filename' => 'relatorioBloqueio'
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
            $this->set('nome_relatorio', 'Relatório de Bloqueio');
        } else {
            $this->set('empresa_codigo', $this->session->read('empresa_selecionada')['empresa_codigo']);
            // $this->set('gerquacod_list', $this->geral->gercamdom('resquacod', $this->session->read('empresa_selecionada')['empresa_codigo']));
            //Busca a lista de quartos
            $dados_quartos = $this->connection->execute("SELECT q.quarto_codigo, qt.quarto_tipo_curto_nome FROM quartos q INNER JOIN quarto_tipos qt ON q.empresa_codigo = qt.empresa_codigo"
                            . " AND q.quarto_tipo_codigo = qt.quarto_tipo_codigo WHERE q.empresa_codigo = :empresa_codigo ORDER BY q.quarto_codigo", ['empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo']])->fetchAll("assoc");
            $quarto_por_tipo = array();
            foreach ($dados_quartos as $quarto_dado) {
                $quarto_por_tipo[$quarto_dado['quarto_codigo']] = $quarto_dado['quarto_tipo_curto_nome'];
            }
            $this->set('quarto_por_tipo', $quarto_por_tipo);
            $this->set('serquatip_list', $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo']));

            $this->set('gerdommot_list', $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                        'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'],
                        'motivo_tipo_codigo' => "'bc'")));
            $this->set('gerdoctip_list', $this->geral->gercamdom('gerdoctip', "rs,ct", "<>"));
            $this->set('gerdomsta_list', $this->geral->gercamdom('resdocsta', 'bc'));
            $this->set('servico_pesquisa_max', $this->session->read('servico_pesquisa_max'));
            $this->set($info_tela);
            $this->viewBuilder()->setLayout('ajax');
        }
    }

    //Usada inicialmente na restardia do painel de reservas, depois precisa adaptar para a resquatar

    public function restardia() {
        $info_tela = $this->pagesController->montagem_tela('rescliide');

        $inicial_data = $this->request->data['inicial_data'];
        $final_data = $this->request->data['final_data'];
        $empresa_codigo = $this->session->read('empresa_selecionada')["empresa_codigo"];
        $quarto_e_tipo_codigos[] = $this->request->data['quarto_tipo_codigo'];
        $info_diarias = $this->request->data['info_diarias'];
        $adulto_quantidade = $this->request->data['adulto_qtd'] ?? 0;

        //Busca a variavel de acesso sequencia do tipo de quarto (melhorar)
        $acesso_sequencia = $this->connection->execute("SELECT acesso_sequencia_codigo FROM quarto_tipos WHERE "
                        . "empresa_codigo=:empresa_codigo AND quarto_tipo_codigo= :quarto_tipo_codigo", ['empresa_codigo' => $empresa_codigo,
                    'quarto_tipo_codigo' => $this->request->data['quarto_tipo_codigo']])->fetchAll("assoc")[0]['acesso_sequencia_codigo'];


        $estadia_data = $this->geral->gerdatdet($inicial_data, $final_data);
        $restartip = $this->reserva->restartip($empresa_codigo, $this->request->data['quarto_tipo_codigo'], $estadia_data['datas'], $adulto_quantidade, $acesso_sequencia);

        foreach ($restartip[$this->request->data['quarto_tipo_codigo']] as $key => $tarifas) {
            if ($key != $this->request->data['tarifa_tipo_codigo'])
                unset($restartip[$this->request->data['quarto_tipo_codigo']][$key]);
        }

        $tarifa_tipos[$this->request->data['quarto_tipo_codigo']] = array();
        if (sizeof($restartip[$this->request->data['quarto_tipo_codigo']]) > 0) {
            $tarifa_tipos[$this->request->data['quarto_tipo_codigo']] = $restartip[$this->request->data['quarto_tipo_codigo']];
        }

        $this->set('quarto_item', $this->request->data['quarto_item']);
        $this->set('tarifa_tipo_codigo', $this->request->data['tarifa_tipo_codigo']);
        $this->set('tarifa_tipos', $tarifa_tipos);
        $this->set('info_diarias', $info_diarias);
        $this->set('quarto_e_tipo_codigos', $quarto_e_tipo_codigos);
        $this->set($info_tela);
        $this->set('tela_nome', 'Valore(s) da(s) Diária(s)');
        $this->set('tarifa_manual_entrada', $this->session->read('empresa_selecionada')['tarifa_manual_entrada']);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function reshordet() {
        $empresa_codigo = $this->request->data['empresa_codigo'];
        $horario_modificacao_tipo = $this->request->data['horario_modificacao_tipo'];
        $horario_modificacao_valor = $this->request->data['horario_modificacao_valor'];
        $inicial_data = $this->request->data['inicial_data'] ?? null;
        $final_data = $this->request->data['final_data'] ?? null;
        $adicional_qtd = $this->request->data['adicional_qtd'] ?? null;
        if ($adicional_qtd != 0) {
            $retorno_reshordet = $this->reserva->reshordet($empresa_codigo, $horario_modificacao_tipo, $horario_modificacao_valor * $adicional_qtd, Util::convertDataHoraSql($inicial_data), Util::convertDataHoraSql($final_data));
            if ($retorno_reshordet['retorno'] == 1) {
                $retorno_reshordet['inicial_data'] = Util::convertDataDMY($retorno_reshordet['inicial_data'], 'd/m/Y H:i');
                $retorno_reshordet['final_data'] = Util::convertDataDMY($retorno_reshordet['final_data'], 'd/m/Y H:i');
            }
        } else {
            $retorno_reshordet['inicial_data'] = Util::convertDataDMY($inicial_data, 'd/m/Y H:i');
            $retorno_reshordet['final_data'] = Util::convertDataDMY($final_data, 'd/m/Y H:i');
            $retorno_reshordet['retorno'] = 1;
        }

        echo json_encode($retorno_reshordet);
        $this->autoRender = false;
    }

    /*
     * Função para a tela de moficação de hóspedes na resdocmod
     */

    public function reshosatu() {
        if ($this->request->is('post') && isset($this->request->data['ajax'])) {
            $empresa_codigo = $this->request->data['empresa_codigo'];
            $documento_numero = $this->request->data['documento_numero'];
            $quarto_item = $this->request->data['quarto_item'];
            $total_hospedes = $this->request->data['total_hospedes'];
            $contratante_codigo = $this->request->data['contratante_codigo'];
            //Mapeamento dos hóspedes de cada quarto
            $hospede_codigos = array();
            $hospede_codigo_antigos = array();
            $hospede_cliente_itens = array();
            $hospede_emails = array();
            $hospede_nomes = array();
            $hospede_sobrenomes = array();
            $hospede_cpfs = array();
            $hospede_celulares = array();
            $hospede_documento_tipos = array();
            $hospede_documento_numeros = array();
            $hospede_modificados = array();

            for ($i = 1; $i <= $total_hospedes; $i++) {
                array_push($hospede_codigos, $this->request->data['h_codigo_' . $quarto_item . '_' . $i]);
                array_push($hospede_codigo_antigos, $this->request->data['h_codigo_antigo_' . $quarto_item . '_' . $i]);
                array_push($hospede_cliente_itens, $this->request->data['h_cliente_itens_' . $quarto_item . '_' . $i]);
                array_push($hospede_emails, $this->request->data['h_email_' . $quarto_item . '_' . $i] ?? '');
                array_push($hospede_nomes, $this->request->data['h_nome_' . $quarto_item . '_' . $i] ?? '');
                array_push($hospede_sobrenomes, $this->request->data['h_sobrenome_' . $quarto_item . '_' . $i] ?? '');
                array_push($hospede_cpfs, $this->request->data['h_cpfnum_' . $quarto_item . '_' . $i] ?? null);
                array_push($hospede_celulares, (isset($this->request->data['h_cel_' . $quarto_item . '_' . $i]) ? $this->request->data['h_cel_' . $quarto_item . '_' . $i] : null));
                array_push($hospede_documento_tipos, $this->request->data['h_doctip_' . $quarto_item . '_' . $i] ?? '');
                array_push($hospede_documento_numeros, $this->request->data['h_docnum_' . $quarto_item . '_' . $i] ?? '');
                array_push($hospede_modificados, $this->request->data['h_has_changed_' . $quarto_item . '_' . $i]);
            }
            //Remove clientes onde o nome é vazio. Isso é necessário, pois na criação da reserva já foram inseridos referencias nulas na documento_cliente.
            //Caso esssa remoção não seja feita, cadastra novamente referencias vazias na documento_cliente
            foreach ($hospede_codigos as $key => $codigos) {
                if ($hospede_nomes[$key] == "") {
                    unset($hospede_modificados[$key], $hospede_codigo_antigos[$key], $hospede_codigos[$key], $hospede_nomes[$key], $hospede_sobrenomes[$key], $hospede_emails[$key], $hospede_cpfs[$key], $hospede_celulares[$key], $hospede_documento_tipos[$key], $hospede_documento_numeros[$key], $hospede_cliente_itens[$key]);
                }
            }
            $retorno_reshosatu = $this->reserva->reshosatu($empresa_codigo, $documento_numero, $quarto_item, $contratante_codigo, $hospede_codigos, $hospede_nomes, $hospede_sobrenomes, $hospede_emails, $hospede_cpfs, $hospede_documento_tipos, $hospede_documento_numeros, null, $hospede_celulares, null, null, null, null, null, null, null, null, null, null, null, null, $hospede_modificados, $hospede_codigo_antigos, $hospede_cliente_itens);

            $this->session->write('retorno_footer', $retorno_reshosatu['mensagem']['mensagem']);
            $this->autoRender = false;
        } else {
            $info_tela = $this->pagesController->montagem_tela('reshosatu');
            $empresa_codigo = $this->request->data['empresa_codigo'];
            $documento_numero = $this->request->data['documento_numero'];
            $quarto_item = $this->request->data['quarto_item'];

            $reserva_dados = $this->reserva->resdocpes($empresa_codigo, "rs", $documento_numero, $quarto_item);
            $indice_quarto_item_atual = array_search($quarto_item, array_column($reserva_dados['results'], 'quarto_item'));
            $this->set('reserva_dados', $reserva_dados['results']);
            $this->set('hospedes_info', $reserva_dados['results'][$indice_quarto_item_atual]['hospedes'] ?? array());
            $this->set('cabecalho_conta', $reserva_dados['results']);
            $this->set('indice_quarto_item_atual', $indice_quarto_item_atual);
            $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
            $this->set('quarto_item', $quarto_item);
            $this->set('empresa_codigo', $empresa_codigo);
            $this->set('documento_numero', $documento_numero);
            $this->set('contratante_codigo', $reserva_dados['results'][$indice_quarto_item_atual]['cliente_codigo']);
            $this->set('quarto_item', $quarto_item);
            $this->set('url_redirect_after', $this->request->data['url_redirect_after']);
            $this->set('total_hospedes', sizeof($reserva_dados['results'][$indice_quarto_item_atual]['hospedes']));
            $this->set($info_tela);
            $this->viewBuilder()->setLayout('ajax');
        }
    }

    public function restarmod() {
        if ($this->request->is('post')) {
            $restarmod = array();
            if ($this->request->data['form'] ?? "" != "") {
                parse_str($this->request->data['form'], $restarmod);
            }

            $retorno_restarmod = $this->reserva->restarmod($this->session->read('empresa_selecionada')['empresa_codigo'], $restarmod['gerdatini'], $restarmod['gerdatfin'], $restarmod['dds'], $restarmod['resquatip'],
                    $restarmod['restartip'], $restarmod['resaduqtd'], $restarmod['tarifa']);
            if ($retorno_restarmod['retorno'] == 0) {
                echo json_encode($retorno_restarmod);
                $this->autoRender = false;
                exit();
            } else {
                echo json_encode($retorno_restarmod);
                $this->autoRender = false;
            }
        }
        $info_tela = $this->pagesController->montagem_tela('restarmod');

        $this->set('resquatip_list', $this->geral->gercamdom('resquatip', $this->session->read('empresa_selecionada')['empresa_codigo']));
        $this->set('restiptar_list', $this->geral->gercamdom('restiptar', $this->session->read('empresa_selecionada')['empresa_codigo']));

        $this->set('resaduqtd', $this->reserva->resadumax($this->session->read('empresa_selecionada')["empresa_codigo"]));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function resveiexi() {
        $info_tela = $this->pagesController->montagem_tela('resveiexi');
        $empresa_codigo = $this->request->data['empresa_codigo'];
        $documento_numero = $this->request->data['documento_numero'];
        $quarto_item = $this->request->data['quarto_item'];

        $veiculos = $this->reserva->resveiexi($empresa_codigo, $documento_numero, $quarto_item);

        if (sizeof($veiculos) == 0)
            array_push($veiculos, array('veiculo_item' => 1, 'placa' => '', 'marca_modelo' => '', 'cor' => '', 'excluido' => 0));

        $this->set('veiculos', $veiculos);
        $this->set('empresa_codigo', $empresa_codigo);
        $this->set('documento_numero', $documento_numero);
        $this->set('quarto_item', $quarto_item);
        $this->set('url_redirect_after', $this->request->data['url_redirect_after']);
        $this->set('cores_lista', $this->geral->gercamdom('resveicor'));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function resveimod() {
        if ($this->request->is('post')) {

            $retorno_resveimod = $this->reserva->resveimod($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['documento_numero'], $this->request->data['quarto_item'], $this->request->data['resveiite'], $this->request->data['resveipla'], $this->request->data['resveimar'], $this->request->data['resveicor'], $this->request->data['resveiexc']);

            $this->session->write('retorno_footer', $retorno_resveimod['mensagem']['mensagem']);
        }
        $this->autoRender = false;
    }

    public function resveipes() {
        $info_tela = $this->pagesController->montagem_tela('resveipes');
        $historico_busca = $this->pagesController->consomeHistoricoTela('reservas/resveipes');
        $this->request->data = array_merge($this->request->data, $historico_busca);
        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];

        if ($this->request->is('post') || sizeof($historico_busca) > 0) {
            $resquacod = array();
            if (array_key_exists('resquacod', $this->request->data)) {
                if (!is_array($this->request->data['resquacod']))
                    $this->request->data['resquacod'] = explode(',', $this->request->data['resquacod']);
                $resquacod = $this->request->data['resquacod'];
            }

            $documento_numero = null;
            $quarto_item = null;

            if (!empty($this->request->data['resdocnum']) && $this->request->data['resdocnum'] != null) {
                if (strpos($this->request->data['resdocnum'], '-') !== false) {
                    $documento_numero = explode('-', $this->request->data['resdocnum'])[0];
                    $quarto_item = explode('-', $this->request->data['resdocnum'])[1];
                } else {
                    $documento_numero = explode('-', $this->request->data['resdocnum'])[0];
                }
            }
            $apenas_hospedados_agora = null;
            if (isset($this->request->data['apenas_hospedados_agora']))
                $apenas_hospedados_agora = 3;

            $pesquisa_veiculos = $this->reserva->resveipes($empresa_codigo, $this->request->data['resveipla'] ?? null, $this->request->data['resveimar'] ?? null, $this->request->data['resveicor'] ?? null, $apenas_hospedados_agora, $documento_numero, $quarto_item, $resquacod, $this->request->data['c_codigo'], $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);

            //exibe a paginação
            $paginator = new Paginator(15);
            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_veiculos['filteredTotal'], $this->request->data['pagina'], 'serveipes', sizeof($pesquisa_veiculos['results'])));
        } else {
            $ordenacao_coluna = "placa|";
            $ordenacao_tipo = "asc|";
            $apenas_hospedados_agora = 3;

            $pesquisa_veiculos = $this->reserva->resveipes($empresa_codigo, null, null, null, $apenas_hospedados_agora, null, null, null, null, $ordenacao_coluna, $ordenacao_tipo, 1);

            //exibe a paginação
            $paginator = new Paginator(15);

            $this->set('paginacao', $paginator->gera_paginacao($pesquisa_veiculos['filteredTotal'], 1, 'serveipes', sizeof($pesquisa_veiculos['results'])));
            $this->set('apenas_hospedados_agora', $apenas_hospedados_agora);
            $this->set('ordenacao_coluna', $ordenacao_coluna);
            $this->set('ordenacao_tipo', $ordenacao_tipo);
            $this->set('ordenacao_sistema', '1');
        }
        $this->set('pesquisa_veiculos', $pesquisa_veiculos);

        $dados_quartos = $this->connection->execute("SELECT q.quarto_codigo, qt.quarto_tipo_curto_nome FROM quartos q INNER JOIN quarto_tipos qt ON q.empresa_codigo = qt.empresa_codigo"
                        . " AND q.quarto_tipo_codigo = qt.quarto_tipo_codigo WHERE q.empresa_codigo = :empresa_codigo ORDER BY q.quarto_codigo", ['empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo']])->fetchAll("assoc");
        $quarto_por_tipo = array();
        foreach ($dados_quartos as $quarto_dado) {
            $quarto_por_tipo[$quarto_dado['quarto_codigo']] = $quarto_dado['quarto_tipo_curto_nome'];
        }

        $this->set('quarto_por_tipo', $quarto_por_tipo);
        $this->set('cores_lista', $this->geral->gercamdom('resveicor'));
        $this->set($info_tela);
        $this->set($this->request->data);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function reslishos() {
        $resdocpes = $this->reserva->resdocpes($this->request->data['empresa_codigo'], "rs", $this->request->data['documento_numero'])['results'];

        //Adiciona o quarto item como chave no vetor
        $retorno_resdocpes = array();

        foreach ($resdocpes as $resdocpes_item)
            $retorno_resdocpes[$resdocpes_item['quarto_item']] = $resdocpes_item;

        foreach ($retorno_resdocpes as $quarto_item) {

            $estadia_data[$quarto_item['quarto_item']] = $this->geral->gerdatdet($retorno_resdocpes[$quarto_item['quarto_item']]['inicial_data'], $retorno_resdocpes[$quarto_item['quarto_item']]['final_data']);
            $data_quantidade[$quarto_item['quarto_item']] = $estadia_data[$quarto_item['quarto_item']][0]['data_quantidade'];
            $diarias[$quarto_item['quarto_item']] = $estadia_data[$quarto_item['quarto_item']]['datas'];
        }
        $this->set('data_quantidade', $data_quantidade);
        $this->set('diarias', $diarias);

        $info_tela = $this->pagesController->montagem_tela('resdocmod');

        $dominio_agencia_viagem = $this->geral->gercamdom('resviaage');
        $agencias = array();
        foreach ($dominio_agencia_viagem as $agencia)
            $agencias[$agencia['valor']] = $agencia['rotulo'];

        $this->set('agencias', $agencias);
        $this->set('reserva_dados', $retorno_resdocpes);
        $this->set($info_tela);
        $this->set('documento_numero', $this->request->data['documento_numero']);

        $this->viewBuilder()
                ->options(['config' => [
                        'orientation' => 'portrait',
                        'filename' => 'impressaoReserva'
                    ]
        ]);
    }

}
