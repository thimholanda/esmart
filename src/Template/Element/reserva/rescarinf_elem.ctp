<?php

use App\Model\Entity\Geral;
use Cake\Network\Session;

$geral = new Geral();
$session = new Session();
?>
<?php
if ($pagina == 'rescliide' || $pagina == 'respdrcri') {
    ?> 
    <script type="text/javascript">
        /* RESERVA EXPIRAÇÃO RESEXPINI */
        if ($('#atual_pagina').val() == 'reservas/rescliide' || $('#atual_pagina').val() == '/reservas/respaiatu') {
            window.clearInterval(var_interval);
            var timer_id = 0;
            var var_interval = window.setInterval('restemcon()',<?= ($reserva_intervalo * 1000) ?>);
            var var_reserva_expiracao =<?= $reserva_expiracao ?>;
            var var_reserva_intervalo =<?= $reserva_intervalo ?>;
        }
    </script>
<?php }
?>
<div id="carrinho" class="col-md-3-5 col-sm-12" style="<?php if ($pagina == 'respdrcri') echo 'margin-top:-7px!important' ?>">
    <div id="cabecalho_reserva" class="col-md-3 col-sm-3" style="position:fixed">
        <div class="cab_res_inner col-md-9 col-sm-9">
            <h1 style="margin:0;"><?= $rot_resrestit ?> </h1> 
        </div>
        <div class="col-md-3">
            <?php if ($pagina == 'rescliide' || $pagina == 'respdrcri') { ?> 
                <div id="reserva_expiracao">
                    <?php
                    $horas = floor($reserva_expiracao / 60 / 60);
                    $minutos = floor($reserva_expiracao / 60) - ($horas * 60);
                    $segundos = $reserva_expiracao - (60 * $minutos);
                    if ($horas <= 9)
                        $horas = '0' . $horas;
                    if ($minutos <= 9)
                        $minutos = '0' . $minutos;

                    if ($segundos <= 9)
                        $segundos = '0' . $segundos;
                    ?>
                    <strong>tempo restante:<label class="reser_exp_inner">  <?= $horas ?>:<?= $minutos ?>:<?= $segundos ?> </label></strong>
                </div> 
            <?php } ?>
        </div>
        <div class="car_det col-md-12 col-sm-12">
            <div class="car_det_top col-md-12 col-sm-12">
                <div class="car_det_1">
                    <div style="float:left">
                        <label style="float:right; margin-bottom: 0px;"><b><?= $rot_gerddetit ?>:</b> <span id="inicial_data_carrinho"><?= $inicial_data_completa ?></span></label><br/> 
                        <label style="float:right; margin-bottom: 0px;"><b><?= $rot_geraaatit ?>:</b> <span id="final_data_carrinho"><?= $final_data_completa ?></span></label>
                    </div>
                </div>
                <div class="car_det_2">
                    <b><?= $rot_gernoitit ?><?php
                        if ($dias_estadia > 1) {
                            echo 's';
                        }
                        ?></b>
                    <br/><?= $dias_estadia ?>
                </div>
            </div>
            <div class="clear"></div>

            <?php
            $total_hospedes = 0;
            $total_tarifas = 0;
            $total_adicionais_com_taxa = 0;
            $indice_quarto = 1;
            for ($quarto_item = 1; $quarto_item <= $resquaqtd; $quarto_item++) {
                //Exibicao normal e incrementa o indice
                if (($pagina == 'resquatar' && !isset($resadisel_volta) && !isset($rescliide_volta)) || ((($pagina == 'resquatar' && (isset($resadisel_volta) || isset($resadisel_volta))) || ($pagina != 'resquatar')) && $quarto_item_removido_array[$quarto_item] != 1 && $quarto_item_sem_tarifas_array[$quarto_item] != 1)) {
                    ?>
                    <div id="info_quarto_item_<?= $quarto_item ?>" class="car_det_quat">
                        <?php if ($resquaqtd > 1) { ?>
                            <div>
                                <a href="#a" onclick="
                                                    //Verifica se é o último item do carrinho
                                                    total_itens_carrinho = 0;
                                                    for (i = 1; i <= $('#resquaqtd').val(); i++) {
                                                        if ($('#quarto_item_removido_' + i).val() == 0)
                                                            total_itens_carrinho++;
                                                    }

                                                    //se for o último
                                                    if (total_itens_carrinho <= 1)
                                                        gerpagexi('/reservas/rescriini', 1, {});
                                                    else {

                                                        $('#quarto_item_<?= $quarto_item ?>').remove();
                                                        $('#total_preco_txt').text(gervalexi(gervalper($('#total_preco_txt').text()) -
                                                                gervalper($('#tarifa_valor_quarto_item_<?= $quarto_item ?>').text()) -
                                                                gervalper($('#total_adicionais_txt_<?= $quarto_item ?>').text())
                                                                ));
                                                        $('#total_original').val($('#total_original').val() - gervalper($('#tarifa_valor_quarto_item_<?= $quarto_item ?>').text()));

                                                        $('#quarto_tipo_nome_<?= $quarto_item ?>').val('');
                                                        $('#quarto_tipo_codigo_<?= $quarto_item ?>').val('');
                                                        $('#tarifa_nome_<?= $quarto_item ?>').val('');
                                                        $('#tarifa_valor_<?= $quarto_item ?>').val('');
                                                        $('#tarifa_tipo_codigo_<?= $quarto_item ?>').val('');
                                                        $('#info_quarto_item_<?= $quarto_item ?>').remove();
                                                        $('#total_preco').val(gervalper($('#total_preco_txt').text()));

                                                        for (i =<?= $quarto_item ?>; i <= $('#resquaqtd').val(); i++) {
                                                            $('#label_quarto_item_carrinho_' + i).text($('#label_quarto_item_carrinho_' + i).text() - 1);
                                                            $('#label_quarto_item_' + i).text($('#label_quarto_item_' + i).text() - 1);
                                                            $('#quarto_item_removido_<?= $quarto_item ?>').val(1);
                                                            $('#quarto_item_label_' + i).val($('#quarto_item_label_' + i).val() - 1);

                                                        }
                                                    }"          
                                   class="btn_can_quat">X</a> 
                                <strong><?= $rot_resquacod ?></strong> 
                                <strong>
                                    <span id="label_quarto_item_carrinho_<?= $quarto_item ?>"><?= $indice_quarto ?></span>
                                </strong>
                            </div>
                        <?php } ?> 
                        <div class="car_pax">
                            <?php
                            $total_hospedes += $this->request->data['resaduqtd_' . $quarto_item] + $this->request->data['rescriqtd_' . $quarto_item];
                            ?>
                            <span id="carrinho_pax_ajustado_adultos_txt_<?= $quarto_item ?>"><?= $this->request->data['resaduqtd_' . $quarto_item] ?></span> / 
                            <span id="carrinho_pax_ajustado_criancas_txt_<?= $quarto_item ?>"><?= $this->request->data['rescriqtd_' . $quarto_item] ?></span>
                            <span id="carrinho_idade_criancas_txt_<?= $quarto_item ?>">
                                <?php
                                $quantidade_criancas = $this->request->data['rescriqtd_' . $quarto_item];
                                if ($quantidade_criancas > 0) {
                                    echo "(";
                                }
                                for ($i = 0; $i < $quantidade_criancas; $i++) {
                                    echo $this->request->data['crianca_idade_' . $quarto_item . '_' . $i];
                                    if ($i < $quantidade_criancas - 1)
                                        echo ',';
                                }
                                if ($quantidade_criancas > 0)
                                    echo ")";

                                echo " - ";
                                ?>
                            </span>
                        </div>
                        <span id="quarto_tipo_nome_quarto_item_<?= $quarto_item ?>" class="car_tipo_quat">
                            <?php
                            if (isset($this->request->data['quarto_tipo_nome_' . $quarto_item])) {
                                echo $this->request->data['quarto_tipo_nome_' . $quarto_item];
                            }
                            ?>
                        </span><br/>
                        <span id="tarifa_nome_quarto_item_<?= $quarto_item ?>">
                            <?php
                            if (isset($this->request->data['tarifa_nome_' . $quarto_item])) {
                                echo $this->request->data['tarifa_nome_' . $quarto_item] . ': ';
                            }
                            ?>
                        </span> 
                        <strong>
                            <span id="tarifa_moeda_quarto_item_<?= $quarto_item ?>">
                                <?php
                                if (isset($this->request->data['tarifa_valor_' . $quarto_item])) {
                                    echo $geral->germoeatr();
                                }
                                ?>
                            </span> 

                            <span id="tarifa_valor_quarto_item_<?= $quarto_item ?>">
                                <?php
                                if (isset($this->request->data['tarifa_valor_' . $quarto_item])) {
                                    echo $geral->gersepatr($this->request->data['tarifa_valor_' . $quarto_item]);
                                    $total_tarifas += $this->request->data['tarifa_valor_' . $quarto_item];
                                }
                                ?>
                            </span>
                        </strong><br/>
                        <?php
                        if ($pagina != 'resquatar') {
                            echo $rot_resadisel . ': ';
                            ?>  
                            <strong>
                                <?= $geral->germoeatr() ?>
                                <span id="total_adicionais_txt_<?= $quarto_item ?>">
                                    <?php
                                    if (isset($this->request->data['total_adicionais_' . $quarto_item])) {
                                        echo $geral->gersepatr($this->request->data['total_adicionais_' . $quarto_item]);
                                    } else
                                        echo $geral->gersepatr(0);
                                    ?>
                                </span>
                            </strong>
                            <br/>
                            <?php
                            if (isset($this->request->data['adicional_item_qtd_' . $quarto_item])) {
                                $total_adicionais = $this->request->data['adicional_item_qtd_' . $quarto_item];
                                for ($var_numero_cell = 1; $var_numero_cell <= $total_adicionais; $var_numero_cell++) {
                                    $adicional_qtd = $this->request->data['adicional_qtd_' . $quarto_item . '_' . $var_numero_cell];
                                    if ($adicional_qtd > 0) {
                                        ?>            
                                        <div style="margin-left: 15px;" id="div_info_adicionais_<?= $quarto_item ?>_<?= $var_numero_cell ?>">
                                            <span id="adicional_nome_<?= $quarto_item ?>_<?= $var_numero_cell ?>_txt"></span> 
                                            <span id="adicional_total_<?= $quarto_item ?>_<?= $var_numero_cell ?>_txt">
                                                <?php
                                                if ($this->request->data['servico_taxa_incide_' . $quarto_item . '_' . $var_numero_cell] == 1)
                                                    $total_adicionais_com_taxa += $this->request->data['adicional_total_' . $quarto_item . '_' . $var_numero_cell];
                                                echo $this->request->data['adicional_nome_' . $quarto_item . '_' . $var_numero_cell] . ': ' . $geral->germoeatr() . ' ' . $this->request->data['adicional_total_' . $quarto_item . '_' . $var_numero_cell];
                                                ?>
                                            </span>
                                        </div>
                                        <?php
                                    }
                                }
                            } else {
                                $var_numero_cell = 0;
                                if (isset($var_resadipro)) {

                                    foreach ($var_resadipro[$quarto_item] as $adicional) {
                                        ++$var_numero_cell;
                                        ?>
                                        <div style="margin-left: 15px;" id="div_info_adicionais_<?= $quarto_item ?>_<?= $var_numero_cell ?>">
                                            <span id="adicional_nome_<?= $quarto_item ?>_<?= $var_numero_cell ?>_txt"></span> 
                                            <span id="adicional_total_<?= $quarto_item ?>_<?= $var_numero_cell ?>_txt"></span>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div style="margin-left: 15px;"  id="div_info_adicionais_<?= $quarto_item ?>_1">
                                        <span id="adicional_nome_<?= $quarto_item ?>_1_txt"></span> 
                                        <span id="adicional_total_<?= $quarto_item ?>_1_txt"></span>
                                    </div>
                                    <?php
                                }

                                if (isset($adicionais_itens_inclusos)) {
                                    foreach ($adicionais_itens_inclusos[$quarto_item] as $adicional_incluso) {
                                        ++$var_numero_cell; ?>
                                        <div style="margin-left: 15px;" id="div_info_adicionais_<?= $quarto_item ?>_<?= $var_numero_cell ?>">
                                            <span id="adicional_nome_<?= $quarto_item ?>_<?= $var_numero_cell ?>_txt"><?= $adicional_incluso['nome'] ?></span> 
                                            <span id="adicional_total_<?= $quarto_item ?>_<?= $var_numero_cell ?>_txt"><?= $geral->germoeatr() ?>  <?= $geral->gersepatr($adicional_incluso['preco']) ?></span>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                    <?php
                    $indice_quarto++;
                } else {
                    //Quarto foi removido, nao exibe nem incrementa o indice
                    if ($quarto_item_removido_array[$quarto_item] == 1) {
                        
                    }
                    //Quarto sem tarifas, não exibe mas incrementa o indice
                    if (($pagina == 'resquatar' && !isset($resadisel_volta) && !isset($rescliide_volta)) && $quarto_item_sem_tarifas_array[$quarto_item] == 1)
                        $indice_quarto++;
                }
            }
            ?>

            <div style="padding: 15px 10px;" class="col-md-12 col-sm-12">
                <strong>
                    <?= $rot_respretot ?>: <?= $geral->germoeatr() ?>  
                    <span id="total_preco_txt">
                        <?php
                        if ($pagina == 'resquatar') {
                            if (isset($resadisel_volta) || isset($rescliide_volta))
                                echo $geral->gersepatr($this->request->data['total_original']);
                            else
                                echo $geral->gersepatr('0');
                        } else
                            echo $geral->gersepatr($total_preco);
                        ?>
                    </span>
                </strong>
            </div>

            <div id="total_servico_taxa_div" class="tam_let_info2" style="padding:15px 10px; <?php if ($pagina == 'resquatar' && !isset($resadisel_volta)) echo 'display:none' ?>">
                <?php
                //Se existe taxa de serviço ou turismo
                if ($session->read('servico_taxa') > 0 || $session->read('turismo_taxa') > 0) {
                    ?>
                    <?= $rot_resextest ?> <br/>
                    <span>
                        <?php
                        //Se existe taxa de turismo
                        if ($session->read('turismo_taxa') > 0) {
                            if ($session->read('hospede_taxa') == 1 && $session->read('diaria_taxa') == 1) {
                                ?>
                                <?= $rot_gertaxtur ?>:  <?= $geral->germoeatr() ?>  <span id="total_turismo_taxa"><?= $geral->gersepatr(($total_hospedes * $dias_estadia ) * $session->read('turismo_taxa')); ?></span>
                            <?php } else { ?>
                                <?= $rot_gertaxtur ?>:  <?= $geral->germoeatr() ?>  <span id="total_turismo_taxa"><?= $geral->gersepatr($session->read('turismo_taxa') * $session->read('hospede_taxa') * $total_hospedes + $session->read('turismo_taxa') * $session->read('diaria_taxa') * $dias_estadia); ?></span>
                            <?php } ?>
                        <?php } ?>

                        <?php
                        //Se existe taxa de serviço nas diárias ou adicionais
                        if ($session->read('servico_taxa_incide_diaria') != 0) {
                            ?>
                            <input type="hidden" id="servico_taxa" value="<?= $session->read('servico_taxa') ?>" />
                            <br/><?= $rot_gertaxser ?>:  <?= $geral->germoeatr() ?>  <span id="total_servico_taxa"><?= $geral->gersepatr(($session->read('servico_taxa') * ($total_tarifas + $total_adicionais_com_taxa)) / 100); ?></span>
                        <?php } ?>
                    </span>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
