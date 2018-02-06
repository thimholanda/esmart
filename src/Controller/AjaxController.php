<?php

namespace App\Controller;

/*
 * Contém todas as chamadas Ajax para outras funções
 */

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Cliente;
use App\Model\Entity\DocumentoConta;
use App\Model\Entity\Reserva;
use App\Model\Entity\Estadia;
use App\Model\Entity\Servico;
use App\Model\Entity\Produto;
use App\Utility\Util;
use App\Model\Entity\Browser;
use App\Utility\Paginator;
use Cake\Datasource\ConnectionManager;

class AjaxController extends AppController {

    private $cliente;
    private $produto;
    private $documento_conta;
    private $reserva;
    private $estadia;
    private $servico;
    private $pagesController;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->cliente = new Cliente();
        $this->documento_conta = new DocumentoConta();
        $this->reserva = new Reserva();
        $this->estadia = new Estadia();
        $this->connection = ConnectionManager::get('default');
        $this->servico = new Servico();
        $this->produto = new Produto();
        $this->pagesController = new PagesController();
    }

    public function ajaxclicadpes() {
        $values = array();
        if ($this->request->data['form'] != "")
            parse_str($this->request->data['form'], $values);
        $table_result = "";

        $pesquisa_clientes = $this->cliente->clicadpes($this->session->read('empresa_grupo_codigo'), $values['cliprinom'], null, $values['clicadema'], $values['clidoctip'], $values['clidocnum'], $values['clicpfcnp'], $values['clicadpap'] ?? null);
        if (sizeof($pesquisa_clientes['results'] ?? "") != 0) {
            foreach ($pesquisa_clientes['results'] as $value) {
                $cpfcnpj = empty($value['cpf']) ? $value['cnpj'] : $value['cpf'];
                $table_result .= "<tr class='dialog_linha_cliente'  aria-cliente-codigo='" . $value['cliente_codigo'] . "'"
                        . " aria-cliente-codigo-id='" . $this->request->data['aria_cliente_codigo_id'] . "' aria-cliente-nome-id='" . $this->request->data['aria_cliente_nome_id'] . "'"
                        . " aria-cliente-nome='" . $value['nome'] . "'  aria-cliente-sobrenome='" . $value['sobrenome'] . "'  aria-cliente-cpf-cnpj-id='" . $this->request->data['aria_cliente_cpf_cnpj_id'] . "'  aria-cliente-cpf-cnpj='" . $value['cpf'] . "' >"
                        . "<td>" . $value['cliente_codigo'] . "</td>"
                        . "<td>" . $value['nome'] . "</td>"
                        . "<td>" . $value['sobrenome'] . "</td>"
                        . "<td>" . $value['email'] . "</td>"
                        . "<td>" . $value['cliente_documento_tipo'] . "</td>"
                        . "<td>" . $value['cliente_documento_numero'] . "</td>"
                        . "<td>" . $cpfcnpj . "</td></tr>";
            }
        }



        echo $table_result;
        $this->autoRender = false;
    }

    public function ajaxclicadmod() {
        if ($this->request->is('post')) {
            $values = array();
            if ($this->request->data['form'] != "")
                parse_str($this->request->data['form'], $values);

            $retorno = $this->cliente->clicadmod($this->session->read('empresa_grupo_codigo_ativo'), $values['clicadcod'], $values['cliprinom'], $values['clisobnom'], $values['clicadema'], $values['clidoctip'], $values['clidocnum'], $values['clicpfcnp'], $values['clicelddi'], $values['clicelnum'], $values['clitelddi'], $values['clitelnum'], $values['clidocorg'], $values['clicadocu'], $values['clicadnac'], Util::convertDataSql($values['clinacdat']), $values['clicadend'], $values['clicadbai'], $values['clicadcid'], $values['clicadest'], $values['clicadpai'], $values['clicadcep'], $values['cliresnum'], $values['clirescom'], 3);
        }
        echo $retorno['mensagem']['mensagem'];
        $this->autoRender = false;
    }

    public function ajaxclicadcri() {
        if ($this->request->is('post')) {
            $values = array();
            if ($this->request->data['form'] != "")
                parse_str($this->request->data['form'], $values);

            $retorno = $this->cliente->clicadcri($this->session->read('empresa_grupo_codigo_ativo'), null, $values['cliprinom'], $values['clisobnom'], $values['clicadema'], $values['clidoctip'], $values['clidocnum'], $values['clicpfcnp'], $values['clicelddi'], $values['clicelnum'], $values['clitelddi'], $values['clitelnum'], $values['clidocorg'], $values['clicadocu'], $values['clicadnac'], Util::convertDataSql($values['clinacdat']), $values['clicadend'], $values['clicadbai'], $values['clicadcid'], $values['clicadest'], $values['clicadpai'], $values['clicadcep'], $values['cliresnum'], $values['clirescom'], 3);
            $retorno = $retorno['mensagem']['mensagem'] . "|" . $retorno['retorno'];
        }
        echo $retorno;
        $this->autoRender = false;
    }

    //Busca os clientes cadastrados em uma reserva por ajax
    public function ajaxclicadres() {
        $empresa = $this->request->data['gerempcod'];
        $documento_numero = $this->request->data['resdocnum'];
        $quarto_item = $this->request->data['quarto_item'];
        $documento_cliente_table = TableRegistry::get('DocumentoClientes');
        $clientes = $documento_cliente_table->findHospedesByDocumentoNumeroEEmpresaCodigo($empresa, $documento_numero, $quarto_item);

        $resultado = "";
        foreach ($clientes as $cliente) {
            $resultado .= $cliente['cliente_codigo'] . "|";
        }

        $resultado = substr($resultado, 0, -1);
        echo $resultado;
        $this->autoRender = false;
    }

    public function ajaxclicrimod() {
        $retorno = $this->cliente->clicadmod($this->session->read('empresa_grupo_codigo'), $this->request->data['clicadcod'], $this->request->data['cliprinom'], $this->request->data['clisobnom'] ?? null, $this->request->data['clicadema'], $this->request->data['clidoctip'], $this->request->data['clidocnum'], $this->request->data['clicpfcnp'], $this->request->data['clicelddi'] ?? null, $this->request->data['clicelnum'], $this->request->data['clitelddi'] ?? null, $this->request->data['clitelnum'] ?? null, $this->request->data['clidocorg'], $this->request->data['clicadocu'], $this->request->data['clicadnac'], Util::convertDataSql($this->request->data['clinacdat']), $this->request->data['clicadend'], $this->request->data['clicadbai'] ?? null, $this->request->data['clicadcid'], $this->request->data['clicadest'], $this->request->data['clicadpai'], $this->request->data['clicadcep'], $this->request->data['cliresnum'], $this->request->data['clirescom'], 1);
        $this->autoRender = false;
    }

    public function ajaxconpagval1() {
        echo json_encode($this->documento_conta->conpagval1(number_format($this->request->data['a_pagar_valor'], 2, '.', ''), number_format($this->request->data['informado_valor'], 2, '.', '')));
        $this->autoRender = false;
    }

    public function ajaxconpagval2() {
        echo $this->documento_conta->conpagval2();
        $this->autoRender = false;
    }

    public function ajaxconcreexi() {
        $concreexi = $this->documento_conta->concreexi($this->session->read('empresa_selecionada')['empresa_codigo'], $this->session->read('empresa_grupo_codigo_ativo'), $this->request->data['cliente_codigo'], null, 2, null);

        echo json_encode($concreexi);
        $this->autoRender = false;
    }

    public function ajaxconcreexidialog() {
        $arr_gertelmon = $this->geral->gertelmon($this->session->read('empresa_grupo_codigo_ativo'), 'concreexi');
        $rotulos = Util::germonrot($arr_gertelmon);

        $pesquisa_creditos = $this->documento_conta->concreexi($this->session->read('empresa_selecionada')['empresa_codigo'], $this->session->read('empresa_grupo_codigo_ativo'), $this->request->data['c_codigo'], null, 3);
        $cliente_nome = $this->request->data['cliprinom'] ?? '';
        $cliente_cpf_cnpj = $this->request->data['clicpfcnp'] ?? '';
        $total = 0;
        $retorno = "";
        if (count($pesquisa_creditos) > 0) {
            $retorno .= "<div>
            <div class=\"col-md-9\">
                <div class=\"cabecalho_credito\" style=\"padding: 8px; border: 1px solid #e5e5e5;\">
                    <div class=\"row\">
                        <div class=\"col-md-12\">
                            <b>" . $rotulos['rot_gerclitit'] . "</b>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-md-4\">
                            <label>" . $rotulos['rot_cliprinom'] . ": " . $cliente_nome . "</label> 
                        </div>
                        <div class=\"col-md-4\">
                            <label>" . $rotulos['rot_clicpfcnp'] . ": " . $cliente_cpf_cnpj . "</label> 
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-md-12\">
                            <b>" . $rotulos['rot_conexttit'] . "</b>
                        </div>
                    </div>
                </div>

                <table class=\"table_cliclipes\">
                    <thead>
                        <tr>
                            <th>" . $rotulos['rot_gerdattit'] . "</th>
                            <th>" . $rotulos['rot_resdocnum'] . "</th>                                
                            <th>" . $rotulos['rot_concreexp'] . "</th>                    
                            <th>" . $rotulos['rot_gervaltit'] . " ( " . $this->geral->germoeatr() . ")</th></tr></thead>";

            foreach ($pesquisa_creditos['lancamentos'] as $value) {
                if ($value['expirado'])
                    $color = "#999";
                else
                    $color = "#000";

                $retorno .= "<tr>                      
                                <td style='color:" . $color . "'>" . date('d/m/Y', strtotime($value['data'])) . "</td>
                                <td style='color:" . $color . "'>" . $value['documento_numero'] . "</td>                                
                                <td style='color:" . $color . "'>" . date('d/m/Y', strtotime($value['expiracao_data'])) . "</td>
                                <td style='color:" . $color . "'>" . $this->geral->gersepatr($value['valor']) . "</td>
                            </tr>";
                if (!$value['expirado'])
                    $total = round($total + ($value['valor']), 2);
            }

            $retorno .= "<tr>
                        <td colspan=\"3\"><b style='font-size: 12px;'>" . $rotulos['rot_gersaltit'] . "</b></td>
                        <td><b style='font-size: 12px;'>" . $this->geral->gersepatr($total) . "</b></td>
                    </tr>
                </table>
            </div>
            <div class=\"col-md-3\">
                <div style=\"padding: 8px; border: 1px solid #e5e5e5;\">
                    <div class=\"row\">
                        <div class=\"col-md-12\">
                            <b>" . $rotulos['rot_conresdex'] . "</b>
                        </div>
                    </div>
                    <table class=\"table_cliclipes\">
                        <thead>
                            <tr>
                                <th>" . $rotulos['rot_concreexp'] . "</th>   
                                <th>" . $rotulos['rot_gervaltit'] . " (" . $this->geral->germoeatr() . ")</th>                                         
                            </tr></thead>";

            foreach ($pesquisa_creditos['agrupados'] as $value) {
                if ($value['expirado'])
                    $color = "#999";
                else
                    $color = "#000";

                $retorno .= "<tr>                      
                                <td style='color:" . $color . "'>" . date('d/m/Y', strtotime($value['expiracao_data'])) . "</td>
                                <td style='color:" . $color . "'>" . $this->geral->gersepatr($value['valor']) . "</td>                            
                            </tr>";
            }
            $retorno .= "</table></div></div></table></div>";
        } else if (isset($pesquisa_creditos)) {
            $retorno .= " <br/><br/>Nenhum item encontrado";
        }
        echo $retorno;
        $this->autoRender = false;
    }

    //Quando envia várias da pagina de modificacao
    public function ajaxestfnrcen() {
        $this->estfnrmoc();
        if (sizeof($this->request->data['fnrhs']) > 0)
            $retorno = $this->estadia->estfnrcen($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['fnrhs'], null);

        $this->session->write('retorno', $retorno);
        $this->redirect('/estadias/estfnrpes/');
        $this->autoRender = false;
    }

    public function ajaxestsavses() {

        $this->session->write('envio_status', $this->request->data['envio_status'] ?? "");
        $this->session->write('retorno_msg', $this->request->data['retorno_msg'] ?? "");
        $this->session->write('envio_data', $this->request->data['envio_data'] ?? "");

        $this->autoRender = false;
    }

    public function ajaxestfnrcri() {
        echo $this->estadia->estfnrcri($this->request->data['empresa_codigo'], $this->request->data['documento_numero'], $this->request->data['cliente_codigo'] ?? null, $this->request->data['procedencia_cidade_codigo'] ?? "", $this->request->data['procedencia_cidade_nome'] ?? "", $this->request->data['procedencia_estado_codigo'] ?? "", $this->request->data['procedencia_pais_codigo'] ?? "", $this->request->data['destino_cidade_codigo'] ?? "", $this->request->data['destino_cidade_nome'] ?? "", $this->request->data['destino_estado_codigo'] ?? "", $this->request->data['destino_pais_codigo'] ?? "", $this->request->data['viagem_motivo_codigo'] ?? "", $this->request->data['transporte_meio_codigo'] ?? "", $this->request->data['placa_veiculo'] ?? "")['retorno'];
        $this->autoRender = false;
    }

    //Gera o novo dominio de quartos quando se altera a empresa em concliges
    function ajaxgerdomqua() {
        $quarto_codigos = $this->geral->gercamdom('resquacod', $this->request->data['gerempcod']);
        $retorno = "";
        foreach ($quarto_codigos as $quarto) {
            $retorno .= $quarto['valor'] . "|";
        }
        $retorno = substr($retorno, 0, -1);
        echo $retorno;
        $this->autoRender = false;
    }

    //Gera o novo dominio de status quando se altera o documento_tipo
    function ajaxgerdomsta() {
        $status = $this->geral->gercamdom('resdocsta', $this->request->data['serdoctip']);
        echo json_encode($status);
        $this->autoRender = false;
    }

    //Gera o novo dominio de motivos quando se altera o documento_tipo
    function ajaxgerdommot() {
        $motivo = $this->geral->gerdommot(array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
            'empresa_codigo' => $this->session->read('empresa_selecionada')['empresa_codigo'],
            'motivo_tipo_codigo' => "'" . $this->request->data['documento_tipo_codigo'] . "'"));
        echo json_encode($motivo);
        $this->autoRender = false;
    }

    public function ajaxgerestdet() {

        $paises_table = TableRegistry::get('Paises');
        $pais = $paises_table->findByPaisNome($this->request->data['pais_codigo']);

        if ($pais != null) {
            $retorno = $this->geral->gerestdet($pais->pais_codigo);
            $resultArr = array();
            foreach ($retorno as $result) {
                $resultArr[] = array('estado_codigo' => $result['estado_codigo'], 'estado_nome' => $result['estado_nome']);
            }

            $this->set('resultArr', $resultArr);
            echo json_encode($resultArr);
        } else {
            echo '';
        }
        $this->autoRender = false;
    }

    function ajaxgerpagsal() {
        $values = array();
        if ($this->request->data['form'] ?? "" != "") {
            parse_str($this->request->data['form'], $values);
        }
        unset($values['form_validator_function']);

        debug($values);

        if ($this->request->data['back_page'][0] == "/")
            $this->request->data['back_page'] = substr($this->request->data['back_page'], 1);
        $this->geral->gerpagsal($this->request->data['back_page'], $values);
        $this->autoRender = false;
    }

    function ajaxgermendel() {
        $this->session->delete('retorno_footer');
        $this->autoRender = false;
    }

    public function ajaxgermencri() {
        $mensagem_codigo = $this->request->data['mensagem_codigo'];
        $exibicao_tipo = $this->request->data['exibicao_tipo'] ?? null;
        $texto_1 = $this->request->data['texto_1'] ?? null;
        $texto_2 = $this->request->data['texto_2'] ?? null;
        $texto_3 = $this->request->data['texto_3'] ?? null;
        $idioma = $this->request->data['idioma'] ?? null;
        $empresa_codigo = $this->request->data['empresa_codigo'] ?? $this->session->read('empresa_selecionada')['empresa_codigo'];

        echo json_encode($this->geral->germencri($empresa_codigo, $mensagem_codigo, $exibicao_tipo, $texto_1, $texto_2, $texto_3, null, $idioma));
        $this->autoRender = false;
    }

    /*
     * Verifica acesso a nivel de empresa_grupo_codigo
     */

    function ajaxgerauever() {
        echo $this->geral->gerauever($this->session->read('empresa_grupo_codigo_ativo'), $this->request->data['controle'], $this->request->data['acao'] ?? null);
        $this->autoRender = false;
    }

    function ajaxgercomcri() {
        echo $this->geral->gercomcri($this->request->data['empresa_codigo'], $this->request->data['comunicacao_tipo_codigo'], $this->request->data['documento_numero'], $this->request->data['destinatario_nome'] ?? '', $this->request->data['destinatario_sobrenome'] ?? '', array(['destinatario_contato' => $this->request->data['destinatario_contato']]));
        $this->autoRender = false;
    }

    function ajaxgercomcen() {
        //Está sendo enviada manualmente pelo administrador
        if (isset($this->request->data['action_form'])) {
            $this->geral->gercomcen($this->request->data['gerempcod'], null, $this->request->data['comunicacao_numeros_selecionados']);

            //Está sendo enviado via tarefa agendada
        } else {
            $empresa_codigo = $_GET['ec'];
            $comunicacao_tipo_codigo = $_GET['ctc'] ?? null;
            echo $this->geral->gercomcen($empresa_codigo, $comunicacao_tipo_codigo);
        }
        $this->autoRender = false;
    }

    /*
     * Retorna erro de mensagem expirada
     */

    public function ajaxgerpagexp() {
        $retorno = $this->geral->germencri(1, 58, 3, null, null, null, null, 'pt')['mensagem'];
        $this->set('retorno', $retorno);
        $this->viewBuilder()->setLayout('default');
    }

    /*
     * Verifica a quantidade de crianças possivel quando se altera o select de adultos
     * Chamada via AJAX
     */

    public function ajaxrescrimax() {
        if (isset($this->request->data['quarto_tipo_codigo']))
            $quarto_tipo_codigo = $this->request->data['quarto_tipo_codigo'];
        else
            $quarto_tipo_codigo = null;
        $max_criancas = $this->reserva->rescrimax($this->session->read('empresa_selecionada')["empresa_codigo"], $this->request->data['qtd_adultos'], $quarto_tipo_codigo);
        $this->set('var_max_crianca', $max_criancas);
        echo $max_criancas;
        $this->autoRender = false;
    }

    /*
     * Busca as opções relacionadas ao cancelamento escolhido em rescliide (AJAX)
     */

    public function ajaxrescansel() {
//Pega o tipo de cancelamento selecionado de acordo com reserva_cancelamento_codigo
        $reserva_cancelamento_codigo = $this->request->data['reserva_cancelamento_codigo'];

//Deve-se preencher o $array_rescandet de acordo com o codigo de cancelamento
        $dados_cancelamento = $this->reserva->findByReservaCancelamentoCodigo($reserva_cancelamento_codigo, $this->session->read('empresa_selecionada')['empresa_codigo']);

        $var_rescansel = $this->reserva->rescansel($this->request->data['empresa_codigo'], $dados_cancelamento, $this->request->data['quarto_item'], $this->request->data['total_preco'], $this->request->data['preco'] / $this->request->data['total_diarias'], $this->request->data['inicial_data'], $this->request->data['final_data'], $this->request->data['reserva_data']);

        echo $var_rescansel;
        $this->autoRender = false;
    }

    /*
     * Busca as opções relacionadas à confirmação escolhido em rescliide (AJAX)
     */

    public function ajaxrescnfsel() {

        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];
        $quarto_item = $this->request->data['quarto_item'];
        $tipo_tarifa_codigo = $this->request->data['tipo_tarifa_codigo'];
        $inicial_data = $this->request->data['inicial_data'];
        $final_data = $this->request->data['final_data'];
        $reserva_data = $this->request->data['reserva_data'];

