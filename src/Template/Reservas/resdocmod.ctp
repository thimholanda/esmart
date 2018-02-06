<!-- Reserva box  -->
<?php

use App\Model\Entity\Geral;
use Cake\Network\Session;
use Cake\Routing\Router;
use App\Utility\Util;
use App\Model\Entity\Estadia;
use App\Model\Entity\Reserva;

$estadia = new Estadia();
$reserva = new Reserva();
$geral = new Geral();
$session = new Session();

function date_sort($a, $b) {
    return strtotime($a) - strtotime($b);
}

function date_compare($a, $b) {
    $t1 = strtotime($a[0]);
    $t2 = strtotime($b[0]);
    return $t1 - $t2;
}

//monta uma lista de hospedes para o cheekin
$codigos_hospedes = "";
$nomes_hospedes = "";
$sobrenomes_hospedes = "";
$emails_hospedes = "";
$cpfs_hospedes = "";
$doc_tipos_hospedes = "";
$doc_nums_hospedes = "";
$total_hospedes = 0;
if (isset($hospedes)) {
    for ($i = 0; $i < sizeof($hospedes); $i++) {
        $codigos_hospedes .= $hospedes[$i]['cliente_codigo'] . "|";
        $nomes_hospedes .= $hospedes[$i]['nome'] . "|";
        $sobrenomes_hospedes .= $hospedes[$i]['sobrenome'] . "|";
        $emails_hospedes .= $hospedes[$i]['email'] . "|";
        $cpfs_hospedes .= $hospedes[$i]['cpf'] . "|";
        $doc_tipos_hospedes .= $hospedes[$i]['cliente_documento_tipo'] . "|";
        $doc_nums_hospedes .= $hospedes[$i]['cliente_documento_numero'] . "|";
        $total_hospedes++;
    }
}
?>
<h1 class="titulo_pag">
    <?= $rot_resrestit ?> <?= $reserva_dados[1]['documento_numero'] ?>
</h1>

