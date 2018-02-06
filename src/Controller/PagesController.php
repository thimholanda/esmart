<?php

namespace App\Controller;

use App\Utility\Util;
use App\Model\Entity\Geral;
use Cake\Network\Session;

class PagesController extends AppController {

    protected $geral;
    protected $session;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->geral = new Geral();
        $this->session = new Session();
    }

    //Método que lida com os parametros de tela, em cada requisição ao controller
    public function montagem_tela($tela_nome) {        
        $arr_gertelmon = $this->geral->gertelmon($this->session->read('empresa_grupo_codigo_ativo'), $tela_nome);

        $info_tela = array();
        $info_tela['rotulos'] = Util::germonrot($arr_gertelmon);
        $info_tela['formatos'] = Util::germonfor($arr_gertelmon);
        $info_tela['propriedades'] = Util::germonpro($arr_gertelmon);
        $info_tela['validadores'] = Util::germonval($arr_gertelmon);
        $info_tela['padroes'] = Util::germonpad($arr_gertelmon);

        $info_tela['extras_info']['tela_nome'] = $arr_gertelmon[0]['tela_nome'];
        $info_tela['extras_info']['nova_tela_botao'] = $arr_gertelmon[0]['nova_tela_botao'];
        $info_tela['extras_info']['voltar_botao'] = $arr_gertelmon[0]['voltar_botao'];
        $info_tela['extras_info']['avancar_botao'] = $arr_gertelmon[0]['avancar_botao'];
        $info_tela['extras_info']['recarregar_botao'] = $arr_gertelmon[0]['recarregar_botao'];
        $info_tela['extras_info']['log_botao'] = $arr_gertelmon[0]['log_botao'];
        $info_tela['extras_info']['pdf_botao'] = $arr_gertelmon[0]['pdf_botao'];
        $info_tela['extras_info']['excel_botao'] = $arr_gertelmon[0]['excel_botao'];
        $info_tela['extras_info']['imprimir_botao'] = $arr_gertelmon[0]['imprimir_botao'];

        return array_merge($info_tela['rotulos'], $info_tela['formatos'], $info_tela['propriedades'], $info_tela['validadores'], $info_tela['padroes'], $info_tela['extras_info']);
    }

    public function consomeHistoricoTela($url) {
        //Verifica se tem item no histórico para consumir
        $dados_historico_retorno = array();
        if ($this->session->check('historico')) {
            $historico_dados = $this->session->read('historico');
            // So consome se a url atual estiver na pilha e na ultima posição
            if (isset($historico_dados[$url]) && (array_search($url,array_keys($historico_dados)) == sizeof($historico_dados) - 1)) {
                foreach ($historico_dados[$url] as $key => $value)
                    $dados_historico_retorno[$key] = $value;

                unset($historico_dados[$url]);
                $this->session->delete('historico');
                $this->session->write('historico', $historico_dados);
            }
        }
        return $dados_historico_retorno;
    }

    public function display() {
        
    }

    public function index() {
        $session = $this->request->session();
        //Verifica se o usuário está logado
        echo $session->check('logado');
        if ($session->check('logado')) {
            $this->redirect(
                    ['controller' => 'geral', 'action' => 'gertelpri']
            );
        } else {
            //Se não estiver logado
            $this->redirect(
                    ['controller' => 'geral', 'action' => 'gerlogin']
            );
        }
    }

}
