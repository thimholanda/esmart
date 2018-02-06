<?php

namespace App\Model\Entity;

use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use App\Utility\Util;
use Cake\ElasticSearch\TypeRegistry;
use App\Model\Entity\Reserva;
use PHPMailer;

class Geral extends AbstractModel {

//Função que adiciona um item na pilha de histórico de páginas
    function gerpagsal($back_page, $form_data) {
        if (!$this->session->check('historico')) {
            $historico = array();
            $historico[$back_page] = $form_data;
            $this->session->write('historico', $historico);
        } else {
            $historico = $this->session->read('historico');
            $historico[$back_page] = $form_data;
            $this->session->delete('historico');
            $this->session->write('historico', $historico);
        }
    }

    /*
     * Determina a data atual, e seta o timezone caso não esteja preenchida
     */

    public function geratudat() {
        date_default_timezone_set($this->session->read("empresa_selecionada")['horario_fuso']);
        return date("d/m/Y");
    }

    public function gertelmon($empresa_grupo_codigo, $tela_codigo, $idioma = null) {
        if (empty($empresa_grupo_codigo))
            $empresa_grupo_codigo = ($this->session->read('empresa_grupo_codigo_ativo') ?? "0");

        if (empty($idioma) && $this->session->check('usuario_idioma'))
            $idioma = $this->session->read('usuario_idioma');
        else
            $idioma = 'pt';

        $usuario_criterio = "";
        if ($this->session->check('usuario_codigo')) {
            $parametros['usuario_codigo'] = $this->session->read('usuario_codigo');
            $usuario_criterio = " AND pv.usuario_codigo = :usuario_codigo ";
        }

        $parametros['idioma'] = $idioma;
        $parametros['tela_codigo'] = $tela_codigo;

        $par_empresa_grupo_codigo = "";
        if ($empresa_grupo_codigo != "0") {
            $par_empresa_grupo_codigo = " AND te.empresa_grupo_codigo= :empresa_grupo_codigo ";
            $parametros['empresa_grupo_codigo'] = $empresa_grupo_codigo;
        }

        $result = $this->connection->execute("SELECT * FROM tela_elementos te INNER JOIN telas t ON t.tela_codigo=te.tela_codigo LEFT JOIN padrao_valores pv ON pv.elemento_codigo = te.elemento_codigo AND "
                        . "pv.tela_codigo=te.tela_codigo $usuario_criterio INNER JOIN elementos e ON te.elemento_codigo=e.elemento_codigo LEFT JOIN elemento_campos ec ON "
                        . "ec.elemento_codigo = te.elemento_codigo LEFT JOIN elemento_idiomas ei ON  ei.elemento_codigo = te.elemento_codigo AND ei.idioma= :idioma WHERE te.tela_codigo= :tela_codigo"
                        . " $par_empresa_grupo_codigo", $parametros)->fetchAll("assoc");
        return $result;
    }

    /*
     * Atribuir dominio de valores a um campo
     */

    public function gercamdom($elemento_codigo, $dominio_filtro_valor = null, $dominio_filtro_operador = null) {
        $elemento = $this->connection->execute("SELECT dominio_tabela_nome, dominio_valor_campo_nome, dominio_rotulo_campo_nome, dominio_filtro_campo_nome, "
                        . " dominio_ordenacao_campo_nome FROM elemento_campos WHERE elemento_codigo = :elemento_codigo", ['elemento_codigo' => $elemento_codigo])->fetchAll("assoc")[0];



        $dominio_filtro_criterio = "";

        if ($dominio_filtro_valor != null) {
            if ($dominio_filtro_operador == null)
                $dominio_filtro_operador = " = ";

            $dominio_filtro_criterio .= " WHERE ";
            $filtro_valores = explode(",", $dominio_filtro_valor);
            foreach ($filtro_valores as $valor)
                $dominio_filtro_criterio .= $elemento['dominio_filtro_campo_nome'] . " " . $dominio_filtro_operador . " "
                        . " '" . $valor . "' AND ";
            $dominio_filtro_criterio .= " 1 ";
        }

        $dominio = $this->connection->execute("SELECT DISTINCT(" . $elemento['dominio_valor_campo_nome'] . ")  as valor, " . $elemento['dominio_rotulo_campo_nome'] . " as rotulo"
                        . " FROM " . $elemento['dominio_tabela_nome'] . " " . $dominio_filtro_criterio . " ORDER BY " . $elemento['dominio_ordenacao_campo_nome'] . "")->fetchAll("assoc");

        return $dominio;
    }

    function gercampro($arr_gertelmon, $elemento_codigo) {
        foreach ($arr_gertelmon AS $ky => $vl) {
            if ($vl['elemento_codigo'] == $elemento_codigo) {
                $elemento = $vl;
                break;
            }
        }
        $retorno = "";
        switch ($elemento['campo_propriedade']) {
            case 1:
                $retorno = " style='display: none;' ";
                break;
            case 2:
                $retorno = " readonly ";
                break;
            case 3:
                $retorno = " ";
                break;
            case 4:
//Se o campo já tiver um validador, a decisão sobre o required é feita no data validation pelo plugin
                if ($elemento['campo_validador'] == '')
                    $retorno = "data-validation='required'";
                elseif ($elemento['campo_validador'] == '')
                    $retorno = "";
                break;
        }
        return $retorno;
    }

    function gercamrot($arr_gertelmon, $elemento_codigo) {
        $arr_find = "";
        foreach ($arr_gertelmon AS $ky => $vl) {
            if ($vl['elemento_codigo'] == $elemento_codigo) {
                $arr_find = $vl['elemento_rotulo'];
                break;
            }
        }

        return $arr_find;
    }

    function gercamrot2($lista_campos) {

        $rotulos = $this->connection->execute("SELECT campo_nome, elemento_rotulo FROM elemento_idiomas ei"
                        . " INNER JOIN elemento_campos ec ON ec.elemento_codigo = ei.elemento_codigo WHERE "
                        . "ei.idioma = :idioma AND ec.campo_nome IN (:lista_campos)", ['idioma' => $this->session->read('usuario_idioma'), 'lista_campos' => $lista_campos])->fetchAll('assoc');
        return $rotulos;
    }

    function gercamfor($arr_gertelmon, $elemento_codigo) {
        $arr_find = "";

        foreach ($arr_gertelmon AS $ky => $vl) {
            if ($vl['elemento_codigo'] == $elemento_codigo) {
                $arr_find = $vl['campo_formato'];
                break;
            }
        }
        return $arr_find;
    }

    function gercamval($arr_gertelmon, $elemento_codigo) {
        $arr_find = "";

        foreach ($arr_gertelmon AS $ky => $vl) {
            if ($vl['elemento_codigo'] == $elemento_codigo) {
                $arr_find = $vl['campo_validador'];
                break;
            }
        }
        return $arr_find;
    }

    public function mk_select($array_sel, $id, $name, $sel_value, $onclick = null, $opcao_vazia = false, $opcao_cancelada = false) {
        if (empty($onclick))
            $onclick = "";
        else
            $onclick = " onclick=\"$onclick\"";

        $str_select = "<select class='form-control' size=1 id=\"$id\" name=\"$name\"$onclick>\n";
        if ($opcao_vazia)
            $str_select .= "<option vale=''></option>\n";

        foreach ($array_sel AS $ky => $vl) {
            if (!$opcao_cancelada) {
                $str_select = $str_select . "\t<option value=\"" . $vl['valor'] . "\"";
                if ($vl['valor'] == $sel_value)
                    $str_select = $str_select . " selected";
                $str_select = $str_select . ">" . $vl['rotulo'] . "</option>\n";
            }else {
                if ($vl['valor'] != $opcao_cancelada) {
                    $str_select = $str_select . "\t<option value=\"" . $vl['valor'] . "\"";
                    if ($vl['valor'] == $sel_value)
                        $str_select = $str_select . " selected";
                    $str_select = $str_select . ">" . $vl['rotulo'] . "</option>\n";
                }
            }
        }
        $str_select = $str_select . "</select>\n";
        return $str_select;
    }

    function gerpadsal($usuario_codigo, $tela_codigo, $elementos) {

        $padrao_valores_table = TableRegistry::get("PadraoValores");

        $this->connection->begin();
        $padrao_valores_table->deleteAll(['usuario_codigo' => $usuario_codigo, 'tela_codigo' => $tela_codigo]);

        try {
            foreach ($elementos as $elemento_codigo => $padrao_valor) {
                if ($padrao_valor != "") {
                    $valor_padrao_elemento = $padrao_valor;

                    if (is_array($padrao_valor)) {
                        $valor_padrao_elemento = "";
                        foreach ($padrao_valor as $value) {
                            $valor_padrao_elemento .= $value . "|";
                        }

                        if (strlen($valor_padrao_elemento) > 0)
                            $valor_padrao_elemento = substr($valor_padrao_elemento, 0, -1);
                    }
                    $padrao_valor_entity = $padrao_valores_table->newEntity();
                    $padrao_valor_entity->usuario_codigo = $usuario_codigo;
                    $padrao_valor_entity->tela_codigo = $tela_codigo;
                    $padrao_valor_entity->elemento_codigo = $elemento_codigo;
                    $padrao_valor_entity->padrao_valor = $valor_padrao_elemento;
                    $padrao_valor_entity->criacao_usuario = $usuario_codigo;
                    $padrao_valor_entity->criacao_data = $this->geragodet(2);
                    $padrao_valores_table->save($padrao_valor_entity);
                }
            }
        } catch (\Exception $e) {
            var_dump($e);
            $this->connection->rollback();
            return 0;
        }

        $this->connection->commit();
    }

    public function geracever($nome_funcao) {
        $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];
        $empresa_grupo_codigo = $this->session->read('empresa_selecionada')['empresa_grupo_codigo'];
        $acesso_perfil_codigo = $this->session->read('acesso_perfil_codigo');

        $arr_ac = array();
        $result = $this->connection->execute("SELECT objeto_codigo,acesso_tipo FROM funcao_acessos WHERE funcao_codigo
        like :nome_funcao", ['nome_funcao' => $nome_funcao])->fetchAll("assoc");

        if (is_array($result))
            foreach ($result AS $ky => $row) {
                $arr_ac[$row['objeto_codigo']] = array($row['acesso_tipo'], 0);
                $result2 = $this->connection->execute("SELECT objeto_codigo, acesso_tipo FROM acesso_controles WHERE
           empresa_grupo_codigo= :empresa_grupo_codigo AND
           acesso_perfil_codigo= :acesso_perfil_codigo AND
           empresa_codigo= :empresa_codigo  AND
           objeto_codigo='" . $row['objeto_codigo'] . "'", ['empresa_codigo' => $empresa_codigo, 'empresa_grupo_codigo' => $empresa_grupo_codigo, 'acesso_perfil_codigo' => $acesso_perfil_codigo])->fetchAll("assoc");
                if (is_array($result2))
                    foreach ($result2 AS $ky2 => $row2)
                        $arr_ac[$row2['objeto_codigo']][1] = $row2['acesso_tipo'];
            }

        $i_acesso_funcao = 0;
        $i_acesso_controle = 1;

        $acesso_ = FALSE;
        foreach ($arr_ac as $key => $value) {
            $acesso_ = ($arr_ac[$key][$i_acesso_controle] >= $arr_ac[$key][$i_acesso_funcao]);
            if (!$acesso_) {
                break;
            }
        }

        $stRR = "";
        if (!$acesso_) {
            $stRR = $this->germencri($empresa_codigo, 1, 1, $nome_funcao, $empresa_codigo)['mensagem'];
        }
        return $stRR;
    }

    public function gerdommot($params) {

        $resultado = $this->geraceseq('motivo', array('motivo_codigo', 'motivo_nome'), $params);

        $resultado_final = array();
        if ($resultado != '') {
            for ($i = 0; $i < sizeof($resultado); $i++) {
                if ($resultado[$i]['motivo_codigo'] != "") {
                    $resultado_final[$i]['valor'] = $resultado[$i]['motivo_codigo'];
                    $resultado_final[$i]['rotulo'] = $resultado[$i]['motivo_nome'];
                }
            }
        }
        return $resultado_final;
    }

    /*
     * MIGRADA
     */

    public function findByGrupoCodigo($empresa_grupo_codigo) {
        $empresa = TableRegistry::get('Empresas');
        $query = $empresa->find('all', array('contain' => array('Paises'),
            'conditions' => array('Empresas.empresa_grupo_codigo' => $empresa_grupo_codigo)));
        return $query;
    }

    function gerdocdis($documento_tipo_codigo, $documento_status_codigo) {
        $var_gerqtdind = $this->connection->execute("SELECT  reserva_disponivel, check_in_disponivel, check_out_disponivel,
        camareira_disponivel, manutencao_disponivel 
        FROM documento_status WHERE documento_tipo_codigo=:documento_tipo_codigo AND documento_status_codigo=:documento_status_codigo", ['documento_tipo_codigo' => $documento_tipo_codigo, 'documento_status_codigo' => $documento_status_codigo])->fetchAll("assoc");
        return $var_gerqtdind[0];
    }

    public function germencri($empresa_codigo, $mensagem_codigo, $exibicao_tipo = 1, $texto_1 = "", $texto_2 = "", $texto_3 = "", $excecao_mensagem = null, $idioma = null, $usuario_codigo = null) {

        if (empty($idioma))
            $idioma = $this->session->read("usuario_idioma") ?? 'pt';

        if ($usuario_codigo == null)
            $usuario_codigo = $this->session->read('usuario_codigo');

        if (empty($exibicao_tipo))
            $exibicao_tipo = 1;

        $rowLine = $this->connection->execute("SELECT MC.*,MI.mensagem_texto, MI.botao_1_texto, MI.botao_2_texto, MI.titulo_texto FROM mensagem_cadastros MC JOIN
        mensagem_idiomas MI ON(MI.mensagem_codigo=MC.mensagem_codigo AND MI.idioma= :idioma)
        WHERE MC.mensagem_codigo= :mensagem_codigo", ['idioma' => $idioma, 'mensagem_codigo' => $mensagem_codigo])->fetchAll("assoc");

        $mensagem = "";
        $botao_1_texto = "";
        $botao_2_texto = "";
        $titulo_texto = "";

        if (count($rowLine) > 0) {
            $mensagem = $rowLine[0]["mensagem_texto"];
            $botao_1_texto = $rowLine[0]["botao_1_texto"];
            $botao_2_texto = $rowLine[0]["botao_2_texto"];
            $titulo_texto = $rowLine[0]["titulo_texto"];
        } else
            $mensagem = "alert('Mensagem não cadastrada para o idioma " . $idioma . " e código da mensagem " . $mensagem_codigo . "');\n";

        $mensagem = str_replace("{1}", $texto_1, $mensagem);
        $mensagem = str_replace("{2}", $texto_2, $mensagem);
        $mensagem = str_replace("{3}", $texto_3, $mensagem);

        if ($exibicao_tipo == 2 || $exibicao_tipo == 3) {
            if (!$this->session->check('usuario_codigo')) {
                $usuario_codigo = "0";
            } else {
                if (empty($_SESSION["usuario_codigo"]))
                    $usuario_codigo = "0";
                else
                    $usuario_codigo = $this->session->read("usuario_codigo");
            }
            $agora = $this->geragodet(3);

            $this->connection->execute("INSERT INTO mensagens (empresa_codigo, exibicao_data, mensagem_codigo,usuario_codigo,idioma,mensagem_texto,
            texto_1,texto_2,texto_3,exibicao_tipo, excecao_mensagem) VALUES(:empresa_codigo,:agora,:mensagem_codigo,:usuario_codigo,
            :idioma,:mensagem,:texto_1,:texto_2,:texto_3,:exibicao_tipo, :excecao_mensagem)", ['empresa_codigo' => $empresa_codigo, 'agora' => $agora, 'mensagem_codigo' => $mensagem_codigo,
                'usuario_codigo' => $usuario_codigo, 'idioma' => $idioma, 'mensagem' => $mensagem, 'texto_1' => $texto_1, 'texto_2' => $texto_2,
                'texto_3' => $texto_3, 'exibicao_tipo' => $exibicao_tipo, 'excecao_mensagem' => $excecao_mensagem]);
        }

        $retorno['exibicao_data'] = $agora ?? null;
        $retorno['mensagem'] = $mensagem;
        $retorno['botao_1_texto'] = $botao_1_texto;
        $retorno['botao_2_texto'] = $botao_2_texto;
        $retorno['titulo_texto'] = $titulo_texto;

        return $retorno;
    }

    function gerchalib($empresa_codigo, $objeto_codigo, $objeto_codigo_2 = null) {

        if ($objeto_codigo_2 != null)
            $this->gerchalib($empresa_codigo, $objeto_codigo_2);

        if (empty($empresa_codigo))
            $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];

        $update_r = $this->connection->execute("UPDATE chaves SET chave='0001-01-01 00:00:00.000000' WHERE empresa_codigo= :empresa_codigo  AND objeto_codigo= :objeto_codigo ", ['empresa_codigo' => $empresa_codigo, 'objeto_codigo' => $objeto_codigo]);

        if ($update_r->rowCount() > 0)
            return 1;
        else
            return 0;
    }

