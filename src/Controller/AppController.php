<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use App\Model\Entity\Geral;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class AppController extends Controller {

    protected $geral;
    protected $session;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->geral = new Geral();
        $this->session = $this->request->session();
    }

    public function initialize() {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->viewBuilder()->layout('frontend');
    }

    public function beforeRender(Event $event) {
        if (!array_key_exists('_serialize', $this->viewVars) &&
                in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    function beforeFilter(Event $event) {
        $session = $this->request->session();

        $acesso_ok = false;

        //Verifica inicialmente se o acesso é feito via email
        $acesso_externo_permitido = false;
        if ($this->request->query('ea') != null) {

            $empresa_table = TableRegistry::get('Empresas');
            $dados_empresa = $empresa_table->findByEmpresaCodigo($this->request->query('ea') ?? '');

            if (sizeof($dados_empresa) > 0) {
                //Cria variáveis de sessão, que são utilizadas em funções internas
                $this->session->write('empresa_grupo_codigo', $dados_empresa['empresa_grupo_codigo']);
                $array['empresa_codigo'] = $dados_empresa['empresa_codigo'];
                $array['horario_fuso'] = $dados_empresa['horario_fuso'];
                $this->session->write('empresa_selecionada', $array);
                $this->session->write('usuario_idioma', 'pt');

                $retorno = $this->geral->gereacver($this->request->query('ea'), $this->request->query('eac') ?? '', $this->request->query('eao') ?? '');
                if ($retorno['retorno'] == 1) {
                    $acesso_externo_permitido = true;
                } else {
                    $this->response = $this->redirect('/ajax/ajaxgerpagexp');
                    $this->response->send();
                    die();
                }
            }
        }

        //Verifica se o acesso é feito quando clica em abrir em nova aba
        if ($this->request->query('page') != null) {

            //String de parametros, caso exista
            $parametros = '';
            foreach ($this->request->query as $param => $param_valor) {
                if ($param != 'page') {
                    $parametros .= '&' . $param . '=' . $param_valor;
                }
            }

            return $this->redirect(
                            ['controller' => 'Geral', 'action' => 'gertelpri?p=' . $this->request->query('page') . $parametros]
            );
        }

        //verifica se é acesso a pagina de login ou logout, nesses casos deve liberar o acesso
        $acoes_usuario_login = $this->request->params['controller'] == 'Usuarios' &&
                ($this->request->params['action'] == 'gerlogin' || $this->request->params['action'] == 'logout' || 
                $this->request->params['action'] == 'gersenmod' || $this->request->params['action'] == 'gerredsen');

        //verifica se é acesso a pagina de login ou logout, nesses casos deve liberar o acesso
        $acoes_ajax = $this->request->params['controller'] == 'Ajax';

        //verifica se tem acesso a esse controller/action ou se a requisição tiver prefixo ajax, ele não precisa ter permissão via gerauuver
        $acesso_permissao_controller_action = $this->geral->gerauuver($this->request->params['controller'], $this->request->params['action']) || $acoes_ajax;

        $sessao_contador_nao_reinicia = null;
        if (isset($this->request->data['sessao_contador_nao_reinicia']))
            $sessao_contador_nao_reinicia = $this->request->data['sessao_contador_nao_reinicia'];
        
        if ($this->request->params['controller'] == 'Reservas' && $this->request->params['action'] == 'restarmod')
            $acesso_externo_permitido = 1;
        
        if ($this->request->params['controller'] == 'Geral' && $this->request->params['action'] == 'gergrusel')
            $acesso_externo_permitido = 1;
        //Caso o usuário tenha permissão para acesso externo
        if ($acesso_externo_permitido)
            $acesso_ok = true;
        else {
            //se for ações de login ou logout, deve permitir acesso
            if ($acoes_usuario_login)
                $acesso_ok = true;
            //se não, caso o usuário tenha sesssão ativa, deve verificar se sua sessão não esgotou e se ele tem acesso ao controler/action
            else if ($session->read('logado')) {
                // var_dump("Controller chamando ".$this->request->params['controller']. ' action '.$this->request->params['action']);
                if ($this->geral->gersescon($sessao_contador_nao_reinicia) && $acesso_permissao_controller_action)
                    $acesso_ok = true;
                //senão, deve redirecionar pra tela de login
                else {
                    //se a sessao for expirada e verificada em uma requisição ajax, o redirecionamento deve ser feito via javascript
                    if ($acoes_ajax || isset($_GET['ajax']) || isset($this->request->data['ajax'])) {
                        echo 'sessao_expirada';
                        die();
                    } else {
                        $this->response = $this->redirect('/usuarios/gerlogin');
                        $this->response->send();
                        die();
                    }
                }
            } else {
                if ($acoes_ajax || isset($_GET['ajax']) || isset($this->request->data['ajax'])) {
                    echo 'sessao_expirada';
                    die();
                } else {
                    $this->response = $this->redirect('/usuarios/gerlogin');
                    $this->response->send();
                    die();
                }
            }
        }

        if ($acesso_ok) {
            if ($session->read('logado') && $this->geral->gersescon($sessao_contador_nao_reinicia) &&
                    (
                    (
                    isset($this->request->data['session_id']) && $this->request->data['session_id'] != $session->read('session_id')
                    ) ||
                    (
                    isset($_GET['session_id']) && $_GET['session_id'] != $session->read('session_id')
                    )
                    )
            ) {

                if ($acoes_ajax || isset($_GET['ajax']) || isset($this->request->data['ajax'])) {
                    echo 'sessao_expirada';
                    die();
                } else {
                    $this->response = $this->redirect('/geral/gertelpri');
                    $this->response->send();
                    die();
                }
            }

            //Define as variaveis de acesso a elementos de tela
            $this->set('ace_cliente', ($this->geral->gerauuver('clientes') && ($this->geral->gerauuver('clientes', 'clicadcri') ||
                    $this->geral->gerauuver('clientes', 'clicadpes') )) ? '' : ' disabled ');
            $this->set('ace_clicadcri', $this->geral->gerauuver('clientes', 'clicadcri') ? '' : ' disabled ');
            $this->set('ace_clicadpes', $this->geral->gerauuver('clientes', 'clicadpes') ? '' : ' disabled ');

            $this->set('ace_reserva', ($this->geral->gerauuver('reservas') && ($this->geral->gerauuver('reservas', 'rescriini') ||
                    $this->geral->gerauuver('reservas', 'resdocpes') )) ? '' : ' disabled ');
            $this->set('ace_rescriini', $this->geral->gerauuver('reservas', 'rescriini') ? '' : ' disabled ');
            $this->set('ace_resdocpes', $this->geral->gerauuver('reservas', 'resdocpes') ? '' : ' disabled ');
            $this->set('ace_respaiatu', $this->geral->gerauuver('reservas', 'respaiatu') ? '' : ' disabled ');

            $this->set('ace_conta', ($this->geral->gerauuver('documentocontas') && ($this->geral->gerauuver('documentocontas', 'conresexi') ||
                    $this->geral->gerauuver('documentocontas', 'concreexi') )) ? '' : ' disabled ');
            $this->set('ace_conresexi', $this->geral->gerauuver('documentocontas', 'conresexi') ? '' : ' disabled ');
            $this->set('ace_concreexi', $this->geral->gerauuver('documentocontas', 'concreexi') ? '' : ' disabled ');

            $this->set('ace_pagamento', ($this->geral->gerauuver('documentocontas') && ($this->geral->gerauuver('documentocontas', 'conpagpes'))) ? '' : ' disabled ');
            $this->set('ace_conpagpes', $this->geral->gerauuver('documentocontas', 'conpagpes') ? '' : ' disabled ');
            $this->set('ace_conpfppes', $this->geral->gerauuver('documentocontas', 'conpfppes') ? '' : ' disabled ');
            $this->set('ace_conitepes', $this->geral->gerauuver('documentocontas', 'conitepes') ? '' : ' disabled ');
            $this->set('ace_conihopes', $this->geral->gerauuver('documentocontas', 'conihopes') ? '' : ' disabled ');

            $this->set('ace_estadia', ($this->geral->gerauuver('estadias') && ($this->geral->gerauuver('estadias', 'estfnrpes'))) ? '' : ' disabled ');
            $this->set('ace_estfnrpes', $this->geral->gerauuver('estadias', 'estfnrpes') ? '' : ' disabled ');
            $this->set('ace_estpaiatu', $this->geral->gerauuver('estadias', 'estpaiatu') ? '' : ' disabled ');

            $this->set('ace_servico', ($this->geral->gerauuver('servicos') && ($this->geral->gerauuver('servicos', 'serdoccri') ||
                    $this->geral->gerauuver('servicos', 'serdocpes') )) ? '' : ' disabled ');
            $this->set('ace_serdoccri', $this->geral->gerauuver('servicos', 'serdoccri') ? '' : ' disabled ');
            $this->set('ace_serdocpes', $this->geral->gerauuver('servicos', 'serdocpes') ? '' : ' disabled ');

            $this->set('ace_comunicacao', ($this->geral->gerauuver('geral') && ($this->geral->gerauuver('geral', 'gercompes'))) ? '' : ' disabled ');
            $this->set('ace_gercompes', $this->geral->gerauuver('geral', 'gercompes') ? '' : ' disabled ');
            
            $this->set('ace_gerempimp', $this->geral->gerauuver('geral', 'gerempimp') ? '' : ' disabled ');

            $usuario_dados['label_empresa_grupo'] = $this->session->read('label_empresa_grupo');
            $usuario_dados['empresa_grupo_nome'] = $this->session->read('empresa_grupo_nome');
            $usuario_dados['label_usuario'] = $this->session->read('label_usuario');
            $usuario_dados['usuario_codigo'] = $this->session->read('usuario_codigo');
            $usuario_dados['usuario_nome'] = $this->session->read('usuario_nome');
            $usuario_dados['label_idioma'] = $this->session->read('label_idioma');
            $usuario_dados['usuario_idioma'] = $this->session->read('usuario_idioma');
            $usuario_dados['label_logout'] = $this->session->read('label_logout');
            $usuario_dados['link_logout'] = Router::url('/', true) . "/usuarios/logout";


            $this->set('usuario_dados', $usuario_dados);
            //$this->set('gerempcod_list', $this->geral->gercamdom('gerempcod', $this->session->read('empresa_selecionada')['empresa_grupo_codigo']));
            $this->set('empresa_codigo', $this->session->read('empresa_selecionada')['empresa_codigo']);
            $this->set('session_id', $session->read('session_id'));
            
            //Checa se a url está sendo acessada em produção ou homologação
            $ambiente_producao = 0;
            if("http://$_SERVER[HTTP_HOST]" == 'http://propms-env-1.4pepcuftqs.sa-east-1.elasticbeanstalk.com'){
                $ambiente_producao = 1;
            }
            
            $this->set('ambiente_producao', $ambiente_producao);
        }
        return parent::beforeFilter($event);
    }

}
