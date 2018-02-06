<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use App\Utility\GerHtmGer;
use App\Utility\Util;
use App\Model\Entity\Servico;
use App\Model\Entity\Estadia;

class GertelgerController extends AppController {

    public function index() {
        $session = $this->request->session();
        $arr_gertelmon = $this->geral->gertelmon($session->read('empresa_grupo_codigo_ativo'), 'gertelpri');
        $this->set(Util::germonrot($arr_gertelmon));
        $this->set(Util::germonfor($arr_gertelmon));
        $this->set(Util::germonpro($arr_gertelmon));
        $this->set(Util::germonval($arr_gertelmon));
        //Monta o cockpit
        $empresa_codigo = $session->read('empresa_selecionada')['empresa_codigo'];
        $quartos_tipos_codigos = $this->geral->gercamdom('resquatip', $empresa_codigo);
        $filtro = array('vazio' => 1, 'ocupado' => 1, 'bloqueado' => 1, 'check_in' => 0, 'check_out' => 0, 'servico' => 0,
            'bloqueio' => 0);
        foreach ($quartos_tipos_codigos as $quarto_tipo_codigo)
            $filtro[$quarto_tipo_codigo['valor']] = 1;

        $estadia = new Estadia();
        $cockpit_dados = $estadia->estpaiatu($empresa_codigo, 'c', $filtro)['estpaiatu_cockpit_dados'];

        $this->set('cockpit_dados', $cockpit_dados);
    }

    public function gertelcri() {
        $elemento = TableRegistry::get('Elementos');
        $this->set('elementos_existentes', $elemento->find()->all());
        $this->set('validadores', $this->geral->gercamdom('gercamval', '', '<>'));
        $this->viewBuilder()->setLayout('ajax');
    }

