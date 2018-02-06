<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Cliente;
use App\Utility\Util;
use App\Utility\Paginator;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Reserva;
use App\Controller\PagesController;

class ClientesController extends AppController {

    private $cliente;
    private $pagesController;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->cliente = new Cliente();
        $this->pagesController = new PagesController();
    }

    public function clicadcri() {
        if ($this->request->is('post')) {
            $retorno = $this->cliente->clicadcri($this->session->read('empresa_grupo_codigo_ativo'), null, $this->request->data['cliprinom'], $this->request->data['clisobnom'], $this->request->data['clicadema'], $this->request->data['clidoctip'], $this->request->data['clidocnum'], $this->request->data['clicpfcnp'], $this->request->data['clicelddi'], $this->request->data['clicelnum'], $this->request->data['clitelddi'], $this->request->data['clitelnum'], $this->request->data['clidocorg'], $this->request->data['clicadocu'], $this->request->data['clicadnac'], Util::convertDataSql($this->request->data['clinacdat']), $this->request->data['clicadend'], $this->request->data['clicadbai'], $this->request->data['clicadcid'], $this->request->data['clicadest'], $this->request->data['clicadpai'], $this->request->data['clicadcep'], $this->request->data['cliresnum'], $this->request->data['clirescom'], 3);
            $this->set('retorno_footer', $retorno['mensagem']);
        }

        $info_tela = $this->pagesController->montagem_tela('clicadcri');

        $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
        $this->set('dominio_estados_lista', $this->geral->gercamdom('clicadest', $this->session->read('pais_codigo_padrao')));
        $this->set('dominio_cidades_lista', $this->geral->gercamdom('clicadcid'));
        $dominio_paises = $this->geral->gercamdom('clicadpai');

        $this->set('dominio_paises_lista', $dominio_paises);
        $this->set('dominio_nacionalidades_lista', $dominio_paises);
        $this->set('dominio_ddi_lista', $this->geral->gercamdom('clicelddi'));

        // $this->set('padcab', $this->geral->gerpadcab('geral/gertelpri', $this->request->data));
        $this->set('ddi_padrao', $this->session->read('ddi_padrao'));
        $this->set('pais_nome_padrao', $this->session->read('pais_nome_padrao'));
        $this->set('estado_codigo_padrao', $this->session->read('empresa_selecionada')['estado']);
        $this->set('empresa_grupo_codigo', $this->session->read('empresa_grupo_codigo_ativo'));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function clicadmod() {
        //Busca o cliente passado por parametro
        $empresa_codigo = isset($this->request->params['pass'][0]) ? trim($this->request->params['pass'][0]) : $this->request->data['gerempcod'];
        $cliente_codigo = isset($this->request->params['pass'][1]) ? trim($this->request->params['pass'][1]) : $this->request->data['clicadcod'];
        $info_tela = $this->pagesController->montagem_tela('clicadmod');
        $historico_busca = $this->pagesController->consomeHistoricoTela('clientes/clicadmod/' . $empresa_codigo . '/' . $cliente_codigo);
        $this->request->data = array_merge($this->request->data, $historico_busca);

        if ($this->request->is('post')) {
            $retorno = $this->cliente->clicadmod($this->session->read('empresa_grupo_codigo_ativo'), $cliente_codigo, $this->request->data['cliprinom'], $this->request->data['clisobnom'], $this->request->data['clicadema'], $this->request->data['clidoctip'], $this->request->data['clidocnum'], $this->request->data['clicpfcnp'], $this->request->data['clicelddi'], $this->request->data['clicelnum'], $this->request->data['clitelddi'], $this->request->data['clitelnum'], $this->request->data['clidocorg'], $this->request->data['clicadocu'], $this->request->data['clicadnac'], Util::convertDataSql($this->request->data['clinacdat']), $this->request->data['clicadend'], $this->request->data['clicadbai'], $this->request->data['clicadcid'], $this->request->data['clicadest'], $this->request->data['clicadpai'], $this->request->data['clicadcep'], $this->request->data['cliresnum'], $this->request->data['clirescom'], 3);
            $this->set($this->request->data);
            $this->session->write('retorno_footer', $retorno['mensagem']['mensagem']);
            $this->autoRender=false;
        } else {

            //Verifica a permissão para editar
            $acesso = $this->geral->geracever('clicadmod');
            $dis_clicadmod = "";
            if ($acesso != "") {
                $dis_clicadmod = "disabled";
            }
            $this->set('dis_clicadmod', $dis_clicadmod);

            $cliente_table = TableRegistry::get('Clientes');

            $results = $cliente_table->findByClienteCodigoEEmpresaGrupoCodigo($this->session->read('empresa_grupo_codigo_ativo'), $cliente_codigo);

            $array_map = array('cliprinom' => $results[0]['nome'], 'clisobnom' => $results[0]['sobrenome'], 'clicadema' => $results[0]['email'],
                'clidoctip' => $results[0]['cliente_documento_tipo'], 'clidocnum' => $results[0]['cliente_documento_numero'],
                'clicpfcnp' => (empty($results[0]['cpf']) ? $results[0]['cnpj'] : $results[0]['cpf'])
                , 'clicelddi' => empty($results[0]['cel_ddi']) ? $this->session->read('ddi_padrao') : $results[0]['cel_ddi'],
                'clicelnum' => $results[0]['cel_numero'], 'clitelddi' => empty($results[0]['tel_ddi']) ? $this->session->read('ddi_padrao') : $results[0]['tel_ddi'],
                'clitelnum' => $results[0]['tel_numero'], 'clicadcod' => $cliente_codigo, 'clidocorg' => $results[0]['cliente_documento_orgao'], 'clicadocu' => $results[0]['ocupacao'],
                'clicadnac' => $results[0]['nacionalidade'],
                'clinacdat' => Util::convertDataDMY($results[0]['nascimento_data']), 'clicadend' => $results[0]['residencia_logradouro'], 'clicadbai' => $results[0]['residencia_bairro'],
                'clicadcid' => $results[0]['residencia_cidade'], 'clicadest' => empty($results[0]['residencia_estado']) ? $this->session->read('empresa_selecionada')['estado'] : $results[0]['residencia_estado'],
                'clicadpai' => $results[0]['residencia_pais'], 'clicadcep' => $results[0]['residencia_cep'],
                'cliresnum' => $results[0]['residencia_numero'], 'clirescom' => $results[0]['residencia_complemento']);

            $this->set($array_map);
            $this->set('empresa_codigo', $this->session->read('empresa_selecionada')['empresa_codigo']);
        }

        $reserva = new Reserva();
        $pesquisa_reservas = $reserva->resdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], "rs", null, null, $cliente_codigo);
        $this->set('pesquisa_reservas', $pesquisa_reservas);
        $this->set($info_tela);
        $dominio_paises = $this->geral->gercamdom('clicadpai');
        $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
        $this->set('dominio_ddi_lista', $this->geral->gercamdom('clicelddi'));
        $this->set($this->request->data);
        $this->set('exibir_reservas', '0');
        $this->set('ace_reserva_exi', $this->geral->gerauuver('reservas', 'resdocpes') ? '' : ' disabled ');
        $this->set('dominio_estados_lista', $this->geral->gercamdom('clicadest'));
        $this->set('dominio_paises_lista', $dominio_paises);
        $this->set('dominio_nacionalidades_lista', $dominio_paises);
        if (sizeof($this->session->read('historico')) > 0)
            $this->set('pagina_referencia', array_keys($this->session->read('historico'))[sizeof($this->session->read('historico')) - 1]);
        else
            $this->set('pagina_referencia', '');
        $this->viewBuilder()->setLayout('ajax');
    }

    public function clicadpes() {
        $info_tela = $this->pagesController->montagem_tela('clicadpes');
        $historico_busca = $this->pagesController->consomeHistoricoTela('clientes/clicadpes');
        $this->request->data = array_merge($this->request->data, $historico_busca);
        if (($this->request->is('post') && isset($this->request->data['ajax'])) || sizeof($historico_busca) > 0) {
            //Se tiver exportando para csv, não passa a paginação
            if (isset($this->request->data['export_csv']) && $this->request->data['export_csv'] == '1') {
                $pesquisa_clientes = $this->cliente->clicadpes($this->session->read('empresa_grupo_codigo'), $this->request->data['cliprinom'] ?? null, $this->request->data['clisobnom'] ?? null, $this->request->data['clicadema'] ?? null, $this->request->data['clidoctip'] ?? null, $this->request->data['clidocnum'] ?? null, $this->request->data['clicpfcnp'] ?? null, $this->request->data['clicadpap'] ?? null, null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], null);

                $this->response->download('export.csv');
                $data = $pesquisa_clientes['results'];
                $_serialize = 'data';
                $_extract = ['cliente_codigo', 'nome', 'sobrenome', 'email', 'cpf', 'cliente_documento_tipo', 'cliente_documento_numero'];
                $_header = [$info_tela['rot_clicadcod'], $info_tela['rot_cliprinom'], $info_tela['rot_clisobnom'], $info_tela['rot_clicadema'], $info_tela['rot_clicpfcnp'],
                    $info_tela['rot_clidoctip'], $info_tela['rot_clidocnum']];
                $_csvEncoding = "iso-8859-1";
                $_delimiter = ";";
                $this->set(compact('data', '_serialize', '_delimiter', '_header', '_extract', '_csvEncoding'));

                $this->viewBuilder()->className('CsvView.Csv');
            } else {
                $pesquisa_clientes = $this->cliente->clicadpes($this->session->read('empresa_grupo_codigo'), $this->request->data['cliprinom'] ?? null, $this->request->data['clisobnom'] ?? null, $this->request->data['clicadema'] ?? null, $this->request->data['clidoctip'] ?? null, $this->request->data['clidocnum'] ?? null, $this->request->data['clicpfcnp'] ?? null, $this->request->data['clicadpap'] ?? null, null, $this->request->data['ordenacao_coluna'], $this->request->data['ordenacao_tipo'], $this->request->data['pagina'] ?? 1);
                $this->set($this->request->data);
                $this->set('pesquisa_clientes', $pesquisa_clientes['results']);

                //exibe a paginação
                $paginator = new Paginator(15);
                $this->set('paginacao', $paginator->gera_paginacao($pesquisa_clientes['filteredTotal'], $this->request->data['pagina'], 'clicadpes', sizeof($pesquisa_clientes['results'])));
            }
        } else {
            $this->set('pesquisa_clientes', '');
        }
        $is_dialog = false;

        //Verifica se a pesquisa vai ser exibida em um dialog
        if (isset($this->request->data['is_dialog'])) {
            $is_dialog = true;
            $this->set('aria_cliente_codigo_id', $this->request->data['aria_cliente_codigo_id']);
            $this->set('aria_cliente_nome_id', $this->request->data['aria_cliente_nome_id']);
            $this->set('aria_cliente_cpf_cnpj_id', $this->request->data['aria_cliente_cpf_cnpj_id']);
        }
        $this->set('is_dialog', $is_dialog);
        $this->set('documento_tipo_lista', $this->geral->gercamdom('clidoctip'));
        $this->set('cliente_papeis_lista', $this->geral->gercamdom('clicadpap'));

        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    /*
     * Usada no autocomplete
     */

    public function cliconaut() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $search = $this->request->query('search');
            $results = $this->cliente->cliconaut($search);
            $resultArr = array();
            foreach ($results as $result) {
                 if ($this->request->query('tela_exibe_sobrenome') == 1) {
                    $value = $result['nome'];
                } else
                    $value = $result['nome'] . ' ' . $result['sobrenome'];
                
                $resultArr[] = array('label' => $result['nome'] . ' ' . $result['sobrenome'], 'value' => $value, 'c_codigo' => $result['cliente_codigo']
                    , 'c_nome' => $result['nome'], 'c_sobrenome' => $result['sobrenome']
                    , 'c_email' => $result['email'], 'c_documento_tipo' => $result['cliente_documento_tipo']
                    , 'c_documento_numero' => $result['cliente_documento_numero'], 'c_documento_orgao' => $result['cliente_documento_orgao']
                    , 'c_cpf_numero' => $result['cpf'], 'c_cnpj_numero' => $result['cnpj'], 'c_cel_ddi' => $result['cel_ddi']
                    , 'c_cel_numero' => $result['cel_numero']
                    , 'c_tel_ddi' => $result['tel_ddi'], 'c_tel_numero' => $result['tel_numero']);

               
            }
            $this->set('resultArr', $resultArr);
            echo json_encode($resultArr);
        }
    }

    /*
     * Usada no autocomplete
     */

    public function clirelaut() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $cliente_codigo_1 = $this->request->query('cliente_codigo_1');

            if ($cliente_codigo_1 != "") {
                $search = $this->request->query('search');
                $results = $this->cliente->clirelaut($search, $cliente_codigo_1, $this->request->query('proprio_cliente') ?? '0');

                $resultArr = array();
                foreach ($results as $result) {
                    if (isset($this->request->query['nome_sobrenome'])) {
                        $resultArr[] = array('label' => $result['nome'] . ' ' . $result['sobrenome'] . "<span style='font-weight:bold; margin-left:35px; font-size:17px; color:red;' onclick=\"removeHospede(" . $result['cliente_codigo'] . ", '" . $result['nome'] . " " . $result['sobrenome'] . "')\">x</a>", 'value' => $result['nome'] . " " . $result['sobrenome'], 'c_codigo' => $result['cliente_codigo']
                            , 'c_nome' => $result['nome'], 'c_sobrenome' => $result['sobrenome'], 'c_cpf' => $result['cpf'], 'c_cnpj' => $result['cnpj']
                            , 'c_email' => $result['email'], 'c_documento_tipo' => $result['cliente_documento_tipo'],
                            'c_documento_numero' => $result['cliente_documento_numero']);
                    } else {
                        $resultArr[] = array('label' => $result['nome'] . ' ' . $result['sobrenome'] . "<span style='font-weight:bold; margin-left:35px; font-size:17px; color:red;' onclick=\"removeHospede(" . $result['cliente_codigo'] . ", '" . $result['nome'] . " " . $result['sobrenome'] . "')\">x</a>", 'value' => $result['nome'], 'c_codigo' => $result['cliente_codigo']
                            , 'c_nome' => $result['nome'], 'c_sobrenome' => $result['sobrenome'], 'c_cpf' => $result['cpf'], 'c_cnpj' => $result['cnpj']
                            , 'c_email' => $result['email'], 'c_documento_tipo' => $result['cliente_documento_tipo'],
                            'c_documento_numero' => $result['cliente_documento_numero']);
                    }
                }
                /* if (isset($this->request->query['mais_opcoes']) && $this->request->query['mais_opcoes'] == '1')
                  $resultArr[] = array('label' => "<span style='color:#666;font-weight:bold; margin-left:5px; font-size:17px;'"
                  . " onclick=\"clicadcri_ajax(); \">+ <i>Novo cliente</b></a>"); */
                $this->set('resultArr', $resultArr);
                echo json_encode($resultArr);
            }
        }
    }

