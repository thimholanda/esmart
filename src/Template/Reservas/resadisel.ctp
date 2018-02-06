<?php

use App\Model\Entity\Geral;
use Cake\Routing\Router;
use App\Utility\Util;

$geral = new Geral();
$path = Router::url('/', true) . "reservas/";
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<div id="resadisel_adicionais" class="col-md-8-5 col-sm-12">
    <form method="POST" action="<?= $path ?>rescliide" id="resadipro" name="resadipro">  
        <input type="hidden" id="codigo_referencia_atual" value="0" />
        <input type="hidden" id="bloqueia_tela" value="1">
        <?php
        $quarto_item_removido_array = array();
        $quarto_item_sem_tarifas_array = array();
        for ($quarto_item = 1; $quarto_item <= $this->request->data['resquaqtd']; $quarto_item++) {
            $quarto_item_removido_array[$quarto_item] = $this->request->data['quarto_item_removido_' . $quarto_item];
            $quarto_item_sem_tarifas_array[$quarto_item] = $this->request->data['quarto_item_sem_tarifas_' . $quarto_item];
        }
        ?>

        <!--<label><?= $rot_resadisel ?></label>-->

        <?php
        $indice_quarto = 1;
        for ($quarto_item = 1; $quarto_item <= $resquaqtd; $quarto_item++) {
            //Exibicao normal e incrementa o indice
            if ($quarto_item_removido_array[$quarto_item] != 1 && $quarto_item_sem_tarifas_array[$quarto_item] != 1) {
                ?>
                                                                        <!--<table class='table-resadisel' id="quarto_item_<?= $quarto_item ?>" border=1>-->
                <div id="quarto_item_<?= $quarto_item ?>" class="dados_item1">
                    <?php //if ($resquaqtd > 1) { ?>
                    <div class="col-md-12 col-sm-12 info_quarto escd_info" onclick="escd_info_quartos('#quarto_item_<?= $quarto_item ?>');">
                        <div class="col-md-3 col-sm-12">
                            <a></a>
                            <strong><?= $rot_resquacod ?></strong> <strong><span id="label_quarto_item_<?= $quarto_item ?>"><?= $indice_quarto ?></span></strong>
                        </div>
                    </div>
                    <?php //} ?>

                    <div class="col-md-12 total_adc">

                        <?php
                        $var_numero_cell = 0;
                        $indice = 0;
                        foreach ($var_resadipro[$quarto_item] as $adicional_item => $adicional) {

                            if ($adicional['incluido'] == 0) {
                                ++$var_numero_cell;


                                //calcula o fator_fixo_valor
                                switch ($adicional['fixo_fator_codigo']) {
                                    case 1:
                                    case 2:
                                        $var_fator_fixo_valor = 1;
                                        break;
                                    case 3:
                                        $var_fator_fixo_valor = $resaduqtd + $rescriqtd;
                                        break;
                                    case 4:
                                        $var_fator_fixo_valor = $resaduqtd;
                                        break;
                                    case 5:
                                        $var_fator_fixo_valor = $dias_estadia;
                                        break;
                                    case 6:
                                        $var_fator_fixo_valor = ($resaduqtd + $rescriqtd) * $dias_estadia;
                                        break;
                                    case 7:
                                        $var_fator_fixo_valor = $resaduqtd * $dias_estadia;
                                        break;
                                    default:
                                }
                                ?>
                                <div class='col-md-12 col-sm-12 list_adc'>
                                    <div class="col-md-4 tam_let_info"><?= $var_numero_cell ?> - <?= $adicional["nome"] ?><br/>
                                        <span class='visible-lg-inline tam_let_info2'><?= $adicional["descricao"] ?></span>
                                    </div>
                                    <div class="col-md-1 tam_let_info tam_vertical">
                                        <span class="sifrao visible-lg-inline"><?= $geral->germoeatr() ?></span> <?= $adicional["preco"] ?>
                                    </div>
                                    <div class="col-md-3 tam_let_info2 tam_vertical">
                                        <span class='visible-lg-inline'><?= $adicional["preco_fator_nome"] ?></span>
                                    </div>
                                    <?php $var_resadimax = $var_resadimax_arr[$quarto_item][$indice]; ?>

                                    <div class="col-md-2 tam_let_info tam_vertical">
                                        <select class="produto_adicional" name="adicional_qtd_<?= $quarto_item ?>_<?= $var_numero_cell ?>" id="adicional_qtd_<?= $quarto_item ?>_<?= $var_numero_cell ?>" size=1 style="padding: 5px;" 
                                                onchange="resatuadi('<?= $resaduqtd[$quarto_item] ?>', '<?= $rescriqtd[$quarto_item] ?>', '<?= $dias_estadia ?>', '<?= $adicional["adicional_codigo"] ?>', '<?= $adicional['fixo_fator_codigo'] ?>', '<?= $adicional["preco"] ?>', this.value, '<?= $quarto_item ?>', '<?= $var_numero_cell ?>', '<?= $adicional["nome"] ?>')">
                                                    <?php
                                                    $quantidade_adicional = 0;
                                                    $valor_adicional = 0.0;

                                                    if (isset($rescliide_volta)) {
                                                        $quantidade_adicional = $this->request->data['adicional_qtd_' . $quarto_item . '_' . $var_numero_cell];
                                                        $valor_adicional = Util::uticonval_br_us($this->request->data['adicional_total_' . $quarto_item . '_' . $var_numero_cell]);
                                                    }

                                                    foreach ($var_resadimax AS $qtd_lista) {
                                                        ?>
                                                <option value="<?= $qtd_lista['valor'] ?>" <?php
                                                if ($qtd_lista['valor'] == $quantidade_adicional) {
                                                    echo 'selected';
                                                }
                                                ?>><?= $qtd_lista["texto"] ?></option>
                                                    <?php } ?>

                                        </select>
                                        <input type="hidden" id="produto_codigo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="produto_codigo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["adicional_codigo"] ?>" />
                                        <input type="hidden" id="adicional_fixo_fator_codigo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="adicional_fixo_fator_codigo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["fixo_fator_codigo"] ?>" />
                                        <input type="hidden" id="preco_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="preco_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["preco"] ?>" />
                                        <input type="hidden" id="adicional_nome_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="adicional_nome_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["nome"] ?>" />
                                        <input type="hidden" id="lancamento_tempo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="lancamento_tempo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["lancamento_tempo"] ?>" />
                                        <input type="hidden" id="servico_taxa_incide_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="servico_taxa_incide_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["servico_taxa_incide"] ?>" />
                                        <input type="hidden" id="horario_modificacao_tipo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="horario_modificacao_tipo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["horario_modificacao"]["horario_modificacao_tipo"] ?? null ?>" />
                                        <input type="hidden" id="horario_modificacao_valor_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="horario_modificacao_valor_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["horario_modificacao"]["horario_modificacao_valor"] ?? null ?>" />
                                        <input type="hidden" name="adicional_fator_fixo_valor_<?= $quarto_item ?>_<?= $var_numero_cell ?>" id="adicional_fator_fixo_valor_<?= $quarto_item ?>_<?= $var_numero_cell ?>" style="text-align:right;" value="<?= $var_fator_fixo_valor ?>" size=13 readonly>

                                        <span class='visible-lg-inline'><?= $adicional["variavel_fator_nome"] ?></span>
                                    </div>
                                    <div class="col-md-2 tam_let_info tam_vertical">
                                        <div class="adc_last">
                                            <?php
                                            $valor_original = $geral->gersepatr(0.0);
                                            if (isset($rescliide_volta)) {
                                                $adicional_desconto_tmp = $this->request->data['adicional_desconto_tmp_' . $quarto_item . '_' . $var_numero_cell];
                                                if ($adicional_desconto_tmp != 'd|0.00|p|0.00|||') {
                                                    $button_background = 'background: url(../img/lapis-2.png) no-repeat center center'; //'background-color: rgb(155, 190, 247)';
                                                    $valor_original = 0.0;
                                                    //verifica se foi dado desconto cortesia ou acrescimo
                                                    $desc_cort = explode("|", $adicional_desconto_tmp) [0];
                                                    $desc_valor = explode("|", $adicional_desconto_tmp) [3];
                                                    if ($desc_cort == 'd')
                                                        $valor_original = Util::uticonval_br_us($this->request->data['adicional_total_' . $quarto_item . '_' . $var_numero_cell]) + $desc_valor;
                                                    else if ($desc_cort == 'a')
                                                        $valor_original = Util::uticonval_br_us($this->request->data['adicional_total_' . $quarto_item . '_' . $var_numero_cell]) - $desc_valor;
                                                    else if ($desc_cort == 'c')
                                                        $valor_original = Util::uticonval_br_us($this->request->data['adicional_total_' . $quarto_item . '_' . $var_numero_cell]);
                                                } else {
                                                    $button_background = '';
                                                    $valor_original = Util::uticonval_br_us($this->request->data['adicional_total_' . $quarto_item . '_' . $var_numero_cell]);
                                                }

                                                $valor_original = $this->request->data['adicional_total_original_' . $quarto_item . '_' . $var_numero_cell];
                                            } else {
                                                $adicional_desconto_tmp = 'd|0.00|p|0.00|||';
                                                $button_background = '';
                                            }
                                            ?>

                                            <span class='sifrao visible-lg-inline'><?= $geral->germoeatr() ?> </span>
                                            <input type="text" name="adicional_total_<?= $quarto_item ?>_<?= $var_numero_cell ?>" id="adicional_total_<?= $quarto_item ?>_<?= $var_numero_cell ?>" class="adc_total" value="<?= $geral->gersepatr($valor_adicional) ?>" size=5 readonly>
                                            <input type="hidden" name="adicional_total_original_<?= $quarto_item ?>_<?= $var_numero_cell ?>" id="adicional_total_original_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $geral->gersepatr($valor_original) ?>">
                                            <input type="hidden" name="adicional_desconto_tmp_<?= $quarto_item ?>_<?= $var_numero_cell ?>" id="adicional_desconto_tmp_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional_desconto_tmp ?>">

                                            <button type="button" id="adicional_btn_<?= $quarto_item ?>_<?= $var_numero_cell ?>" title="Modificar valores" class="btn-editar condesapl_adicionais" style="<?= $button_background ?> " aria-adicional-referencia-id='<?= $quarto_item ?>_<?= $var_numero_cell ?>'>
                                                <!--a class="btn-editar-inner"><span class='ui-icon ui-icon-pencil'></span></a-->
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $indice++;
                            }
                        }
                        //Percorre os adicionais incluidos
                        $quantidade_adicionais_inclusos = array_count_values(array_column($var_resadipro[$quarto_item], 'incluido'));
                        //se tem itens adicionais inclusos
                        if (isset($quantidade_adicionais_inclusos[1]) && $quantidade_adicionais_inclusos[1] > 0) {
                            ?>
                            <div class="col-md-12 list_adc_incl">
                                <strong class="col-md-12 col-sm-12 title_topic"><?= $rot_resadiinc ?></strong>
                                <?php
                                foreach ($var_resadipro[$quarto_item] as $adicional_item => $adicional) {
                                    if ($adicional['incluido'] == 1) {
                                        ++$var_numero_cell;


                                        //calcula o fator_fixo_valor
                                        switch ($adicional['fixo_fator_codigo']) {
                                            case 1:
                                            case 2:
                                                $var_fator_fixo_valor = 1;
                                                break;
                                            case 3:
                                                $var_fator_fixo_valor = $resaduqtd + $rescriqtd;
                                                break;
                                            case 4:
                                                $var_fator_fixo_valor = $resaduqtd;
                                                break;
                                            case 5:
                                                $var_fator_fixo_valor = $dias_estadia;
                                                break;
                                            case 6:
                                                $var_fator_fixo_valor = ($resaduqtd + $rescriqtd) * $dias_estadia;
                                                break;
                                            case 7:
                                                $var_fator_fixo_valor = $resaduqtd * $dias_estadia;
                                                break;
                                            default:
                                        }
                                        ?>
                                        <div class='col-md-12 col-sm-12 list_adc2'>
                                            <div class="col-md-4 tam_let_info"><?= $adicional["nome"] ?><br/>
                                                <span class='visible-lg-inline tam_let_info2'><?= $adicional["descricao"] ?></span>
                                            </div>
                                            <div class="col-md-1 tam_let_info tam_vertical">
                                                <span class="sifrao visible-lg-inline"><?= $geral->germoeatr() ?></span> <?= $geral->gersepatr(0) ?>
                                            </div>
                                            <div class="col-md-3 tam_let_info2 tam_vertical">
                                                <span class='visible-lg-inline'><?= $adicional["preco_fator_nome"] ?></span>
                                            </div>
                                            <?php                                            
                                            $var_resadimax = $var_resadimax_arr[$quarto_item][$adicional_item]; ?>

                                            <div class="col-md-2 tam_let_info tam_vertical">
                                                <?= $var_resadimax[sizeof($var_resadimax) - 1]['valor'] ?>
                                                <input type="hidden"  name="adicional_qtd_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $var_resadimax[sizeof($var_resadimax) - 1]['valor'] ?>">
                                                <input type="hidden" id="adicional_nome_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="adicional_nome_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["nome"] ?>" />
                                                <input type="hidden" id="lancamento_tempo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="lancamento_tempo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["lancamento_tempo"] ?>" />
                                                <input type="hidden" id="produto_codigo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="produto_codigo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["adicional_codigo"] ?>" />
                                                <input type="hidden" id="adicional_fixo_fator_codigo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="adicional_fixo_fator_codigo_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["fixo_fator_codigo"] ?>" />
                                                <input type="hidden" id="preco_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="preco_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="0" />
                                                <input type="hidden" id="servico_taxa_incide_<?= $quarto_item ?>_<?= $var_numero_cell ?>" name="servico_taxa_incide_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="<?= $adicional["servico_taxa_incide"] ?>" />
                                                <input type="hidden" name="adicional_fator_fixo_valor_<?= $quarto_item ?>_<?= $var_numero_cell ?>" id="adicional_fator_fixo_valor_<?= $quarto_item ?>_<?= $var_numero_cell ?>" style="text-align:right;" value="<?= $var_fator_fixo_valor ?>" size=13 readonly>

                                                <span class='visible-lg-inline'><?= $adicional["variavel_fator_nome"] ?></span>
                                            </div>
                                            <div class="col-md-2 tam_let_info tam_vertical">
                                                <div class="adc_last">
                                                    <span class="sifrao visible-lg-inline"><?= $geral->germoeatr() ?> </span>
                                                    <input type="text" name="adicional_total_<?= $quarto_item ?>_<?= $var_numero_cell ?>" id="adicional_total_<?= $quarto_item ?>_<?= $var_numero_cell ?>" class="adc_total" value="<?= $geral->gersepatr(0) ?>" size=5 readonly>
                                                    <input type="hidden" name="adicional_total_original_<?= $quarto_item ?>_<?= $var_numero_cell ?>" id="adicional_total_original_<?= $quarto_item ?>_<?= $var_numero_cell ?>">
                                                    <input type="hidden" name="adicional_desconto_tmp_<?= $quarto_item ?>_<?= $var_numero_cell ?>" id="adicional_desconto_tmp_<?= $quarto_item ?>_<?= $var_numero_cell ?>" value="d|0.00|p|0.00|||">
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $indice++;
                                    }
                                }
                                ?>
                            </div>    
                            <?php
                        }
                        ?>
                    </div>
                    <input type="hidden" name="adicional_item_qtd_<?= $quarto_item ?>" id="adicional_item_qtd_<?= $quarto_item ?>" value="<?= $indice ?>">
                </div>
                <?php
                $indice_quarto++;
            }
        }
        ?>

        <!--Armazena os dados da página anterior -->
        <?php
        foreach ($this->request->data as $campo => $valor) {
            if (!(strpos($campo, 'adicional_qtd_') !== false) && !(strpos($campo, 'adicional_nome_') !== false) && !(strpos($campo, 'adicional_total_') !== false) && !(strpos($campo, 'total_original') !== false) && !(strpos($campo, 'adicional_desconto_tmp') !== false) && !(strpos($campo, 'resentdat') !== false) && !(strpos($campo, 'ressaidat') !== false)) {
                ?>
                <input type="hidden" id="<?= $campo ?>" name="<?= $campo ?>" value="<?= $valor ?>" />
            <?php } else {
                ?>
                <input type="hidden" name="<?= $campo ?>" value="<?= $valor ?>" />
                <?php
            }
        }
        ?>

        <input type="hidden" name="total_original" id="total_original" value="<?php
        if (!isset($rescliide_volta)) {
            echo $total_preco;
        } else {
            echo $total_original;
        }
        ?>">

        <input type="hidden" name="resadisel_volta" id="resadisel_volta" value="1">

        <?php
        if (!isset($rescliide_volta)) {
            for ($quarto_item = 1; $quarto_item <= $resquaqtd; $quarto_item++) {
                //Somente quartos nao removidos ou que tenham tarifas
                if ($quarto_item_removido_array[$quarto_item] != 1 && $quarto_item_sem_tarifas_array[$quarto_item] != 1) {
                    ?>
                    <input type="hidden" name="total_adicionais_<?= $quarto_item ?>" id="total_adicionais_<?= $quarto_item ?>" value="0">
                    <input type="hidden" name="tarifa_quarto_item_<?= $quarto_item ?>" value="<?= eval('return $quarto_tipo_codigo_' . $quarto_item . ';'); ?>_<?= eval('return $tarifa_tipo_codigo_' . $quarto_item . ';'); ?>">
                    <?php
                }
            }
        }
        ?>

        <div class="row col-md-12 col-sm-12 quat_botoes">
            <div class="col-md-6 col-sm-4"></div>
            <div class="cancel-right col-md-3 col-sm-4">
                <input class="submit-button btn-default form-control" id="reserva_voltar" type="submit" name="resmodbtn" aria-form-id="resadipro"  value="<?= $rot_gervolbot ?>" onclick="$('#resadipro').attr('action', '<?= $path ?>resquatar')" >
            </div>
            <div class="pull-left col-md-3 col-sm-4">
                <input aria-form-id='resadipro' type="submit" id="resadisel_avancar" class="form-control btn-primary submit-button"  value="<?= $rot_geravabot ?>">          
            </div>
        </div>
    </form>
</div>

<?php
echo $this->element('reserva/rescarinf_elem', ['pagina' => 'resadisel', 'quarto_item_removido_array' => $quarto_item_removido_array,
    'quarto_item_sem_tarifas_array' => $quarto_item_sem_tarifas_array, 'adicionais_itens_inclusos' => $adicionais_itens_inclusos]);
?>