    public function gertelatu($tela_codigo) {
        $session = $this->request->session();
        $arr_gertelmon = $this->geral->gertelmon($session->read('empresa_grupo_codigo_ativo'), 'gertelpri');
        $this->set(Util::germonrot($arr_gertelmon));
        $this->set(Util::germonfor($arr_gertelmon));
        $this->set(Util::germonpro($arr_gertelmon));
        $this->set(Util::germonval($arr_gertelmon));
        //Monta o cockpit
        $empresa_codigo = $session->read('empresa_selecionada')['empresa_codigo'];
        $quartos_tipos_codigos = $this->geral->gercamdom('resquatip', $empresa_codigo);
        $filtro = array('vazio' => 1, 'ocupado' => 1, 'bloqueado' => 1, 'check_in' => 0, 'check_out' => 0, 'servico' => 0,
            'bloqueio' => 0);
        foreach ($quartos_tipos_codigos as $quarto_tipo_codigo)
            $filtro[$quarto_tipo_codigo['valor']] = 1;

        $estadia = new Estadia();
        $cockpit_dados = $estadia->estpaiatu($empresa_codigo, 'c', $filtro)['estpaiatu_cockpit_dados'];

        $this->set('cockpit_dados', $cockpit_dados);

        if ($this->request->is('post')) {
            $tela_codigo = $this->request->data['tela_codigo'];
            $elemento_codigos = $this->request->data['elemento_codigos'];
            $elemento_tipos = $this->request->data['elemento_tipos'];
            $elemento_propriedades = $this->request->data['elemento_propriedades'];

            $connection = ConnectionManager::get('default');
            $elementos_com_valor_padrao = $connection->execute("SELECT elemento_codigo FROM tela_elementos  WHERE tela_codigo = :tela_codigo  AND campo_padrao_valor=1", ['tela_codigo' => $tela_codigo])->fetchAll("assoc");

            $campo_propriedade_anterior = $connection->execute("SELECT campo_propriedade FROM tela_elementos  WHERE tela_codigo = :tela_codigo "
                            , ['tela_codigo' => $tela_codigo])->fetchAll("assoc");

            //Remove todos os elementos dessa tela
            $tela_elemento_table = TableRegistry::get('TelaElementos');
            $tela_elemento_table->deleteAll(['tela_codigo' => $tela_codigo]);


            $consultas['consulta_1'][] = "DELETE FROM tela_elementos  WHERE tela_codigo ='" . $tela_codigo . "'";

            $indice = 0;
            foreach ($elemento_codigos as $elemento) {

                $tela_elemento_entity = $tela_elemento_table->newEntity();
                $tela_elemento_entity->empresa_grupo_codigo = 1;
                $tela_elemento_entity->tela_codigo = $tela_codigo;
                $tela_elemento_entity->elemento_codigo = $elemento;
                $tela_elemento_entity->campo_propriedade = $elemento_propriedades[$indice];
                $verifica_campo_padrao_valor = array_search($elemento, array_column($elementos_com_valor_padrao, 'elemento_codigo'));
                if ($verifica_campo_padrao_valor !== false) {
                    $tela_elemento_entity->campo_padrao_valor = 1;
                    $consultas['consulta_2'][] = "INSERT INTO tela_elementos (empresa_grupo_codigo, tela_codigo, "
                            . "elemento_codigo, campo_propriedade, campo_padrao_valor) VALUES (1,'$tela_codigo', '$elemento', "
                            . "'$elemento_propriedades[$indice]', 1)";
                } else {
                    $consultas['consulta_2'][] = "INSERT INTO tela_elementos (empresa_grupo_codigo, tela_codigo, "
                            . "elemento_codigo, campo_propriedade) VALUES (1,'$tela_codigo', '$elemento', "
                            . "'$elemento_propriedades[$indice]')";
                }

                $tela_elemento_table->save($tela_elemento_entity);

                $tela_elemento_entity = $tela_elemento_table->newEntity();
                $tela_elemento_entity->empresa_grupo_codigo = 2;
                $tela_elemento_entity->tela_codigo = $tela_codigo;
                $tela_elemento_entity->elemento_codigo = $elemento;
                $tela_elemento_entity->campo_propriedade = $elemento_propriedades[$indice];
                if ($verifica_campo_padrao_valor !== false) {
                    $tela_elemento_entity->campo_padrao_valor = 1;
                    $consultas['consulta_2'][] = "INSERT INTO tela_elementos (empresa_grupo_codigo, tela_codigo, "
                            . "elemento_codigo, campo_propriedade, campo_padrao_valor) VALUES (2,'$tela_codigo', '$elemento', "
                            . "'$elemento_propriedades[$indice]', 1)";
                } else {
                    $consultas['consulta_2'][] = "INSERT INTO tela_elementos (empresa_grupo_codigo, tela_codigo, "
                            . "elemento_codigo, campo_propriedade) VALUES (2,'$tela_codigo', '$elemento', "
                            . "'$elemento_propriedades[$indice]')";
                }
                
                $tela_elemento_table->save($tela_elemento_entity);
                
                $tela_elemento_entity = $tela_elemento_table->newEntity();
                $tela_elemento_entity->empresa_grupo_codigo = 3;
                $tela_elemento_entity->tela_codigo = $tela_codigo;
                $tela_elemento_entity->elemento_codigo = $elemento;
                $tela_elemento_entity->campo_propriedade = $elemento_propriedades[$indice];
                if ($verifica_campo_padrao_valor !== false) {
                    $tela_elemento_entity->campo_padrao_valor = 1;
                    $consultas['consulta_2'][] = "INSERT INTO tela_elementos (empresa_grupo_codigo, tela_codigo, "
                            . "elemento_codigo, campo_propriedade, campo_padrao_valor) VALUES (3,'$tela_codigo', '$elemento', "
                            . "'$elemento_propriedades[$indice]', 1)";
                } else {
                    $consultas['consulta_2'][] = "INSERT INTO tela_elementos (empresa_grupo_codigo, tela_codigo, "
                            . "elemento_codigo, campo_propriedade) VALUES (3,'$tela_codigo', '$elemento', "
                            . "'$elemento_propriedades[$indice]')";
                }
                
                $tela_elemento_table->save($tela_elemento_entity);

                $indice++;
            }
            //Gera o código fonte para página
            $this->set('codigo_gerado', $this->gercodfon($elemento_codigos, $elemento_tipos));
            $this->set($consultas);
            $this->set('elementos_criados', $this->request->data['elementos_criados']);
            $this->render('gerexicod');
        } else {
            $elemento = TableRegistry::get('Elementos');
            $this->set('elementos_existentes', $elemento->find()->all());

            $tela_table = TableRegistry::get('Telas');
            $tela = $tela_table->find()->where(['tela_codigo' => $tela_codigo])->first();

            $connection = ConnectionManager::get('default');
            $results = $connection->execute("SELECT DISTINCT(e.elemento_codigo) as elemento_codigo, e.elemento_nome as elemento_nome, te.campo_propriedade as campo_propriedade"
                            . " FROM tela_elementos te INNER JOIN elementos e ON te.elemento_codigo = e.elemento_codigo "
                            . " WHERE te.tela_codigo = :tela_codigo ", ['tela_codigo' => $tela_codigo])->fetchAll("assoc");
            $this->set('elementos_da_tela', $results);
            $this->set('tela_codigo', $tela->tela_codigo);
            $this->set('tela_nome', $tela->tela_nome);
            $this->set('validadores', $this->geral->gercamdom('gercamval', '', '<>'));
        }
    }