    /* function gercharlib_proc($empresa_codigo, $objeto_codigo) {
      $this->connection->execute("CALL gercharlib($empresa_codigo, $objeto_codigo, @result);");
      $result = $this->connection->execute("SELECT @result;");
      return $result;
      } */

    function gerchasol($empresa_codigo = null, $objeto_codigo = null, $usuario_codigo = null) {
        if (empty($empresa_codigo))
            $empresa_codigo = $this->session->read('empresa_selecionada')['empresa_codigo'];

        if (empty($usuario_codigo))
            $usuario_codigo = $this->session->read('usuario_codigo');

        $sucesso = 0;

        $chave_dados = $this->connection->execute("SELECT chave, tentativa_qtd, tentativa_tempo, expiracao_tempo,
       usuario_codigo FROM chaves WHERE empresa_codigo=:empresa_codigo
       AND objeto_codigo=:objeto_codigo", ['empresa_codigo' => $empresa_codigo, 'objeto_codigo' => $objeto_codigo])->fetchAll("assoc");

        for ($i = 1; $i <= ($chave_dados[0]["tentativa_qtd"]); $i++) {
            $atualizar = $this->connection->execute("UPDATE chaves SET chave='" . $this->geragodet(3) . "', usuario_codigo=$usuario_codigo WHERE empresa_codigo=$empresa_codigo
          AND objeto_codigo='$objeto_codigo' AND '" . $this->geragodet(3) . "'>
          DATE_ADD(chave, INTERVAL expiracao_tempo*1000 MICROSECOND);");

            if ($atualizar->rowCount() > 0) {
                $sucesso = 1;
                if ($chave_dados[0]["chave"] != "0001-01-01 00:00:00.000000")
                    $this->germencri($empresa_codigo, 6, 2, $objeto_codigo, $chave_dados[0]["chave"], $chave_dados[0]["usuario_codigo"]);
                break;
            } else {
                if ($i < $chave_dados[0]["tentativa_qtd"])
                    usleep($chave_dados[0]["tentativa_tempo"] * 1000);
                else {
                    $sucesso = 0;
                    $this->germencri($empresa_codigo, 7, 3, $objeto_codigo, ($chave_dados[0]["chave"] ?? ""), ($chave_dados[0]["usuario_codigo"] ?? ""));
                }
            }
        }
        return $sucesso;
    }

    /*
     * M�todo que verifica o login do usu�rio
     */

    public function gerlogin($usuario_login, $usuario_senha, $idioma) {
        //$usuario = $this->connection->execute("SELECT * FROM usuarios WHERE usuario_login = :usuario_login AND usuario_senha=password(:usuario_senha)", ['usuario_login' => $usuario_login, 'usuario_senha' => $usuario_senha])->fetchAll('assoc');
        $usuario = $this->connection->execute("SELECT *, password(:usuario_senha) as senha FROM usuarios u INNER JOIN empresas e ON u.empresa_grupo_codigo = e.empresa_grupo_codigo WHERE usuario_login = :usuario_login", ['usuario_login' => $usuario_login, 'usuario_senha' => $usuario_senha])->fetchAll('assoc');

        if ($usuario != null) {
            $usuario = $usuario[0];
            if ($usuario['login_tentativa_qtd'] < 3) {
                if ($usuario['senha'] == $usuario['usuario_senha']) {
                    $agora = $this->geragodet(2, $usuario['empresa_codigo']);
                    if (Util::comparaDatas($usuario['senha_expiracao_data'], $agora) == 1) {
                        //Atualiza a quantidade de tentativas
                        $this->connection->execute("UPDATE usuarios SET login_tentativa_qtd=0 WHERE usuario_login=:usuario_login AND empresa_grupo_codigo=:empresa_grupo_codigo", ['empresa_grupo_codigo' => $usuario['empresa_grupo_codigo'], 'usuario_login' => $usuario['usuario_login']]);
                        $this->session->write('logado', TRUE);
                        $this->session->write('usuario_nome', $usuario['nome']);
                        $this->session->write('usuario_codigo', $usuario['usuario_codigo']);
                        $this->session->write('usuario_login', $usuario_login);
                        $this->session->write('usuario_idioma', $idioma);
                        $this->session->write('acesso_perfil_codigo', $usuario['acesso_perfil_codigo']);
                        $this->session->write('venda_canal_codigo', $usuario['venda_canal_codigo']);
                        $this->session->write('empresa_grupo_codigo_ativo', $usuario["empresa_grupo_codigo"]);
                        $this->session->write('empresa_grupo_codigo', $usuario["empresa_grupo_codigo"]);
                        $this->session->write('session_id', md5(uniqid(rand(), true)));

                        //Carrega as urls que esse usuário tem permissão
                        $acesso = $this->gerauudet($usuario["empresa_grupo_codigo"], $usuario['acesso_perfil_codigo']);
                        $this->session->write('acesso_url', $acesso);

                        $empresa_grupo_dados = $this->connection->execute("SELECT * FROM empresa_grupos WHERE empresa_grupo_codigo = :empresa_grupo_codigo", ['empresa_grupo_codigo' => $usuario["empresa_grupo_codigo"]])->fetchAll("assoc");

                        foreach ($empresa_grupo_dados as $empresa_grupo_dado) {
                            $this->session->write('empresa_grupo_nome', $empresa_grupo_dado['empresa_grupo_nome']);
                            $this->session->write('empresa_grupo_cpf_obrigatorio', $empresa_grupo_dado['cpf_obrigatorio']);
                            $this->session->write('empresa_grupo_menor_documento_obrigatorio', $empresa_grupo_dado['menor_documento_obrigatorio']);
                            $this->session->write('cliente_univoco_campo', $empresa_grupo_dado['cliente_univoco_campo']);
                        }

                        $empresa_dados = $this->connection->execute("SELECT e.*, p.pais_codigo, pais_nome, decimal_separador, moeda, ddi FROM empresas e INNER JOIN paises p ON e.pais_codigo = p.pais_codigo "
                                        . "WHERE empresa_grupo_codigo = :empresa_grupo_codigo", ['empresa_grupo_codigo' => $usuario["empresa_grupo_codigo"]])->fetchAll("assoc");

                        $this->session->write('empresa_dados', $empresa_dados);

                        $produtos_automaticos_codigos = $this->connection->execute("SELECT automatica_criacao_codigo, produto_codigo FROM produto_empresa_grupos "
                                        . "WHERE empresa_grupo_codigo = :empresa_grupo_codigo AND automatica_criacao_codigo IN (1,2,3,4)", ['empresa_grupo_codigo' => $usuario["empresa_grupo_codigo"]])->fetchAll("assoc");

                        foreach ($produtos_automaticos_codigos as $automatico_codigo) {
                            if ($automatico_codigo['automatica_criacao_codigo'] == 1)
                                $this->session->write('diaria_codigo', $automatico_codigo['produto_codigo']);
                            else if ($automatico_codigo['automatica_criacao_codigo'] == 2)
                                $this->session->write('turismo_taxa_codigo', $automatico_codigo['produto_codigo']);
                            else if ($automatico_codigo['automatica_criacao_codigo'] == 3)
                                $this->session->write('servico_taxa_codigo', $automatico_codigo['produto_codigo']);
                            else if ($automatico_codigo['automatica_criacao_codigo'] == 4)
                                $this->session->write('multa_cancelamento_codigo', $automatico_codigo['produto_codigo']);
                        }
                        $gerdadest_dados = $this->gertelmon($this->session->read('empresa_grupo_codigo_ativo'), 'gerdadest', $idioma);
                        $this->session->write('label_empresa_grupo', $this->gercamrot($gerdadest_dados, 'gerempgru'));
                        $this->session->write('label_usuario', $this->gercamrot($gerdadest_dados, 'gerusucod'));
                        $this->session->write('label_idioma', $this->gercamrot($gerdadest_dados, 'geridicod'));
                        $this->session->write('label_logout', $this->gercamrot($gerdadest_dados, 'gerlgobot'));

                        $this->session->write('reserva_pesquisa_max', $this->gercnfpes('reserva_pesquisa_max'));
                        $this->session->write('servico_pesquisa_max', $this->gercnfpes('servico_pesquisa_max'));
                        $this->session->write('estpaiatu_atualizacao', $this->gercnfpes('estpaiatu_atualizacao'));
                        $this->session->write('sessao_expiracao', $this->gercnfpes('sessao_expiracao'));
                        $this->session->write('respaiatu_periodo', $this->gercnfpes('respaiatu_periodo'));
                        $this->session->write('primeiro_acesso', 1);
                        $dados_gertelmon = $this->gertelmon($usuario["empresa_grupo_codigo"], 'gertelpri');

                        foreach ($dados_gertelmon as $dados) {
                            if ($dados['elemento_codigo'] == 'gerempcod') {
                                //se tiver uma empreas pre definida como padrao
                                if ($dados['campo_padrao_valor'] != null && $dados['padrao_valor'] != null) {
                                    echo $dados['padrao_valor'];
                                    $this->gerempsel($dados['padrao_valor']);
                                } else
                                    $this->gerempsel($empresa_dados[0]['empresa_codigo']);
                            }
                        }
                        $this->session->write('ultima_requisicao_tempo', $this->geragodet(2, $this->session->read("empresa_selecionada")['empresa_codigo']));

                        $produto_empresa_grupo_dados = $this->connection->execute("SELECT * FROM produto_empresa_grupos WHERE empresa_grupo_codigo = :empresa_grupo_codigo AND produto_tipo_codigo = 'DIA'", ['empresa_grupo_codigo' => $usuario["empresa_grupo_codigo"]])->fetchAll("assoc");
                        if (sizeof($produto_empresa_grupo_dados) > 0)
                            $servico_taxa_incide_diaria = $produto_empresa_grupo_dados[0]['servico_taxa_incide'];
                        else
                            $servico_taxa_incide_diaria = 0;
                        $this->session->write('servico_taxa_incide_diaria', $servico_taxa_incide_diaria);
                        $retorno['retorno'] = 1;
                        $retorno['mensagem'] = $this->germencri($usuario['empresa_codigo'], 144, 3);
                        $retorno['redefinir_senha'] = 0;
                        $retorno['empresa_redefinir_senha'] = 0;
                    } else {
                        $retorno['retorno'] = 0;
                        $retorno['mensagem'] = $this->germencri($usuario['empresa_codigo'], 143, 3);
                        $retorno['redefinir_senha'] = 1;
                        $retorno['empresa_redefinir_senha'] = $usuario["empresa_codigo"];
                    }
                } else {
                    //Atualiza a quantidade de tentativas
                    $this->connection->execute("UPDATE usuarios SET login_tentativa_qtd=:login_tentativa_qtd WHERE usuario_login=:usuario_login AND empresa_grupo_codigo=:empresa_grupo_codigo", ['login_tentativa_qtd' => $usuario['login_tentativa_qtd'] + 1, 'empresa_grupo_codigo' => $usuario['empresa_grupo_codigo'], 'usuario_login' => $usuario['usuario_login']]);
                    $retorno['retorno'] = 0;
                    $retorno['mensagem'] = $this->germencri($usuario['empresa_codigo'], 4, 3);
                    $retorno['redefinir_senha'] = 0;
                    $retorno['empresa_redefinir_senha'] = 0;
                }
            } else {
                $retorno['retorno'] = 0;
                $retorno['mensagem'] = $this->germencri($usuario['empresa_codigo'], 102, 3);
                $retorno['redefinir_senha'] = 1;
                $retorno['empresa_redefinir_senha'] = $usuario["empresa_codigo"];
            }
        } else {
            $retorno['retorno'] = 0;
            $retorno['mensagem'] = $this->germencri(null, 4, 1);
            $retorno['redefinir_senha'] = 0;
            $retorno['empresa_redefinir_senha'] = 0;
        }
        return $retorno;
    }

    function geraceseq($acesso_sequencia, $campo_buscado, $parametros) {
        $tabelas = $this->connection->execute("SELECT a_s.tabela as tabela, a_s.sequencia as sequencia, a_s_c.campo as campo_chave"
                        . " FROM acesso_sequencias as a_s INNER JOIN acesso_sequencia_chaves as a_s_c "
                        . "ON a_s_c.acesso_sequencia_codigo=a_s.acesso_sequencia_codigo AND a_s_c.sequencia=a_s.sequencia "
                        . "WHERE a_s.acesso_sequencia_codigo= :acesso_sequencia ORDER BY a_s.sequencia ASC", ['acesso_sequencia' => $acesso_sequencia])->fetchAll("assoc");

        $array = array();

        foreach ($tabelas as $tabela) {
            $array[$tabela['tabela']] = array();
            $array[$tabela['tabela']]['colunas'] = array();
        }
        foreach ($tabelas as $tabela) {
            $array[$tabela['tabela']]['sequencia'] = $tabela['sequencia'];
            array_push($array[$tabela['tabela']]['colunas'], $tabela['campo_chave']);
        }
        $string_campo_buscado = "";
        for ($i = 0; $i < sizeof($campo_buscado); $i++) {
            $string_campo_buscado .= $campo_buscado[$i] . ",";
        }
        $string_campo_buscado = substr($string_campo_buscado, 0, -1);

//Faz as consultas
        foreach ($array as $key => $value) {
            $tabela = $key;
            $where = " ";

            foreach ($value['colunas'] as $coluna) {
                if (array_key_exists($coluna, $parametros))
                    $where .= $coluna . " = " . $parametros[$coluna] . " AND ";
            }
            $where .= "1";

            $result = $this->connection->execute("SELECT $string_campo_buscado FROM $tabela WHERE $where")->fetchAll("assoc");

            for ($j = 0; $j < sizeof($result); $j++) {
//se o campo buscado for unico, verifica apenas o primeiro registro
                if (sizeof($campo_buscado) == 1) {
                    if (isset($result[0]) && $result[0][$campo_buscado[0]] != '' && $result[0][$campo_buscado[0]] != null) {
                        return $result[0];
                    }
                } else {
                    $campo_nulo = true;
                    for ($k = 0; $k < sizeof($campo_buscado); $k++) {
                        if ($campo_nulo) {
                            if (isset($result[$j]) && $result[$j][$campo_buscado[$k]] != '' && $result[$j][$campo_buscado[$k]] != null) {
                                $campo_nulo = true;
                            } else
                                $campo_nulo = false;
                        }
                    }
                    if ($campo_nulo)
                        return $result;
                }
            }
        }
        return "";
    }

//Atribui o separador de moedas
    function gersepatr($valor) {
        if ($valor != '' && $valor != null) {
            if ($this->session->read('decimal_separador') == '.')
                return number_format((float) $valor, 2, '.', ',');
            else if ($this->session->read('decimal_separador') == ',')
                return number_format((float) $valor, 2, ',', '.');
        } else {
            if ($this->session->read('decimal_separador') == '.')
                return number_format(0, 2, '.', ',');
            else if ($this->session->read('decimal_separador') == ',')
                return number_format(0, 2, ',', '.');
        }
    }

//Atribui o separador de moedas
    function germoeatr() {
        return $this->session->read('moeda');
    }

    public function gerempsel($empresa_selecionada) {
// Localiza empresa de acordo com o indice
        for ($i = 0; $i < count($this->session->read("empresa_dados")); $i++) {
            if ($empresa_selecionada == $this->session->read("empresa_dados")[$i]["empresa_codigo"]) {
                $this->session->write("empresa_selecionada", $this->session->read("empresa_dados")[$i]);
                $this->session->write('decimal_separador', $this->session->read("empresa_dados")[$i]['decimal_separador']);
                $this->session->write('moeda', $this->session->read("empresa_dados")[$i]['moeda']);
                $this->session->write('ddi_padrao', $this->session->read("empresa_dados")[$i]['ddi']);
                $this->session->write('pais_nome_padrao', $this->session->read("empresa_dados")[$i]['pais_nome']);
                $this->session->write('pais_codigo_padrao', $this->session->read("empresa_dados")[$i]['pais_codigo']);
                $this->session->write('servico_taxa', $this->session->read('empresa_dados')[$i]['servico_taxa']);
                $this->session->write('turismo_taxa', $this->session->read('empresa_dados')[$i]['turismo_taxa']);
                $this->session->write('hospede_taxa', $this->session->read('empresa_dados')[$i]['hospede_taxa']);
                $this->session->write('diaria_taxa', $this->session->read('empresa_dados')[$i]['diaria_taxa']);
                $this->session->write('inicial_padrao_horario', $this->session->read('empresa_dados')[$i]['inicial_padrao_horario']);
                $this->session->write('final_padrao_horario', $this->session->read('empresa_dados')[$i]['final_padrao_horario']);
            }
        }
    }

    public function gerlogexi($tela_codigo, $idioma, $empresa_codigo, $chave_valores = null) {
        $busca_tabelas = $this->connection->execute("SELECT tabela_nome, chave FROM tela_tabela_chaves WHERE"
                        . " tela_codigo = :tela_codigo", ['tela_codigo' => $tela_codigo])->fetchAll("assoc");
        foreach ($busca_tabelas as $dado_tabela) {
            $info_tabelas[$dado_tabela['tabela_nome']]['chaves'][] = $dado_tabela['chave'];
            $lista_tabelas[] = $dado_tabela['tabela_nome'];
        }

        $lista_tabelas = array_unique($lista_tabelas);

        $string_lista_tabelas = " ";
        foreach ($lista_tabelas as $tabela) {
            $string_lista_tabelas .= "'" . $tabela . "',";
        }

        $string_lista_tabelas = substr($string_lista_tabelas, 0, -1);

        $busca_nome_tabelas = $this->connection->execute("SELECT et.tabela_nome, ei.elemento_rotulo FROM "
                        . "elemento_tabelas et INNER JOIN elemento_idiomas ei ON et.elemento_codigo=ei.elemento_codigo "
                        . " WHERE ei.idioma=:idioma AND et.tabela_nome IN (:string_lista_tabelas) "
                        . "ORDER BY et.tabela_nome ", ['idioma' => $idioma, 'string_lista_tabelas' => $string_lista_tabelas])->fetchAll("assoc");

//COmeça a fazer as buscas nos documentos
        foreach ($busca_nome_tabelas as $tabela) {
            $type = $tabela['tabela_nome'];
            $label = $tabela['elemento_rotulo'];
            $elastic_search_doc = TypeRegistry::get($type);

            $query = $elastic_search_doc->find('all')
                    ->order('@timestamp', 'DESC');

            /* $query = $elastic_search_doc->find('all')
              ->order('@timestamp', 'DESC')
              ->where([
              'source' => $type]); */

            $results = $query->toArray();
            $string_lista_campos = "";
            $vetor_campos = array();

            foreach ($results as $result) {
                foreach ($result->original as $key => $value) {
                    if (!array_key_exists($key, $vetor_campos))
                        $vetor_campos[$key] = $key;
                }
            }

            foreach ($vetor_campos as $campo) {
                $string_lista_campos .= "'" . $campo . "',";
            }

            if ($string_lista_campos != "") {
                $string_lista_campos = substr($string_lista_campos, 0, -1);

                $rotulos = $this->gercamrot2($string_lista_campos);
                $retorno_type = "<table class='table'>"
                        . "<thead>"
                        . "<th style='width:11%'>Data</th>"
                        . "<th style='width:8%'>Usuário</th>"
                        . "<th style='width:40%'>Dados antes</th>"
                        . "<th style='width:40%'>Dados depois</th>"
                        . "</thead><tbody>";

                foreach ($results as $result) {
                    if ($empresa_codigo == $result->primary_key['empresa_codigo']) {
                        if ($result['type'] == 'create') {
                            $data = array_key_exists('criacao_data', $result->original) ?
                                    date('d/m/Y H:i:s', strtotime($result->original['criacao_data'])) : null;
                            $usuario = array_key_exists('criacao_usuario', $result->original) ?
                                    $result->original['criacao_usuario'] : null;
                        } else {
                            $data = array_key_exists('modificacao_data', $result->changed) ?
                                    date('d/m/Y H:i:s', strtotime($result->changed['modificacao_data'])) : null;
                            $usuario = array_key_exists('modificacao_usuario', $result->changed) ?
                                    $result->changed['modificacao_usuario'] : null;
                        }

                        $retorno_type .= "<tr><td>" . $data . "</td>";
                        $retorno_type .= "<td>" . $usuario . "</td>";

                        $retorno_type .= "<td>";
                        foreach ($result->original as $key => $value) {
                            if ($key != 'empresa_codigo' && $key != 'criacao_usuario' && $key != 'modificacao_usuario' && $key != 'modificacao_data' && $key != 'criacao_data')
                                if ($result['type'] == 'create')
                                    $retorno_type .= "<b>" . $this->gerencrot($rotulos, $key) . ": </b></br>";
                                else
                                    $retorno_type .= "<b>" . $this->gerencrot($rotulos, $key) . ": </b>" . $value . "</br>";
                        }
                        $retorno_type .= "</td><td>";
                        foreach ($result->changed as $key => $value) {
                            if ($key != 'empresa_codigo' && $key != 'criacao_usuario' && $key != 'modificacao_usuario' && $key != 'modificacao_data' && $key != 'criacao_data')
                                $retorno_type .= "<b>" . $this->gerencrot($rotulos, $key) . ": </b>" . $value . "</br>";
                        }
                        $retorno_type .= "</td></tr>";
                    }
                }


                $retorno_type .= "</tbody></table>";

                $retorno[$label]['html'] = $retorno_type;
            }else {
                $retorno[$label]['html'] = '';
            }
            $retorno[$label]['type'] = $type;
        }

        $retorno_html = "<script type='text/javascript'>$(function () {
                $('#tabs-logs').tabs();});</script>"
                . "<div id='tabs-logs'><ul>";

        foreach ($retorno as $key => $info_tabs) {
            $retorno_html .= "<li><a href='#tab_" . $info_tabs['type'] . "'> $key </a></li>";
        }
        $retorno_html .= "</ul>";

        foreach ($retorno as $key => $info_tabs) {
            $retorno_html .= "<div id='tab_" . $info_tabs['type'] . "'>" . $info_tabs['html'] . "</div>";
        }

        $retorno_html .= "</div>";
        echo $retorno_html;

        $this->autoRender = false;
    }

