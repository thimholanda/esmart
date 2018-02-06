<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class UsuariosController extends AppController {

    private $pagesController;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->pagesController = new PagesController();
    }

    public function logout() {
        $connection = ConnectionManager::get('default');
        //limpa as chaves que ele utilizava
        $connection->execute("UPDATE chaves SET chave='0001-01-01 00:00:00.000000' WHERE usuario_codigo = :usuario_codigo", ['usuario_codigo' => $this->session->read('usuario_codigo')]);

        //destroi a sessão
        $this->request->session()->destroy();
        return $this->redirect(
                        ['controller' => 'usuarios', 'action' => 'gerlogin']
        );
    }

    public function gerlogin() {
        
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $usuario = $this->geral->gerlogin($data['gerusulog'], $data['gerlogsen'], $this->request->data['usuario_idioma']);

            if ($usuario['retorno'] == 1) {
                $this->redirect(['controller' => 'geral', 'action' => 'gertelpri']);
            } else {
                //Usuário não encontrado, chamar germencri
                $this->set('retorno', $usuario['mensagem']['mensagem']);
                $this->set('redefinir_senha', $usuario['redefinir_senha']);
                $this->set('empresa_redefinir_senha', $usuario['empresa_redefinir_senha']);
                $this->set('login_redefinir_senha', $data['gerusulog']);
                $this->set('usuario_idioma', $this->request->data['usuario_idioma']);
                $this->set('gerusulog', $this->request->data['gerusulog']);
            }
        } else {
            if (!isset($usuario_idioma) || $usuario_idioma == "")
                $usuario_idioma = "pt";
            $this->set('usuario_idioma', $usuario_idioma);

            if ($this->session->check("retorno")) {
                $this->set('retorno', $this->session->read("retorno"));
                $this->session->delete("retorno");
            }
        }

        $info_tela = $this->pagesController->montagem_tela('gerlogin');
        $this->set($info_tela);
        $this->set('usuario_senha', '');

        $this->render('gerlogin');
    }

    //Alteraçao nos dados cadastrais do usuário
    public function gerusumod() {
        
    }

    /*
     * Modificar senha de login
     */

    public function gersenmod() {
        $info_tela = $this->pagesController->montagem_tela('gerlogin');
        if ($this->request->is('post')) {
            $retorno_gersenmod = $this->geral->gersenmod($this->request->data['empresa_codigo'], $this->request->data['acesso_objeto'], $this->request->data['acesso_chave'], null, $this->request->data['usuario_login'], $this->request->data['gerususen']);
            $this->session->write('retorno', $retorno_gersenmod['mensagem']['mensagem']);
            $this->redirect('/usuarios/gerlogin/');
            $this->autoRender = false;
        } else {
            $empresa_codigo = $_GET['ea'];
            $this->set('empresa_codigo', $empresa_codigo);
            $this->set('acesso_objeto', $_GET['eao']);
            $this->set('acesso_chave', $_GET['eac']);
            $this->set('usuario_login', $_GET['eao']);

            //Busca as mensagens de senhas incompativeis e senha fraca
            $this->set('senha_fraca_mensagem', $this->geral->germencri($empresa_codigo, 141, 1)['mensagem']);
            $this->set('senha_incompativel_mensagem', $this->geral->germencri($empresa_codigo, 142, 1)['mensagem']);
            $this->set($info_tela);
        }
    }

    //Redefine a senha do usuário
    public function gerredsen() {
        //Busca os dados do usuario 
        $connection = ConnectionManager::get('default');
        $login = $_GET['login'];
        $empresa_codigo = $_GET['empresa'];

        $usuario_dados = $connection->execute("SELECT * FROM usuarios WHERE usuario_login =:usuario_login", ['usuario_login' => $login])->fetchAll("assoc");
        if ($usuario_dados != null && sizeof($usuario_dados) > 0 && $usuario_dados[0]['usuario_email'] != '') {
            //Gera um item de comunicação
            $destinatario['destinatario_contato'] = $usuario_dados[0]['usuario_email'];

            $this->session->write('usuario_codigo', $usuario_dados[0]['usuario_codigo']);
            $this->geral->gercomcri($empresa_codigo, 'us', $login, $usuario_dados[0]['nome'], null, array($destinatario));

            //Busca os dados do item recem criado
            $comunicacao_item_dados = $connection->execute("SELECT * FROM comunicacao_item WHERE empresa_codigo =:empresa_codigo AND acesso_objeto=:acesso_objeto AND comunicacao_tipo_codigo='us' ORDER BY comunicacao_numero DESC LIMIT 1", ['empresa_codigo' => $empresa_codigo, 'acesso_objeto' => $login])->fetchAll("assoc");

            //Envia o item de comunicação
            $this->geral->geremaenv($empresa_codigo, $comunicacao_item_dados[0]['comunicacao_numero'], null, 'us', $comunicacao_item_dados[0]['destinatario_contato'], $comunicacao_item_dados[0]['email_parametros'], $comunicacao_item_dados[0]['acesso_chave'], $comunicacao_item_dados[0]['acesso_expiracao']);
        }
        $this->session->write('retorno', $this->geral->germencri($empresa_codigo, 145, 3)['mensagem']);
        $this->redirect('/usuarios/gerlogin/');
        $this->autoRender = false;
    }

}