    public function gertelcad() {

        $elemento_codigos = $this->request->data['elemento_codigos'];
        $elemento_tipos = $this->request->data['elemento_tipos'];
        $elemento_propriedades = $this->request->data['elemento_propriedades'];

        $tela_codigo = $this->request->data['tela_codigo'];
        $tela_nome = $this->request->data['tela_nome'];
        $telas_table = TableRegistry::get('Telas');
        $tela = $telas_table->newEntity();
        $tela->tela_codigo = $tela_codigo;
        $tela->tela_nome = $tela_nome;
        $telas_table->save($tela);
        $consultas['consulta_1'][] = "INSERT INTO telas (tela_codigo, tela_nome) VALUES "
                . "('$tela_codigo', '$tela_nome')";
        $tela_elemento_table = TableRegistry::get('TelaElementos');

        $indice = 0;
        foreach ($elemento_codigos as $elemento) {
            $tela_elemento_entity = $tela_elemento_table->newEntity();
            $tela_elemento_entity->empresa_grupo_codigo = 1;
            $tela_elemento_entity->tela_codigo = $tela_codigo;
            $tela_elemento_entity->elemento_codigo = $elemento;
            $tela_elemento_entity->campo_propriedade = $elemento_propriedades[$indice];
            $tela_elemento_table->save($tela_elemento_entity);
            $consultas['consulta_2'][] = "INSERT INTO tela_elementos (empresa_grupo_codigo, tela_codigo, "
                    . "elemento_codigo, campo_propriedade) VALUES (1,'$tela_codigo', '$elemento', "
                    . "'$elemento_propriedades[$indice]')";

            $tela_elemento_entity = $tela_elemento_table->newEntity();
            $tela_elemento_entity->empresa_grupo_codigo = 2;
            $tela_elemento_entity->tela_codigo = $tela_codigo;
            $tela_elemento_entity->elemento_codigo = $elemento;
            $tela_elemento_entity->campo_propriedade = $elemento_propriedades[$indice];

            $tela_elemento_table->save($tela_elemento_entity);
            $consultas['consulta_3'][] = "INSERT INTO tela_elementos (empresa_grupo_codigo, tela_codigo, "
                    . "elemento_codigo, campo_propriedade) VALUES (1,'$tela_codigo', '$elemento', "
                    . "'$elemento_propriedades[$indice]')";
            $indice++;
        }

        //Gera o código fonte para página
        $this->set('codigo_gerado', $this->gercodfon($elemento_codigos, $elemento_tipos));
        $this->set($consultas);
        $this->set('elementos_criados', $this->request->data['elementos_criados']);
        $this->render('gerexicod');
    }

    public function gertelexi() {
        $tela_table = TableRegistry::get('Telas');
        $this->set('telas_existentes', $tela_table->find()->all());
        $this->viewBuilder()->setLayout('ajax');
    }

    public function gerinsele() {

        $elemento_table = TableRegistry::get('Elementos');
        $elemento = $elemento_table->newEntity();
        $elemento->elemento_codigo = $this->request->data['codigo'];
        $elemento->elemento_nome = $this->request->data['nome'];
        $elemento->elemento_tipo = $this->request->data['tipo_elemento'];

        $elemento_table->save($elemento);
        $consultas['consulta_1'][] = "INSERT INTO elementos (elemento_codigo, elemento_nome, elemento_tipo) "
                . "VALUES ('$elemento->elemento_codigo','$elemento->elemento_nome','$elemento->elemento_tipo');";

        //Se for campo
        if ($this->request->data['tipo_elemento'] == 'c') {
            $elemento_campos_table = TableRegistry::get('ElementoCampos');
            $elemento_campo = $elemento_campos_table->newEntity();
            $elemento_campo->elemento_codigo = $this->request->data['codigo'];
            $elemento_campo->campo_nome = $this->request->data['campo'];
            $elemento_campo->campo_formato = $this->request->data['formato'];
            $elemento_campo->campo_validador = $this->request->data['validador'];

            $elemento_campos_table->save($elemento_campo);
            $consultas['consulta_2'][] = "INSERT INTO elemento_campos (elemento_codigo, campo_nome, campo_formato,campo_validador,campo_padrao_valor) "
                    . "VALUES ('$elemento_campo->elemento_codigo','$elemento_campo->campo_nome', '$elemento_campo->campo_formato', '$elemento_campo->campo_validador');";
            //Se for tabela
        } else if ($this->request->data['tipo_elemento'] == 'b') {
            $elemento_tabelas_table = TableRegistry::get('ElementoTabelas');
            $elemento_tabela = $elemento_tabelas_table->newEntity();
            $elemento_tabela->elemento_codigo = $this->request->data['codigo'];
            $elemento_tabela->tabela_nome = $this->request->data['tabela'];
            $elemento_tabelas_table->save($elemento_tabela);
            $consultas['consulta_2'][] = "INSERT INTO elemento_tabelas (elemento_codigo, elemento_nome) "
                    . "VALUES ('$elemento_tabela->elemento_codigo','$elemento_tabela->tabela_nome');";
        }

        $elemento_idioma_table = TableRegistry::get('ElementoIdiomas');
        $elemento_idioma = $elemento_idioma_table->newEntity();
        $elemento_idioma->elemento_codigo = $this->request->data['codigo'];
        $elemento_idioma->idioma = 'pt';
        $elemento_idioma->elemento_rotulo = $this->request->data['portugues'];

        $elemento_idioma_table->save($elemento_idioma);

        $consultas['consulta_3'][] = "INSERT INTO elemento_idiomas (elemento_codigo, idioma, "
                . "elemento_rotulo) VALUES ('$elemento_idioma->elemento_codigo','pt',' $elemento_idioma->elemento_rotulo');";

        $elemento_idioma = $elemento_idioma_table->newEntity();
        $elemento_idioma->elemento_codigo = $this->request->data['codigo'];
        $elemento_idioma->idioma = 'en';
        $elemento_idioma->elemento_rotulo = $this->request->data['ingles'];
        $elemento_idioma_table->save($elemento_idioma);
        $consultas['consulta_4'][] = "INSERT INTO elemento_idiomas (elemento_codigo, idioma, "
                . "elemento_rotulo) VALUES ('$elemento_idioma->elemento_codigo','en','$elemento_idioma->elemento_rotulo');";

        foreach ($consultas['consulta_1'] as $consulta) {
            echo($consulta);
        }

        if (array_key_exists('consulta_2', $consultas))
            foreach ($consultas['consulta_2'] as $consulta) {
                echo($consulta);
            }

        foreach ($consultas['consulta_3'] as $consulta) {
            echo($consulta);
        }

        foreach ($consultas['consulta_4'] as $consulta) {
            echo($consulta);
        }

        $this->autoRender = false;
        // $this->render('gertelcri');
    }