<div class="content_inner">
    <div id="tab-geral">        
        <form method="POST" name="resdocmod" id="resdocmod" class="form-horizontal" action="<?= Router::url('/', true) ?>reservas/resdocmod/<?= $this->request->params['pass'][0] . '/' . $this->request->params['pass'][1] ?>">
            <input type="hidden" name="empresa_codigo" id="empresa_codigo" value="<?= $empresa_codigo ?? '' ?>" />
            <input type="hidden" id="form_atual" name="form_atual" value="resdocmod" />
            <input type="hidden" id="form_force_submit" value="0" />
            <input type="hidden" id="export_pdf" value="0" name="export_pdf" />
            <input type="hidden" id="aria-form-id-pdf" value="resdocmod" >
            <input type="hidden" name="documento_numero" id="doc_num" value="<?= $reserva_dados[1]['documento_numero'] ?>" />
            <input type="hidden" name="geracever_conitemod" value="<?= $geracever_conitemod ?>" />
            <input type="hidden" name="geracever_coniteexc" value="<?= $geracever_coniteexc ?>" />
            <input type="hidden" name="tab-atual" id="tab-atual" value="<?php if (isset($tab_atual) && $tab_atual == 'tab-contas') echo "tab-contas" ?>" />
            <input type="hidden" name="pagina_referencia" id="pagina_referencia" value="<?= $pagina_referencia ?>" />
            <input type="hidden" name="url_redirect_after" id="url_redirect_after" value="<?= $pagina_referencia ?>" />
            <input type="hidden" id="resresdat" value="<?= $reserva_dados[1]['reserva_data'] ?>" />
            <input type="hidden" id="log-empresa_codigo" value="<?= $empresa_codigo ?>" />
            <input type="hidden" id="log-documento_numero" value="<?= $reserva_dados[1]['documento_numero'] ?>" />
            <input type="hidden" id="log-idioma" value="<?= $session->read('usuario_idioma') ?>" />


            <div>
                <div class="col-md-12 col-sm-12"  style='padding-top: 3px!important;'>
                    <table style="margin-top:6px;"><tr>
                            <td style='padding:3px 0px;'>Contratado por

                                <a class="link_ativo" href="mailto:<?= $reserva_dados[1]['email'] ?>" target="_top"><i class="reservas_icones fa fa-envelope-o fa-lg fa-fw mhp" style="font-size: 1.233333em;width: 0.9571429em; "></i></a>

                                <a href="#a" class="clicadmod link_ativo" aria-cliente-codigo = '<?= $reserva_dados[1]["cliente_codigo"] ?>'><?= $reserva_dados[1]['nome'] . ' ' . $reserva_dados[1]['sobrenome'] ?></a>
                            </td>

                        </tr>

                        <tr>
                            <td colspan='3' style='padding:0px 0px;'>
                                <?php if ($reserva_dados[1]['cel_numero'] != null && $reserva_dados[1]['cel_numero'] != "") { ?>
                                    <b><?= $reserva_dados[1]['cel_numero'] ?></b> 
                                <?php } ?>
                                <?php if ($reserva_dados[1]['tel_numero'] != null && $reserva_dados[1]['tel_numero'] != "") { ?>
                                    <b><?= $reserva_dados[1]['tel_numero'] ?></b>,
                                <?php } ?>
                                em <b><?= Util::convertDataDMY($reserva_dados[1]['reserva_data'], 'd/m/Y') ?></b>
                                </label>



                                <label style="float:left">
                                    <?= $rot_geratrtit ?> </label> <span id="agencia_codigo_label" style="float:left"> <b style="float:left">&nbsp;  <?= array_key_exists($reserva_dados[1]['agencia_codigo'], $agencias) ? $agencias[$reserva_dados[1]['agencia_codigo']] : '' ?></b>
                                </span>
                                &nbsp;<select class="form-control" name="resviaage" id="resviaage" style="display:none; width: 150px; float:left; margin-left: 6px; margin-top: -4px;
                                              }"> 
                                    <option value=""></option>
                                    <?php
                                    foreach ($dominio_agencia_viagem as $item) {
                                        $selected = "";
                                        if ($item['valor'] == $reserva_dados[1]['agencia_codigo']) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?= $item["valor"] ?>" <?= $selected ?>>
                                            <?= $item["rotulo"] ?>
                                        </option>
                                    <?php } ?>
                                </select> 
                                <span id="altera_agencia_codigo" class='ui-icon ui-icon-pencil' style="float:left"></span>

                                <label style="float:left"> , 
                                    sob o número </label>
                                &nbsp;<input class="form-control" type="text" name="docnumage" style="display:none; width: 150px;float:left; margin-left: 6px; margin-top: -4px; " id="docnumage" value="<?= $reserva_dados[1]['externo_documento_numero'] ?>" /> 

                                <span id="externo_documento_numero_label" style="float:left"><b style="float:left">&nbsp;<?= $reserva_dados[1]['externo_documento_numero'] ?></b></span> 
                                <span id="altera_externo_documento_numero" class='ui-icon ui-icon-pencil' style="float:left"></span>


                            </td>
                        </tr>
                    </table>
                </div>
            </div> 
            <div> 
            </div>
            <!--Percorre cada quarto item -->
            <?php
            foreach ($reserva_dados as $quarto_item) {

                $quartos_por_datas_vetor = explode("|", $reserva_dados[$quarto_item['quarto_item']]['quartos_por_datas']);
                $datas_por_quartos = array();
                $quartos_tipos_alocados = array();
                foreach ($quartos_por_datas_vetor as $quartos_por_data_item) {
                    $quartos_por_data_item = trim($quartos_por_data_item);
                    if ($quartos_por_data_item != '' && sizeof(explode(" ", $quartos_por_data_item)) >= 3 && strlen($quartos_por_data_item) > 10) {
                        $quarto_codigo = explode(" ", $quartos_por_data_item)[0];
                        $quarto_tipo_codigo = explode(" ", $quartos_por_data_item)[1];
                        $quartos_tipos_alocados[$quarto_codigo] = $quarto_tipo_codigo;
                        $data = explode(" ", $quartos_por_data_item)[2];
                        if (!array_key_exists($quarto_codigo, $datas_por_quartos))
                            $datas_por_quartos[$quarto_codigo][] = $data;
                        else {
                            $verifica_data_existe = array_search($data, $datas_por_quartos[$quarto_codigo]);
                            //Verifica se esta ocupado
                            if ($verifica_data_existe === false) {
                                $datas_por_quartos[$quarto_codigo][] = $data;
                            }
                        }
                    }
                }

                //Ordena as datas internas
                foreach ($datas_por_quartos as $key => $datas_quarto_item) {
                    usort($datas_por_quartos[$key], "date_sort");
                }
                //Ordena os quartos de acordo com suas datas
                uasort($datas_por_quartos, function($a, $b) {
                    return new DateTime($a[0]) <=> new DateTime($b[0]);
                });

                //Formata as datas
                foreach ($datas_por_quartos as $key => $datas_quarto_item) {
                    for ($i = 0; $i < sizeof($datas_por_quartos[$key]); $i++)
                        $datas_por_quartos[$key][$i] = Util::convertDataDMY($datas_por_quartos[$key][$i]);
                }

                $datas = explode("|", $reserva_dados[$quarto_item['quarto_item']]['datas']);

                $exibe_quarto = 0;
                if ($quarto_item_selecionado == $quarto_item['quarto_item'])
                    $exibe_quarto = 1;

                $string_alocacao = json_encode($datas_por_quartos);

                $string_quartos_tipos_alocados = json_encode($quartos_tipos_alocados);

                $quartos_alocados = array_keys($datas_por_quartos);
                ?>
                <div id="Exib_quarto_<?= $quarto_item['quarto_item'] ?>">
                    <div id="exibir_quarto_inner" class="dados_item2">
                        <div class="col-md-12 col-sm-12 info_quarto" style="margin-bottom:0px; margin-top:5px">
                            <div class="col-md-9 col-sm-10 <?php
                            if (!$exibe_quarto) {
                                echo 'exibi_info';
                            } else
                                echo 'escd_info';
                            ?>" onclick="<?php if ($exibe_quarto) { ?>
                                            escd_info_quartos('#Exib_quarto_<?= $quarto_item['quarto_item'] ?>');
                                 <?php } else { ?>
                                            exibi_info_quartos('#Exib_quarto_<?= $quarto_item['quarto_item'] ?>');
                                 <?php } ?>" >

                                <a ></a>

                                <strong><?= $rot_resquacod ?>  <?= $quarto_item['quarto_item'] ?> - 
                                    <span style="font-weight: normal"><?= $quarto_item['documento_status_nome'] ?></span>
                                </strong>
                            </div>
                            <div class="col-md-3 com-sm-2" style="padding: 10px 5px;text-align: right">
                                <?php
                                echo $this->element('reserva/acoes_reserva', ['quarto_item' => $quarto_item, 'string_alocacao' => $string_alocacao, 'string_quartos_tipos_alocados' => $string_quartos_tipos_alocados]);
                                ?>
                            </div>
                        </div>   

                    </div>    
                    <div class="panel col-md-12 col-sm-12 cinza"  <?php
                    if ($exibe_quarto)
                        echo "style='display:block'";
                    else
                        echo "style='display:none'";
                    ?>> 

                        <div class='branco'>

                            <div class="col-md-4 col-sm-12">
                                <label class="col-md-12 col-sm-12"><?= $rot_gerdattit ?>: <b><?= Util::convertDataDMY($quarto_item['inicial_data'], 'd/m/Y H:i') ?> - <?= Util::convertDataDMY($quarto_item['final_data'], 'd/m/Y H:i') ?></b> (<?= sizeof($datas) ?> <?php
                                    if (sizeof($datas) > 1)
                                        echo 'diárias';
                                    else
                                        echo 'diária';
                                    ?>)</label>  

                                <label class="col-md-12 col-sm-12"><?= $rot_gerdatrea ?>: <b>
                                        <?=
                                        (Util::convertDataDMY($quarto_item['real_inicial_data'], 'd/m/Y H:i') != "") ? Util::convertDataDMY($quarto_item['real_inicial_data'], 'd/m/Y H:i') :
                                                '&nbsp;'
                                        ?>  - <?= (Util::convertDataDMY($quarto_item['real_inicial_data'], 'd/m/Y H:i') != "") ? Util::convertDataDMY($quarto_item['real_final_data'], 'd/m/Y H:i') : '&nbsp;' ?> </b></label>


                                <span class="col-md-12 col-sm-12 "><?= $rot_clihostit ?> <b><?= $quarto_item['adulto_qtd_ajustada'] ?> / <?= $quarto_item['crianca_qtd_ajustada'] ?></b>
                                    <a href="#a" class="reshosatu link_ativo" data-empresa-codigo="<?= $quarto_item["empresa_codigo"] ?>"
                                       data-documento-numero="<?= $quarto_item["documento_numero"] ?>" data-quarto-item="<?= $quarto_item["quarto_item"] ?>">Revisar hóspedes</a></span>

                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label class="col-md-12 col-sm-12"><?= $rot_resqticon ?>: <b><?= $quarto_item['quarto_tipo_nome'] ?></b></label>
                                <label class="col-md-12 col-sm-12">
                                    <div style='border:0px solid #ff6600;display:block;float:left;'><?= $rot_resquacod ?>: </div>
                                    <div style='border:0px solid #ff6600;display:block;float:left; width:80%;'>
                                        <b>
                                            <?php
                                            $i = 0;
                                            unset($datas_por_quartos['*']);
                                            $len = count($datas_por_quartos);
                                            if ($len > 1) {
                                                foreach ($datas_por_quartos as $key => $datas_do_quarto) {

                                                    if ($i == $len - 1)
                                                        echo $key . ' (' . implode(', ', $datas_do_quarto) . ') ';
                                                    else
                                                        echo $key . ' (' . implode(', ', $datas_do_quarto) . '), ';
                                                    $i++;
                                                }
                                            } else
                                                echo array_keys($datas_por_quartos)[0];
                                            ?>
                                        </b>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="col-md-12 col-sm-12">
                                <?= $rot_restiptar ?>: <b><?= $quarto_item['tarifa_tipo_nome'] ?></b></label>
                            <label class="col-md-12 col-sm-12">
                                <div style='border:0px solid #ff6600;display:block;float:left;'> <?= $rot_resadisel ?>:</div>
                                <div style='border:0px solid #ff6600;display:block;float:left; width:80%;'>
                                    <b>
                                        <?php
                                        $len = count($adicionais);
                                        for ($i = 0; $i < sizeof($adicionais); $i++) {
                                            if ($adicionais[$i]['quarto_item'] == $quarto_item['quarto_item']) {
                                                if ($i == $len - 1)
                                                    echo $adicionais[$i]['nome'] . " (" . intval($adicionais[$i]['produto_qtd']) . ') ';
                                                else
                                                    echo $adicionais[$i]['nome'] . " (" . intval($adicionais[$i]['produto_qtd']) . '); ';
                                            }
                                        }
                                        ?>
                                    </b>
                                </div>
                            </label>
                        </div>

                        <div class='col-md-12 col-sm-12 ' >
                            <table style="margin-top:5px;"><tr>
                                    <?php
                                    if (isset($quarto_item['hospedes'])) {
                                        foreach ($quarto_item['hospedes'] as $quarto_item_hospede) {
                                            if ($quarto_item_hospede["cliente_codigo"] != null) {
                                                ?>

                                                <td> <div class="" style='float:left;white-space: nowrap; margin-right:10px; width:220px; positivo:relative; '>
                                                        <a href="#a" class="clicadmod link_ativo" aria-cliente-codigo = '<?= $quarto_item_hospede["cliente_codigo"] ?>'><?= $quarto_item_hospede['nome'] ?? '' ?> <?= $quarto_item_hospede['sobrenome'] ?? '' ?></a>
                                                        <?php if ($quarto_item_hospede['email'] != null && $quarto_item_hospede['email'] != '') { ?>
                                                            <div style='float:left; margin-right:5px;'> <a class="link_ativo" href="mailto:<?= $quarto_item_hospede['email'] ?>" target="_top"><i class="reservas_icones fa fa-envelope-o fa-lg fa-fw mhp" style="font-size: 1.233333em;width: 0.9571429em; "></i></a>
                                                            <?php } ?>
                                                        </div>                                        
                                                    </div></td>

                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tr>
                            </table>
                        </div> 


                    </div>
                    <div class='branco  mt2' ><div class="col-md-12 col-sm-12 top3">
                            <div class="col-md-4 col-sm-6">

                                <div class='col-md-6 col-sm-12 ' style='padding:0px;'>
                                    <?php
                                    $reserva_confirmacao_tipo = "";
                                    $valor_tipo = "";
                                    //Verifica se a reserva está confirmada. Em caso positivo, desabilita a seleção de tipos de confirmação
                                    foreach ($var_rescnfdet[$quarto_item['quarto_item']] AS $ky => $value) {
                                        if ($reserva_dados[$quarto_item['quarto_item']]['confirmacao_codigo'] == $value['reserva_confirmacao_codigo']) {

                                            $reserva_confirmacao_tipo = $value['reserva_confirmacao_tipo'];
                                            $valor_tipo = $value["valor_tipo"];
                                        }
                                    }

                                    $confirmacao_disabed = "";
                                    if ($reserva_confirmacao_tipo == 1)
                                        $confirmacao_disabed = " readonly ";
                                    ?>

                                    <label class='control-label col-md-12 col-sm-12' for="rescnfdet" <?= $pro_rescnfdet ?>><?= $rot_rescnfdet ?></label>
                                    <select <?= $confirmacao_disabed ?> class='form-control rescnfdet' <?= $pro_rescnfdet ?> id="rescnfdet_<?= $quarto_item['quarto_item'] ?>" aria-quarto-item="<?= $quarto_item['quarto_item'] ?>" name="rescnfdet_<?= $quarto_item['quarto_item'] ?>">
                                        <?php
                                        foreach ($var_rescnfdet[$quarto_item['quarto_item']] AS $ky => $value) {
                                            $selected = '';
                                            if ($reserva_dados[$quarto_item['quarto_item']]['confirmacao_codigo'] == $value['reserva_confirmacao_codigo']) {
                                                $selected = ' selected ';
                                                $reserva_confirmacao_tipo = $value['reserva_confirmacao_tipo'];
                                            }
                                            ?>

                                            <option value="<?= $value["valor_tipo"] . "|" . $value["reserva_confirmacao_codigo"] ?>" <?= $selected ?>><?= $value["reserva_confirmacao_nome"] ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type='hidden' name="reserva_confirmacao_tipo_<?= $quarto_item['quarto_item'] ?>" id="reserva_confirmacao_tipo_<?= $quarto_item['quarto_item'] ?>" value='<?= $reserva_confirmacao_tipo ?>' />
                                    <input type='hidden' name="reserva_valor_tipo_<?= $quarto_item['quarto_item'] ?>" id="reserva_valor_tipo_<?= $quarto_item['quarto_item'] ?>" value="<?= $valor_tipo ?>" />

                                </div>
                                <div style='display:inline' id='div_dados_confirmacao_<?= $quarto_item['quarto_item'] ?>'>
                                    <label class='control-label col-md-6 col-sm-12' for="rescnfdet">  &nbsp;&nbsp;</label>
                                    <?php
                                    //Se não for reserva confirmada, exibe a data limite para confirmação
                                    if ($reserva_confirmacao_tipo != 1) {
                                        ?>
                                        <label class='control-label col-md-6 col-sm-12' for="rescnfdet"> ( Até
                                            <b><?= $var_rescnfsel[$quarto_item['quarto_item']]['texto'] ?> )</b>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">

                                <div class='col-md-12 col-sm-12'>

                                    <label class='control-label col-md-12 col-sm-12' for="rescnfdet">&nbsp;&nbsp</label>
                                    <label class='control-label col-md-12 col-sm-12' for="rescnfdet"> À partir  de<b> 	
                                            <?php echo date('d/m/Y H:i', strtotime($quarto_item['cancelamento_limite'])); ?></b>, multa de <b><?= $moeda_simbolo . " " ?> <?= $geral->gersepatr($quarto_item['cancelamento_valor'] ?? '') ?></b>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 top1" style='padding-left:10px;'>
                                <?php
                                $partidas_total = 0;
                                foreach ($partidas_por_quarto_item[$quarto_item['quarto_item']] as $partida) {
                                    $partidas_total += $partida['partida_valor'];
                                }
                                ?>
                                <label class='col-md-12 col-sm-12'>
                                    Valor total de   <strong> <?= $moeda_simbolo ?> <?= $geral->gersepatr($partidas_total) ?></strong>, parcelado em
                                </label>

                                <table class="table_resadisel">
                                    <tbody>

                                        <?php foreach ($partidas_por_quarto_item[$quarto_item['quarto_item']] as $partida) { ?>
                                            <tr>
                                                <td><?= $partida['partida_item'] ?></td>
                                                <td><?= Util::convertDataDMY($partida['partida_liquidacao_data']) ?></td>
                                                <td><?= $moeda_simbolo ?> <?= $geral->gersepatr($partida['partida_valor']) ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class='col-md-12 col-sm-12'>
                            <div class='col-md-8 col-sm-12' style='padding:0px;'>
                                <label class='control-label col-md-12 col-sm-12' for="resmsghot_<?= $quarto_item['quarto_item'] ?>" ><?= $rot_resmsghot ?></label> 
                                <div class='col-md-12 col-sm-12'>
                                    <input class='form-control' type="text" name="resmsghot_<?= $quarto_item['quarto_item'] ?>" id="resmsghot_<?= $quarto_item['quarto_item'] ?>" placeholder="<?= $for_resmsghot ?>" value="<?= $quarto_item['texto'] ?>" />
                                </div>
                            </div>

                            <div class='col-md-4 col-sm-12'>
                                <label class='control-label col-md-12 col-sm-12' for="resmsgcam_<?= $quarto_item['quarto_item'] ?>" ><?= $rot_resmsgcam ?></label> 
                                <div class='col-md-12 col-sm-12'>
                                    <input class='form-control' type="text" name="resmsgcam_<?= $quarto_item['quarto_item'] ?>" id="resmsgcam_<?= $quarto_item['quarto_item'] ?>" value="<?= $quarto_item['camareira_texto'] ?>"  />
                                </div>
                            </div>


                        </div>


                    </div>
                </div>


                <input type="hidden" name="tarifa_tipo_codigo_<?= $quarto_item['quarto_item'] ?>" id="tarifa_tipo_codigo_<?= $quarto_item['quarto_item'] ?>" value="<?= $quarto_item['tipo_tarifa_codigo'] ?>" />
                <input type="hidden" name="inicial_data_<?= $quarto_item['quarto_item'] ?>" id="inicial_data_<?= $quarto_item['quarto_item'] ?>" value="<?= $quarto_item['inicial_data'] ?>" />
                <input type="hidden" name="final_data_<?= $quarto_item['quarto_item'] ?>" id="final_data_<?= $quarto_item['quarto_item'] ?>" value="<?= $quarto_item['final_data'] ?>" />
                <input type="hidden" name="resaduqtd_<?= $quarto_item['quarto_item'] ?>" id="resaduqtd_<?= $quarto_item['quarto_item'] ?>" value="<?= $quarto_item['adulto_quantidade'] ?>" />
                <input type="hidden" name="resdocsta_<?= $quarto_item['quarto_item'] ?>" id="resdocsta_<?= $quarto_item['quarto_item'] ?>" value="<?= $quarto_item['quarto_status_codigo'] ?>" />
                <input type="hidden" name="rescriqtd_<?= $quarto_item['quarto_item'] ?>" id="rescriqtd_<?= $quarto_item['quarto_item'] ?>" value="<?= $quarto_item['crianca_quantidade'] ?>" />
                <input type="hidden" name="resresdat_<?= $quarto_item['quarto_item'] ?>" id="rescriqtd_<?= $quarto_item['quarto_item'] ?>" value="<?= $quarto_item['crianca_quantidade'] ?>" />
                <input type=hidden id='prazo_<?= $quarto_item['quarto_item'] ?>' name='prazo_<?= $quarto_item['quarto_item'] ?>' value='<?= $quarto_item['pagamento_prazo_codigo'] ?>'>

                <!--Armazena as informações da primeira partida de cada quarto item -->
                <?php
                foreach ($partidas_por_quarto_item[$quarto_item['quarto_item']] as $partida) {
                    if ($partida['partida_item'] == 1) {
                        ?>
                        <input type=hidden id='valor_parcela1_<?= $partida['quarto_item'] ?>_<?= $quarto_item['pagamento_prazo_codigo'] ?>' name='valor_parcela1_<?= $partida['quarto_item'] ?>_<?= $quarto_item['pagamento_prazo_codigo'] ?>' value='<?= Util::uticonval_us_br($partida['partida_valor']) ?>'>
                    <?php } ?>
                <?php } ?>
                </div>
            <?php } ?>

            <?php if (sizeof($reserva_dados) > 0) { ?>
                <input type="hidden" name="clicadcod" id="c_codigo" value="<?= $reserva_dados[1]['cliente_codigo'] ?>" />
                <input type="hidden" name="clicpfcnp" id="clicpfcnp" value="<?php
                if ($reserva_dados[1]['cpf'] != '')
                    echo $reserva_dados[1]['cpf'];
                else
                    echo $reserva_dados[1]['cnpj'];
                ?>" />
                <input type="hidden" name="cliprinom" id="c_nome_autocomplete" value="<?= $reserva_dados[1]['nome'] ?>" />
                <input type="hidden" name="clisobnom" id="clisobnom" value="<?= $reserva_dados[1]['sobrenome'] ?>" />
                <input type="hidden" name="resquaqtd" id="resquaqtd" value="<?= sizeof($reserva_dados) ?>" />
            <?php } ?>

            <?php
            echo $this->element('reserva/pagamentos_reserva', []);
            ?>
            <div class="form-group">
                <div class="col-md-2 col-sm-4">
                    <input class="form-control btn-default" style="float:left; margin-right:10px" type="button" value="<?= $rot_gerdesbot ?>" onclick="$('.voltar').click()" >
                </div>
                <div class="col-md-2 col-sm-4 ">
                    <input class=" form-control  btn-default  <?= $ace_gerlogexi ?>" type="button" value="Logs" onclick="abreDialogLogs('exibe-logs')"/>
                </div>


                <div class="pull-right col-md-2 col-sm-4">
                    <input class="form-control btn-primary <?= $ace_resdocmod ?>" style="display:none" aria-form-id='resdocmod' onclick="resdocval()" type="button" id="resmodbtn" name="resmodbtn" <?= $disabled ?> value="<?= $rot_gersalbot ?>">
                </div>
            </div>
            <br/> 

        </form>
    </div>
</div>

<div id="dialogs" >
    <div id="motivo-cancelamento" style="display:none"> 
        <form>
            <table style="margin-top:10px">
                <span style="font-size:15px;"><?= $rot_rescncdoc ?> <strong id="documento_quarto_item_cancelar"></strong></span>
                <input type="hidden" id="documento-numero-canc" value="" />
                <input type="hidden" id="quarto-item-canc" value="" />
                <input type="hidden" id="empresa-codigo-canc" value="" />

                <tr id="row-motivo-codigo">
                    <td><label for="cancelamento-motivo-codigo" id="cancelamento-motivo-codigo-lbl" ><?= $rot_germottit ?>:</label></td>
                    <td><select id="cancelamento-motivo-codigo" name="cancelamento_motivo_codigo">
                            <?php
                            foreach ($cancelamento_motivos as $item) {
                                ?>
                                <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select></td></tr>
                <tr id="row-motivo-texto">
                    <td><label for="cancelamento-motivo-texto" id="cancelamento-motivo-texto-lbl" ><?= $rot_gerobstit ?>:</label></td>
                    <td><textarea cols="31" id="cancelamento-motivo-texto" name="cancelamento_motivo_texto" maxlength="50"></textarea></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<div style="padding-left:20px"> 
    <label class="control-label col-md-12 col-sm-12">Criado por <b><?= $reserva_dados[1]['usuario_criacao_nome'] ?></b>    em <b><?= Util::convertDataDMY($reserva_dados[1]['criacao_data'], 'd/m/Y H:i') ?></b>
    </label>
</div> 
<div id="exibe-logs">

</div>

<div id="exibe-comunicacoes">

</div>