<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use App\Utility\Util;
use App\Model\Entity\Produto;
use App\Utility\Paginator;
use Cake\Orm\TableRegistry;
use App\Controller\PagesController;

class ProdutosController extends AppController {

    private $pagesController;
    private $produto;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);

        $this->loadModel('ProdutoEmpresas');
        $this->loadModel('ProdutoEmpresaGrupos');
        $this->loadModel('ProdutoVendaPontos');
        $this->loadModel('ProdutoTipos');

        $this->produto = new Produto();
        $this->connection = ConnectionManager::get('default');
        $this->pagesController = new PagesController();
    }

    // Exibir cadastro de produto
    public function procadexi($param_empresa_codigo = null) {
        $info_tela = $this->pagesController->montagem_tela('procadexi');

        $geracever_progrucri = empty($this->geral->geracever('progrucri')) ? 3 : 2;
        $geracever_progrumod = empty($this->geral->geracever('progrumod')) ? 3 : 2;
        $geracever_proempmod = empty($this->geral->geracever('proempmod')) ? 3 : 2;
        $geracever_proempvis = empty($this->geral->geracever('proempvis')) ? 1 : 0;

        // Manual atÃ© que seja feita a alteraÃ§Ã£o na funÃ§Ã£o GERACEVER
        // $geracever_progrucri = 3;
        // $geracever_progrumod = 3;
        // $geracever_proempmod = 3;

        $this->set('geracever_progrucri', $geracever_progrucri);
        $this->set('geracever_progrumod', $geracever_progrumod);
        $this->set('geracever_proempmod', $geracever_proempmod);
        $this->set('geracever_proempvis', $geracever_proempvis);

        $empresa_grupo_codigo = $this->request->data['progruemp'] ?? $this->request->params['pass'][0] ?? null;
        $empresa_codigo = $param_empresa_codigo ?? $this->request->data['proempemp'] ?? $this->request->params['pass'][1] ?? null;
        $produto_codigo = $this->request->data['progrucod'] ?? $this->request->params['pass'][2] ?? null;
        $exibir_excluidos = $this->request->data['proexbexc'] ?? 0;
        if (empty($produto_codigo)) {
            unset($produto_codigo);
        }

        $produto_tipo_codigo = $this->request->data['progrutip'] ?? $this->request->params['pass'][3] ?? null;
        if ($produto_tipo_codigo == 'null') {
            unset($produto_tipo_codigo);
        }

        $nome = $this->request->data['progrunom'] ?? $this->request->params['pass'][4] ?? null;
        if (empty($nome)) {
            unset($nome);
        } else {
            $produto_nome = $nome;
            $nome = '%' . $nome . '%';
        }

        if (isset($produto_tipo_codigo) || isset($produto_codigo) || isset($nome)) {
            if (!(isset($empresa_grupo_codigo))) {
                if (isset($empresa_codigo)) {
                    $empresa_grupo_codigo = $this->connection
                                    ->newQuery()
                                    ->select('empresa_grupo_codigo')
                                    ->from('empresas')
                                    ->where([
                                        'empresa_codigo =' => $empresa_codigo
                                    ])
                                    ->execute()
                                    ->fetchAll('assoc')[0]['empresa_grupo_codigo'];
                } else {
                    $empresa_codigo = $this->session->read('empresa_selecionada')["empresa_codigo"];
                    $empresa_grupo_codigo = $this->session->read('empresa_selecionada')["empresa_grupo_codigo"];

                    /*
                      $this->set('retorno', $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 101, 3,
                      'parametro.produto_codigo')['mensagem']);
                      $this->viewBuilder()->setLayout('ajax');
                      return;
                     */
                }
            }

            $this->set($this->request->data);

            $produto = $this->ProdutoEmpresaGrupos->find()
                    ->select([
                        'empresa_grupo_codigo',
                        'Empresas.empresa_codigo',
                        'produto_codigo',
                        'ProdutoTipos.produto_tipo_nome',
                        'nome',
                        'preco',
                        'ProdutoEmpresaGrupos.excluir'
                    ])
                    ->order(
                            [$this->request->data['ordenacao_coluna'] ?? 'nome' => $this->request->data['ordenacao_tipo'] ?? 'asc']
                    )
                    ->distinct()
                    ->innerJoin(
                    ['ProdutoTipos' => 'produto_tipos'], ['ProdutoTipos.produto_tipo_codigo = ProdutoEmpresaGrupos.produto_tipo_codigo']
            );
            if (isset($empresa_grupo_codigo)) {
                $produto->where(['ProdutoEmpresaGrupos.empresa_grupo_codigo' => $empresa_grupo_codigo]);
            }
            if (isset($empresa_codigo)) {
                $produto
                        ->innerJoin(
                                ['Empresas' => 'empresas'], ['Empresas.empresa_grupo_codigo = ProdutoEmpresaGrupos.empresa_grupo_codigo']
                        )
                        ->innerJoin(
                                ['ProdutoEmpresas' => 'produto_empresas'], ['ProdutoEmpresas.empresa_codigo = Empresas.empresa_codigo']
                        )
                        ->where(['ProdutoEmpresas.empresa_codigo' => $empresa_codigo]);
            }
            if (isset($produto_codigo) && (!(empty($produto_codigo)))) {
                $this->set('codProd', $produto_codigo);
                $produto->where(['ProdutoEmpresaGrupos.produto_codigo' => $produto_codigo]);
            }
            if (isset($produto_tipo_codigo) && (!(empty($produto_tipo_codigo)))) {
                $this->set('tipoProd', $produto_tipo_codigo);
                $produto->where(['ProdutoEmpresaGrupos.produto_tipo_codigo' => $produto_tipo_codigo]);
            }
            if (isset($nome) && (!(empty($nome)))) {
                $this->set('nomeProd', $produto_nome);
                $produto->where(['ProdutoEmpresaGrupos.nome LIKE' => $nome]);
            }
            if ($exibir_excluidos == 0) {
                $this->set('exibeExc', $exibir_excluidos);
                $produto->where(['ProdutoEmpresaGrupos.excluido' => 0]);
            }

            $total = $produto->count();
            $atual = $produto->limit(10)->page($this->request->data['pagina'] ?? 1)->count();

            $produto->limit(10)
                    ->page($this->request->data['pagina'] ?? 1);

            $this->set('listaProdutos', $produto->hydrate(false)->all()->toArray());

            $paginator = new Paginator(10);
            $this->set('paginacao', $paginator->gera_paginacao($total, $this->request->data['pagina'], 'procadexi', $atual));
        }

        $selectProdutoTipo = $this->geral->gercamdom('procadtip', $empresa_grupo_codigo);
        $this->set('produtoTipoLista', $selectProdutoTipo);
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    // Cria ou modifica um cadastro de produto
    public function procadmod() {
        //debug($this->request->data);
        try {
            //$this->set('gerchasol', $this->geral->gerchasol(null, 'pro'));
            $this->set('gerchasol', 1);
            $this->connection->transactional(function() {
                $arr_gertelmon = $this->geral->gertelmon($this->session->read('empresa_grupo_codigo_ativo'), 'procadmod');

                $geracever_progrucri = empty($this->geral->geracever('progrucri')) ? 3 : 2;
                $geracever_progrumod = empty($this->geral->geracever('progrumod')) ? 3 : 2;
                $geracever_proempmod = empty($this->geral->geracever('proempmod')) ? 3 : 2;
                $geracever_proempvis = empty($this->geral->geracever('proempvis')) ? 1 : 0;

                // Manual atÃ© que seja feita a alteraÃ§Ã£o na funÃ§Ã£o GERACEVER
                // $geracever_progrucri = 3;
                // $geracever_progrumod = 3;
                // $geracever_proempmod = 3;

                $this->set('geracever_progrucri', $geracever_progrucri);
                $this->set('geracever_progrumod', $geracever_progrumod);
                $this->set('geracever_proempmod', $geracever_proempmod);
                $this->set('geracever_proempvis', $geracever_proempvis);

                $this->set(Util::germonrot($arr_gertelmon));
                $this->set(Util::germonfor($arr_gertelmon));
                $this->set(Util::germonpro($arr_gertelmon));
                $this->set(Util::germonval($arr_gertelmon));


                if ($this->request->is('get')) {
                    $produto_codigo = null;
                    $empresa_grupo_codigo = null;
                    if (isset($this->request->params['?']['del'])) {
                        $produto_codigo = $this->request->params['?']['pro'] ?? null;
                        if (isset($this->request->params['?']['gru'])) {
                            $empresa_grupo_codigo = $this->request->params['?']['gru'];
                            $del = $this->request->params['?']['del'];

                            $this->ProdutoEmpresaGrupos->query()
                                    ->update()
                                    ->set([
                                        'excluir' => $del,
                                        'modificacao_usuario' => $this->session->read('usuario_codigo')
                                    ])
                                    ->where([
                                        'produto_codigo' => $produto_codigo,
                                        'empresa_grupo_codigo' => $empresa_grupo_codigo
                                    ])
                                    ->execute();

                            if ($del == 1) {
                                $this->ProdutoEmpresas->query()
                                        ->update()
                                        ->set([
                                            'excluir' => 1,
                                            'modificacao_usuario' => $this->session->read('usuario_codigo')
                                        ])
                                        ->where([
                                            'produto_codigo' => $produto_codigo
                                        ])
                                        ->execute();

                                $this->ProdutoVendaPontos->query()
                                        ->update()
                                        ->set([
                                            'excluir' => 1,
                                            'modificacao_usuario' => $this->session->read('usuario_codigo')
                                        ])
                                        ->where([
                                            'produto_codigo' => $produto_codigo,
                                            'empresa_codigo' => $this->session->read('empresa_selecionada')["empresa_codigo"]
                                        ])
                                        ->execute();
                            }
                        } elseif (isset($this->request->params['?']['emp'])) {
                            $empresa_codigo = $this->request->params['?']['emp'];
                            $del = $this->request->params['?']['del'];

                            $this->ProdutoEmpresas->query()
                                    ->update()
                                    ->set([
                                        'excluir' => $del,
                                        'modificacao_usuario' => $this->session->read('usuario_codigo')
                                    ])
                                    ->where([
                                        'produto_codigo' => $produto_codigo,
                                        'empresa_codigo' => $empresa_codigo
                                    ])
                                    ->execute();

                            $this->ProdutoVendaPontos->query()
                                    ->update()
                                    ->set([
                                        'excluir' => 1,
                                        'modificacao_usuario' => $this->session->read('usuario_codigo')
                                    ])
                                    ->where([
                                        'produto_codigo' => $produto_codigo,
                                        'empresa_codigo' => $empresa_codigo
                                    ])
                                    ->execute();

                            if ($del == 0) {
                                $this->ProdutoEmpresaGrupos->query()
                                        ->update()
                                        ->set([
                                            'excluir' => 0,
                                            'modificacao_usuario' => $this->session->read('usuario_codigo')
                                        ])
                                        ->where([
                                            'produto_codigo' => $produto_codigo,
                                            'empresa_grupo_codigo' => $this->session->read('empresa_selecionada')["empresa_grupo_codigo"]
                                        ])
                                        ->execute();
                            }
                        }
                        $this->geral->gerchalib(null, 'pro');
                        $this->set('gerchasol', 1);
                    } else {
                        $empresa_grupo_codigo = $this->request->params['pass'][0] ?? null;
                        $produto_codigo = $this->request->params['pass'][1] ?? null;
                    }

                    if (isset($produto_codigo, $empresa_grupo_codigo)) {

                        // PRODUTO GRUPO SEMPRE EXIBE
                        $produtoGrupo = $this->ProdutoEmpresaGrupos->find('produtoGrupo', [
                                    'produto' => $produto_codigo,
                                    'grupo' => $empresa_grupo_codigo,
                                ])
                                ->hydrate(false)
                                ->first();

                        $selectProdutoTipo = $this->geral->gercamdom('procadtip', $empresa_grupo_codigo);
                        $selectPdv = $this->geral->gercamdom('provenpon', $empresa_grupo_codigo);
                        $selectFatorFixo = $this->geral->gercamdom('profatfix');
                        $selectFatorVariavel = $this->geral->gercamdom('profatvar');

                        $this->set('produtoGrupo', $produtoGrupo);
                        $this->set('produtoPdvLista', $selectPdv);
                        $this->set('produtoTipoLista', $selectProdutoTipo);
                        $this->set('fatorFixoLista', $selectFatorFixo);
                        $this->set('fatorVariavelLista', $selectFatorVariavel);


                        // IF PERMISSÃƒO DE EMPRESA 
                        if ($geracever_proempmod > 0) {

                            $empresa_codigo = $this->session->read('empresa_selecionada')["empresa_codigo"];
                            $produtoEmpresa = $this->ProdutoEmpresas->find('produtoEmpresa', [
                                        'produto' => $produto_codigo,
                                        'empresa' => $empresa_codigo
                                    ])
                                    ->first();
                            if ($produtoEmpresa == false) {
                                $produtoEmpresa = $this->ProdutoEmpresas->newEntity();
                                $produtoEmpresa->empresa_codigo = $empresa_codigo;
                            } else {
                                $produtoPdv = $this->ProdutoVendaPontos->find('produtos', [
                                            'produto' => $produto_codigo,
                                            'empresa' => $empresa_codigo
                                        ])
                                        ->where(['excluido' => 0])
                                        ->toArray();

                                $this->set('produtoPdv', $produtoPdv);
                            }

                            $this->set('produtoEmpresa', $produtoEmpresa);
                        }
                    }
                } elseif ($this->request->is('post')) {
                    if (isset($this->request->data['procadnov'])) {
                        // IF PERMISSÃƒO DE CRIAÃ‡ÃƒO 
                        if ($geracever_progrucri == 3) {
                            $empresa_grupo_codigo = $this->session->read('empresa_selecionada')["empresa_grupo_codigo"];
                            $empresa_codigo = $this->session->read('empresa_selecionada')["empresa_codigo"];

                            $produtoGrupo = TableRegistry::get('ProdutoEmpresaGrupos')->newEntity();
                            $produtoGrupo->empresa_grupo_codigo = $empresa_grupo_codigo;

                            $selectProdutoTipo = $this->geral->gercamdom('procadtip', $empresa_grupo_codigo);
                            $selectPdv = $this->geral->gercamdom('provenpon', $empresa_grupo_codigo);
                            $selectFatorFixo = $this->geral->gercamdom('profatfix');
                            $selectFatorVariavel = $this->geral->gercamdom('profatvar');

                            $this->set('produtoGrupo', $produtoGrupo);
                            $this->set('produtoPdvLista', $selectPdv);
                            $this->set('produtoTipoLista', $selectProdutoTipo);
                            $this->set('fatorFixoLista', $selectFatorFixo);
                            $this->set('fatorVariavelLista', $selectFatorVariavel);

                            // IF PERMISSÃƒO DE EMPRESA 
                            if ($geracever_proempmod == 3) {
                                $produtoEmpresa = TableRegistry::get('ProdutoEmpresas')->newEntity();
                                $produtoEmpresa->empresa_codigo = $empresa_codigo;
                                $this->set('produtoEmpresa', $produtoEmpresa);

                                $produtoPdv = TableRegistry::get('ProdutoEmpresaGrupos')->newEntity();
                                $this->set('produtoPdv', $produtoPdv);
                            }
                        }
                    } else {
                        $produto_codigo = $this->request->data['progrucod'] ?? 0;
                        $retorno['mensagem'] = '';
                        if ($geracever_progrumod == 3) {

                            $tabelaPeg = TableRegistry::get('ProdutoEmpresaGrupos');
                            $codigo_antigo = $this->request->data['procodold'] ?? 0;
                            if (($codigo_antigo != 0) &&
                                    ($codigo_antigo != $produto_codigo)) {
                                $tabelaPeg->query()
                                        ->update()
                                        ->set(['produto_codigo' => $produto_codigo])
                                        ->where([
                                            'produto_codigo' => $codigo_antigo,
                                            'empresa_grupo_codigo' => $this->request->data['progruemp']
                                        ])
                                        ->execute();


                                $empresas = $this->connection->newQuery()
                                        ->select('empresa_codigo')
                                        ->from('empresas')
                                        ->where(['empresa_grupo_codigo' => $this->request->data['progruemp']])
                                        ->execute()
                                        ->fetchAll('assoc');
                                foreach ($empresas as $emp) {
                                    $sql = $this->connection->newQuery()
                                            ->update('produto_empresas')
                                            ->set(['produto_codigo' => $produto_codigo])
                                            ->where([
                                                'empresa_codigo' => $emp['empresa_codigo'],
                                                'produto_codigo' => $codigo_antigo
                                            ])
                                            ->execute();


                                    $sql = $this->connection->newQuery()
                                            ->update('produto_venda_pontos')
                                            ->set(['produto_codigo' => $produto_codigo])
                                            ->where([
                                                'empresa_codigo' => $emp['empresa_codigo'],
                                                'produto_codigo' => $codigo_antigo
                                            ])
                                            ->execute();
                                }
                            }

                            //$modeloProdutoGrupo = $tabelaPeg->find('produtoGrupo', [
                            //debug($modeloProdutoGrupo); die();

                            $modeloProdutoGrupo = $tabelaPeg->newEntity();
                            $modeloProdutoGrupo->produto_codigo = $this->request->data['progrucod'] ?? $this->request->data['procodold'] ?? 0;
                            $modeloProdutoGrupo->empresa_grupo_codigo = $this->request->data['progruemp'] ?? 0;

                            $modeloGrupoAntigo = $tabelaPeg->find('produtoGrupo', [
                                        'produto' => $modeloProdutoGrupo->produto_codigo,
                                        'grupo' => $modeloProdutoGrupo->empresa_grupo_codigo
                                    ])
                                    ->first();

                            $modeloProdutoGrupo->nome = $this->request->data['progrunom'] ?? '';
                            $modeloProdutoGrupo->descricao = $this->request->data['progrudsc'] ?? '';
                            $modeloProdutoGrupo->produto_tipo_codigo = $this->request->data['progrutip'] ?? '';
                            $modeloProdutoGrupo->adicional = $this->request->data['progruadi'] ?? 0;
                            $modeloProdutoGrupo->preco = $this->request->data['progruprc'] ?? 0;
                            $modeloProdutoGrupo->variavel_fator_codigo = $this->request->data['progruftv'] ?? 0;
                            $modeloProdutoGrupo->fixo_fator_codigo = $this->request->data['progruftf'] ?? 0;
                            $modeloProdutoGrupo->servico_taxa_incide = $this->request->data['progrutxs'] ?? 0;
                            $modeloProdutoGrupo->contabil_tipo = $this->request->data['progrutpc'] ?? 0;
                            $modeloProdutoGrupo->excluido = 0;
                            $modeloProdutoGrupo->criacao_usuario = $this->request->data['progruusu'] ?? 0;
                            $modeloProdutoGrupo->criacao_data = $this->geral->geragodet(2);

                            if ($modeloProdutoGrupo->criacao_usuario == 0) {
                                $modeloProdutoGrupo->criacao_usuario = $this->session->read('usuario_codigo');
                                $modeloProdutoGrupo->modificacao_usuario = 0;
                            }

                            if ($modeloProdutoGrupo->produto_codigo == 0) {
                                $modeloProdutoGrupo->produto_codigo = $this->ProdutoEmpresaGrupos->procadprx($modeloProdutoGrupo->empresa_grupo_codigo);
                                $produto_codigo = $this->ProdutoEmpresaGrupos->procadmod($modeloProdutoGrupo->produto_codigo, $modeloProdutoGrupo);
                            } elseif (comparaEntidades($modeloProdutoGrupo, $modeloGrupoAntigo) == 0) {
                                if ($modeloProdutoGrupo->criacao_usuario != 0) {
                                    $modeloProdutoGrupo->modificacao_usuario = $this->session->read('usuario_codigo');
                                    $modeloProdutoGrupo->modificacao_data = $this->geral->geragodet(2);
                                }
                                $produto_codigo = $this->ProdutoEmpresaGrupos->procadmod($modeloProdutoGrupo->produto_codigo, $modeloProdutoGrupo);
                            }
                        }
                        if ($geracever_proempmod == 3) {
                            if (!(empty($this->request->data['proempcod'])) || !(empty($this->request->data['proempdsc']))) {
                                $tabelaPe = TableRegistry::get('ProdutoEmpresas');
                                $modeloProdutoEmpresa = $tabelaPe->newEntity();

                                $modeloProdutoEmpresa->empresa_codigo = $this->request->data['proempemp'] ?? 0;
                                $modeloProdutoEmpresa->produto_codigo = $produto_codigo ?? $this->request->data['progrucod'];

                                $modeloEmpresaAntigo = $tabelaPe->find('produtoEmpresa', [
                                            'produto' => $modeloProdutoEmpresa->produto_codigo,
                                            'empresa' => $modeloProdutoEmpresa->empresa_codigo
                                        ])
                                        ->first();

                                $modeloProdutoEmpresa->descricao = $this->request->data['proempdsc'] ?? "";
                                $modeloProdutoEmpresa->preco = $this->request->data['proempprc'] ?? 0;
                                $modeloProdutoEmpresa->servico_taxa_incide = $this->request->data['proemptxs'] ?? 0;
                                $modeloProdutoEmpresa->excluido = 0;
                                $modeloProdutoEmpresa->criacao_usuario = $this->request->data['proempusu'] ?? 0;
                                $modeloProdutoEmpresa->criacao_data = $this->geral->geragodet(2);
                                if ($modeloProdutoEmpresa->criacao_usuario == 0) {
                                    $modeloProdutoEmpresa->criacao_usuario = $this->session->read('usuario_codigo');
                                    $modeloProdutoEmpresa->modificacao_usuario = 0;
                                    $this->ProdutoEmpresas->procadmod($modeloProdutoEmpresa->produto_codigo, $modeloProdutoEmpresa);
                                } elseif (comparaEntidades($modeloProdutoEmpresa, $modeloEmpresaAntigo) == 0) {
                                    $modeloProdutoEmpresa->modificacao_usuario = $this->session->read('usuario_codigo');
                                    $this->ProdutoEmpresas->procadmod($modeloProdutoEmpresa->produto_codigo, $modeloProdutoEmpresa);
                                }

                                $i = -1;
                                $pdv = array();
                                if (isset($this->request->data['propdvpdv'])) {
                                    foreach ($this->request->data['propdvpdv'] as $propdv) {
                                        $i++;
                                        $modeloProdutoPdv = TableRegistry::get('ProdutoEmpresas')->newEntity();
                                        $modeloProdutoPdv->venda_ponto_codigo = $this->request->data['propdvpdv'][$i];
                                        if ($modeloProdutoPdv->venda_ponto_codigo != "0") {
                                            $modeloProdutoPdv->empresa_codigo = $modeloProdutoEmpresa->empresa_codigo;
                                            $modeloProdutoPdv->produto_codigo = $produto_codigo ?? $this->request->data['progrucod'];

                                            $modeloPdvAntigo = $this->ProdutoVendaPontos->find('produtos', [
                                                        'produto' => $modeloProdutoPdv->produto_codigo,
                                                        'empresa' => $modeloProdutoPdv->empresa_codigo
                                                    ])
                                                    ->first();

                                            $modeloProdutoPdv->preco = $this->request->data['propdvprc'][$i];
                                            $modeloProdutoPdv->servico_taxa_incide = $this->request->data['propdvtxs'][$i] ?? 0;
                                            $modeloProdutoPdv->excluido = 0;
                                            $modeloProdutoPdv->criacao_usuario = $this->request->data['propdvusu'][$i] ?? 0;
                                            $modeloProdutoPdv->criacao_data = $this->geral->geragodet(2);
                                            if ($modeloProdutoPdv->criacao_usuario == 0) {
                                                $modeloProdutoPdv->criacao_usuario = $this->session->read('usuario_codigo');
                                                $modeloProdutoPdv->modificacao_usuario = 0;
                                                $this->ProdutoVendaPontos->procadmod($modeloProdutoPdv->produto_codigo, $modeloProdutoPdv);
                                            } elseif (comparaEntidades($modeloProdutoPdv, $modeloPdvAntigo) == 0) {
                                                $modeloProdutoPdv->modificacao_usuario = $this->session->read('usuario_codigo');
                                                $modeloProdutoPdv->modificacao_data = $this->geral->geragodet(2);
                                                $this->ProdutoVendaPontos->procadmod($modeloProdutoPdv->produto_codigo, $modeloProdutoPdv);
                                            }

                                            $pdv[] = $modeloProdutoPdv->venda_ponto_codigo;
                                        }
                                    }
                                }

                                $update = $this->ProdutoVendaPontos->query()
                                        ->update()
                                        ->set([
                                            'excluido' => 1,
                                            'modificacao_usuario' => $this->session->read('usuario_codigo')
                                        ])
                                        ->where(['empresa_codigo' => $modeloProdutoEmpresa->empresa_codigo]);
                                foreach ($pdv as $i) {
                                    $update->where(['venda_ponto_codigo <> ' => $i]);
                                }
                                $update->execute();
                            }
                        }

                        // PRODUTO GRUPO SEMPRE EXIBE
                        $empresa_grupo_codigo = $this->request->data['progruemp'];
                        $produtoGrupo = $this->ProdutoEmpresaGrupos->find('produtoGrupo', [
                                    'produto' => $produto_codigo,
                                    'grupo' => $empresa_grupo_codigo,
                                ])
                                ->first();

                        $selectProdutoTipo = $this->geral->gercamdom('procadtip', $empresa_grupo_codigo);
                        $selectPdv = $this->geral->gercamdom('provenpon', $empresa_grupo_codigo);
                        $selectFatorFixo = $this->geral->gercamdom('profatfix');
                        $selectFatorVariavel = $this->geral->gercamdom('profatvar');

                        $this->set('produtoGrupo', $produtoGrupo);
                        $this->set('produtoPdvLista', $selectPdv);
                        $this->set('produtoTipoLista', $selectProdutoTipo);
                        $this->set('fatorFixoLista', $selectFatorFixo);
                        $this->set('fatorVariavelLista', $selectFatorVariavel);


                        // IF PERMISSÃƒO DE EMPRESA 
                        if ($geracever_proempmod > 0) {

                            $empresa_codigo = $this->session->read('empresa_selecionada')["empresa_codigo"];
                            $produtoEmpresa = $this->ProdutoEmpresas->find('produtoEmpresa', [
                                        'produto' => $produto_codigo,
                                        'empresa' => $empresa_codigo
                                    ])
                                    ->first();
                            if ($produtoEmpresa == false) {
                                $produtoEmpresa = $this->ProdutoEmpresas->newEntity();
                                $produtoEmpresa->empresa_codigo = $empresa_codigo;
                            } else {
                                $produtoPdv = $this->ProdutoVendaPontos->find('produtos', [
                                            'produto' => $produto_codigo,
                                            'empresa' => $empresa_codigo
                                        ])
                                        ->where(['excluido' => 0])
                                        ->toArray();

                                $this->set('produtoPdv', $produtoPdv);
                            }

                            $this->set('produtoEmpresa', $produtoEmpresa);
                        }

                        $this->geral->gerchalib(null, 'pro');
                        $this->set('gerchasol', 1);
                        $this->set('retorno', $this->geral->germencri($this->session->read('empresa_selecionada')['empresa_codigo'], 100, 3, $produto_codigo));
                    }
                }
                $this->viewBuilder()->setLayout('ajax');
            });
        } catch (Exception $ex) {
            $mensagem = germencri($this->session->read('empresa_grupo_codigo_ativo')['empresa_codigo'], 99, 3, $produto_codigo, null, null, $ex->getMessage());
            $this->set('retorno', $mensagem);
        }
    }

    public function prolisexi() {
        $info_tela = $this->pagesController->montagem_tela('prolisexi');
        $historico_busca = $this->pagesController->consomeHistoricoTela('produtos/prolisexi');
        $this->request->data = array_merge($this->request->data, $historico_busca);
        if ($this->request->is('post')) {
            $lista_tecnica_itens = $this->produto->prolisexi($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['conprocod']);

            if (sizeof($lista_tecnica_itens) == 0)
                array_push($lista_tecnica_itens, array('produto_codigo' => '', 'qtd' => '1', 'fator_codigo' => '', 'filho_produto_nome' => '', 'excluido' => 0));

            $this->set('lista_tecnica_itens', $lista_tecnica_itens);
            $this->set($this->request->data);
        } else {
            $this->set('lista_tecnica_itens', array());
        }

        $this->set('unidades_medida', $this->geral->gercamdom('profatvar'));
        $this->set($info_tela);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function prolismod() {

        if ($this->request->is('post')) {
            $retorno_resveimod = $this->produto->prolismod($this->session->read('empresa_selecionada')['empresa_codigo'], $this->request->data['conprocod'], $this->request->data['proprofil'], $this->request->data['qtd'], $this->request->data['prounimed'], $this->request->data['prolisexc']);

            $this->session->write('retorno_footer', $retorno_resveimod['mensagem']['mensagem']);

            //Redireciona para a listagem 
            $this->request->data['back_page'] = 'produtos/prolisexi';
            $this->geral->gerpagsal('produtos/prolisexi', $this->request->data);
            $this->setAction('prolisexi');
        } else {

            $this->autoRender = false;
        }
    }

}

function comparaEntidades($atual, $original) {
    if (!is_array($atual)) {
        $atual = $atual->toArray();
    }

    if ($original !== null) {
        foreach ($atual as $prop => $valor) {
            //echo $prop . ' -> ' . $valor . '<br />';
            if (isset($original[$prop])) {
                if ($original[$prop] != $valor) {
                    return 0;
                }
            }
        }
        return 1;
    }
    return 0;
}

?>
