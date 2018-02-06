<?php

use App\Model\Entity\Geral;
use Cake\Routing\Router;
use Cake\Network\Session;
use App\Utility\Util;

$path = Router::url('/', true);
$geral = new Geral();
$session = new Session();
?>

<form id="respdrcri" action="<?= Router::url('/', true) ?>reservas/respdrcri">
    <input type="hidden" id="codigo_referencia_atual" value="0" />
    <input type="hidden" id="form_atual" value="respdrcri" />
    <input type="hidden" name="url_redirect_after"  id="url_redirect_after" value="<?= $atual_pagina ?>">
    <input type="hidden" id="resquaqtd" name="resquaqtd" value="<?= sizeof($quarto_e_tipo_codigos) ?>" />
    <input type="hidden" name="inicial_data_completa" id="inicial_data_completa" value="<?= $inicial_data_completa ?>" />
    <input type="hidden" name="final_data_completa" id="final_data_completa" value="<?= $final_data_completa ?>" />
    <input type="hidden" name="inicial_data_completa_original" id="inicial_data_completa_original" value="<?= $inicial_data_completa ?>" />
    <input type="hidden" name="final_data_completa_original" id="final_data_completa_original" value="<?= $final_data_completa ?>" />
    <input type="hidden" id="tarifa_manual_entrada" value="<?= $tarifa_manual_entrada ?>" name="tarifa_manual_entrada" />
    <input type="hidden" id="total_original" value="<?= $total_preco ?>" />
    <div id="rescliide_pag" class="col-md-8-5 col-sm-12">
        <?php
        $quarto_item = 1;
        foreach ($quarto_e_tipo_codigos as $quarto_codigo => $quarto_tipo_codigo) {
            ?>
            <input type="hidden" name="inicial_data" id="inicial_data_<?= $quarto_item ?>" value="<?= $inicial_data ?>" />
            <input type="hidden" name="final_data" id="final_data_<?= $quarto_item ?>" value="<?= $final_data ?>" />
            <input type="hidden" name="quarto_codigo_<?= $quarto_item ?>" id="quarto_codigo_<?= $quarto_item ?>" value="<?= $quarto_codigo ?>" />
            <input type="hidden" name="quarto_tipo_codigo_<?= $quarto_item ?>" id="quarto_tipo_codigo_<?= $quarto_item ?>" value="<?= $quarto_tipo_codigo ?>" />
            <input type="hidden" name="total_adicionais_<?= $quarto_item ?>" id="total_adicionais_<?= $quarto_item ?>" value="0" />
            <input type="hidden" id="tarifa_valor_<?= $quarto_item ?>" name="tarifa_valor_<?= $quarto_item ?>" value="0" />
            <input type="hidden" name="venda_canal_codigo" value= "0">
            <input type="hidden" name="venda_ponto_codigo" value= "GER">
            <input type="hidden" id="total_quarto_item_<?= $quarto_item ?>" name="total_quarto_item_<?= $quarto_item ?>" value="0" />
            <input type="hidden" id="quarto_item_removido_<?= $quarto_item ?>" name="quarto_item_removido_<?= $quarto_item ?>" value="0" />

            <div id="quarto_item_<?= $quarto_item ?>">
                <div class="col-md-12 col-sm-12" style="margin-bottom:10px">
                    <b><?= $rot_resquacod ?> <?= $quarto_item ?>:</b> <?= $quarto_codigo ?>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 col-sm-6">    
                        <label class="control-label col-md-12 col-sm-12" for="resaduqtd_<?= $quarto_item ?>">PAX Socilitado</label>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control respdrcri_aduqtd rescrimax" aria-quarto-tipo-codigo='<?= $quarto_tipo_codigo ?>' name="resaduqtd[]" id="resaduqtd_<?= $quarto_item ?>"
                                    aria-quarto-item="<?= $quarto_item ?>"> 
                                        <?php
                                        for ($i = 1; $i <= $var_max_adultos[$quarto_tipo_codigo]; $i++) {
                                            $selected = '';
                                            if ($adultos_padrao == $i)
                                                $selected = 'selected';
                                            ?>
                                    <option value="<?= $i ?>"  <?= $selected ?>>
                                        <?= $i ?>
                                    </option>
                                <?php } ?>
                            </select> 
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control respdrcri_criqtd rescriida"  onchange="$('#lista_criancas_<?= $quarto_item ?>').css('display', 'block')" name="rescriqtd[]" id="rescriqtd" aria-quarto-item="<?= $quarto_item ?>"> 
                                <?php
                                for ($i = 0; $i <= $var_max_criancas[$quarto_tipo_codigo]; $i++) {
                                    $selected = '';
                                    if ($criancas_padrao == $i)
                                        $selected = 'selected';
                                    ?>
                                    <option value="<?= $i ?>"  <?= $selected ?>>
                                        <?= $i ?>
                                    </option>
                                <?php } ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-3 lista_c" id="lista_criancas_<?= $quarto_item ?>" >
                        <!--<span class=" col-sm-12" style="float:left"></span>-->
                    </div>
                    <div class="col-md-7">
                        <div class="form-group row" id="tarifa_adicionais_linha_<?= $quarto_item ?>_1">
                            <div id="linha_tarifa_quarto_item_<?= $quarto_item ?>">
                                <div class="col-md-4 col-sm-6">    
                                    <label class='col-md-12 col-sm-12' for="tarifa_tipo_<?= $quarto_item ?>_1"><?= $rot_restiptar ?>*</label>
                                    <div class="col-md-11 col-sm-11 ">
                                        <select class="form-control respdrcri_tarifa" aria-quarto-item="<?= $quarto_item ?>" name="tarifa_tipo_codigo_<?= $quarto_item ?>" id="tarifa_tipo_codigo_<?= $quarto_item ?>" data-validation='required'> 
                                            <option></option>

                                            <?php
                                            foreach ($tarifa_tipos[$quarto_tipo_codigo] as $tarifa_tipo) {
                                                $selected = '';
                                                if (sizeof($tarifa_tipos[$quarto_tipo_codigo]) <= 1)
                                                    $selected = 'selected';
                                                ?>
                                                <option value="<?= $tarifa_tipo['tarifa_tipo_codigo'] ?>" <?= $selected ?>>
                                                    <?= $tarifa_tipo['tarifa_tipo_nome'] ?>
                                                </option>
                                            <?php } ?>
                                        </select> 

                                    </div>
                                    <div class="col-md-1 col-sm-1 ">
                                        <?php
                                        $display = 'display:none';
                                        if (sizeof($tarifa_tipos[$quarto_tipo_codigo]) <= 1)
                                            $display = 'display:block';

                                        foreach ($tarifa_tipos[$quarto_tipo_codigo] as $tarifa_tipo) {
                                            ?>
                                            <a style = 'margin-top: 5px; margin-left: -5px; <?= $display ?>' title="Valores das diárias" id="restardia_<?= $quarto_item ?>_<?= $quarto_tipo_codigo ?>_<?= $tarifa_tipo['tarifa_tipo_codigo'] ?>" class = "restardia col-md-12 col-sm-12" aria-quarto-item = "<?= $quarto_item ?>"
                                               aria-tarifa-tipo-codigo = "<?= $tarifa_tipo['tarifa_tipo_codigo'] ?>" aria-quarto-tipo-codigo = "<?= $quarto_tipo_codigo ?>"></a>
                                               <?php
                                           }
                                           ?>
                                    </div>
                                </div>
                                <!--Armazena os valores das tarifas para atualização no carrinho -->
                                <?php foreach ($tarifa_tipos[$quarto_tipo_codigo] as $tarifa_tipo) { ?>
                                    <input type="hidden" id="tarifa_valor_<?= $quarto_item ?>_<?= $quarto_tipo_codigo ?>_<?= $tarifa_tipo['tarifa_tipo_codigo'] ?>" value="<?= $tarifa_tipo['total_tarifa'] ?>" />

                                    <input type="hidden" class="tarifa moeda" id="tarifa_<?= $quarto_item ?>_<?= $quarto_tipo_codigo ?>_<?= $tarifa_tipo['tarifa_tipo_codigo'] ?>"
                                           value="<?= $geral->gersepatr($tarifa_tipo['total_tarifa']) ?>" type="hidden">

                                    <input type="hidden" class="tarifa moeda" id="total_original_<?= $quarto_item ?>_<?= $quarto_tipo_codigo ?>_<?= $tarifa_tipo['tarifa_tipo_codigo'] ?>"
                                           value="<?= $geral->gersepatr($tarifa_tipo['total_tarifa']) ?>" type="hidden">
                                       <?php } ?>

                            </div>
                            <div class='col-md-6 col-sm-6'>
                                <label class='col-md-12 col-sm-12' for="resadisel_<?= $quarto_item ?>_1"><?= $rot_resadisel ?></label>
                                <div class='col-md-11 col-sm-12'>
                                    <select class='form-control respdrcri_adicional' aria-quarto-item="<?= $quarto_item ?>" aria-linha-adicional="1" id="resadisel_<?= $quarto_item ?>_1" name="resadisel_<?= $quarto_item ?>_1">
                                        <option></option>
                                        <?php
                                        $selected = '';
                                        if (sizeof($adicionais_itens[$quarto_codigo]) <= 1)
                                            $selected = 'selected';
                                        foreach ($adicionais_itens[$quarto_codigo] as $adicional) {
                                            if ($adicional['incluido'] != 1) {
                                                ?>
                                                <option value="<?= $adicional['adicional_codigo'] ?>" <?= $selected ?>>
                                                    <?= $adicional['nome'] ?>  <?= $adicional['preco'] ?>/<?= $adicional['preco_fator_nome'] ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-1 ">
                                    <button  style = 'margin-top: 5px; margin-left: -5px; display: none' type="button" id="adicional_btn_<?= $quarto_item ?>_1" title="Modificar valores" class="btn-editar condesapl_adicionais" aria-adicional-referencia-id='<?= $quarto_item ?>_1'>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class='col-md-12 col-sm-12' for="adicional_qtd_<?= $quarto_item ?>_1"><?= $rot_gerqtdres ?></label>
                                <select class="adicional_qtd produto_adicional" name="adicional_qtd_<?= $quarto_item ?>_1" id="adicional_qtd_<?= $quarto_item ?>_1" size=1 style="padding: 5px;" aria-quarto-item="<?= $quarto_item ?>" aria-linha-adicional="1">
                                    <option></option>
                                </select>

                                <input type="hidden" id="produto_codigo_<?= $quarto_item ?>_1" name="produto_codigo_<?= $quarto_item ?>_1" />
                                <input type="hidden" id="adicional_fixo_fator_codigo_<?= $quarto_item ?>_1" name="adicional_fixo_fator_codigo_<?= $quarto_item ?>_1" />
                                <input type="hidden" id="preco_<?= $quarto_item ?>_1" name="preco_<?= $quarto_item ?>_1" />
                                <input type="hidden" id="adicional_nome_<?= $quarto_item ?>_1" name="adicional_nome_<?= $quarto_item ?>_1" />
                                <input type="hidden" id="adicional_total_<?= $quarto_item ?>_1" name="adicional_total_<?= $quarto_item ?>_1"  value="0" />
                                <input type="hidden" id="adicional_total_original_<?= $quarto_item ?>_1" name="adicional_total_original_<?= $quarto_item ?>_1" value="0">
                                <input type="hidden" id="servico_taxa_incide_<?= $quarto_item ?>_1" name="servico_taxa_incide_<?= $quarto_item ?>_1" />
                                <input type="hidden" id="adicional_fator_fixo_valor_<?= $quarto_item ?>_1" name="adicional_fator_fixo_valor_<?= $quarto_item ?>_1">
                                <input type="hidden" id="lancamento_tempo_<?= $quarto_item ?>_1" name="lancamento_tempo_<?= $quarto_item ?>_1">
                                <input type="hidden" id="horario_modificacao_tipo_<?= $quarto_item ?>_1" name="horario_modificacao_tipo_<?= $quarto_item ?>_1" />
                                <input type="hidden" id="horario_modificacao_valor_<?= $quarto_item ?>_1" name="horario_modificacao_valor_<?= $quarto_item ?>_1" />

                                <input type="hidden" name="adicional_desconto_tmp_<?= $quarto_item ?>_1" id="adicional_desconto_tmp_<?= $quarto_item ?>_1" value="d|0.00|p|0.00|||">
                                <a href="#" style="margin-left: 11px; font-size: 20px;" class="add_adicional" title="Incluir mais adicionais" aria-quarto-item='<?= $quarto_item ?>'>+</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <input type="hidden" name="adicional_item_qtd_<?= $quarto_item ?>" id="adicional_item_qtd_<?= $quarto_item ?>" value="1">
            <?php
            $quarto_item++;
        }
        ?>

        <!--Dados do contratante-->
        <?php
        echo $this->element('reserva/contratante_agencia_dados', []);
        ?>

        <?php
        $quarto_item = 1;
        foreach ($quarto_e_tipo_codigos as $quarto_codigo => $quarto_tipo_codigo) {
            ?>
            <div id="quarto_item_<?= $quarto_item ?>" class="dados_item2">
                <div class="col-md-12 col-sm-12 info_quarto escd_info"  onclick="escd_info_quartos('#quarto_item_<?= $quarto_item ?>');">
                    <div class="col-md-3 col-sm-12">
                        <a></a>
                        <strong><?= $rot_resquacod ?></strong> <strong><span id="label_quarto_item_<?= $quarto_item ?>"><?= $quarto_item ?></span></strong>
                    </div>
                </div>
                <div class="col-md-12" style="margin-left: 20px; margin-bottom: 5px;">
                    <input type="checkbox" value="1" checked="checked" style="margin-right: 10px;" class="hospede_mesmo_contratante" data-quarto-item="<?= $quarto_item ?>" name="hospede_mesmo_contratante_<?= $quarto_item ?>" id="hospede_mesmo_contratante_<?= $quarto_item ?>" /><label for="hospede_mesmo_contratante_<?= $quarto_item ?>">O hóspede 1 é o contratante</label>
                </div>
                <div class="col-md-12 col-sm-12 list_rescli">
                    <?php
                    $total_hospedes = 1;
                    echo $this->element('reserva/reslishos_elem', ['total_hospedes' => $total_hospedes, 'exibe_campos_adicionais' => false, 'quarto_item' => $quarto_item, 'hospede_mesmo_contratante' => 1]);
                    ?>
                    <div class='col-md-12 col-sm-12 list_rescli_inner'>
                        <div class='col-md-6 col-sm-7'>
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
                                    foreach ($pagamento_prazo_itens[$quarto_codigo] as $pagamento_prazo) {
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
                                        <td></td>
                                        <td><strong>Total</strong></td>
                                        <td colspan="2"><strong><?= $geral->germoeatr() ?> <span id="total_pagamento_prazo_<?= $quarto_item ?>"></span></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class='col-md-12 col-sm-12 list_rescli_inner'>
                        <div class='col-md-6 col-sm-12'>
                            <label class='control-label col-md-12 col-sm-12' for="rescandet" <?= $pro_rescandet ?>><?= $rot_rescandet ?>
                                *
                            </label>
                            <div class='col-md-11 col-sm-12'>
                                <select  class='form-control rescandet' <?= $pro_rescandet ?> id="rescandet_<?= $quarto_item ?>" data-validation='required' name="rescandet_<?= $quarto_item ?>" aria-quarto-item='<?= $quarto_item ?>'>
                                    <option value=''></option>
                                    <?php
                                    $selected = '';
                                    if (sizeof($cancelamento_itens[$quarto_codigo]) <= 1)
                                        $selected = 'selected';
                                    foreach ($cancelamento_itens[$quarto_codigo] as $cancelamento) {
                                        ?>
                                        <option value="<?= $cancelamento['reserva_cancelamento_codigo'] ?>" <?= $selected ?>>
                                            <?= $cancelamento['reserva_cancelamento_nome'] ?> 
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>                            
                        </div>
                        <div class='col-md-6 col-sm-6' id='div_dados_cancelamento_<?= $quarto_item ?>'></div>
                    </div>
                    <div class='col-md-12 col-sm-12 list_rescli_inner'>
                        <div class='col-md-6 col-sm-12'>
                            <label class='control-label col-md-12 col-sm-12' for="rescnfdet" <?= $pro_rescnfdet ?>><?= $rot_rescnfdet ?>
                                <?= Util::verificaAsterisco($pro_rescnfdet) ?>
                            </label>
                            <div class='col-md-11 col-sm-12'>
                                <select class='form-control rescnfdet'  <?= $pro_rescnfdet ?> id="rescnfdet_<?= $quarto_item ?>" aria-quarto-item='<?= $quarto_item ?>' name="rescnfdet_<?= $quarto_item ?>" >
                                    <option value=''></option>
                                    <?php
                                    $selected = '';
                                    if (sizeof($confirmacao_itens[$quarto_codigo]) <= 1)
                                        $selected = 'selected';
                                    foreach ($confirmacao_itens[$quarto_codigo] as $confirmacao) {
                                        ?>
                                        <option value="<?= $confirmacao['reserva_confirmacao_codigo'] ?>" <?= $selected ?>>
                                            <?= $confirmacao['reserva_confirmacao_nome'] ?> 
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type='hidden' name="reserva_confirmacao_tipo_<?= $quarto_item ?>" id="reserva_confirmacao_tipo_<?= $quarto_item ?>" /> 
                            <input type='hidden' name="reserva_valor_tipo_<?= $quarto_item ?>" id="reserva_valor_tipo_<?= $quarto_item ?>" />
                        </div>
                        <div style='display:inline' id='div_dados_confirmacao_<?= $quarto_item ?>'></div>
                    </div>

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
                </div>
            </div>
            <?php
            $quarto_item++;
        }
        echo $this->element('reserva/pagamentos_reserva', []);
        ?>
    </div>
    <?php
    echo $this->element('reserva/rescarinf_elem', ['pagina' => 'respdrcri', 'inicial_data_completa' => Util::convertDataDMY($inicial_data_completa, 'd/m/Y H:i'), 
        'final_data_completa' => Util::convertDataDMY($final_data_completa, 'd/m/Y H:i'), 'resquaqtd' => sizeof($quarto_e_tipo_codigos),
        'quarto_item_removido_array' => $quarto_item_removido_array, 'quarto_item_sem_tarifas_array' => $quarto_item_sem_tarifas_array,
        'adicionais_itens_inclusos' => $adicionais_itens_inclusos]);
    ?>  

    <div class="row col-md-12 col-sm-12 quat_botoes">
        <div class="col-md-6 col-sm-4"></div>
        <div class="cancel-right col-md-3 col-sm-4">
            <input class="form-control btn-default close_dialog" type="button" value="<?= $rot_gerdesbot ?>" onclick="<?php if (strpos($atual_pagina, 'respaiatu') !== false) echo 'removeMarcacao()'; ?>" />
        </div>
        <div class="pull-left col-md-3 col-sm-4">
            <input type="submit" style="display:none"  class="submit-button" aria-form-id='respdrcri' id="criar_reserva_painel" />
            <input style="margin-right:10px" class='form-control btn-primary'  type="button" onclick="resdocval()" value='<?= $rot_resresbot ?>' /> 
        </div>
    </div>

    <!-- Dados para serem acessados via JS - Inicio -->
    <input type="hidden" id="dias_estadia_js" value="<?= $dias_estadia ?>" />
    <input type="hidden" id="rot_rescriida_js" value="<?= $rot_rescriida ?>" />
    <input type="hidden" id="nao_pagante_crianca_idade_js" value="<?= $this->request->session()->read("empresa_selecionada")["nao_pagante_crianca_idade"] ?>" />
    <input type="hidden" id="diarias_max_js" value="<?= $this->request->session()->read("empresa_selecionada")["diarias_max"] ?>" />
    <input type="hidden" id="pagante_crianca_idade_js" value="<?= $this->request->session()->read("empresa_selecionada")["pagante_crianca_idade"] ?>" />
    <input type="hidden" id="inicial_padrao_horario" name="inicial_padrao_horario" value="<?= $inicial_padrao_horario ?>" />
    <input type="hidden" id="final_padrao_horario" name="final_padrao_horario" value="<?= $final_padrao_horario ?>" />
    <input type="hidden" id="moeda" value="<?= $geral->germoeatr() ?>" />
    <input type="hidden" id="servico_taxa" value="<?= $session->read('servico_taxa') ?>" />
    <input type="hidden" id="turismo_taxa" value="<?= $session->read('turismo_taxa') ?>" />
    <input type="hidden" id="hospede_taxa" value="<?= $session->read('hospede_taxa') ?>" />
    <input type="hidden" id="diaria_taxa" value="<?= $session->read('diaria_taxa') ?>" />

    <!-- Dados para serem acessados via JS - Fim -->
    <!-- variáveis para serem acessadas no momento de salvar a reserva-->
    <?php
    $quarto_item = 1;
    foreach ($quarto_e_tipo_codigos as $quarto_codigo => $quarto_tipo_codigo) {
        foreach ($tarifa_tipos[$quarto_tipo_codigo] as $quarto_tipo_tarifas) {
            $data_indice = 1;
            foreach ($quarto_tipo_tarifas as $key => $tarifa_info) {
                if ($key != 'tarifa_tipo_nome' && $key != 'condicao' && $key != 'total_tarifa' && $key != 'tarifa_tipo_codigo') {
                    if ($quarto_tipo_tarifas[$key] == null)
                        $quarto_tipo_tarifas[$key] = 0;
                    print "<input type='hidden' id='info_diarias_" . $quarto_item . "_" . $quarto_tipo_codigo . "_" . $quarto_tipo_tarifas['tarifa_tipo_codigo'] . "_$data_indice' "
                            . "name='info_diarias_" . $quarto_item . "_" . $quarto_tipo_codigo . "_" . $quarto_tipo_tarifas['tarifa_tipo_codigo'] . "_$data_indice' value='" . $key . "|" . $quarto_tipo_tarifas[$key] . "'/>";
                    print "<input type='hidden' id='tarifa_valor_original_" . $quarto_item . "_" . $quarto_tipo_codigo . "_" . $quarto_tipo_tarifas['tarifa_tipo_codigo'] . "_$data_indice' value='" . $geral->gersepatr($quarto_tipo_tarifas[$key]) . "' /> ";
                    $data_indice++;
                }

                //Armazena a variavel para controle de desconto total
                print "<input type='hidden' id='total_desconto_tmp_" . $quarto_item . "_" . $quarto_tipo_codigo . "_" . $quarto_tipo_tarifas['tarifa_tipo_codigo'] . "'"
                        . " name='total_desconto_tmp_" . $quarto_item . "_" . $quarto_tipo_codigo . "_" . $quarto_tipo_tarifas['tarifa_tipo_codigo'] . "'  value='d|0.00|p|0.00|||' />";
            }
        }
        $quarto_item++;
    }
    ?>
    <input type="hidden" id="dias_estadia" name="dias_estadia" value="<?= $dias_estadia ?>" />

</form>
<!-- Fim da respdrcri -->