    function gerencrot($vetor_rotulos, $campo_procurado) {
        foreach ($vetor_rotulos as $rotulo) {
            if ($rotulo['campo_nome'] == $campo_procurado)
                return $rotulo['elemento_rotulo'];
        }
    }

    /*
     * Busca uma configuração da tabela de configurações gerais
     */

    public function gercnfpes($configuracao_nome) {
        $retorno = $this->connection->execute("SELECT configuracao_valor FROM geral_configuracao WHERE configuracao_nome = :configuracao_nome", ['configuracao_nome' => $configuracao_nome])->fetchAll("assoc");
        return $retorno[0]['configuracao_valor'];
    }

    public function gerestdet($pais_codigo) {
        $retorno = $this->connection->execute("SELECT estado_codigo, estado_nome FROM dominio_estados WHERE pais_codigo = :pais_codigo", ['pais_codigo' => $pais_codigo])->fetchAll("assoc");
        return $retorno;
    }

    function gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo = null, $quarto_codigo = null, $variante = null, $desconsiderado_documento_numero = null, $desconsiderado_quarto_item = null) {

        $reserva = new Reserva();
        $disponivel_soma = " ";
        $quarto_tipo_codigo_criterio = "";
        $quarto_codigo_criterio = "";
        $datas_criterio = "";
        $disponivel_soma_2 = " ,SUM(0) ";
        $disponivel_soma_2_criterio = "";
        $desconsiderado_criterio = "";
        $parametros['empresa_codigo'] = $empresa_codigo;

        switch ($evento) {
            case 0:
                $disponivel_soma_1 = " ,SUM(dd.reserva_disponivel) ";
                $reserva->restemexc($empresa_codigo);
                if ($datas != "" && sizeof($datas) > 0) {
                    $indice_data_parametro = 0;
                    $datas_sql = "";
                    foreach ($datas as $data) {
                        $datas_sql .= " :data_$indice_data_parametro ,";
                        $parametros['data_' . $indice_data_parametro] = $data;
                        $indice_data_parametro++;
                    }

                    $datas_sql = substr($datas_sql, 0, -1);
                    $datas_criterio = " AND dd.data IN ($datas_sql) ";
                }
                break;
            case 1:
                $disponivel_soma_1 = " ,SUM(dd.check_in_disponivel) ";

                if ($datas != "" && sizeof($datas) > 0) {
                    $datas_criterio = " AND dd.data <= :data ";
                    $parametros['data'] = $datas[0];
                }

                break;
            case 2:
                $disponivel_soma_1 = " ,SUM(dd.check_out_disponivel) ";
                if ($datas != "" && sizeof($datas) > 0) {
                    $datas_criterio = " AND dd.data <= :data ";
                    $parametros['data'] = $datas[0];
                }

                break;
            case 3:
                $disponivel_soma_1 = " ,SUM(dd.reserva_disponivel) ";
                $reserva->restemexc($empresa_codigo);
                if ($datas != "" && sizeof($datas) > 0) {
                    $indice_data_parametro = 0;
                    $datas_sql = "";
                    foreach ($datas as $data) {
                        $datas_sql .= " :data_$indice_data_parametro ,";
                        $parametros['data_' . $indice_data_parametro] = $data;
                        $indice_data_parametro++;
                    }

                    $datas_sql = substr($datas_sql, 0, -1);
                    $datas_criterio = " AND dd.data IN ($datas_sql) ";
                }
                break;
            case 4:
                $disponivel_soma_1 = " ,SUM(dd.camareira_disponivel)  ";
                if ($datas != "" && sizeof($datas) > 0) {
                    $indice_data_parametro = 0;
                    $datas_sql = "";
                    foreach ($datas as $data) {
                        $datas_sql .= " :data_$indice_data_parametro ,";
                        $parametros['data_' . $indice_data_parametro] = $data;
                        $indice_data_parametro++;
                    }

                    $datas_sql = substr($datas_sql, 0, -1);
                    $datas_criterio = " AND dd.data IN ($datas_sql) ";
                }
                break;
            case 5:
                $disponivel_soma_1 = " ,SUM(dd.manutencao_disponivel) ";
                if ($datas != "" && sizeof($datas) > 0) {
                    $indice_data_parametro = 0;
                    $datas_sql = "";
                    foreach ($datas as $data) {
                        $datas_sql .= " :data_$indice_data_parametro ,";
                        $parametros['data_' . $indice_data_parametro] = $data;
                        $indice_data_parametro++;
                    }

                    $datas_sql = substr($datas_sql, 0, -1);
                    $datas_criterio = " AND dd.data IN ($datas_sql) ";
                }
                break;
            case 6:
                $disponivel_soma_1 = " ,SUM(dd.reserva_disponivel) ";
                $disponivel_soma_2 = " ,SUM(dd.manutencao_disponivel) ";
                $disponivel_soma_2_criterio = " SUM(dd.manutencao_disponivel) < 0 ";
                $reserva->restemexc($empresa_codigo);
                if ($datas != "" && sizeof($datas) > 0) {
                    $indice_data_parametro = 0;
                    $datas_sql = "";
                    foreach ($datas as $data) {
                        $datas_sql .= " :data_$indice_data_parametro ,";
                        $parametros['data_' . $indice_data_parametro] = $data;
                        $indice_data_parametro++;
                    }

                    $datas_sql = substr($datas_sql, 0, -1);
                    $datas_criterio = " AND dd.data IN ($datas_sql) ";
                }
                break;
            case 7:
                $disponivel_soma_1 = " ,SUM(dd.reserva_disponivel) ";
                $reserva->restemexc($empresa_codigo);
                if ($datas != "" && sizeof($datas) > 0) {
                    $indice_data_parametro = 0;
                    $datas_sql = "";
                    foreach ($datas as $data) {
                        $datas_sql .= " :data_$indice_data_parametro ,";
                        $parametros['data_' . $indice_data_parametro] = $data;
                        $indice_data_parametro++;
                    }

                    $datas_sql = substr($datas_sql, 0, -1);
                    $datas_criterio = " AND dd.data IN ($datas_sql) ";
                }
                break;
            default:
                break;
        }

        if ($desconsiderado_documento_numero != null && $desconsiderado_quarto_item != null)
            $desconsiderado_criterio = " AND (dd.documento_numero <> $desconsiderado_documento_numero OR dd.quarto_item <> $desconsiderado_quarto_item) ";

        if (is_array($quarto_codigo) && sizeof($quarto_codigo > 0)) {
            $indice_quarto_parametro = 0;
            $quartos_sql = "";
            foreach ($quarto_codigo as $quarto) {
                $quartos_sql .= ":quarto_$indice_quarto_parametro,";
                $parametros['quarto_' . $indice_quarto_parametro] = $quarto;
                $indice_quarto_parametro++;
            }

            $quartos_sql = substr($quartos_sql, 0, -1);
            $quarto_codigo_criterio = " q.quarto_codigo IN ($quartos_sql) ";
        } else {
            if ($quarto_codigo != null) {
                $quarto_codigo_criterio = " q.quarto_codigo = :quarto_codigo";
                $parametros['quarto_codigo'] = $quarto_codigo;
            }
        }

        if ($quarto_codigo != null) {
            $quarto_tipo_criterio = " AND quarto_tipo_codigo IN (SELECT quarto_tipo_codigo FROM quartos q WHERE $quarto_codigo_criterio) ";
        } else {
            if ($quarto_tipo_codigo != null && sizeof($quarto_tipo_codigo) > 0) {
                $indice_quarto_tipo_parametro = 0;
                $quarto_tipos_sql = "";
                foreach ($quarto_tipo_codigo as $quarto_tipo) {
                    $quarto_tipos_sql .= ":quarto_tipo_$indice_quarto_tipo_parametro ,";
                    $parametros['quarto_tipo_' . $indice_quarto_tipo_parametro] = $quarto_tipo;
                    $indice_quarto_tipo_parametro++;
                }

                $quarto_tipos_sql = substr($quarto_tipos_sql, 0, -1);
                $quarto_tipo_criterio = " AND q.quarto_tipo_codigo IN ($quarto_tipos_sql)";
            } else {
                $retorno['resultado'] = 0;
                $retorno['mensagem'] = $this->germencri($empresa_codigo, 116, 1)['mensagem'];
                return $retorno;
            }
        }

        $parametros_cadastrado = $parametros;
        foreach ($parametros_cadastrado as $key => $value) {
            if (!(strpos($key, 'data') !== 0)) {
                unset($parametros_cadastrado[$key]);
            }
        }

        $cadastrado = $this->connection->execute("SELECT quarto_tipo_codigo, quarto_codigo FROM quartos q WHERE empresa_codigo=:empresa_codigo AND excluido=0 $quarto_tipo_criterio", $parametros_cadastrado)->fetchAll('assoc');
        if ($quarto_codigo != null) {
            $quarto_tipo_codigo = array();
            foreach ($quarto_codigo as $quarto) {
                $key = array_search($quarto, array_column($cadastrado, 'quarto_codigo'));
                if ($key !== false)
                    array_push($quarto_tipo_codigo, $cadastrado[$key]['quarto_tipo_codigo']);
            }
        }

//Define o formato das datas para serem usadas no criterio da busca
//Formata o quarto tipo codigo para poder passar como parametro na consulta
        if ($quarto_tipo_codigo != null && sizeof($quarto_tipo_codigo) > 0) {
            $indice_quarto_tipo_parametro = 0;
            $quarto_tipos_sql = "";
            foreach ($quarto_tipo_codigo as $quarto_tipo) {
                $quarto_tipos_sql .= ":quarto_tipo_$indice_quarto_tipo_parametro ,";
                $parametros['quarto_tipo_' . $indice_quarto_tipo_parametro] = $quarto_tipo;
                $indice_quarto_tipo_parametro++;
            }

            $quarto_tipos_sql = substr($quarto_tipos_sql, 0, -1);
            $quarto_tipo_codigo_criterio = " AND dd.quarto_tipo_codigo IN ($quarto_tipos_sql)";
        }

        $parametros_indisponivel = $parametros;
        foreach ($parametros_indisponivel as $key => $value) {
            if (!(strpos($key, 'quarto_') !== 0) && (strpos($key, 'quarto_tipo_') !== 0)) {
                unset($parametros_indisponivel[$key]);
            }
        }

        $indisponivel = $this->connection->execute("SELECT quarto_tipo_codigo, quarto_codigo, DATE(data) as data  $disponivel_soma_1 as disponivel_soma_1 $disponivel_soma_2 as disponivel_soma_2  
                    FROM
                        documento_datas dd 
                    WHERE
                        empresa_codigo = :empresa_codigo
                            $datas_criterio
                            $quarto_tipo_codigo_criterio $desconsiderado_criterio AND excluido<>1                     
                    GROUP BY quarto_tipo_codigo,quarto_codigo, data "
                        . "HAVING disponivel_soma_1 < 0 OR disponivel_soma_2 < 0 ", $parametros_indisponivel)->fetchAll('assoc');

        //Coloca o quarto_tipo_codigo como chave
        $disponivel_soma = array();
        foreach ($indisponivel as $data) {
            if (array_key_exists($data['quarto_tipo_codigo'], $disponivel_soma))
                if (array_key_exists($data['data'], $disponivel_soma[$data['quarto_tipo_codigo']])) {
                    $disponivel_soma[$data['quarto_tipo_codigo']][$data['data']]['disponivel_soma_1'] += $data['disponivel_soma_1'];
                    $disponivel_soma[$data['quarto_tipo_codigo']][$data['data']]['disponivel_soma_2'] += $data['disponivel_soma_2'];
                } else {
                    $disponivel_soma[$data['quarto_tipo_codigo']][$data['data']]['disponivel_soma_1'] = $data['disponivel_soma_1'];
                    $disponivel_soma[$data['quarto_tipo_codigo']][$data['data']]['disponivel_soma_2'] = $data['disponivel_soma_2'];
                } else {
                $disponivel_soma[$data['quarto_tipo_codigo']][$data['data']]['disponivel_soma_1'] = $data['disponivel_soma_1'];
                $disponivel_soma[$data['quarto_tipo_codigo']][$data['data']]['disponivel_soma_2'] = $data['disponivel_soma_2'];
            }
        }
        if ($evento == 0 || $evento == 6 || $evento == 7) {
            $cadastrado_soma = array();
            foreach ($cadastrado as $key => $value) {
                if (array_key_exists($value['quarto_tipo_codigo'], $cadastrado_soma))
                    $cadastrado_soma[$value['quarto_tipo_codigo']] += 1;
                else
                    $cadastrado_soma[$value['quarto_tipo_codigo']] = 1;
            }

            //Calcula o minimo de cada quarto_tipo
            $minimo_disponivel_soma = array();
            foreach ($disponivel_soma as $quarto_tipo_codigo_chave => $soma_2) {
                if (min(array_column($soma_2, 'disponivel_soma_1')) < min(array_column($soma_2, 'disponivel_soma_2')))
                    $minimo_disponivel_soma[$quarto_tipo_codigo_chave] = min(array_column($soma_2, 'disponivel_soma_1'));
                else
                    $minimo_disponivel_soma[$quarto_tipo_codigo_chave] = min(array_column($soma_2, 'disponivel_soma_2'));
            }

            $disponivel_quarto_tipo = $cadastrado_soma;
            array_walk($disponivel_quarto_tipo, function (&$val, $key, $foo) {
                if (array_key_exists($key, $foo))
                    $val += $foo[$key];
            }, $minimo_disponivel_soma);

            if ($variante == 1) {
                $retorno['disponivel_quarto_tipo'] = $disponivel_quarto_tipo;
                return $retorno;
            }

            $solicitado_quarto_tipo = array();

            foreach ($quarto_tipo_codigo as $q) {
                if (!array_key_exists($q, $solicitado_quarto_tipo))
                    $solicitado_quarto_tipo[$q] = 1;
                else
                    $solicitado_quarto_tipo[$q] += 1;
            }

            foreach ($solicitado_quarto_tipo as $n => $solicitado) {
                if (!array_key_exists($n, $disponivel_quarto_tipo) || ($disponivel_quarto_tipo[$n] < $solicitado_quarto_tipo[$n])) {
                    $quarto_tipo_lista = $this->gercamdom('gerquatip', $empresa_codigo);
                    $key = array_search($n, array_column($cadastrado, 'valor'));
                    $retorno['mensagem'] = $this->germencri($empresa_codigo, 117, 1, $quarto_tipo_lista[$key]['rotulo'], $solicitado_quarto_tipo[$n], $disponivel_quarto_tipo[$n])['mensagem'];
                    if ($variante == 3) {
                        $retorno['resultado'] = array();
                        return $retorno;
                    } else {
                        $retorno['resultado'] = 0;
                        return $retorno;
                    }
                }
            }
        }
        if ($quarto_codigo != null) {

            $indisponivel_procurar = false;
            foreach ($indisponivel as $key => $value) {
                foreach ($quarto_codigo as $quarto) {
                    if ($value['quarto_codigo'] == $quarto && ($value['disponivel_soma_1'] < 0 || $value['disponivel_soma_2'] < 0)) {
                        $indisponivel_procurar = true;
                    }
                }
            }
            if ($indisponivel_procurar) {

                $parametros_indisponivel_info = $parametros;
                foreach ($parametros_indisponivel_info as $key => $value) {
                    if (!(strpos($key, 'quarto_tipo_') !== 0)) {
                        unset($parametros_indisponivel_info[$key]);
                    }
                }

                $indisponivel_info = $this->connection->execute("SELECT DISTINCT(dd.quarto_codigo) as indisponivel_quarto_codigo, dd.documento_numero as indisponivel_documento_numero,  dt.documento_tipo_nome as indisponivel_documento_tipo_nome "
                                . " $disponivel_soma_1 as disponivel_soma_1 $disponivel_soma_2 as disponivel_soma_2   FROM documento_datas dd INNER JOIN documento_tipos dt 
                        ON dd.documento_tipo_codigo = dt.documento_tipo_codigo  WHERE empresa_codigo = :empresa_codigo $datas_criterio AND " . str_replace('q.', 'dd.', $quarto_codigo_criterio) . "  "
                                . "GROUP BY dd.documento_tipo_codigo,data HAVING disponivel_soma_1 < 0 OR disponivel_soma_2 < 0  ", $parametros_indisponivel_info)->fetchAll('assoc');

//Monta a mensagem que mostra os tipos e numeros indisponiveis
                $mensagem_indisponivel = "";
                $mensagem_quarto_codigo_indisponivel = "";
                $quartos_exibidos = array();
                foreach ($indisponivel_info as $item_indisponivel) {
                    $mensagem_indisponivel .= $item_indisponivel['indisponivel_documento_tipo_nome'] . ' ' . $item_indisponivel['indisponivel_documento_numero'] . ', ';
                    if (!in_array($item_indisponivel['indisponivel_quarto_codigo'], $quartos_exibidos)) {
                        $mensagem_quarto_codigo_indisponivel .= $item_indisponivel['indisponivel_quarto_codigo'] . ', ';
                        array_push($quartos_exibidos, $item_indisponivel['indisponivel_quarto_codigo']);
                    }
                }
                $mensagem_indisponivel = substr($mensagem_indisponivel, 0, -2);
                $mensagem_quarto_codigo_indisponivel = substr($mensagem_quarto_codigo_indisponivel, 0, -2);

                $retorno['mensagem'] = $this->germencri($empresa_codigo, 118, 1, ucfirst($mensagem_indisponivel), $mensagem_quarto_codigo_indisponivel)['mensagem'];
                $retorno['resultado'] = 0;

                return $retorno;
            }
            $retorno['resultado'] = 1;
            return $retorno;
        } else {
            if ($variante == 3) {
                $retorno['quarto_codigo'] = array();
                foreach ($cadastrado as $quarto_cadastrado) {
                    $disponivel = true;
                    foreach ($indisponivel as $quarto_indisponivel)
                        if ($quarto_cadastrado['quarto_codigo'] == $quarto_indisponivel['quarto_codigo'])
                            $disponivel = false;
                    if ($disponivel)
                        array_push($retorno['quarto_codigo'], $quarto_cadastrado);
                }
                return $retorno;
            } else {
                $retorno['resultado'] = 1;
                return $retorno;
            }
        }
    }

    function gerqtpdis($empresa_codigo, $datas) {

        $datas_criterio = "";
        $parametros['empresa_codigo'] = $empresa_codigo;
        if ($datas != "" && sizeof($datas > 0)) {
            $indice_data_parametro = 0;
            $datas_sql = "";
            foreach ($datas as $data) {
                $datas_sql .= " :data_$indice_data_parametro ,";
                $parametros['data_' . $indice_data_parametro] = $data;
                $indice_data_parametro++;
            }

            $datas_sql = substr($datas_sql, 0, -1);
            $datas_criterio = " AND dd.data IN ($datas_sql) ";
        }

        $quarto_tipos_qtd_disponivel = $this->connection->execute("SELECT 
                qt.quarto_tipo_codigo,
                COALESCE(SUM(dd.reserva_disponivel),0) as reserva_disponivel 
            FROM
                quarto_tipos qt
                    LEFT JOIN
                documento_datas dd ON qt.quarto_tipo_codigo = dd.quarto_tipo_codigo
                    AND qt.empresa_codigo = dd.empresa_codigo
                    $datas_criterio
            WHERE
                qt.excluido <> 1
                    AND qt.empresa_codigo = :empresa_codigo
            GROUP BY qt.quarto_tipo_codigo", $parametros)->fetchAll("assoc");

        foreach ($quarto_tipos_qtd_disponivel as $q) {
            $retorno[$q['quarto_tipo_codigo']] = $q['reserva_disponivel'];
        }
        return $retorno;
    }

    /*
     * Busca a lista de controllers e ações permitidas a esse usuário
     * @param int $empresa_grupo_codigo
     * @param int $acesso_perfil_codigo     * 
     * @return controle,acao 
     */

    public function gerauudet($empresa_grupo_codigo, $acesso_perfil_codigo) {
        $result = $this->connection->execute("SELECT controle, acao FROM acesso_url_empresa_grupo_perfil WHERE"
                        . " empresa_grupo_codigo = :empresa_grupo_codigo "
                        . "AND acesso_perfil_codigo = :acesso_perfil_codigo", ['empresa_grupo_codigo' => $empresa_grupo_codigo, 'acesso_perfil_codigo' => $acesso_perfil_codigo])->fetchAll("assoc");
        return $result;
    }

    /*
     * Verificar acesso a um controle e/ou ação de URL por empresa grupo e perfil de acesso
     * Utilizado para exibição ou não de links  em paginas - menus, botões, etc - que possuem controle e/ou ação associados
     * @return 0 = acesso não permitido, 1 = acesso permitido
     */

    public function gerauuver($controle, $acao = null) {
        $acesso = 0;
        if ($this->session->check('logado')) {
            if ($acao == null) {
//Verifica se o controller é permitido
                if ($this->session->check('acesso_url')) {
                    foreach ($this->session->read('acesso_url') as $acesso_lista)
                        if (strcasecmp($controle, $acesso_lista['controle']) == 0)
                            $acesso = 1;
                }
            }else {
//Verifica se o controller e acao é permitido
                if ($this->session->check('acesso_url')) {
                    foreach ($this->session->read('acesso_url') as $acesso_lista)
                        if (strcasecmp($controle, $acesso_lista['controle']) == 0 &&
                                strcasecmp($acao, $acesso_lista['acao']) == 0)
                            $acesso = 1;
                }
            }
        }
        return $acesso;
    }

    /*
     * Verificar acesso a partir de email ao sistema, por exemplo, quando se envia o preenchimento da fnrh online,
     * emails de avaliação, etc. Que devem liberar o acesso a uma determinada parte do sistema sem login.
     * @return 0 acesso não concedido, 1 acesso concedido
     */

    public function gereacver($empresa_codigo, $email_acesso_chave, $acesso_objeto, $email_acesso_expiracao = null) {
        $comunicacao_item_table = TableRegistry::get('ComunicacaoItem');
        $filtros = ['acesso_chave' => $email_acesso_chave, 'empresa_codigo' => $empresa_codigo,
            'acesso_expiracao >=' => $this->geragodet(2), 'acesso_objeto' => $acesso_objeto];

        $return = $comunicacao_item_table->find()
                ->where($filtros);

        if ($return->count() > 0) {
            $retorno['retorno'] = 1;
        } else {
            $retorno['retorno'] = 0;
            $retorno['mensagem'] = $this->germencri($empresa_codigo, 58, 1);
        }
        return $retorno;
    }

    /*
     * Verificar acesso a um controle e/ou ação de URL por um grupo de empresa, ou seja, verificar o licensiamento de módulos de um grupo de empresa
     *  (AUU: Acesso a URL pela Empresa)
     */

    public function gerauever($empresa_grupo_codigo, $controle, $acao = null) {
        $acesso = 0;
        $acao_criterio = "";


        $acesso_url_empresa_grupo_table = TableRegistry::get('AcessoUrlEmpresaGrupo');
        $selecionar = $acesso_url_empresa_grupo_table->find()->where(['empresa_grupo_codigo' => $empresa_grupo_codigo,
            'controle' => $controle]);

        if ($acao != null)
            $selecionar->where(['acao' => $acao]);

        $resultado = $selecionar->first();

//Possui acesso pela empresa_grupo
        if ($selecionar->count() > 0) {
            $acesso = 1;
        }
        return $acesso;
    }

    /*
     * Função que determina todas as datas entre um intervalo
     */

    public function gerdatdet($inicial_data, $final_data) {
        $rowLine = array();
        $rowLine[] = array("inicial_data" => $inicial_data, "final_data" => $final_data,
            "data_quantidade" => Util::NDias($inicial_data, $final_data));

        $var_m = array();
        if ($final_data != '' && ($inicial_data != $final_data)) {
            for ($x = 0; $x < Util::NDias($inicial_data, $final_data); $x++) {
                $var_m[] = date("Y-m-d", strtotime(date("Y-m-d", strtotime($inicial_data)) . " +" . $x . "days"));
            }
        } else {
            $var_m[] = date("Y-m-d", strtotime(date("Y-m-d", strtotime($inicial_data))));
        }
        $rowLine['datas'] = $var_m;
        return($rowLine);
    }

    /*
     * Criar item de comunicação
     */

    public function gercomcri($empresa_codigo, $comunicacao_tipo_codigo, $acesso_objeto, $destinatario_nome = null, $destinatario_sobrenome = null, $destinatario_contato = null) {

        $comunicacao_empresa_table = TableRegistry::get('ComunicacaoEmpresa');
        $selecionar = $comunicacao_empresa_table->find()->where(['empresa_codigo' => $empresa_codigo,
            'comunicacao_tipo_codigo' => $comunicacao_tipo_codigo]);

        $comunicacao_empresa = $selecionar->first();

        $parametros['empresa_codigo'] = $empresa_codigo;
        $parametros['acesso_objeto'] = $acesso_objeto;

        if ($selecionar->count() > 0) {
            $destinatario_contatos = $destinatario_contato;
            if ($destinatario_contato == null) {
                if ($comunicacao_empresa->documento_tipo_codigo == 'rs') {
                    $cliente_item_criterio = "";
                    if ($comunicacao_empresa->destinatario_cliente_item != null) {
                        $cliente_item_criterio = " AND cliente_item = :cliente_item ";
                        $parametros['cliente_item'] = $comunicacao_empresa->destinatario_cliente_item;
                    }
                    $parametros['papel_codigo'] = $comunicacao_empresa->destinatario_papel_codigo;
                    switch ($comunicacao_empresa->comunicacao_meio_codigo) {
                        case 1:
                            $destinatario_contatos = $this->connection->execute("SELECT c.nome as destinatario_nome, c.sobrenome as destinatario_sobrenome,"
                                            . "c.email as destinatario_contato FROM clientes c INNER JOIN documento_clientes dc "
                                            . "ON c.cliente_codigo = dc.cliente_codigo WHERE dc.empresa_codigo = $empresa_codigo "
                                            . "AND dc.documento_numero = :documento_numero AND dc.papel_codigo = :papel_codigo "
                                            . "$cliente_item_criterio")->fetchAll("assoc");
                            break;
                        case 2 || 3:
                            $destinatario_contatos = $this->connection->execute("SELECT c.nome as destinatario_nome, c.sobrenome as destinatario_sobrenome,"
                                            . "CONCAT(c.celular_ddi, c.celular_ddd, c.celular_numero) as destinatario_contato "
                                            . " FROM clientes c INNER JOIN documento_clientes dc "
                                            . "ON c.cliente_codigo = dc.cliente_codigo WHERE dc.empresa_codigo = :empresa_codigo "
                                            . "AND dc.documento_numero = :documento_numero AND dc.papel_codigo = :papel_codigo "
                                            . "$cliente_item_criterio", $parametros)->fetchAll("assoc");
                            break;
                        default:
                            break;
                    }
                }
            }

            $base_data = $this->geragodet(2);
            if ($comunicacao_empresa->documento_tipo_codigo == 'rs') {
                $documento_table = TableRegistry::get('Documentos');
                $dados_documento = $documento_table->find()->select(['criacao_data', 'inicial_data', 'final_data'])
                                ->where(['empresa_codigo' => $empresa_codigo, 'documento_tipo_codigo' => $comunicacao_empresa->documento_tipo_codigo,
                                    'documento_numero' => $acesso_objeto])->first();

                switch ($comunicacao_empresa->envio_evento) {
                    case 0:
                        $base_data = $dados_documento->criacao_data;
                        break;
                    case 1:
                        $base_data = $dados_documento->inicial_data;
                        break;
                    case 2:
                        $base_data = $dados_documento->final_data;
                        break;
                }
            }
            $envio_planejada_data = Util::somaHora($base_data, $comunicacao_empresa->envio_tempo, 0);

            $base_data = $this->geragodet(2);
            $acesso_chave = rand(1000000000, 2147483647);

            if ($comunicacao_empresa->documento_tipo_codigo == 'rs') {
                switch ($comunicacao_empresa->acesso_expiracao_evento) {
                    case 0:
                        $base_data = $dados_documento->criacao_data;
                        break;
                    case 1:
                        $base_data = $dados_documento->inicial_data;
                        break;
                    case 2:
                        $base_data = $dados_documento->final_data;
                        break;
                }
            }

            $acesso_expiracao = Util::somaHora($base_data, $comunicacao_empresa->acesso_expiracao_tempo, 0);
            $inserir_sucesso = 1;

            $comunicacao_item_table = TableRegistry::get('ComunicacaoItem');

            $this->connection->begin();

            foreach ($destinatario_contatos as $destinatario) {
                $comunicacao_item = $comunicacao_item_table->newEntity();
                $comunicacao_item->empresa_codigo = $empresa_codigo;
                $comunicacao_item->acesso_objeto = $acesso_objeto;
                $comunicacao_item->comunicacao_tipo_codigo = $comunicacao_tipo_codigo;
                $comunicacao_item->comunicacao_meio_codigo = $comunicacao_empresa->comunicacao_meio_codigo;
                $comunicacao_item->destinatario_contato = $destinatario['destinatario_contato'];
                if ($destinatario_contato == null) {
                    $destinatario_nome = $destinatario['destinatario_nome'];
                    $destinatario_sobrenome = $destinatario['destinatario_sobrenome'];
                }

                //Se for parametros de envio de reserva
                if ($comunicacao_empresa->documento_tipo_codigo == 'rs') {
                    $comunicacao_item->email_parametros = '{"inicial_data": "' . $dados_documento->inicial_data->i18nFormat('dd/MM/yyyy HH:mm:ss') . '",'
                            . ' "final_data":" ' . $dados_documento->final_data->i18nFormat('dd/MM/yyyy HH:mm:ss') . '", "acesso_objeto":' . $acesso_objeto . ','
                            . '"empresa_codigo":' . $empresa_codigo . ', "acesso_chave":' . $acesso_chave . ', "hospede_nome":"' . $destinatario_nome . ' ' . $destinatario_sobrenome . '"}';
                    //Se for parametros para alteração de senha de usuario
                } else {
                    $comunicacao_item->email_parametros = '{ "acesso_objeto":"' . $acesso_objeto . '", "endereco_url":"' . Router::url('/', true) . '",'
                            . '"empresa_codigo":' . $empresa_codigo . ', "acesso_chave":' . $acesso_chave . ', "usuario_nome":"' . $destinatario_nome . ' ' . $destinatario_sobrenome . '"}';
                }
                $comunicacao_item->envio_planejada_data = $envio_planejada_data;
                $comunicacao_item->acesso_chave = $acesso_chave;
                $comunicacao_item->acesso_expiracao = $acesso_expiracao;
                $comunicacao_item->comunicacao_status_codigo = 0;
                $comunicacao_item->tentativa_quantidade = 0;
                $comunicacao_item->criacao_usuario = $this->session->read("usuario_codigo");
                $comunicacao_item->criacao_data = $this->geragodet(2);

                try {
                    $comunicacao_item_table->save($comunicacao_item);
                } catch (\Exception $e) {
                    $excecao_mensagem = $e->getMessage();
                    $inserir_sucesso = 0;
                }
            }

            if (!$inserir_sucesso) {
                $this->germencri($empresa_codigo, 50, 2, $empresa_codigo, $acesso_objeto, $comunicacao_tipo_codigo, $excecao_mensagem)['mensagem'];
                $this->connection->rollback();
            }

            $this->connection->commit();
        }
    }

    /*
     * Controla o envio de comunicações
     */

    public function gercomcen($empresa_codigo, $comunicacao_tipo_codigo = null, $comunicacao_numero = null) {
        $agora = $this->geragodet(2, $empresa_codigo);
        $comunicacao_tipo_codigo_criterio = "";

        $comunicacao_item_table = TableRegistry::get('ComunicacaoItem');
        $dados_comunicacao_item = $comunicacao_item_table->find()->select(['comunicacao_numero', 'acesso_objeto', 'comunicacao_tipo_codigo',
                    'comunicacao_meio_codigo', 'destinatario_contato', 'email_parametros', 'acesso_chave', 'acesso_expiracao', 'tentativa_quantidade'])
                ->where(['empresa_codigo' => $empresa_codigo, 'envio_planejada_data <= ' => $agora,
            'comunicacao_status_codigo' => array(0, 3)], ['comunicacao_status_codigo' => 'integer[]']);

        if ($comunicacao_tipo_codigo != null)
            $dados_comunicacao_item->where(['comunicacao_tipo_codigo' => $comunicacao_tipo_codigo]);

        if ($comunicacao_numero != null && sizeof($comunicacao_numero) > 0)
            $dados_comunicacao_item->where(['comunicacao_numero' => $comunicacao_numero], ['comunicacao_numero' => 'integer[]']);

        $comunicacao_itens = $dados_comunicacao_item->all();
        $comunicacao_numeros_itens = "(";
        foreach ($comunicacao_itens as $comunicacao_item) {
            $comunicacao_numeros_itens .= "$comunicacao_item->comunicacao_numero,";
        }

        if (strlen($comunicacao_numeros_itens) > 1)
            $comunicacao_numeros_itens = substr($comunicacao_numeros_itens, 0, -1);
        $comunicacao_numeros_itens .= ")";


        if (!$comunicacao_itens->isEmpty()) {
            $atualizar = $this->connection->execute("UPDATE comunicacao_item SET comunicacao_status_codigo=1 WHERE comunicacao_numero IN $comunicacao_numeros_itens");
            if ($atualizar->rowCount() == 0)
                $this->germencri($empresa_codigo, 53, 2, $empresa_codigo)['mensagem'];
            else {
                foreach ($comunicacao_itens as $comunicacao_item) {
                    switch ($comunicacao_item->comunicacao_meio_codigo) {
                        case 1:
                            $this->geremaenv($empresa_codigo, $comunicacao_item->comunicacao_numero, null, $comunicacao_item->comunicacao_tipo_codigo, $comunicacao_item->destinatario_contato, $comunicacao_item->email_parametros, $comunicacao_item->acesso_chave, $comunicacao_item->acesso_expiracao, $comunicacao_item->tentativa_quantidade);
                            break;
                        default:
                            break;
                    }
                }
            }
        }
    }

    /*
     * Enviar email
     */

    public function geremaenv($empresa_codigo, $comunicacao_numero, $documento_numero = null, $comunicacao_tipo_codigo = null, $destinatario_contato = null, $email_parametros = null, $acesso_chave = null, $acesso_expiracao = null, $tentativa_quantidade = null) {
        $agora = $this->geragodet(2, $empresa_codigo);


        if ($destinatario_contato == null) {
            $info_envio = $this->connection->execute("SELECT destinatario_contato, remetente_nome, remetente_contato, responder_para, email_parametros,assunto, corpo_template, acesso_chave,"
                            . "acesso_expiracao, tentativa_quantidade FROM comunicacao_item ci INNER JOIN comunicacao_empresa ce ON ci.empresa_codigo = "
                            . "ce.empresa_codigo AND ci.comunicacao_tipo_codigo = ce.comunicacao_tipo_codigo WHERE ci.empresa_codigo = :empresa_codigo AND"
                            . " ci.comunicacao_numero = :comunicacao_numero", ['empresa_codigo' => $empresa_codigo, 'comunicacao_numero' => $comunicacao_numero])->fetchAll("assoc");
        } else {
            $info_envio = $this->connection->execute("SELECT remetente_nome, remetente_contato, responder_para, assunto, corpo_template FROM comunicacao_empresa"
                            . " WHERE empresa_codigo = :empresa_codigo AND comunicacao_tipo_codigo = :comunicacao_tipo_codigo", ['empresa_codigo' => $empresa_codigo, 'comunicacao_tipo_codigo' => $comunicacao_tipo_codigo])->fetchAll("assoc");
            $info_envio[0]['destinatario_contato'] = $destinatario_contato;
        }

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        /* $mail->SMTPOptions = array(
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
          )
          ); */
        $mail->Host = "email-ssl.com.br";
        $mail->CharSet = 'UTF-8';
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->SMTPAuth = true;
        //$mail->SMTPDebug = 4; // turn it off in production
        $mail->Username = "sistema@fasthotel.com.br";
        $mail->Password = "adgj!#%&";
        $mail->IsHTML(true);
        if ($info_envio[0]['responder_para'] != null && $info_envio[0]['responder_para'] != '')
            $mail->addReplyTo($info_envio[0]['responder_para'], '');
        $mail->From = "sistema@fasthotel.com.br";
        $mail->FromName = $info_envio[0]['remetente_nome'];
        $mail->AddAddress($info_envio[0]['destinatario_contato']);
        $mail->Subject = $info_envio[0]['assunto'];
        $mail->Body = $this->gerhtmmon($info_envio[0]['corpo_template'], $info_envio[0]['email_parametros'] ?? $email_parametros);
        $tentativa_quantidade = $info_envio[0]['tentativa_quantidade'] ?? 0 + 1;

        if ($mail->Send()) {
            $comunicacao_status_codigo = 2;
            $erro_mensagem = "";
        } else {
            $comunicacao_status_codigo = 3;
            $erro_mensagem = $mail->ErrorInfo;
        }

        $usuario_codigo = 0;
        if ($this->session->check('usuario_codigo'))
            $usuario_codigo = $this->session->read("usuario_codigo");

        $atualizar = $this->connection->execute("UPDATE comunicacao_item SET comunicacao_status_codigo = $comunicacao_status_codigo, erro_mensagem = '$erro_mensagem',"
                . "tentativa_quantidade = $tentativa_quantidade, envio_real_data = '$agora', envio_usuario = " . $usuario_codigo . " "
                . " WHERE empresa_codigo=$empresa_codigo AND comunicacao_numero = $comunicacao_numero");

        if ($atualizar->rowCount() == 0)
            $this->germencri($empresa_codigo, 54, 2, $comunicacao_numero)['mensagem'];

        return $comunicacao_status_codigo;
    }

    public function gerhtmmon($corpo_template, $html_parametros) {
        $html_parametros = (array) json_decode($html_parametros);
        foreach ($html_parametros as $chave => $valor) {
            $corpo_template = str_replace("|" . $chave . "|", $valor, $corpo_template);
        }
        return $corpo_template;
    }

    /*
     * Determina data e tempo de agora considerando fuso da empresa
     */

    public function geragodet($tempo_formato = null, $empresa_codigo = null) {
        if ($empresa_codigo == null) {
            if ($this->session->check('empresa_selecionada'))
                date_default_timezone_set($this->session->read("empresa_selecionada")['horario_fuso']);
        }else {
            $empresa_table = TableRegistry::get('Empresas');
            $empresa = $empresa_table->find()
                            ->where(['empresa_codigo' => $empresa_codigo])->first();
            date_default_timezone_set($empresa->horario_fuso);
        }

        switch ($tempo_formato) {
            case 1:
                $d = new \DateTime(date('Y-m-d'));
                return $d->format("Y-m-d");
            case 2:
                $d = new \DateTime(date('Y-m-d H:i:s'));
                return $d->format("Y-m-d H:i:s");
            case 3:
                $t = microtime(true);
                $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
                $d = new \DateTime(date('Y-m-d H:i:s.' . $micro, $t));
                return $d->format("Y-m-d H:i:s.u");
            default:
                $d = new \DateTime(date('Y-m-d H:i:s'));
                return $d->format("Y-m-d H:i:s");
        }
    }

    /*
     * Pesquisa comunicações
     */

    public function gercompes($empresa_codigo, $comunicacao_tipo_codigo = null, $comunicacao_numero = null, $documento_numero = null, $documento_tipo = null, $destinatario = null, $comunicacao_status_codigo = null, $envio_planejada_data = null, $envio_real_data = null, $envio_usuario = null, $ordenacao_coluna = null, $ordenacao_tipo = null, $pagina_numero = null) {
        $comunicacao_tipo_codigo_criterio = "";
        $comunicacao_numero_criterio = "";
        $documento_numero_criterio = "";
        $documento_tipo_criterio = "";
        $destinatario_criterio = "";
        $comunicacao_status_criterio = "";
        $envio_planejada_data_criterio = "";
        $envio_real_data_criterio = "";
        $envio_usuario_criterio = "";
        $retorno = "";

        $limite = "";
        if ($pagina_numero != null) {
            $tamanho_pagina = 10;
            $inicio = ($tamanho_pagina * $pagina_numero) - $tamanho_pagina;
            $limite = " LIMIT  $inicio , $tamanho_pagina";
        }

        $ordem = "";
        if ($ordenacao_coluna != null) {
            $colunas = explode('|', $ordenacao_coluna);
            $tipos = explode('|', $ordenacao_tipo);
            $ordem = " ORDER BY ";
            for ($i = 0; $i < sizeof($colunas); $i++) {
                if (!empty($colunas[$i]))
                    $ordem .= trim($colunas[$i] . ' ' . $tipos[$i] . ',');
            }
            $ordem = substr($ordem, 0, -1);
        } else
            $ordem = " ORDER BY ci.envio_planejada_data DESC ";

        $parametros['empresa_codigo'] = $empresa_codigo;
        if ($comunicacao_tipo_codigo != null) {
            $comunicacao_tipo_codigo_criterio = " AND ci.comunicacao_tipo_codigo = :comunicacao_tipo_codigo ";
            $parametros['comunicacao_tipo_codigo'] = $comunicacao_tipo_codigo;
        }

        if ($comunicacao_numero != null) {
            $comunicacao_numero_criterio = " AND ci.comunicacao_numero= :comunicacao_numero ";
            $parametros['comunicacao_numero'] = $comunicacao_numero;
        }

        if ($documento_numero != null) {
            $documento_numero_criterio = " AND ci.acesso_objeto = :documento_numero ";
            $parametros['documento_numero'] = $documento_numero;
        }

        if ($documento_tipo != null) {
            $documento_tipo_criterio = " AND ce.documento_tipo_codigo = :documento_tipo ";
            $parametros['documento_tipo'] = $documento_tipo;
        }

        if ($destinatario != null) {
            $destinatario_criterio = " AND ci.destinatario_contato = :destinatario ";
            $parametros['destinatario'] = $destinatario;
        }

        if ($comunicacao_status_codigo != null) {
            $comunicacao_status_criterio = " AND ci.comunicacao_status_codigo = :comunicacao_status_codigo ";
            $parametros['comunicacao_status_codigo'] = $comunicacao_status_codigo;
        }

        if ($envio_planejada_data != null) {
            if ($envio_planejada_data['comdapini'] != null && $envio_planejada_data['comdapini'] != '') {
                $envio_planejada_data_criterio .= " AND ci.envio_planejada_data >= :comdapini ";
                $parametros['comdapini'] = Util::convertDataSQL($envio_planejada_data['comdapini']) . " 00:00:00 ";
            }
        }
        if ($envio_planejada_data['comdapfin'] != null && $envio_planejada_data['comdapfin'] != '') {
            $envio_planejada_data_criterio .= " AND ci.envio_planejada_data <= :comdapfin ";
            $parametros['comdapfin'] = Util::convertDataSQL($envio_planejada_data['comdapfin']) . " 23:59:59 ";
        }

        if ($envio_real_data != null) {
            $envio_real_data_criterio = " AND ci.envio_real_data = :envio_real_data ";
            $parametros['envio_real_data'] = $envio_real_data;
        }

        if ($envio_usuario != null) {
            $envio_usuario_criterio = " AND ci.envio_usuario = :envio_usuario ";
            $parametros['envio_usuario'] = $envio_usuario;
        }

        $comunicacao['results'] = $this->connection->execute("SELECT SQL_CALC_FOUND_ROWS ci.empresa_codigo, ci.comunicacao_numero, ci.acesso_objeto, dct.comunicacao_tipo_nome, 
          ci.comunicacao_meio_codigo, ci.destinatario_contato, ci.envio_planejada_data, ci.comunicacao_status_codigo, ci.erro_mensagem, ci.tentativa_quantidade, 
          ci.envio_real_data, ci.envio_usuario, dt.documento_tipo_nome FROM comunicacao_item ci INNER JOIN comunicacao_empresa ce ON ci.empresa_codigo = ce.empresa_codigo 
          AND ci.comunicacao_tipo_codigo = ce.comunicacao_tipo_codigo LEFT JOIN documento_tipos dt ON ce.documento_tipo_codigo = 
          dt.documento_tipo_codigo INNER JOIN dominio_comunicacao_tipo dct ON dct.comunicacao_tipo_codigo = ci.comunicacao_tipo_codigo
          WHERE ci.empresa_codigo=:empresa_codigo $comunicacao_tipo_codigo_criterio"
                        . "  $comunicacao_numero_criterio $documento_numero_criterio $documento_tipo_criterio $destinatario_criterio $comunicacao_status_criterio   
          $envio_planejada_data_criterio $envio_real_data_criterio $envio_usuario_criterio $ordem $limite", $parametros)->fetchAll("assoc");

        $sQuery = "SELECT FOUND_ROWS() as found_rows";
        $aResultFilterTotal = $this->connection->execute($sQuery)->fetchAll("assoc");
        $iFilteredTotal = $aResultFilterTotal[0]['found_rows'];
        $comunicacao['filteredTotal'] = $iFilteredTotal;

        if (sizeof($comunicacao) <= 0)
            $retorno['mensagem'] = $this->germencri($empresa_codigo, 57, 1);

        $retorno = $comunicacao;
        return $retorno;
    }

    /*
     * Pesquisar mensagem de sistema
     */

    public function germenpes($empresa_codigo, $usuario_codigo = null, $exibicao_data = null, $exibicao_periodo = 0) {
        $exibicao_data_criterio = "";
        $usuario_codigo_criterio = "";
        $parametros['empresa_codigo'] = $empresa_codigo;

        if ($exibicao_data != null) {
            $exibicao_data_criterio = " AND m.exibicao_data BETWEEN DATE_SUB(:exibicao_data, INTERVAL :exibicao_periodo DAY) AND :exibicao_data ";
            $parametros['exibicao_data'] = Util::convertDataSQL($exibicao_data);
            $parametros['exibicao_periodo'] = $exibicao_periodo;
        }

        if ($usuario_codigo != null) {
            $usuario_codigo_criterio = " AND m.usuario_codigo = :usuario_codigo ";
            $parametros['usuario_codigo'] = $usuario_codigo;
        }

        $mensagens = $this->connection->execute("SELECT m.empresa_codigo, m.exibicao_data, m.mensagem_codigo, m.mensagem_texto, u.usuario_login FROM mensagens m INNER JOIN usuarios u ON m.usuario_codigo = u.usuario_codigo "
                        . " WHERE m.empresa_codigo=:empresa_codigo $exibicao_data_criterio $usuario_codigo_criterio ORDER BY m.exibicao_data DESC", $parametros)->fetchAll("assoc");

        return $mensagens;
    }

    public function gercidaut($busca, $estado_codigo, $pais) {
        $terms = explode(" ", $busca);

        $nome_like_query = '(';
        $sum_nome_matches = '';
        for ($i = 0; $i < sizeof($terms); $i++) {
            if ($terms[$i] != "")
                $nome_like_query .= "dc.cidade_nome LIKE '%" . $terms[$i] . "%' OR ";
            $sum_nome_matches .= " (dc.cidade_nome LIKE '%" . $terms[$i] . "%')+";
        }

        $sum_nome_matches = substr($sum_nome_matches, 0, -1);
        $nome_like_query = substr($nome_like_query, 0, -4);
        $nome_like_query .= ")";

        $rows = $this->connection->execute("SELECT dc.cidade_nome, CASE WHEN $nome_like_query then $sum_nome_matches else 0 end as nomematch 
        FROM dominio_cidades dc INNER JOIN paises p ON dc.pais_codigo = p.pais_codigo WHERE $nome_like_query AND dc.estado_codigo = '$estado_codigo' AND p.pais_nome = '$pais' ORDER BY nomematch DESC")->fetchAll("assoc");
        return $rows;
    }

//controla se a sessão já expirou
    public function gersescon($sessao_contador_nao_reinicia = null) {
        $agora = $this->geragodet(2);
//se a sessão ja esgotou
        if (round(abs(strtotime($agora) - strtotime($this->session->read('ultima_requisicao_tempo'))) / 60, 2) > $this->session->read('sessao_expiracao')) {
            return 0;
        } else {
//Se não estiver vindo do temporizador
            if ($sessao_contador_nao_reinicia != 1) {
                $this->session->write('ultima_requisicao_tempo', $agora);
            }
            return 1;
        }
    }

//faz o autocomplete nos produtos
    public function progeraut($texto, $produto_tipo_codigo = null, $produto_codigo = null, $vendavel = null) {
        $produto_tipo_codigo_criterio = "";
        $produto_codigo_criterio = "";
         $vendavel_criterio = "";

        if ($produto_tipo_codigo != null)
            $produto_tipo_codigo_criterio = " AND peg.produto_tipo_codigo = '$produto_tipo_codigo' ";

        if ($produto_codigo != null)
            $produto_codigo_criterio = " AND peg.produto_codigo = '$produto_codigo' ";
        
        if ($vendavel != null)
            $vendavel_criterio = " AND peg.vendavel = $vendavel ";

        $terms = explode(" ", $texto);

        $nome_like_query = '(';
        $sum_nome_matches = '';
        for ($i = 0; $i < sizeof($terms); $i++) {
            if ($terms[$i] != "")
                $nome_like_query .= "peg.nome LIKE '%" . $terms[$i] . "%' OR ";
            $sum_nome_matches .= " (peg.nome LIKE '%" . $terms[$i] . "%')+";
        }

        $sum_nome_matches = substr($sum_nome_matches, 0, -1);
        $nome_like_query = substr($nome_like_query, 0, -4);
        $nome_like_query .= ")";

        //Se nao estiver buscando diretamente pelo código
        if ($produto_codigo == null) {
            $dados = $this->connection->execute("SELECT peg.nome,peg.conta_editavel_preco, peg.produto_codigo,peg.contabil_tipo,peg.produto_tipo_codigo, peg.preco_fator_codigo, 
                    CASE WHEN $nome_like_query then $sum_nome_matches else 0 end as nomematch  FROM produto_empresa_grupos peg 
                    WHERE $nome_like_query $produto_tipo_codigo_criterio $produto_codigo_criterio $vendavel_criterio
                     AND peg.excluido = 0  AND peg.empresa_grupo_codigo = :empresa_grupo_codigo  AND peg.produto_tipo_codigo NOT IN ('PAG', 'TAX', 'CTB')  ORDER BY nomematch DESC", ['empresa_grupo_codigo' => $this->session->read('empresa_grupo_codigo')])->fetchAll("assoc");
        } else {
            $dados = $this->connection->execute("SELECT peg.nome,peg.conta_editavel_preco, peg.produto_codigo,peg.produto_tipo_codigo,peg.contabil_tipo, peg.preco_fator_codigo 
                    FROM produto_empresa_grupos peg 
                    WHERE peg.excluido = 0 AND peg.empresa_grupo_codigo = :empresa_grupo_codigo  $produto_tipo_codigo_criterio $produto_codigo_criterio  $vendavel_criterio
                    AND peg.produto_tipo_codigo NOT IN ('PAG', 'TAX', 'CTB') ", ['empresa_grupo_codigo' => $this->session->read('empresa_grupo_codigo')])->fetchAll("assoc");
        }
        return $dados;
    }

    public function gerferdet($empresa_codigo, $inicial_data, $final_data) {
        return $this->connection->execute("SELECT data FROM calendarios WHERE empresa_codigo = $empresa_codigo AND data >= '" . $inicial_data . "' AND data <= '" . $final_data . "'")->fetchAll('assoc');
    }

    /*
     * Criar motivo para documento, quarto ou item de conta
     */

    public function germotcri($empresa_codigo, $documento_numero, $quarto_item, $conta_item = null, $motivo) {

        try {
            if ($conta_item == null) {
                $motivo_quarto_item_table = TableRegistry::get("MotivoQuartoItem");
                $motivo_quarto_item = array();
                foreach ($motivo as $m) {
                    $motivo_inserir = $motivo_quarto_item_table->newEntity();
                    $motivo_inserir->empresa_codigo = $empresa_codigo;
                    $motivo_inserir->documento_numero = $documento_numero;
                    $motivo_inserir->quarto_item = $quarto_item;
                    $motivo_inserir->motivo_tipo_codigo = $m['motivo_tipo_codigo'];
                    $motivo_inserir->motivo_codigo = $m['motivo_codigo'];
                    $motivo_inserir->motivo_texto = $m['motivo_texto'];
                    $motivo_inserir->criacao_usuario = $this->session->read('usuario_codigo');
                    $motivo_inserir->criacao_data = $this->geragodet(2);
                    array_push($motivo_quarto_item, $motivo_inserir);
                }
                $motivo_quarto_item_table->saveMany($motivo_quarto_item);
            } else {
                $motivo_conta_item_table = TableRegistry::get("MotivoContaItem");
                $motivo_conta_item = array();
                foreach ($motivo as $m) {
                    $motivo_inserir = $motivo_conta_item_table->newEntity();
                    $motivo_inserir->empresa_codigo = $empresa_codigo;
                    $motivo_inserir->documento_numero = $documento_numero;
                    $motivo_inserir->quarto_item = $quarto_item;
                    $motivo_inserir->conta_item = $conta_item;
                    $motivo_inserir->motivo_tipo_codigo = $m['motivo_tipo_codigo'];
                    $motivo_inserir->motivo_codigo = $m['motivo_codigo'];
                    $motivo_inserir->motivo_texto = $m['motivo_texto'];
                    $motivo_inserir->criacao_usuario = $this->session->read('usuario_codigo');
                    $motivo_inserir->criacao_data = $this->geragodet(2);
                    array_push($motivo_conta_item, $motivo_inserir);
                }
                $motivo_conta_item_table->saveMany($motivo_conta_item);
            }
        } catch (\Exception $e) {
            $excecao_mensagem = $e->getMessage();
            $retorno['mensagem'] = $this->germencri($empresa_codigo, 121, 3, $documento_numero, $quarto_item, null, $excecao_mensagem);
            $retorno['retorno'] = 0;
            debug($retorno);
            return $retorno;
        }
        $retorno['mensagem'] = $this->germencri($empresa_codigo, 120, 1, $documento_numero, $quarto_item);
        $retorno['retorno'] = 1;
        return $retorno;
    }

    /*
     * Exibir motivo para documento, quarto ou item de conta
     */

    public function germotexi($empresa_codigo, $documento_numero, $quarto_item, $conta_item = null, $motivo_tipo_codigo = null) {
        $motivo_tipo_codigo_condicao = "";

        $parametros['empresa_codigo'] = $empresa_codigo;
        $parametros['documento_numero'] = $documento_numero;
        $parametros['quarto_item'] = $quarto_item;

        if ($motivo_tipo_codigo != null) {
            $motivo_tipo_codigo_condicao = " AND motivo_tipo_codigo = :motivo_tipo_codigo ";
            $parametros['motivo_tipo_codigo'] = $motivo_tipo_codigo;
        }

        if ($conta_item == null) {
            $retorno = $this->connection->execute("SELECT motivo_tipo_codigo, motivo_codigo, motivo_texto FROM motivo_quarto_item WHERE empresa_codigo = :empresa_codigo AND documento_numero =:documento_numero "
                    . "AND quarto_item=:quarto_item $motivo_tipo_codigo_condicao", $parametros);
        } else {
            $parametros['conta_item'] = $conta_item;
            $retorno = $this->connection->execute("SELECT motivo_tipo_codigo, motivo_codigo, motivo_texto FROM motivo_conta_item WHERE empresa_codigo = :empresa_codigo AND documento_numero =:documento_numero "
                    . " AND conta_item = :conta_item AND quarto_item=:quarto_item $motivo_tipo_codigo_condicao", $parametros);
        }

        return $retorno;
    }

    /*
     * Pesquisar motivo para documento, quarto ou item de conta
     */

    public function germotpes($empresa_codigo, $documento_tipo_codigo = null, $documento_numero = null, $quarto_item = null, $inicial_data = null, $final_data = null, $estadia_data = null, $criacao_data = null, $motivo_tipo_codigo = null, $motivo_codigo = null, $cliente_codigo = null, $usuario_codigo = null, $ordenacao_coluna = null, $ordenacao_tipo = null, $pagina_numero = null) {

        if ($ordenacao_coluna != null) {
            $colunas = explode('|', $ordenacao_coluna);
            $tipos = explode('|', $ordenacao_tipo);
            $ordem = " ORDER BY ";
            for ($i = 0; $i < sizeof($colunas); $i++) {
                if (!empty($colunas[$i]))
                    $ordem .= trim($colunas[$i] . ' ' . $tipos[$i] . ',');
            }
            $ordem = substr($ordem, 0, -1);
        } else
            $ordem = " ";

        $limite = "";
        if ($pagina_numero != null) {
            $tamanho_pagina = 10;
            $inicio = ($tamanho_pagina * $pagina_numero) - $tamanho_pagina;
            $limite = " LIMIT  $inicio , $tamanho_pagina";
        }

        $geral_criterio = "";

        $parametros['empresa_codigo'] = $empresa_codigo;

        $indice_parametro = 0;
        if ($documento_tipo_codigo != null) {
            $documento_tipo_criterio = '';
            foreach ($documento_tipo_codigo as $documento_tipo) {
                if ($documento_tipo != '') {
                    $documento_tipo_criterio .= " :documento_tipo_codigo_$indice_parametro ,";
                    $parametros['documento_tipo_codigo_' . $indice_parametro] = $documento_tipo;
                    $indice_parametro++;
                }
            }

            if (!empty($documento_tipo_criterio))
                $geral_criterio .= " AND dq.documento_tipo_codigo IN (" . substr($documento_tipo_criterio, 0, -1) . ") ";
        }

        if ($documento_numero != null) {
            $geral_criterio .= " AND dq.documento_numero = :documento_numero ";
            $parametros['documento_numero'] = $documento_numero;
        }

        if ($quarto_item != null) {
            $geral_criterio .= " AND dq.quarto_item = :quarto_item ";
            $parametros['quarto_item'] = $quarto_item;
        }

        //Se uma data veio preenchida, o sistema preenche a outra automaticamente
        if ($inicial_data['inicial'] != null && $inicial_data['final'] != null) {
            $geral_criterio .= " AND DATE(dq.inicial_data) >= :inicial_data_inicio AND DATE(dq.inicial_data) <= :inicial_data_final ";
            $parametros['inicial_data_inicio'] = Util::convertDataSql($inicial_data['inicial']);
            $parametros['inicial_data_final'] = Util::convertDataSql($inicial_data['final']);
        }

        if ($final_data['inicial'] != null && $final_data['final'] != null) {
            $geral_criterio .= " AND DATE(dq.final_data) >= :final_data_inicio AND DATE(dq.final_data) <= :final_data_final ";
            $parametros['final_data_inicio'] = Util::convertDataSql($final_data['inicial']);
            $parametros['final_data_final'] = Util::convertDataSql($final_data['final']);
        }

        if ($estadia_data['inicial'] != null && $estadia_data['final'] != null) {
            $geral_criterio .= " AND DATE(dd.data) >= :estadia_data_inicio AND DATE(dd.data) <= :estadia_data_final ";
            $parametros['estadia_data_inicio'] = Util::convertDataSql($estadia_data['inicial']);
            $parametros['estadia_data_final'] = Util::convertDataSql($estadia_data['final']);
        }

        if ($criacao_data['inicial'] != null && $criacao_data['final'] != null) {
            $geral_criterio .= " AND DATE(m.criacao_data) >= :criacao_data_inicio AND DATE(m.criacao_data) <= :criacao_data_final ";
            $parametros['criacao_data_inicio'] = Util::convertDataSql($criacao_data['inicial']);
            $parametros['criacao_data_final'] = Util::convertDataSql($criacao_data['final']);
        }

        $indice_parametro = 0;
        if ($motivo_tipo_codigo != null) {
            $motivo_tipo_criterio = "";
            foreach ($motivo_tipo_codigo as $motivo_tipo) {
                if ($motivo_tipo != '') {
                    $motivo_tipo_criterio .= " :motivo_tipo_codigo_$indice_parametro ,";
                    $parametros['motivo_tipo_codigo_' . $indice_parametro] = $motivo_tipo;
                    $indice_parametro++;
                }
            }

            if (!empty($motivo_tipo_criterio))
                $geral_criterio .= " AND motivo_tipo_codigo IN (" . substr($motivo_tipo_criterio, 0, -1) . ") ";
        }

        $indice_parametro = 0;
        if ($motivo_codigo != null) {
            $motivo_criterio = "";
            foreach ($motivo_codigo as $motivo) {
                if ($motivo != '') {
                    $motivo_criterio .= " :motivo_codigo_$indice_parametro ,";
                    $parametros['motivo_codigo_' . $indice_parametro] = $motivo;
                    $indice_parametro++;
                }
            }

            if (!empty($motivo_criterio))
                $geral_criterio .= " AND m.motivo_codigo IN (" . substr($motivo_criterio, 0, -1) . ") ";
        }

        if ($cliente_codigo != null) {
            $geral_criterio .= " AND dc.cliente_codigo= :cliente_codigo ";
            $parametros['cliente_codigo'] = $cliente_codigo;
        }

        if ($usuario_codigo != null) {
            $geral_criterio .= " AND m.criacao_usuario = :usuario_codigo  ";
            $parametros['usuario_codigo'] = $usuario_codigo;
        }

        $referencia['results'] = $this->connection->execute("SELECT 'item_de_conta' as referencia, dq.documento_numero, dq.quarto_item, dq.inicial_data, dq.final_data, m.conta_item, m.motivo_tipo_codigo, m.motivo_codigo, m.motivo_texto, m.criacao_data, m.criacao_usuario "
                        . " FROM motivo_conta_item m INNER JOIN documento_quarto dq ON dq.empresa_codigo = m.empresa_codigo AND dq.documento_numero = m.documento_numero AND dq.quarto_item = m.quarto_item  INNER JOIN "
                        . " documento_clientes dcli ON dq.empresa_codigo = dcli.empresa_codigo AND dq.documento_numero = dcli.documento_numero AND dq.quarto_item = dcli.quarto_item INNER JOIN clientes c ON dcli.cliente_codigo = c.cliente_codigo "
                        . " WHERE dq.empresa_codigo = :empresa_codigo $geral_criterio "
                        . " UNION "
                        . " SELECT dt.documento_tipo_nome_curto as referencia, dq.documento_numero, dq.quarto_item, dq.inicial_data, dq.final_data, '' as conta_item, m.motivo_tipo_codigo, m.motivo_codigo, m.motivo_texto, m.criacao_data, m.criacao_usuario "
                        . " FROM motivo_quarto_item m INNER JOIN documento_quarto dq ON dq.empresa_codigo = m.empresa_codigo AND dq.documento_numero = m.documento_numero AND dq.quarto_item = m.quarto_item INNER JOIN "
                        . " documento_clientes dcli ON dq.empresa_codigo = dcli.empresa_codigo AND dq.documento_numero = dcli.documento_numero AND dq.quarto_item = dcli.quarto_item INNER JOIN clientes c ON dcli.cliente_codigo = c.cliente_codigo"
                        . "  INNER JOIN documento_tipos dt ON dq.documento_tipo_codigo = dt.documento_tipo_codigo  WHERE dq.empresa_codigo = :empresa_codigo $geral_criterio $ordem $limite ", $parametros)->fetchAll('assoc');

        if ($pagina_numero != null) {
            $sQuery = "SELECT FOUND_ROWS() as found_rows";
            $aResultFilterTotal = $this->connection->execute($sQuery)->fetchAll("assoc");
            $iFilteredTotal = $aResultFilterTotal[0]['found_rows'];
            $referencia['filteredTotal'] = $iFilteredTotal;
        }

        return $referencia;
    }

    /* Cria tarifas nulas (script) */

    public function gertarcri() {
        $empresa_codigo = 1;
        $quarto_tipo_codigo = array(2, 3, 4, 5);
        $tarifa_tipo_codigo = array(1);
        $adulto_quantidade = 0;
        $data_inicial = '2017-11-06';
        $data_final = '2017-12-31';


        while (Util::comparaDatas($data_final, $data_inicial)) {
            foreach ($quarto_tipo_codigo as $quarto_tipo) {
                foreach ($tarifa_tipo_codigo as $tarifa_tipo) {
                    $this->connection->execute("INSERT INTO tarifas (empresa_codigo, quarto_tipo_codigo, data, tarifa_tipo_codigo,adulto_quantidade, tarifa) VALUES ("
                            . " $empresa_codigo, $quarto_tipo, '$data_inicial', $tarifa_tipo, $adulto_quantidade, null)");
                }
            }

            $data_inicial = Util::somaDias($data_inicial, 1, 0);
        }
    }

    /*
     * Determinar o codigo de produto para processo aumtomatico 
     */

    public function proautdet($empresa_codigo, $automatica_criacao_codigo) {
        $produto_codigo = $this->connection->execute("SELECT produto_codigo FROM produto_empresa_grupos peg INNER JOIN empresas e ON e.empresa_grupo_codigo = peg.empresa_grupo_codigo WHERE empresa_codigo = :empresa_codigo"
                        . " AND automatica_criacao_codigo=:automatica_criacao_codigo", ['empresa_codigo' => $empresa_codigo, 'automatica_criacao_codigo' => $automatica_criacao_codigo])->fetchAll('assoc')[0];
        return $produto_codigo['produto_codigo'];
    }

    /*
     * Modificar senha de login
     */

    public function gersenmod($empresa_codigo, $acesso_objeto, $email_acesso_chave, $email_acesso_expiracao, $usuario_login, $usuario_senha) {

        if ($this->gereacver($empresa_codigo, $email_acesso_chave, $acesso_objeto)['retorno'] == 1) {
            try {
                $this->connection->execute("UPDATE usuarios SET usuario_senha = password('$usuario_senha'), login_tentativa_qtd = 0, modificacao_data = '" . $this->geragodet(3, $empresa_codigo) . "',"
                        . "senha_expiracao_data='" . Util::somaDias($this->geragodet(3, $empresa_codigo), 90) . "' WHERE usuario_login = :usuario_login", ['usuario_login' => $usuario_login]);
            } catch (\Exception $ex) {
                $retorno['mensagem'] = $this->germencri($empresa_codigo, 104, 3, $usuario_login, null, null, $ex->getMessage());
                $retorno['retorno'] = 0;
                return $retorno;
            }

            $retorno['mensagem'] = $this->germencri($empresa_codigo, 103, 3, $usuario_login);
            $retorno['retorno'] = 1;
            return $retorno;
        } else {
            $retorno['mensagem'] = $this->germencri($empresa_codigo, 140, 3, $usuario_login);
            $retorno['retorno'] = 0;
            return $retorno;
        }
    }

    /*
     * Selecionar um grupo de empresa quando o usuario logado é sistema
     */

    public function gergrusel($empresa_grupo_codigo) {
        $empresa_grupo_dados = $this->connection->execute("SELECT * FROM empresa_grupos WHERE empresa_grupo_codigo = :empresa_grupo_codigo", ['empresa_grupo_codigo' => $empresa_grupo_codigo])->fetchAll("assoc");

        foreach ($empresa_grupo_dados as $empresa_grupo_dado) {
            $this->session->write('empresa_grupo_nome', $empresa_grupo_dado['empresa_grupo_nome']);
            $this->session->write('empresa_grupo_cpf_obrigatorio', $empresa_grupo_dado['cpf_obrigatorio']);
            $this->session->write('empresa_grupo_menor_documento_obrigatorio', $empresa_grupo_dado['menor_documento_obrigatorio']);
            $this->session->write('cliente_univoco_campo', $empresa_grupo_dado['cliente_univoco_campo']);
        }

        $empresa_dados = $this->connection->execute("SELECT e.*, p.pais_codigo, pais_nome, decimal_separador, moeda, ddi FROM empresas e INNER JOIN paises p ON e.pais_codigo = p.pais_codigo "
                        . "WHERE empresa_grupo_codigo = :empresa_grupo_codigo", ['empresa_grupo_codigo' => $empresa_grupo_codigo])->fetchAll("assoc");

        $this->session->write('empresa_dados', $empresa_dados);

        $produtos_automaticos_codigos = $this->connection->execute("SELECT automatica_criacao_codigo, produto_codigo FROM produto_empresa_grupos "
                        . "WHERE empresa_grupo_codigo = :empresa_grupo_codigo AND automatica_criacao_codigo IN (1,2,3,4)", ['empresa_grupo_codigo' => $empresa_grupo_codigo])->fetchAll("assoc");

        foreach ($produtos_automaticos_codigos as $automatico_codigo) {
            if ($automatico_codigo['automatica_criacao_codigo'] == 1)
                $this->session->write('diaria_codigo', $automatico_codigo['produto_codigo']);
            else if ($automatico_codigo['automatica_criacao_codigo'] == 2)
                $this->session->write('turismo_taxa_codigo', $automatico_codigo['produto_codigo']);
            else if ($automatico_codigo['automatica_criacao_codigo'] == 3)
                $this->session->write('servico_taxa_codigo', $automatico_codigo['produto_codigo']);
            else if ($automatico_codigo['automatica_criacao_codigo'] == 4)
                $this->session->write('multa_cancelamento_codigo', $automatico_codigo['produto_codigo']);
        }

        $dados_gertelmon = $this->gertelmon($empresa_grupo_codigo, 'gertelpri');

        foreach ($dados_gertelmon as $dados) {
            if ($dados['elemento_codigo'] == 'gerempcod') {
                //se tiver uma empreas pre definida como padrao
                if ($dados['campo_padrao_valor'] != null && $dados['padrao_valor'] != null) {
                    echo $dados['padrao_valor'];
                    $this->gerempsel($dados['padrao_valor']);
                } else
                    $this->gerempsel($empresa_dados[0]['empresa_codigo']);
            }
        }
        $this->session->write('ultima_requisicao_tempo', $this->geragodet(2, $this->session->read("empresa_selecionada")['empresa_codigo']));

        $produto_empresa_grupo_dados = $this->connection->execute("SELECT * FROM produto_empresa_grupos WHERE empresa_grupo_codigo = :empresa_grupo_codigo AND produto_tipo_codigo = 'DIA'", ['empresa_grupo_codigo' => $empresa_grupo_codigo])->fetchAll("assoc");
        if (sizeof($produto_empresa_grupo_dados) > 0)
            $servico_taxa_incide_diaria = $produto_empresa_grupo_dados[0]['servico_taxa_incide'];
        else
            $servico_taxa_incide_diaria = 0;
        $this->session->write('servico_taxa_incide_diaria', $servico_taxa_incide_diaria);
        $this->session->write('empresa_grupo_codigo_ativo', $empresa_grupo_codigo);
        $this->session->write('empresa_grupo_codigo', $empresa_grupo_codigo);
    }

}
