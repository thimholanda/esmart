<?php

use App\Model\Entity\Geral;

$geral = new Geral();
?>
<div id="restardia">
    <form class="form-horizontal">
        <input type="hidden" id="codigo_referencia_atual_diarias" value="0" />
        <input type="hidden" id="codigo_referencia_atual_total" value="" />
        <div id="restardia-table" class="table col-md-12 col-sm-12">
            <input type="hidden" id="restardia_quarto_item_atual" value="<?= $quarto_item ?>" />
            <?php
            $total_tarifas = 0;
            $quarto_tipo_codigo = $quarto_e_tipo_codigos[0];
            foreach ($tarifa_tipos[$quarto_tipo_codigo] as $tarifa_dados) {
                ?>
                <div class="valor_esc col-md-12 col-sm-12">
                    <?php
                    $diaria_indice = 1;
                    foreach ($tarifa_dados as $key => $tarifa_info) {
                        if ($key != 'tarifa_tipo_nome' && $key != 'condicao' && $key != 'total_tarifa' && $key != 'tarifa_tipo_codigo') {
                            //Verifica se a restardia ja foi aberta anteriormente, nesse caso os valores de desconto já foram adicionados a info_diaria
                            $vetor_info_diarias = explode("|", $info_diarias[$diaria_indice - 1]);
                            if (sizeof($vetor_info_diarias) > 3) {
                                $tarifa_desconto_tmp = $vetor_info_diarias[2] . '|' . $vetor_info_diarias[3] . '|' . $vetor_info_diarias[4] . '|' . $vetor_info_diarias[5] . '|' . $vetor_info_diarias[6] . '|' .
                                        $vetor_info_diarias[7] . '|' . $vetor_info_diarias[8];
                                $button_background = 'background: url(../img/lapis-2.png) no-repeat center center';
                            } else {
                                $tarifa_desconto_tmp = 'd|0.00|p|0.00|||';
                                $button_background = '';
                            }

                            $desconto_valor = explode("|", $tarifa_desconto_tmp)[3];

                            //O valor da tarifa sempre fica atualizado na info_diarias (decrementando descontos se tiver)                              
                            //$tarifa_txt = $vetor_info_diarias[1] - $desconto_valor;
                            $tarifa_txt = $vetor_info_diarias[1];
                            ?>
                            <div class="col-md-6 col-sm-6" style="text-align:right">Diária <?php echo $diaria_indice . " " ?>: <?= date('d/m/Y', strtotime($key)) ?></div>
                            <?php if ($tarifa_manual_entrada == 0) { ?>
                                <div class="col-md-3 col-sm-3">                                    
                                    <span class="tarifa_txt" id="tarifa_txt_<?= $quarto_item . '_' . $quarto_tipo_codigo . '_' . $tarifa_dados['tarifa_tipo_codigo'] . '_' . $diaria_indice ?>" 
                                          style="float:right"> <?= $geral->gersepatr($tarifa_txt) ?></span>
                                    <span style="float:right"><?= $geral->germoeatr() . " " ?>  </span>
                                </div> 
                            <?php } else { ?>
                                <div class="col-md-1 col-sm-1">     
                                    <span><?= $geral->germoeatr() . " " ?>  </span>
                                </div>
                                <div class="col-md-3 col-sm-3">                                    

                                    <input style="margin-top: -6px;" type="text" onfocus="this.select();" class="form-control moeda tarifa_manual_entrada" 
                                           id="tarifa_txt_<?= $quarto_item . '_' . $quarto_tipo_codigo . '_' . $tarifa_dados['tarifa_tipo_codigo'] . '_' . $diaria_indice ?>" 
                                           style="float:right" value="<?= $geral->gersepatr($tarifa_txt) ?>"
                                           aria-tarifa-referencia-id='<?= $quarto_item . '_' . $quarto_tipo_codigo . '_' . $tarifa_dados['tarifa_tipo_codigo'] . '_' . $diaria_indice ?>' /> 
                                </div> 

                            <?php } ?>
                            <div class="col-md-1 col-sm-1">
                                <input type="hidden" id="tarifa_diaria_<?= $quarto_item . '_' . $quarto_tipo_codigo . '_' . $tarifa_dados['tarifa_tipo_codigo'] . '_' . $diaria_indice ?>" value="<?= $geral->gersepatr($tarifa_txt) ?>" />
                                <input type="hidden" id="tarifa_desconto_tmp_<?= $quarto_item . '_' . $quarto_tipo_codigo . '_' . $tarifa_dados['tarifa_tipo_codigo'] . '_' . $diaria_indice ?>" value="<?= $tarifa_desconto_tmp ?>" />
                                <button tabindex="-1" type="button" title="Modificar Valores" id="tarifa_btn_<?= $quarto_item . '_' . $quarto_tipo_codigo . '_' . $tarifa_dados['tarifa_tipo_codigo'] . '_' . $diaria_indice ?>"
                                        class="btn-editar condesapl_diarias_respdrcri"  style="<?= $button_background ?> <?php if ($tarifa_txt == 0) echo '; display:none' ?>"
                                        aria-tarifa-referencia-id='<?= $quarto_item . '_' . $quarto_tipo_codigo . '_' . $tarifa_dados['tarifa_tipo_codigo'] . '_' . $diaria_indice ?>'
                                        aria-desconto-total='0'>
                                </button>
                            </div>
                            <?php
                            $diaria_indice++;
                            $total_tarifas += $tarifa_txt;
                        }
                    }
                    ?>
                </div>
                <?php
            }
            ?>

        </div>
        <div class="col-md-6 col-sm-6" style="text-align:right">
            <strong>Total </strong>
        </div>
        <div class="col-md-1 col-sm-1">     
            <span><?= $geral->germoeatr() . " " ?>  </span>
        </div>
        <div class="col-md-3 col-sm-3">
            <!--<input type="hidden" id="total_tarifas_restardia_original" value="<?= $total_tarifas ?>" />-->
            <span id="total_tarifas_restardia"><?= $geral->gersepatr($total_tarifas) ?></span>
        </div>
        <div class="col-md-1 col-sm-1">
            <button tabindex="-1" type="button" title="Aplicar desconto geral" id="total_btn_<?= $quarto_item . '_' . $quarto_tipo_codigo . '_' . $tarifa_tipo_codigo ?>" class="btn-editar condesapl_diarias_respdrcri" 
                    aria-desconto-total='1' style=" <?php if ($total_tarifas == 0) echo '; display:none' ?>">
            </button>
        </div>

        <div class="row col-md-12 col-sm-12 quat_botoes2">
            <div class="col-md-6 col-sm-4"></div>
            <div class="cancel-right col-md-3 col-sm-4 ui-dialog-btn-close">
                <input class="form-control btn-default close_dialog" style="float:left; margin-right:10px" type="button" value="<?= $rot_gerdesbot ?>">
            </div>
            <div class="pull-left col-md-3 col-sm-4">
                <input class="form-control btn-primary condessal_diarias_respdrcri" type="button" name="resmodbtn"  value="<?= $rot_gersalbot ?>">
            </div>
        </div>
    </form>
</div>