    //Gera o codigo fonte
    public function gercodfon($elemento_codigos, $elemento_tipos) {

        $indice = 0;
        $cont = 0;
        $gerador_html = new GerHtmGer();
        $abre_div_bootstrap = "<div class='form-group'>";
        $fecha_div_bootstrap = "</div>";
        $retorno = htmlspecialchars($abre_div_bootstrap) . "<br/><br/>";
        foreach ($elemento_codigos as $elemento) {

            if (isset($elemento_tipos[$indice])) {
                if ($elemento_tipos[$indice] == 'texto') {
                    $retorno .= htmlspecialchars($gerador_html->gertextex($elemento)) . "<br/><br/>";
                } else if ($elemento_tipos[$indice] == 'input') {
                    $retorno .= htmlspecialchars($gerador_html->gerinptxt($elemento, "", $elemento)) . "<br/><br/>";
                    $cont++;
                } else if ($elemento_tipos[$indice] == 'select') {
                    $retorno .= htmlspecialchars($gerador_html->gerinpsel($elemento, $elemento . '_list', 'label_item', 'selected_item')) . "<br/><br/>";
                    $cont++;
                }
            }
            $indice++;
            if (($cont % 4) == 0 && $cont != 0) {
                $retorno .= htmlspecialchars($fecha_div_bootstrap) . "<br/><br/>";
                $retorno .= htmlspecialchars($abre_div_bootstrap) . "<br/><br/>";
                $cont = 0;
            }
        }

        if (sizeof($elemento_codigos) % 4 != 0) {
            $retorno .= htmlspecialchars($fecha_div_bootstrap) . "<br/><br/>";
        }

        return $retorno;
    }

    //Script para geração de documentos aleatórios para teste
    public function gerdocger() {
        $documentos_table = TableRegistry::get('Documentos');
        $documento_datas_table = TableRegistry::get('DocumentoDatas');
        $servico = new Servico();
        
        $connection = ConnectionManager::get('default');

        $quartos = $connection->execute("SELECT quarto_codigo FROM quartos WHERE empresa_codigo = 1 AND excluido<>1")->fetchAll("assoc");
        $tamanho_doc = 2;

        $num_iteracoes = 8;

        foreach ($quartos as $quarto) {
            $inicial_data = '2017-08-01';
            for ($i = 1; $i <= $num_iteracoes; $i++) {
                if($i%2 == 0)
                    $documento_tipo_codigo = 'mb';
                else
                    $documento_tipo_codigo = 'bc';
                
                $serdoccri = $servico->serdoccri(1, $documento_tipo_codigo, $inicial_data, Util::somaDias($inicial_data,$tamanho_doc), $quarto['quarto_codigo'], 1);
                $inicial_data = Util::somaDias($inicial_data,$tamanho_doc);
            }
            
        }
    }

}