//Utilizada em ajax
    function clicadexi() {
        if ($this->request->is('post')) {
            $cliente_table = TableRegistry::get('Clientes');
            $results['registros'] = $cliente_table->findByClienteCodigoEEmpresaGrupoCodigo($this->session->read('empresa_grupo_codigo_ativo'), $this->request->data['cliente_codigo']);
        } else {
            $cliente_codigo = $this->request->query('cliente_codigo');
            $results = $this->cliente->clicadpes($this->session->read('empresa_grupo_codigo_ativo'), null, null, null, null, null, null, null, $cliente_codigo, 2);
        }
        $resultArr = array();

        foreach ($results['registros'] as $result) {
            $resultArr[] = array('cliprinom' => $result['nome'], 'clisobnom' => $result['sobrenome']
                , 'clicadema' => $result['email'], 'clidoctip' => $result['cliente_documento_tipo']
                , 'clidocnum' => $result['cliente_documento_numero'], 'clicpfcnp' => (empty($result['cpf']) ? $result['cnpj'] : $result['cpf'])
                , 'clicelddi' => $result['cel_ddi'] ?? '', 'clicelnum' => $result['cel_numero'] ?? ''
                , 'clitelddi' => $result['tel_ddi'] ?? '', 'clitelnum' => $result['tel_numero'] ?? '',
                'clidocorg' => $result['cliente_documento_orgao'], 'clicadocu' => $result['ocupacao'],
                'clicadnac' => $result['nacionalidade'],
                'clinacdat' => Util::convertDataDMY(($result['nascimento_data'] == '0000-00-00') ? '' : $result['nascimento_data']), 'clicadend' => $result['residencia_logradouro'],
                'clicadbai' => $result['residencia_bairro'],
                'clicadcid' => $result['residencia_cidade'], 'clicadest' => $result['residencia_estado'],
                'clicadpai' => $result['residencia_pais'], 'clicadcep' => $result['residencia_cep'], 'clicadcod' => $result['cliente_codigo'], 'sexo' => $result['sexo']
            );
        }

        echo json_encode($resultArr);

        $this->autoRender = false;
    }

    public function clirelatu() {
        $this->cliente->clirelatu($this->request->data['cliente_codigo_1'], $this->request->data['cliente_codigo_2'], $this->session->read("empresa_grupo_codigo_ativo"), $this->request->data['excluir']);
        $this->autoRender = false;
    }

    public function clireslis() {
        $reserva = new Reserva();
        $pesquisa_reservas = $reserva->resdocpes($this->request->data['empresa_codigo'], "rs", null, null, $this->request->data['cliente_codigo'], null, 0, null, null, null, null, null, null, null, null, 'documento_numero', 'desc');
        $indice = 0;
        $str_retorno = "";
        foreach ($pesquisa_reservas['results'] as $value) {
            $str_retorno .= "<tr onclick='seleciona_reserva_cliente(" . $value['documento_numero'] . ")'>
            <td>" . $value['documento_numero'] . "-" . $value['quarto_item'] . "</td>
            <td>" . date('d/m/Y', strtotime($value['inicial_data'])) . "</td>
            <td>" . date('d/m/Y', strtotime($value['final_data'])) . "</td>
            <td>" . $value['quarto_tipo_nome'] . "</td>
            <td>" . $value['documento_status_nome'] . "</td>
        </tr>";
            $indice++;
        }

        echo $str_retorno;
        $this->autoRender = false;
    }

    public function cliunival1() {
        echo json_encode($this->cliente->cliunival1($this->request->data['empresa_grupo_codigo'], $this->request->data['cliente_univoco_campo_1'], $this->request->data['tela_cliente_univoco_campo_1'], $this->request->data['cliente_univoco_campo_2'] ?? null, $this->request->data['tela_cliente_univoco_campo_2'] ?? null));
        $this->autoRender = false;
    }

}
