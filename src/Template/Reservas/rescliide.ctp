<?php

use App\Utility\Util;
use App\Model\Entity\Geral;
use Cake\Routing\Router;

$geral = new Geral();
$path = Router::url('/', true) . "reservas/";
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<form method="POST" action="#" name="rescliide" id="rescliide"  class="form-horizontal" autocomplete="off">
    <input type="hidden" id="bloqueia_tela" value="1">
    <input type="hidden" id="form_atual" name="form_atual" value="rescliide" />
    <div id="rescliide_pag" class="col-md-8-5 col-sm-12">


        <div id="rescliide_identificacao_cliente">
            <input type="hidden" name="documento_numero" value= "<?= $documento_numero ?>">
            <input type="hidden" name="venda_canal_codigo" value= "0">
            <input type="hidden" name="venda_ponto_codigo" value= "GER">
            <input type="hidden" name="resbtnclc" id="resbtnclc" value="0">

            <!--Dados do contratante-->
            <?php
            echo $this->element('reserva/contratante_agencia_dados', []);
            ?>
            <!--p><?= $rot_clihostit ?></p-->
            <?php
            $quarto_item_removido_array = array();
            $quarto_item_sem_tarifas_array = array();
            for ($quarto_item = 1; $quarto_item <= $this->request->data['resquaqtd']; $quarto_item++) {
                $quarto_item_removido_array[$quarto_item] = $this->request->data['quarto_item_removido_' . $quarto_item];
                $quarto_item_sem_tarifas_array[$quarto_item] = $this->request->data['quarto_item_sem_tarifas_' . $quarto_item];
            }

            $indice_quarto = 1;
            for ($quarto_item = 1; $quarto_item <= $this->request->data['resquaqtd']; $quarto_item++) {
                //Exibicao normal e incrementa o indice
                if ($quarto_item_removido_array[$quarto_item] != 1 && $quarto_item_sem_tarifas_array[$quarto_item] != 1) {
                    ?>
                    <div id="quarto_item_<?= $quarto_item ?>" class="dados_item2">
                        <?php //if ($this->request->data['resquaqtd'] > 1) {  ?>
                        <div class="col-md-12 col-sm-12 info_quarto escd_info" onclick="escd_info_quartos('#quarto_item_<?= $quarto_item ?>');">
                            <div class="col-md-3 col-sm-12">
                                <a></a>
                                <strong><?= $rot_resquacod ?></strong> <strong><span id="label_quarto_item_<?= $quarto_item ?>"><?= $indice_quarto ?></span></strong>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-left: 20px; margin-bottom: 5px;">
                            <input type="checkbox" value="1" checked="checked" style="margin-right: 10px;" class="hospede_mesmo_contratante" data-quarto-item="<?= $quarto_item ?>" name="hospede_mesmo_contratante_<?= $quarto_item ?>" id="hospede_mesmo_contratante_<?= $quarto_item ?>" /><label for="hospede_mesmo_contratante_<?= $quarto_item ?>">O hóspede 1 é o contratante</label>
                        </div>
                        <div class="col-md-12 col-sm-12 list_rescli">
                            <?php
                            //} 
                            $total_hospedes = $this->request->data['resaduqtd_' . $quarto_item] + $this->request->data['rescriqtd_' . $quarto_item];

                            echo $this->element('reserva/reslishos_elem', ['total_hospedes' => $total_hospedes, 'exibe_campos_adicionais' => false, 'quarto_item' => $indice_quarto, 'hospede_mesmo_contratante' => 1]);
                            ?>
                            <div class='col-md-12 col-sm-12 list_rescli_inner'>
                                <div class="col-md-6 col-sm-12">
                                    <label class='control-label col-md-12 col-sm-12' for="resmsghot_<?= $quarto_item ?>" ><?= $rot_resmsghot ?>:</label> 
                                    <div class='col-md-12 col-sm-12'>
                                        <input class='form-control' height="3" type="text" name="resmsghot_<?= $quarto_item ?>" id="resmsghot_<?= $quarto_item ?>" placeholder="<?= $for_resmsghot ?>" />
                                    </div>
                                </div>
                                <div class='col-md-6 col-sm-12'>
                                    <label class='control-label col-md-12 col-sm-12' for="resmsgcam_<?= $quarto_item ?>" ><?= $rot_resmsgcam ?></label> 
                                    <div class='col-md-12 col-sm-12'>
                                        <input class='form-control' type="text" name="resmsgcam_<?= $quarto_item ?>" id="resmsgcam_<?= $quarto_item ?>" />
                                    </div>
                                </div>
                            </div>

                            <strong class='col-md-12 col-sm-12 title_topic'>Informações comerciais</strong>
                            <div class='col-md-12 col-sm-12 list_rescli_inner'>
                                <div class='col-md-6 col-sm-6'>
                                    <label class='col-md-12 col-sm-12' for="prazo" <?= $pro_respagpra ?>><?= $rot_respagpra ?>
                                        <?= Util::verificaAsterisco($pro_respagpra) ?>
                                    </label>
                                    <div class='col-md-11 col-sm-12'>
                                        <select class='form-control respdrcri_pagamento_prazos' <?= $pro_respagpra ?>  id="prazo_<?= $quarto_item ?>" name="prazo_<?= $quarto_item ?>" aria-quarto-item='<?= $quarto_item ?>'>
                                            <option></option>                                           
                                            <?php
                                            //Faz uma verificação para limpar prazos repetidos
                                            $pagamentos_exibir = array();
                                            $pagamentos_ja_exibidos = array();
                                            foreach ($var_respagpar[$quarto_item] as $pagamento_prazo) {
                                                if (!array_key_exists($pagamento_prazo['pagamento_prazo_codigo'], $pagamentos_ja_exibidos)) {
                                                    array_push($pagamentos_exibir, $pagamento_prazo);
                                                }
                                                $pagamentos_ja_exibidos[$pagamento_prazo['pagamento_prazo_codigo']] = $pagamento_prazo['pagamento_prazo_codigo'];
                                            }

                                            $selected = '';
                                            if (sizeof($pagamentos_exibir) <= 1)
                                                $selected = 'selected';

                                            foreach ($pagamentos_exibir as $pagamento_prazo) {

                                                //Define o texto dentro do input
                                                if ($pagamento_prazo['tarifa_variacao'] == 0)
                                                    $texto_pagamento_prazo = '';
                                                elseif ($pagamento_prazo['tarifa_variacao'] > 0)
                                                    $texto_pagamento_prazo = '(juros de ' . $pagamento_prazo['tarifa_variacao'] . '%)';
                                                elseif ($pagamento_prazo['tarifa_variacao'] < 0)
                                                    $texto_pagamento_prazo = '(desconto de ' . $pagamento_prazo['tarifa_variacao'] . '%)';
                                                ?>
                                                <option value="<?= $pagamento_prazo['pagamento_prazo_codigo'] ?>" <?= $selected ?>>
                                                    <?= $pagamento_prazo['pagamento_prazo_nome'] ?> <?= $texto_pagamento_prazo ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="pagamento_prazo_partidas_<?= $quarto_item ?>" class="col-md-5 col-sm-5" style="padding-right:10px; display: none;">
                                    <table class="table_resadisel">
                                        <tbody>

                                            <tr>
                                                <td>

                                                </td>
                                                <td><strong>Total</strong></td>
                                                <td colspan="2"><strong><?= $geral->germoeatr() ?> <span id="total_pagamento_prazo_<?= $quarto_item ?>"></span></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php
                            print "<div class='col-md-12 col-sm-12 list_rescli_inner'>
                                <div class='col-md-6 col-sm-12'>";

                            // Prazo de Pagamento - Fim
                            // Cancelamento - Início
                            $array_rescandet = null;
                            $add_rescandet = "";
                            if (count($var_rescandet[$quarto_item]) > 0) {
                                $add_rescandet .= "<label class='control-label col-md-12 col-sm-12' for=\"rescandet\" $pro_rescandet>$rot_rescandet *</label>
                                    <div class='col-md-11 col-sm-12'>
                            <select  class='form-control rescandet' data-validation='required' $pro_rescandet id=\"rescandet_$quarto_item\" name=\"rescandet_$quarto_item\" aria-quarto-item='$quarto_item'>";
                                //Se tiver apenas uma forma de cancelamento, força sua seleção
                                if (sizeof($var_rescandet[$quarto_item]) == 1) {
                                    $add_rescandet = $add_rescandet . "<option value=\"" . $var_rescandet[$quarto_item][0]["reserva_cancelamento_codigo"] . "\" selected>" . $var_rescandet[$quarto_item][0]["reserva_cancelamento_nome"] . "</option>";
                                    $add_rescandet = $add_rescandet . "<select></div></div>";
                                    $add_rescandet = $add_rescandet . "<div class='col-md-6 col-sm-6' id='div_dados_cancelamento_$quarto_item'>" . $var_rescansel[$quarto_item] . "</div>";

                                    //Se tiver mais de uma forma de cancelamento, cria uma opção vazia
                                } else {
                                    $add_rescandet = $add_rescandet . "<option value=''></option>";
                                    foreach ($var_rescandet[$quarto_item] as $ky => $value)
                                        $add_rescandet = $add_rescandet . "<option value='" . $value["reserva_cancelamento_codigo"] . "'>" . $value["reserva_cancelamento_nome"] . "</option>";
                                    $add_rescandet = $add_rescandet . "<select></div></div>";
                                    $add_rescandet = $add_rescandet . "<div class='col-md-6 col-sm-12' id='div_dados_cancelamento_$quarto_item'></div>";
                                }
                            }

                            print $add_rescandet;
                            print "</div>";
                            // Cancelamento - Fim
                            // Confirmação - Inicio
                            $array_rescnfdet = null; // Array para respagreg
                            $sel_name_rescnfdet = "";
                            $sel_rescnfdet = "";
                            $add_rescnfdet = "<div class='col-md-12 col-sm-12 list_rescli_inner'>
                                <div class='col-md-6 col-sm-12'>";
                            $add_rescnfdet = $add_rescnfdet . "<label class='control-label col-md-12 col-sm-12' for=\"rescnfdet\" $pro_rescnfdet>$rot_rescnfdet " . Util::verificaAsterisco($pro_rescnfdet) . "</label>
                                <div class='col-md-11 col-sm-12'>
                    <select class='form-control rescnfdet' $pro_rescnfdet id=\"rescnfdet_$quarto_item\" aria-quarto-item='$quarto_item' name=\"rescnfdet_$quarto_item\" \">\n";

                            //Se tiver apenas uma forma de confirmacao, força sua seleção
                            if (sizeof($var_rescnfdet[$quarto_item]) == 1) {
                                $add_rescnfdet = $add_rescnfdet . "<option value=\"" . $var_rescnfdet[$quarto_item][0]["reserva_confirmacao_codigo"] . "\" selected>" . $var_rescnfdet[$quarto_item][0]["reserva_confirmacao_nome"] . "</option>";
                                $add_rescnfdet = $add_rescnfdet . "</select></div>"
                                        . "<input type='hidden' name=\"reserva_confirmacao_tipo_$quarto_item\" id=\"reserva_confirmacao_tipo_$quarto_item\" value=\"" . $var_rescnfdet[$quarto_item][0]["reserva_confirmacao_tipo"] . "\" />"
                                        . "<input type='hidden' name=\"reserva_valor_tipo_$quarto_item\" id=\"reserva_valor_tipo_$quarto_item\" value=\"" . $var_rescnfdet[$quarto_item][0]["valor_tipo"] . "\" />";
                                print $add_rescnfdet;
                                print "<div style='display:inline' id='div_dados_confirmacao_$quarto_item'>" . $var_rescnfsel[$quarto_item] . "<br>";
                                print "</div></div>";
                                //Se tiver mais de uma forma de confirmacao, cria uma opção vazia
                            } else {
                                $add_rescnfdet = $add_rescnfdet . "<option value=''></option>";
                                foreach ($var_rescnfdet[$quarto_item] as $ky => $value)
                                    $add_rescnfdet = $add_rescnfdet . "<option value='" . $value["reserva_confirmacao_codigo"] . "'>" . $value["reserva_confirmacao_nome"] . "</option>";

                                $add_rescnfdet = $add_rescnfdet . "</select></div>"
                                        . "<input type='hidden' name=\"reserva_confirmacao_tipo_$quarto_item\" id=\"reserva_confirmacao_tipo_$quarto_item\" />"
                                        . "<input type='hidden' name=\"reserva_valor_tipo_$quarto_item\" id=\"reserva_valor_tipo_$quarto_item\" />"
                                        . "</div>\n";
                                print $add_rescnfdet;
                                print "<div style='display:inline' id='div_dados_confirmacao_$quarto_item'><br>";
                                print "</div></div>";
                            }
                            ?>
                        </div>

                        <input type="hidden" id="inicial_data_<?= $quarto_item ?>" value="<?= $resentdat ?>" />
                        <input type="hidden" id="final_data_<?= $quarto_item ?>" value="<?= $ressaidat ?>" />
                    </div>
                    <?php $indice_quarto++; ?>  
                    <?php
                }
            }
            ?>

            <!-- Dados para serem acessados via JS - Inicio -->
            <input type="hidden" id="documento_numero_js" value="<?= $documento_numero ?>" />
            <input type="hidden" id="empresa_codigo_js" value="<?= $empresa_codigo ?>" />
            <input type="hidden" id="empresa_grupo_codigo_js" value="<?= $empresa_grupo_codigo ?>" />
            <input type="hidden" id="cliente_univoco_campo_js" value="<?= $cliente_univoco_campo ?>" />
            <input type="hidden" id="total_preco_js" value="<?= $total_preco ?>" />
            <input type="hidden" id="cliente_univoco_campo" value="<?= $cliente_univoco_campo ?>" />
            <input type="hidden" id="dias_estadia_js" value="<?= $dias_estadia ?? 0 ?>" />
            <input type="hidden" name="rescliide_volta" id="rescliide_volta" value="1">
            <input type="hidden" name="resquaqtd" id="resquaqtd" value="<?= $this->request->data['resquaqtd'] ?>">


            <?php
            foreach ($this->request->data as $campo => $valor) {
                if (!(strpos($campo, 'adicional_qtd_') !== false) && !(strpos($campo, 'adicional_item_qtd_') !== false) && !(strpos($campo, 'adicional_nome_') !== false) && !(strpos($campo, 'adicional_total_') !== false) &&
                        !(strpos($campo, 'total_original') !== false) && !(strpos($campo, 'adicional_desconto_tmp') !== false) && !(strpos($campo, 'resentdat') !== false) && !(strpos($campo, 'ressaidat') !== false) &&
                        !(strpos($campo, 'respagfor_') !== false) && !(strpos($campo, 'pagante_codigo_') !== false) && !(strpos($campo, 'pagante_nome_') !== false) && !(strpos($campo, 'pagante_cpf_cnpj_') !== false) &&
                        !(strpos($campo, 'pre_pagante_codigo') !== false) && !(strpos($campo, 'pre_pagante_nome') !== false) && !(strpos($campo, 'pre_pagante_cpf_cnpj') !== false) &&
                        !(strpos($campo, 'reserva_valor_tipo_') !== false) && !(strpos($campo, 'reserva_confirmacao_tipo_') !== false) && !(strpos($campo, 'total_pagamento_formas') !== false) && !(strpos($campo, 'cli') !== false) &&
                        !(strpos($campo, 'c_') !== false) && !(strpos($campo, 'h_') !== false) && !(strpos($campo, 'rescandet_') !== false) && !(strpos($campo, 'rescnfdet_') !== false)) {
                    ?>
                    <input type="hidden" id="<?= $campo ?>" name="<?= $campo ?>" value="<?= $valor ?>" />
                    <?php
                } else if (!(strpos($campo, 'reserva_valor_tipo_') !== false) && !(strpos($campo, 'reserva_confirmacao_tipo_') !== false) && !(strpos($campo, 'total_pagamento_formas') !== false) && !(strpos($campo, 'cli') !== false) && !(strpos($campo, 'c_') !== false) && !(strpos($campo, 'h_') !== false) && !(strpos($campo, 'rescandet_') !== false) && !(strpos($campo, 'rescnfdet_') !== false)) {
                    ?>
                    <input type="hidden" name="<?= $campo ?>" value="<?= $valor ?>" />
                    <?php
                }
            }

            //Armazena campos que deveriam ser armazenados na resadisel
            for ($quarto_item = 1; $quarto_item <= $resquaqtd; $quarto_item++) {
                //Somente quartos nao removidos ou que tenham tarifas
                if ($quarto_item_removido_array[$quarto_item] != 1 && $quarto_item_sem_tarifas_array[$quarto_item] != 1) {
                    ?>
                    <input type="hidden" id="total_quarto_item_<?= $quarto_item ?>" name="total_quarto_item_<?= $quarto_item ?>" value="<?= $this->request->data['tarifa_valor_' . $quarto_item] + (isset($this->request->data['total_adicionais_' . $quarto_item]) ? $this->request->data['total_adicionais_' . $quarto_item] : 0) ?>" />
                    <input type="hidden" id="adicional_item_qtd_<?= $quarto_item ?>" name="adicional_item_qtd_<?= $quarto_item ?>"
                           value="<?= (isset($this->request->data['adicional_item_qtd_' . $quarto_item]) ? $this->request->data['adicional_item_qtd_' . $quarto_item] : 0) ?>" />
                           <?php
                       }
                   }

                   if (isset($existem_adicionais) && !$existem_adicionais) {
                       //Armazena campos que deveriam ser armazenados na resadisel
                       for ($quarto_item = 1; $quarto_item <= $resquaqtd; $quarto_item++) {
                           //Somente quartos nao removidos ou que tenham tarifas
                           if ($quarto_item_removido_array[$quarto_item] != 1 && $quarto_item_sem_tarifas_array[$quarto_item] != 1) {
                               ?>
                        <input type="hidden" name="total_adicionais_<?= $quarto_item ?>" id="total_adicionais_<?= $quarto_item ?>" value="0">
                        <input type="hidden" name="tarifa_quarto_item_<?= $quarto_item ?>" value="<?= eval('return $quarto_tipo_codigo_' . $quarto_item . ';'); ?>_<?= eval('return $tarifa_tipo_codigo_' . $quarto_item . ';'); ?>">
                        <?php
                    }
                }
                ?>
                <input type="hidden" id="total_original" name="total_original" value="<?= $this->request->data['total_preco'] ?>" />
            <?php } ?>
            <?php
            echo $this->element('reserva/pagamentos_reserva', []);
            ?>

            <div class="row col-md-12 col-sm-12 quat_botoes">
                <div class="col-md-6 col-sm-4"></div>
                <div class="cancel-right col-md-3 col-sm-4">
                    <?php if (isset($existem_adicionais) && !$existem_adicionais) { ?>
                        <input class="form-control btn-default submit-button" id="reserva_voltar" type="submit" name="resmodbtn" aria-form-id="rescliide"  value="<?= $rot_gervolbot ?>" onclick="$('#rescliide').attr('action', '<?= $path ?>resquatar')" />
                    <?php } else { ?>
                        <input class="form-control btn-default submit-button" id="reserva_voltar" type="submit" name="resmodbtn" aria-form-id="rescliide"  value="<?= $rot_resrevadi ?>" onclick="$('#rescliide').attr('action', '<?= $path ?>resadisel')" />
                    <?php } ?>
                </div>
                <div class="pull-left col-md-3 col-sm-4">
                    <input style="margin-right:10px" class='form-control btn-primary' type="button" id="btnreserva" value='<?= $rot_resresbot ?>' onclick="resdocval()" />       
                </div>
            </div>
        </div>
    </div>
    <?php
    echo $this->element('reserva/rescarinf_elem', ['pagina' => 'rescliide', 'quarto_item_removido_array' => $quarto_item_removido_array,
        'quarto_item_sem_tarifas_array' => $quarto_item_sem_tarifas_array]);
    ?>
</form>