//Pega o tipo de cancelamento selecionado de acordo com valor_tipo
        $nome_cnf_selecionada = $this->request->data['nome_cnf_selecionada'];

//Busca a confirmação no array de confirmações pelo nome
        $var_rescnfdet = $this->reserva->rescnfdet($empresa_codigo, $tipo_tarifa_codigo);
        $reserva_confirmacao_tipo = 0;
        $reserva_valor_tipo = 0;
        foreach ($var_rescnfdet AS $ky => $value) {
            if ($value["reserva_confirmacao_nome"] == $nome_cnf_selecionada) {
                $array_rescnfdet = $value;
                $reserva_confirmacao_tipo = $array_rescnfdet['reserva_confirmacao_tipo'];
                $reserva_valor_tipo = $array_rescnfdet['valor_tipo'];
            }
        }

        if (isset($array_rescnfdet))
            $var_rescnfsel = $this->reserva->rescnfsel($array_rescnfdet, $quarto_item, $inicial_data, $final_data, null, $reserva_data) ?? "";
        else {
            $var_rescnfsel['html'] = "";
            $var_rescnfsel['texto'] = "";
        }
//Monta as opções da forma de pagamento se necessário:
        if ($var_rescnfsel['html'] == "(forma de pagamento)") {
            echo " |" . $reserva_confirmacao_tipo . "|" . $reserva_valor_tipo;
        } else
            echo $var_rescnfsel['html'] . "|" . $reserva_confirmacao_tipo . "|" . $reserva_valor_tipo . "|" . $var_rescnfsel['texto'];

        $this->autoRender = false;
    }

    public function ajaxrespagreg2() {
        $var_respagreg = $this->reserva->respagreg($this->request->data['sel_respagfor'], $this->request->data['quarto_item'], $this->request->data['h_nome'] ?? "", $this->request->data['h_sobrenome'] ?? "", $this->request->data['valor_parcela1'] ?? "0.00", "", $this->request->data['linha_pagamento'], null, $this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['contabil_tipo'] ?? null
                , $this->request->data['cliente_codigo'] ?? null, $this->request->data['reserva_data'] ?? null);
        echo $var_respagreg;
        $this->autoRender = false;
    }

    /*
     * Busca as op��es relacionadas à forma de pagamento selecionada em rescliide (AJAX)
     */

    public function ajaxrespagreg() {
        $var_respagreg = $this->reserva->respagreg($this->request->data['sel_respagfor'], $this->request->data['quarto_item'], $this->request->data['h_nome'], $this->request->data['h_sobrenome'] ?? "", $this->request->data['valor_parcela1'] ?? "0.00", "", null, null, $this->session->read('empresa_selecionada')['empresa_codigo'], null, null, $this->request->data['reserva_data']);
        echo $var_respagreg;
        $this->autoRender = false;
    }

    /*
     * Busca o documento baseado no numero e empresa codigo
     */

    public function ajaxresdoccan() {

        echo json_encode($this->reserva->resdoccan($this->request->data['empresa_codigo'], $this->request->data['documento_numero'], $this->request->data['quarto_item'], $this->request->data['cancelamento_motivo_codigo'] ?? 1, $this->request->data['cancelamento_motivo_texto'] ?? null, $this->request->data['cancelamento_limite'] ?? null, $this->request->data['cancelamento_valor'] ?? null, $this->request->data['total_pago'] ?? null));

        $this->autoRender = false;
    }

    public function ajaxresdocmod() {
        //Modifica a reserva, até o momento só é utilizada no cancelamento de reserva
        //Faz o mapeamento das outras informações para cada quarto
        $quarto_item_dados = array();
        $quarto_item_dados[$this->request->data['quarto_item']]['reserva_dados']['cancelamento_motivo_codigo'] = $this->request->data['cancelamento_motivo_codigo'] ?? 1;
        $quarto_item_dados[$this->request->data['quarto_item']]['reserva_dados']['cancelamento_motivo_texto'] = $this->request->data['cancelamento_motivo_texto'] ?? '';
        $quarto_item_dados[$this->request->data['quarto_item']]['reserva_dados']['quarto_status_codigo'] = 5;

        echo json_encode($this->reserva->resdocmod($this->request->data['empresa_codigo'], $this->request->data['documento_numero'], 2, null, null, $quarto_item_dados));

        $this->autoRender = false;
    }

    public function ajaxreshosatu() {

        $empresa_codigo = $this->request->data['empresa_codigo'];
        $documento_numero = $this->request->data['documento_numero'];
        $quarto_item = $this->request->data['quarto_item'];
        $contratante_codigo = $this->request->data['contratante_codigo'];
        $hospedes_modificados = $this->request->data['hospedes_modificados'];
        $hospedes_cliente_itens = $this->request->data['hospedes_cliente_itens'];
        $hospedes_codigo_antigos = $this->request->data['hospedes_codigo_antigos'];
        $hospedes_codigos = $this->request->data['hospedes_codigos'];
        $hospedes_nomes = $this->request->data['hospedes_nomes'];
        $hospedes_sobrenomes = $this->request->data['hospedes_sobrenomes'];
        $hospedes_emails = $this->request->data['hospedes_emails'];
        $hospedes_cpfs = $this->request->data['hospedes_cpfs'];
        $hospedes_telefones = $this->request->data['hospedes_telefones'] ?? null;
        $hospedes_documento_tipos = $this->request->data['hospedes_documento_tipos'];
        $hospedes_documento_numeros = $this->request->data['hospedes_documento_numeros'];

        //Remove clientes onde o nome é vazio. Isso é necessário, pois na criação da reserva já foram inseridos referencias nulas na documento_cliente.
        //Caso esssa remoção não seja feita, cadastra novamente referencias vazias na documento_cliente
        foreach ($hospedes_codigos as $key => $codigos) {
            if ($hospedes_nomes[$key] == "") {
                unset($hospedes_modificados[$key], $hospedes_codigo_antigos[$key], $hospedes_codigos[$key], $hospedes_nomes[$key], $hospedes_sobrenomes[$key], $hospedes_emails[$key], $hospedes_cpfs[$key], $hospedes_telefones[$key], $hospedes_documento_tipos[$key], $hospedes_documento_numeros[$key], $hospedes_cliente_itens[$key]);
            }
        }
        echo json_encode($this->reserva->reshosatu($empresa_codigo, $documento_numero, $quarto_item, $contratante_codigo, $hospedes_codigos, $hospedes_nomes, $hospedes_sobrenomes, $hospedes_emails, $hospedes_cpfs, $hospedes_documento_tipos, $hospedes_documento_numeros, null, $hospedes_telefones, null, null, null, null, null, null, null, null, null, null, null, null, $hospedes_modificados, $hospedes_codigo_antigos, $hospedes_cliente_itens));

        $this->autoRender = false;
    }

    public function ajaxgermenpes() {
        $arr_gertelmon = $this->geral->gertelmon($this->session->read('empresa_grupo_codigo_ativo'), 'gertelpri');
        $rotulos = Util::germonrot($arr_gertelmon);

        $mensagens = $this->geral->germenpes($this->session->read('empresa_selecionada')['empresa_codigo'], null, $this->geral->geragodet($this->session->read('empresa_selecionada')['empresa_codigo'], 1), 7);

        $gerempcod_list = $this->geral->gercamdom('gerempcod', $this->session->read('empresa_selecionada')['empresa_grupo_codigo']);
        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];


        $retorno = "<table id='example' class=\"table_cliclipes\">";
        /* $retorno .= "<div class='form-group' >
          <label class='control-label col-md-1 col-sm-3' >" . $rotulos['rot_gerempcod'] . ":</label>
          <div class='col-md-3 col-sm-3' style='margin-bottom:10px'>
          <div class='col-md-9 row'>
          <select class='form-control' data-index='1' name='gerempcod' id='gerempcod_msg' onchange='gerempsel(this.value);'>
          <option value='' selected='selected' ></option>";

          foreach ($gerempcod_list as $item) {
          if ($empresa_codigo == $item["valor"]) {
          $selected = "";
          } else {
          $selected = "";
          }

          $retorno .= "<option value=" . $item['valor'] . "  " . $selected . " >" . $item['rotulo'] . "</option> ";
          }
          $retorno .=" </select>
          </div>
          </div>
          </div>"; */
        $retorno .= "<thead>
      <tr>
        <th>" . $rotulos['rot_gerdattit'] . "<span></span></th>
        <th>" . $rotulos['rot_gerlogtit'] . "<span></span></th>
        <th style='display:none'>&nbsp;</th>                                
        <th>" . $rotulos['rot_germsgcod'] . "</th>                    
        <th>" . $rotulos['rot_estmsgtit'] . "</th>
    </tr></thead><tbody>";
        if (sizeof($mensagens) > 0) {
            foreach ($mensagens as $value) {
                $retorno .= "<tr>                      
            <td>" . date('d/m/Y', strtotime($value['exibicao_data'])) . "</td>
            <td>" . $value['usuario_login'] . "</td> 
            <td style='display:none'>" . $value['empresa_codigo'] . "</td>                                
            <td>" . $value['mensagem_codigo'] . "</td>
            <td>" . $value['mensagem_texto'] . "</td>
        </tr>";
            }
            $retorno .= " </tbody></table>";
        }

        echo $retorno;
        $this->autoRender = false;
    }

    public function ajaxserccomod() {
        $servico = new Servico();
        echo $servico->serccomod($this->request->data['empresa_codigo'], $this->request->data['exibicao_data'])['retorno'];
        $this->autoRender = false;
    }

    function ajaxData() {
        $browser = new Browser();
        $output = $browser->GetData();
        echo json_encode($output);
        $this->autoRender = false;
    }

    public function ajaxserrefexi() {
        $servico = new Servico();
        $output = $servico->serrefexi($this->request->data['empresa_codigo'], $this->request->data['quarto_codigo'], $this->request->data['documento_tipo_codigo'], $this->request->data['data']);
        echo json_encode($output);
        $this->autoRender = false;
    }

    /* public function ajaxgerpadexi(){
      $gerpadexi_dados = ($this->geral->gerpadexi($this->request->data['tela_codigo']));
      $retorno = "";
      foreach ($gerpadexi_dados AS $ky => $vl) {
      $retorno = $retorno . "<label>" . $vl['elemento_rotulo'] . ":&nbsp;</label>";

      if (!empty($vl['campo_dominio'])) {
      $retorno .= "<div class='row'><div class='col-md-3'>";
      $retorno .= $this->mk_select($this->gercamdom($vl['elemento_codigo'], $vl['campo_dominio']), $vl['elemento_codigo'], 'lista_gerpadexi', $vl['padrao_valor']);
      $retorno .= "</div></div>";
      } else
      $retorno .= "<input type='text' name='lista_gerpadexi' id='" . $vl['elemento_codigo'] . "' value='" . $vl['padrao_valor'] . "'>";

      $retorno = $retorno . "<br>";
      }
      echo $retorno;
      $this->autoRender = false;
      } */

    public function ajaxgerpadsal() {
        $values = array();
        if ($this->request->data['form'] ?? "" != "")
            parse_str($this->request->data['form'], $values);

        echo $this->geral->gerpadsal($this->request->data['usuario_codigo'], $this->request->data['tela_codigo'], $values);
        $this->autoRender = false;
    }

    /* public function ajaxcliunival() {
      echo json_encode($this->cliente->cliunival($this->request->data['empresa_grupo_codigo'], $this->request->data['cliente_univoco_campo'], $this->request->data['tela_univoco_campo']));
      $this->autoRender = false;
      }
     */

    public function ajaxgerlogemp() {
        $this->viewBuilder()->setLayout('logo');
    }

    public function ajaxestpaiatu() {
        //Monta o cockpit
        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];
        $quartos_tipos_codigos = $this->geral->gercamdom('resquatip', $empresa_codigo);
        $filtro = array('vazio' => 1, 'ocupado' => 1, 'bloqueado' => 1, 'check_in' => 0, 'check_out' => 0, 'servico' => 0,
            'bloqueio' => 0);
        foreach ($quartos_tipos_codigos as $quarto_tipo_codigo)
            $filtro[$quarto_tipo_codigo['valor']] = 1;

        echo json_encode($this->estadia->estpaiatu($empresa_codigo, $this->request->data['saida_tipo'], $filtro)['estpaiatu_cockpit_dados']);
        $this->autoRender = false;
    }

    public function ajaxresdocpes() {
        $arr_gertelmon = $this->geral->gertelmon($this->session->read('empresa_grupo_codigo_ativo'), 'resdocpes');

        $rotulos = Util::germonrot($arr_gertelmon);
        $arr_elementos = array();
        foreach ($arr_gertelmon as $elemento)
            $arr_elementos[$elemento['elemento_codigo']] = $elemento;

        $this->set($rotulos);
        $this->set(Util::germonfor($arr_gertelmon));
        $this->set(Util::germonval($arr_gertelmon));
        $this->set(Util::germonpro($arr_gertelmon));
        $this->set(Util::germonpad($arr_gertelmon));

        $pesquisa_reservas = $this->reserva->resdocpes($this->session->read('empresa_selecionada')['empresa_codigo'], "rs", null, null, $this->request->data['cliente_codigo'] ?? null);
        $this->set('pesquisa_reservas', $pesquisa_reservas);
        //echo json_encode($retorno);
        //Itens setados apenas para o cliente modificação até o momento
        $this->set('has_form', 1);
        $this->set('id_form', 'clicadmod');
        $this->set('back_page', "clientes/clicadmod/" . $this->request->data['cliente_codigo']);

        //Checa as permissões em elementos da tela
        $this->set('ace_estchicri', $this->geral->gerauuver('estadias', 'estchicri') ? '' : ' disabled ');
        $this->set('multiple_select', false);
        $this->set('limite_confirmacao', false);
        $this->set('limited_actions', true);
        $this->viewBuilder()->template('/Element/reserva/resexiele_elem');
        $this->viewBuilder()->setLayout('ajax');
    }

    public function ajaxgersesdel() {

        $this->session->delete('retorno');
        $this->autoRender = false;
    }

    /*
     * Deleta o histórico
     */

    public function ajaxgerdelhis() {
        $this->session->delete('historico');
        $this->session->delete('paginas_pilha');
        $this->session->delete('is_redirect');
        $this->autoRender = false;
    }

    //Insere uma informação na sessão
    public function ajaxgersescri() {
        $this->session->write($this->request->data['rotulo'], $this->request->data['valor']);
        $this->autoRender = false;
    }

    public function ajaxprocodver() {
        $produto_codigo = $this->request->data['produto_codigo']; // ?? $this->request->params['?']['produto_codigo'];
        $tabelaProdutos = TableRegistry::get('ProdutoEmpresaGrupos');
        $pesq = $tabelaProdutos->find()
                ->select(['produto_codigo'])
                ->where(['produto_codigo' => $produto_codigo])
                ->first();
        //debug($pesq); die();
        if ($pesq !== null) {
            echo "0";
        } else {
            echo "1";
        }
        $this->autoRender = false;
    }

    public function ajaxprocodprx() {
        $tabelaProdutos = TableRegistry::get('ProdutoEmpresaGrupos');
        $gruEmp = $this->request->data['empresa_grupo_codigo'];
        $proximo = $tabelaProdutos->procadprx($gruEmp);

        echo $proximo;
        $this->autoRender = false;
    }

    public function ajaxserdocmod() {
        if (!isset($this->request->data['documento_status_codigo'])) {
            $documento_dados = $this->servico->serdocexi($this->request->data['empresa_codigo'], $this->request->data['serdoctip'], $this->request->data['serdocnum']);
            $documento_status_codigo = $documento_dados['servico']['documento_status_codigo'];
            $anterior_documento_status_codigo = $documento_dados['servico']['documento_status_codigo'];
        } else {
            $documento_status_codigo = $this->request->data['documento_status_codigo'];
            $anterior_documento_status_codigo = $this->request->data['anterior_documento_status_codigo'];
        }

        $retorno = $this->servico->serdocmod($this->request->data['empresa_codigo'], $this->request->data['serdoctip'], $this->request->data['serdocnum'], $this->request->data['serquacod'], $documento_status_codigo, $anterior_documento_status_codigo, $this->request->data['serinidat'] ?? null, $this->request->data['serfindat'] ?? null, null, null, $this->request->data['anterior_inicial_data'] ?? null, $this->request->data['anterior_final_data'] ?? null)['mensagem']['mensagem'];
        $this->session->write('retorno_footer', $retorno);
        $this->autoRender = false;
    }

    public function ajaxserdoccri() {
        echo $this->servico->serdoccri($this->request->data['empresa_codigo'], $this->request->data['serdoctip'], $this->request->data['serinidat'], $this->request->data['serfindat'], $this->request->data['serquacod'])['mensagem']['mensagem'];
        $this->autoRender = false;
    }

    /*
     * Calcula a acomodação, na RESPDCCRI
     */

    public function ajaxresquaaco() {
        $acomodacao = $this->reserva->resquaaco($this->request->data['empresa_codigo'], $this->request->data['adulto_qtd'], $this->request->data['criancas_idades'] ?? null);
        echo json_encode($acomodacao);
        $this->autoRender = false;
    }

    /*
     * Propõe os adicionais, na RESPDCCRI
     */

    public function ajaxresadipro() {
        $adicionais = $this->reserva->resadipro($this->request->data['empresa_codigo'], $this->request->data['tarifa_tipo_codigo']);
        for ($j = 0; $j < sizeof($adicionais); $j++) {
            //geraceseq para a descrição do produto
            $parametros = array('empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'], 'empresa_codigo' => $this->request->data['empresa_codigo'],
                'produto_codigo' => $adicionais[$j]['adicional_codigo']);
            $adicionais[$j]['descricao'] = $this->geral->geraceseq('produto_descricao', array('descricao'), $parametros)['descricao'];

            //geraceseq para o preço do produto
            $parametros = array('produto_codigo' => $adicionais[$j]['adicional_codigo'],
                'empresa_grupo_codigo' => $this->session->read('empresa_selecionada')['empresa_grupo_codigo'],
                'empresa_codigo' => $this->request->data['empresa_codigo'], 'venda_ponto_codigo' => "''");

            $adicionais[$j]['preco'] = $this->geral->geraceseq('produto_preco', array('preco'), $parametros)['preco'];
            $adicionais[$j]['servico_taxa_incide'] = $this->geral->geraceseq('servico_taxa_incide', array('servico_taxa_incide'), $parametros)['servico_taxa_incide'];
            $horario_modificacao = $this->geral->geraceseq('horario_modificacao_tipo', array('horario_modificacao_tipo', 'horario_modificacao_valor'), $parametros);
            if (is_array($horario_modificacao) && sizeof($horario_modificacao) > 0 && array_key_exists('horario_modificacao_tipo', $horario_modificacao[0])) {
                $adicionais[$j]['horario_modificacao']['horario_modificacao_tipo'] = $horario_modificacao[0]['horario_modificacao_tipo'];
                $adicionais[$j]['horario_modificacao']['horario_modificacao_valor'] = $horario_modificacao[0]['horario_modificacao_valor'];
            } else {
                $adicionais[$j]['horario_modificacao']['horario_modificacao_tipo'] = "";
                $adicionais[$j]['horario_modificacao']['horario_modificacao_valor'] = "";
            }
        }

        echo json_encode($adicionais);
        $this->autoRender = false;
    }

    /*
     * Calcula os valores possíveis para o adicional selecionado
     */

    public function ajaxresadimax() {
        $adicionais = $this->reserva->resadipro($this->request->data['empresa_codigo'], $this->request->data['tarifa_tipo_codigo']);
        $resadimax = array();
        for ($i = 0; $i < sizeof($adicionais); $i++) {
            if ($adicionais[$i]['adicional_codigo'] == $this->request->data['adicional_codigo'])
                $resadimax = $this->reserva->resadimax($this->request->data['adulto_qtd'], $this->request->data['crianca_qtd'], $this->request->data['dias_estadia'], $adicionais[$i]["variavel_fator_codigo"], $adicionais[$i]["max_qtd"]);
        }

        echo json_encode($resadimax);
        $this->autoRender = false;
    }

    /*
     * Propõe os prazos de pagamento, na RESPDCCRI
     */

    public function ajaxrespagpar() {
        $estadia_data = $this->geral->gerdatdet($this->request->data['inicial_data'], $this->request->data['final_data']);
        $prazos_pagamento = $this->reserva->respagpar($this->request->data['empresa_codigo'], $this->request->data['tarifa_tipo_codigo'], $this->request->data['tarifa_valor'] + $this->request->data['total_adicionais'], $this->request->data['dias_estadia'], $estadia_data['datas'], $this->request->data['reserva_data']);

        $prazos_pagamento_retorno = array();
        for ($i = 0; $i < sizeof($prazos_pagamento); $i++) {
            $prazos_pagamento[$i]["tarifa_variacao"] = $this->geral->gersepatr($prazos_pagamento[$i]["tarifa_variacao"]);
            $prazos_pagamento[$i]["partida_valor"] = $this->geral->gersepatr($prazos_pagamento[$i]["partida_valor"]);

            if (!array_key_exists($prazos_pagamento[$i]["pagamento_prazo_codigo"], $prazos_pagamento_retorno))
                $prazos_pagamento_retorno[] = $prazos_pagamento[$i];
        }

        echo json_encode($prazos_pagamento_retorno);
        $this->autoRender = false;
    }

    /*
     * Retorna todas as partidas do prazo de pagamento selecionada
     */

    public function ajaxrespagptd() {
        $estadia_data = $this->geral->gerdatdet(Util::convertDataSQL($this->request->data['inicial_data']), Util::convertDataSQL($this->request->data['final_data']));
        $prazos_pagamento = $this->reserva->respagpar($this->request->data['empresa_codigo'], $this->request->data['tarifa_tipo_codigo'], $this->request->data['tarifa_valor'] + $this->request->data['total_adicionais'], $this->request->data['dias_estadia'], $estadia_data['datas'], $this->request->data['reserva_data']);

        $prazos_pagamento_partidas = array();
        for ($i = 0; $i < sizeof($prazos_pagamento); $i++) {
            $prazos_pagamento[$i]["tarifa_variacao"] = $this->geral->gersepatr($prazos_pagamento[$i]["tarifa_variacao"]);
            $prazos_pagamento[$i]["partida_valor"] = $this->geral->gersepatr($prazos_pagamento[$i]["partida_valor"]);
            $prazos_pagamento[$i]["partida_data_formatada"] = Util::convertDataDMY($prazos_pagamento[$i]["partida_data"]);

            if ($prazos_pagamento[$i]["pagamento_prazo_codigo"] == $this->request->data['pagamento_prazo_codigo'])
                $prazos_pagamento_partidas[] = $prazos_pagamento[$i];
        }

        echo json_encode($prazos_pagamento_partidas);
        $this->autoRender = false;
    }

    /*
     * Retorna todas formas de cancelamento, na respdccri
     */

    public function ajaxrescandet() {
        $cancelamento_dados = $this->reserva->rescandet($this->request->data['empresa_codigo'], $this->request->data['tarifa_tipo_codigo']);

        //limpa os cancelamentos que não fazem sentido pela data de checkin/checkout
        /* for ($i = 0; $i < sizeof($cancelamento_dados); $i++) {
          if ($cancelamento_dados[$i]['evento'] == 1) {
          $data_cancelamento = Util::somaHora($this->request->data['inicial_data_completa'], $cancelamento_dados[$i]['tempo_hora']);
          //se a data de cancelamento estiver no passado
          if (Util::comparaDatas($this->geral->geragodet(2), $data_cancelamento) == 1) {
          unset($cancelamento_dados[$i]);
          $cancelamento_dados = array_values($cancelamento_dados);
          }
          } elseif ($cancelamento_dados[$i]['evento'] == 2) {
          $data_cancelamento = Util::somaHora($this->request->data['final_data_completa'], $cancelamento_dados[$i]['tempo_hora']);
          //se a data de cancelamento estiver no passado
          if (Util::comparaDatas($this->geral->geragodet(2), $data_cancelamento) == 1) {
          unset($cancelamento_dados[$i]);
          $cancelamento_dados = array_values($cancelamento_dados);
          }
          }
          } */
        echo json_encode($cancelamento_dados);
        $this->autoRender = false;
    }

    /*
     * Retorna todas formas de confirmação, na respdccri
     */

    public function ajaxrescnfdet() {
        $confirmacao_dados = $this->reserva->rescnfdet($this->request->data['empresa_codigo'], $this->request->data['tarifa_tipo_codigo']);

        for ($i = 0; $i < sizeof($confirmacao_dados); $i++) {
            if ($confirmacao_dados[$i]['evento'] == 1) {
                $data_confirmacao = Util::somaHora($this->request->data['inicial_data_completa'], $confirmacao_dados[$i]['tempo_hora']);
                //se a data de cancelamento estiver no passado
                if (Util::comparaDatas($this->geral->geragodet(2), $data_confirmacao) == 1) {
                    unset($confirmacao_dados[$i]);
                    $confirmacao_dados = array_values($confirmacao_dados);
                }
            } elseif ($confirmacao_dados[$i]['evento'] == 2) {
                $data_confirmacao = Util::somaHora($this->request->data['final_data_completa'], $confirmacao_dados[$i]['tempo_hora']);
                //se a data de cancelamento estiver no passado
                if (Util::comparaDatas($this->geral->geragodet(2), $data_confirmacao) == 1) {
                    unset($confirmacao_dados[$i]);
                    $confirmacao_dados = array_values($confirmacao_dados);
                }
            }
        }
        echo json_encode($confirmacao_dados);
        $this->autoRender = false;
    }

    public function ajaxrestartip() {
        $empresa_codigo = $this->request->data["empresa_codigo"];
        $inicial_data = $this->request->data['inicial_data'];
        $final_data = $this->request->data['final_data'];

        $adulto_quantidade = $this->request->data['adulto_qtd'] ?? 0;
        $quarto_tipo_codigo = $this->request->data['quarto_tipo_codigo'];

        //Busca a variavel de acesso sequencia do tipo de quarto (melhorar)
        $acesso_sequencia = $this->connection->execute("SELECT acesso_sequencia_codigo FROM quarto_tipos WHERE "
                        . "empresa_codigo=:empresa_codigo AND quarto_tipo_codigo= :quarto_tipo_codigo", ['empresa_codigo' => $empresa_codigo,
                    'quarto_tipo_codigo' => $quarto_tipo_codigo])->fetchAll("assoc")[0]['acesso_sequencia_codigo'];

        $estadia_data = $this->geral->gerdatdet($inicial_data, $final_data);
        $restartip = $this->reserva->restartip($empresa_codigo, $quarto_tipo_codigo, $estadia_data['datas'], $adulto_quantidade, $acesso_sequencia);

        $tarifa_tipos[$quarto_tipo_codigo] = array();
        if (sizeof($restartip[$quarto_tipo_codigo]) > 0) {
            $tarifa_tipos[$quarto_tipo_codigo] = $restartip[$quarto_tipo_codigo];
        }

        echo json_encode($tarifa_tipos);
        $this->autoRender = false;
    }

    public function ajaxprolisexi() {
        if ($this->request->is('post')) {
            $lista_tecnica_itens = $this->produto->prolisexi($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['produto_codigo']);
            echo json_encode($lista_tecnica_itens);
        }
        $this->autoRender = false;
    }

    //Socilita as datas via dialog para criação de uma reserva
    public function ajaxreservadatas() {
        $info_tela = $this->pagesController->montagem_tela('rescriini');

        $this->set('inicial_data', Util::convertDataDMY($this->geral->geragodet(2)));
        $this->set('final_data', Util::convertDataDMY(Util::somaDias($this->geral->geragodet(2), 1, 0)));
        $this->set($info_tela);

        $this->set('quarto_tipo_codigo', $this->request->data['quarto_tipo_codigo']);
        $this->set('quarto_codigo', $this->request->data['quarto_codigo']);

        $this->viewBuilder()->template('/Element/reserva/reserva_datas');
        $this->viewBuilder()->setLayout('ajax');
    }

}
