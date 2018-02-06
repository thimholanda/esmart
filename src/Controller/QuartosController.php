<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Geral;
use App\Model\Entity\Quartos;
use App\Model\Entity\QuartoTipos;
use Cake\Datasource\ConnectionManager;
use App\Utility\Util;
use App\Utility\Paginator;
use Cake\Orm\TableRegistry;
use App\Controller\PagesController;

class QuartosController extends AppController
{
    private $pagesController;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);

        $this->loadModel("Quartos");
        $this->loadModel("QuartoTipos");

        $this->connection = ConnectionManager::get('default');
        $this->pagesController = new PagesController();
    }

    // Exibição de tipos de quartos para pesquisa 
    public function gerquaini() {
        $info_tela = $this->pagesController->montagem_tela('gerquaini');
        $tipos = $this->gerqtpexi($this->session->read('empresa_selecionada')['empresa_codigo']);

        $this->set('quartoTipos', $tipos);

        $this->set($this->request->data);

        $pesq = array(
            'gerquacod' => '', 
            'gerquatip' => 0, 
            'gerquaexc' => 0
            );

        if ($this->request->is('post')) {
            $codigo = $tipo =  null;
            $excluido = 0;
            if (isset($this->request->data['gerquacod']) && (!(empty($this->request->data['gerquacod']))) ) {
                $codigo = $this->request->data['gerquacod'];
                $pesq['gerquacod'] = $this->request->data['gerquacod'];
            }
            if (isset($this->request->data['gerquatip']) && (!(empty($this->request->data['gerquatip']))) ) {
                $tipo = $this->request->data['gerquatip'];
                $pesq['gerquatip'] = $this->request->data['gerquatip'];
            }
            if (isset($this->request->data['gerquaexc'])) {
                $excluido = 1;
                $pesq['gerquaexc'] = 1;
            }

            $quartos = $this->gerquaexi(
                $this->session->read('empresa_selecionada')['empresa_codigo'],
                    $codigo, // Codigo
                    $tipo, // Tipo
                    $excluido, // Excluido
                    true // Retornar query builder
                );
        }
        else {
            $quartos = $this->gerquaexi($this->session->read('empresa_selecionada')['empresa_codigo'], null, null, 0, true);
        }

        $qtdResult = $quartos->count();

        $quartos->order([$this->request->data['ordenacao_coluna'] ?? 'Quartos.quarto_codigo' => $this->request->data['ordenacao_tipo'] ?? 'asc' ])
            ->limit(10)
            ->page($this->request->data['pagina'] ?? 1);

        $paginator = new Paginator(10);
        $this->set('paginacao', $paginator->gera_paginacao($qtdResult, $this->request->data['pagina'] ?? 1, 'gerquaini'));
        $this->set('ordenacao_coluna', $this->request->data['ordenacao_coluna'] ?? 'Quartos.quarto_codigo');
        $this->set('ordenacao_tipo', $this->request->data['ordenacao_tipo'] ?? 'asc');
        $this->set($info_tela);
        $this->set('quartos', $quartos->all());
        $this->set('pesq', $pesq);
        $this->set('tipoQuartos', $this->geral->gercamdom('gerquatip', $this->session->read('empresa_selecionada')["empresa_codigo"]));

        $this->viewBuilder()->setLayout('ajax');
    }

    function gerqtpexi($empresa_codigo, $quarto_tipo_codigo = null, $excluido = null) {
        $consulta = $this->QuartoTipos->find()
            ->select()
            ->where(['empresa_codigo' => $empresa_codigo]);

        if (isset($quarto_tipo_codigo)) {
            $consulta->where(['quarto_tipo_codigo' => $quarto_tipo_codigo]);
        }
        if ($excluido === 0 || $excluido === 1) {
            $consulta->where(['excluido' => $excluido]);
        }

        $consulta->hydrate(false);

        return $consulta->all();
    }

    function gerquaexi($empresa_codigo, $quarto_codigo = null, $quarto_tipo_codigo = null, $excluido = null, $queryBuilder = false) {
        $consulta = $this->Quartos->find()
            ->select(['Quartos.quarto_codigo', 'Quartos.empresa_codigo', 'Quartos.quarto_tipo_codigo', 'QuartoTipos.quarto_tipo_nome', 'Quartos.quarto_nome', 'Quartos.criacao_usuario', 'Quartos.excluido', 'Quartos.quarto_grupo'])
            ->innerJoin(
                ['QuartoTipos' => 'quarto_tipos'],
                ['Quartos.quarto_tipo_codigo = QuartoTipos.quarto_tipo_codigo']
                )
            ->distinct()
            ->where(['Quartos.empresa_codigo' => $this->session->read('empresa_selecionada')["empresa_codigo"]])
            ->where(['QuartoTipos.empresa_codigo' => $this->session->read('empresa_selecionada')["empresa_codigo"]]);

        if (isset($quarto_codigo)) {
            $consulta->where(['Quartos.quarto_codigo' => $quarto_codigo]);
        }

        if (isset($quarto_tipo_codigo)) {
            $consulta->where(['QuartoTipos.quarto_tipo_codigo' => $quarto_tipo_codigo]);
        }

        if ($excluido !== 1) {
            $consulta->where(['Quartos.excluido' => 0]);
        }

        if ($queryBuilder == false) {
            $consulta->hydrate(false);

            return $consulta->all();
        }
        else {
            return $consulta;
        }
    }

    // Modificar cadastro de tipo de quarto
    public function gerqtpmod() {
        try {
            $this->connection->transactional( function() {
                $arr_gertelmon = $this->geral->gertelmon($this->session->read('empresa_grupo_codigo_ativo'), 'gerqtpmod');

                $this->set(Util::germonrot($arr_gertelmon));
                $this->set(Util::germonfor($arr_gertelmon));
                $this->set(Util::germonpro($arr_gertelmon));
                $this->set(Util::germonval($arr_gertelmon));

                $info_tela = $this->pagesController->montagem_tela('gerqtpmod');
                
                if ($this->request->is('get')) {
                    if (isset($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'novo') {
                        $tipo = $this->QuartoTipos->newEntity();
                        $this->set('tipo', $tipo);
                    }
                    elseif (isset($this->request->params['pass'][0])) {
                        $empresa_codigo = $this->request->params['pass'][0];
                        $quarto_tipo_codigo = $this->request->params['pass'][1];
                        $tipo = $this->gerqtpexi($empresa_codigo, $quarto_tipo_codigo, null)->first();
                        $this->set('tipo', $tipo);
                    }
                    elseif (isset($this->request->params['?']['del'])) {
                        $del = $this->request->params['?']['del'];
                        $cod = $this->request->params['?']['cod'];
                        $emp = $this->session->read('empresa_selecionada')["empresa_codigo"];

                        $query = $this->connection->newQuery()
                            ->update('quarto_tipos')
                            ->set([
                                'excluido' => (int)$del,
                                'modificacao_usuario' => $this->session->read('usuario_codigo')
                                ])
                            ->where([
                                'quarto_tipo_codigo' => $this->request->params['?']['cod'],
                                'empresa_codigo' => $emp
                                ])
                            ->execute();

                        //echo 'del = ' . $del;
                        //debug($query);

                        $this->gerquaini();
                        $this->render('gerquaini');
                        return;
                    }
                    $this->viewBuilder()->setLayout('ajax');
                }
                elseif ($this->request->is('post')) {
                    $empresa_codigo = $this->session->read('empresa_selecionada')["empresa_codigo"];

                    $modeloQuartoTipo = $this->QuartoTipos->newEntity();
                    $modeloQuartoTipo->empresa_codigo = $empresa_codigo;
                    $modeloQuartoTipo->quarto_tipo_codigo = $this->request->data['gerqtpcod'] ?? 0;
                    $modeloQuartoTipo->quarto_tipo_nome = $this->request->data['gerqtpnom'] ?? "";
                    $modeloQuartoTipo->quarto_tipo_curto_nome = $this->request->data['gerqtpnmc'] ?? "";
                    $modeloQuartoTipo->adulto_quantidade = $this->request->data['gerqtpmxa'] ?? 0;
                    $modeloQuartoTipo->crianca_quantidade = $this->request->data['gerqtpmxc'] ?? 0;
                    $modeloQuartoTipo->acesso_sequencia_codigo = $this->request->data['gerqtpacs'] ?? 0;
                    $modeloQuartoTipo->criacao_usuario = $this->request->data['gerqtpusu'] ?? 0;
                    $modeloQuartoTipo->criacao_data = $this->geral->geragodet(2);

                    $modeloAntigo = $this->gerqtpexi($empresa_codigo, $modeloQuartoTipo->quarto_tipo_codigo)->first();

                    if ($modeloQuartoTipo->criacao_usuario == 0) {
                        $modeloQuartoTipo->criacao_usuario = $this->session->read('usuario_codigo');
                        $this->QuartoTipos->gerqtpmod($modeloQuartoTipo->quarto_tipo_codigo, $modeloQuartoTipo);
                    }
                    elseif (comparaEntidades($modeloQuartoTipo, $modeloAntigo) == 0) {
                        $modeloQuartoTipo->modificacao_usuario = $this->session->read('usuario_codigo');
                        $modeloQuartoTipo->modificacao_data = $this->geral->geragodet(2);
                        $this->QuartoTipos->gerqtpmod($modeloQuartoTipo->quarto_tipo_codigo, $modeloQuartoTipo);
                    }

                    $this->gerquaini();
                    $this->set($info_tela);
                    $this->render('gerquaini');
                }
            });
        }
        catch (Exception $ex) {
            //$mensagem = germencri($this->session->read('empresa_grupo_codigo_ativo')['empresa_codigo'], 99, 3, $produto_codigo, null, null, $ex->getMessage());
            $mensagem = 'Erro no sistema com mensagem não cadastrada.';
            $this->set('retorno', $mensagem);
        }
    }

    // Modificar cadastro de tipo de quarto
    public function gerquamod() {
        try {
            $this->connection->transactional( function() {
                $info_tela = $this->pagesController->montagem_tela('gerquamod');
                if ($this->request->is('get')) {
                    if (isset($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'novo') {
                        $quarto = $this->Quartos->newEntity();
                        $this->set('quarto', $quarto);

                        $this->set('tipoQuartos', $this->geral->gercamdom('gerquatip', $this->session->read('empresa_selecionada')["empresa_codigo"]));
                    }
                    elseif (isset($this->request->params['pass'][0])) {
                        $empresa_codigo = $this->request->params['pass'][0];
                        $quarto_codigo = $this->request->params['pass'][1];
                        $quarto = $this->gerquaexi($empresa_codigo, $quarto_codigo, null)->first();
                        $this->set('quarto', $quarto);
                        $this->set('tipoQuartos', $this->geral->gercamdom('gerquatip', $this->session->read('empresa_selecionada')["empresa_codigo"]));
                    }
                    elseif (isset($this->request->params['?']['del'])) {
                        $del = $this->request->params['?']['del'];
                        $cod = $this->request->params['?']['cod'];
                        $emp = $this->session->read('empresa_selecionada')["empresa_codigo"];

                        $query = $this->connection->newQuery()
                            ->update('quartos')
                            ->set([
                                'excluido' => (int)$del,
                                'modificacao_usuario' => $this->session->read('usuario_codigo')
                                ])
                            ->where([
                                'quarto_codigo' => $this->request->params['?']['cod'],
                                'empresa_codigo' => $emp
                                ])
                            ->execute();

                        $this->gerquaini();
                        $this->render('gerquaini');
                        return;
                    }
                    $this->viewBuilder()->setLayout('ajax');
                }
                elseif ($this->request->is('post')) {
                    $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];

                    $modeloQuarto = $this->Quartos->newEntity();
                    $modeloQuarto->empresa_codigo = $empresa_codigo;
                    $modeloQuarto->quarto_codigo = $this->request->data['gerquacod'] ?? '';
                    $modeloQuarto->quarto_nome = $this->request->data['gerquanom'] ?? '';
                    $modeloQuarto->quarto_tipo_codigo = $this->request->data['gerquatip'] ?? 0;
                    $modeloQuarto->quarto_grupo = $this->request->data['gerquagru'] ?? null;
                    $modeloQuarto->criacao_usuario = $this->request->data['gerquausu'] ?? 0;
                    $modeloQuarto->criacao_data = $this->geral->geragodet(2);
                    $modeloAntigo = $this->Quartos->find()
                            ->select(['empresa_codigo', 'quarto_codigo', 'quarto_nome', 'quarto_tipo_codigo', 'quarto_grupo', 'criacao_usuario'])
                            ->where([
                                'empresa_codigo' => $empresa_codigo,
                                'quarto_codigo' => $modeloQuarto->quarto_codigo
                                ])
                            ->hydrate(false)
                            ->first();
                    if ($modeloQuarto->criacao_usuario == 0) {
                        $modeloQuarto->criacao_usuario = $this->session->read('usuario_codigo');
                        $this->Quartos->gerquamod($modeloQuarto->quarto_codigo, $modeloQuarto);
                    }
                    elseif (comparaEntidades($modeloQuarto, $modeloAntigo) == 0) {
                        $modeloQuarto->modificacao_usuario = $this->session->read('usuario_codigo');
                        $modeloQuarto->modificacao_data = $this->geral->geragodet(2);
                        $this->Quartos->gerquamod($modeloQuarto->quarto_codigo, $modeloQuarto);
                    }

                    $this->gerquaini();
                    $this->set($info_tela);
                    $this->render('gerquaini');
                }/*
                if ($this->request->is('get')) {
                    if ($this->request->params['pass'][0] == 'novo') {
                        $quarto = $this->Quartos->newEntity();
                        $this->set('quarto', $quarto);
                    }
                    else {
                        $empresa_codigo = $this->request->params['pass'][0];
                        $quarto_codigo = $this->request->params['pass'][1];
                        $quarto = $this->Quartos->find()
                            ->select(['quarto_codigo', 'quarto_nome', 'quarto_tipo_codigo', 'excluido', 'quarto_grupo', 'criacao_usuario'])
                            ->where([
                                'empresa_codigo' => $empresa_codigo,
                                'quarto_codigo' => $quarto_codigo
                                ])
                            ->hydrate(false);

                        $this->set('quarto', $quarto->first());

                        $this->set('tipoQuartos', $this->geral->gercamdom('gerquatip', $empresa_codigo));
                    }
                    $this->viewBuilder()->setLayout('ajax');
                }
                elseif ($this->request->is('post')) {

                }*/
            });
        }
        catch (Exception $ex) {
            //$mensagem = germencri($this->session->read('empresa_grupo_codigo_ativo')['empresa_codigo'], 99, 3, $produto_codigo, null, null, $ex->getMessage());
            $mensagem = 'Erro no sistema com mensagem não cadastrada.';
            $this->set('retorno', $mensagem);
        }
    }
}


function comparaEntidades($atual, $original) {
    if (!is_array($atual)) {
        $atual = $atual->toArray();
    }
    //debug($atual);
    //debug($original);
    if ($original !== null) {
        foreach ($atual as $prop => $valor) {
            if (isset($original[$prop])) {
                if ($original[$prop] != $valor) {
                    //echo $prop . ' -> ' . $valor . '<br />';
                    return 0;
                }
            }
        }
        return 1;
    }
    return 0;
}