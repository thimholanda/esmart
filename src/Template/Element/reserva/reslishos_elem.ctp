<button type="button" class="accordion-conta es-accordion-conta" style="margin-top: 15px; pointer-events: none;"><div class="es-room-title"><strong>Revisão de hóspedes</strong></div></button>
<div class="es-container-generico" style="padding: 0; margin-bottom: 0;">
    <div class="form-group col-md-12 col-sm-12 es-title-topic" style="margin-bottom: 0px; margin-top: 0; padding: 10px;">
        <div class="row es-inner-row es-inner-row-gray" style="margin-bottom: 0; padding: 15px 0;">
    <?php
    for ($i = 1; $i <= $total_hospedes; $i++) {
        ?>
        <div class="hospede-linha col-md-12 col-sm-12 list_rescli_iinner" id="hospede_linha_<?= $quarto_item ?>_<?= $i ?>" style="margin-bottom:10px; padding: 0;">
            <input type="hidden" id="h_has_changed_<?= $quarto_item ?>_<?= $i ?>" name="h_has_changed_<?= $quarto_item ?>_<?= $i ?>" value="0">
            <input type="hidden" name="h_codigo_antigo_<?= $quarto_item ?>_<?= $i ?>" id="h_codigo_antigo_<?= $quarto_item ?>_<?= $i ?>" value="<?= isset($hospedes_info[$i - 1]) ? $hospedes_info[$i - 1]['cliente_codigo'] : '' ?>" >
            <input type="hidden" name="h_codigo_<?= $quarto_item ?>_<?= $i ?>" id="h_codigo_<?= $quarto_item ?>_<?= $i ?>" value="<?= isset($hospedes_info[$i - 1]) ? $hospedes_info[$i - 1]['cliente_codigo'] : '' ?>">
            <input type="hidden" name="h_cliente_itens_<?= $quarto_item ?>_<?= $i ?>" id="h_cliente_itens_<?= $quarto_item ?>_<?= $i ?>" value="<?= isset($hospedes_info[$i - 1]) ? $hospedes_info[$i - 1]['cliente_item'] : '' ?>">
            <input type="hidden" id="clean_entries_<?= $quarto_item ?>_<?= $i ?>" value="0">

            <div class="<?php
            if ($exibe_campos_adicionais)
                echo ' col-md-2 ';
            else
                echo ' col-md-4 ';
            ?> col-sm-6">
                 <?php if ($i == 1) { ?>
                    <label class="control-label col-md-12 col-sm-12" style="margin-left: 32px;"><b><?= $rot_cliprinom ?> do hóspede
                        <?php if ($i == 1) echo "*" ?></b>
                    </label>
                <?php } ?>
                <div class="col-md-12 col-sm-12">
                    <div class="col-md-1 col-sm-1">
                        <span><span id="label_linha_hospede_<?= $quarto_item ?>_<?= $i ?>"><?= $i ?></span>)</span>
                    </div>
                    <div class="col-md-11 col-sm-11">
                        <input class="form-control h_nome input_autocomplete <?php if($i == 1) echo 'primeiro_hospede' ?>"  data-quarto-item="<?= $quarto_item ?>" <?php if($hospede_mesmo_contratante == 1 && $i == 1) echo 'readonly' ?>  style="margin-left: 10px;"  onchange="$('#h_has_changed_<?= $quarto_item ?>_<?= $i ?>').val('1')"  <?php if ($i == 1) echo "data-validation='required'" ?> aria-quarto-item ="<?= $quarto_item ?>" aria-linha-hospede="<?= $i ?>" type="text" name="h_nome_<?= $quarto_item ?>_<?= $i ?>" id="h_nome_<?= $quarto_item ?>_<?= $i ?>" value="<?= isset($hospedes_info[$i - 1]) ? $hospedes_info[$i - 1]['nome'] : '' ?>">
                    </div>
                </div>
            </div>
            <div class="<?php
            if ($exibe_campos_adicionais)
                echo ' col-md-2 ';
            else
                echo ' col-md-4 ';
            ?> col-sm-6">
                 <?php if ($i == 1) { ?>
                    <label class="control-label col-md-12 col-sm-12"><b><?= $rot_clisobnom ?></b></label>
                <?php } ?>
                <div class="col-md-12 col-sm-12">
                    <input  class="form-control h_sobrenome  <?php if($i == 1) echo 'primeiro_hospede' ?> " <?php if($hospede_mesmo_contratante == 1 && $i == 1) echo 'readonly' ?> type="text"  onchange="$('#h_has_changed_<?= $quarto_item ?>_<?= $i ?>').val('1')"
                            name="h_sobrenome_<?= $quarto_item ?>_<?= $i ?>" data-quarto-item="<?= $quarto_item ?>" id="h_sobrenome_<?= $quarto_item ?>_<?= $i ?>"
                            value="<?= isset($hospedes_info[$i - 1]) ? $hospedes_info[$i - 1]['sobrenome'] : '' ?>">
                </div>
            </div>
            <div class="<?php
            if ($exibe_campos_adicionais)
                echo ' col-md-2 ';
            else
                echo ' col-md-4 ';
            ?> col-sm-6">
                 <?php if ($i == 1) { ?>
                    <label class="control-label col-md-12 col-sm-12"><b><?= $rot_clicadema ?></b></label>

                <?php } ?>
                <div class="col-md-12 col-sm-12">
                    <input  class="form-control h_email" type="text" <?php if($hospede_mesmo_contratante == 1 && $i == 1) echo 'readonly' ?> onchange="$('#h_has_changed_<?= $quarto_item ?>_<?= $i ?>').val('1')"  name="h_email_<?= $quarto_item ?>_<?= $i ?>"
                            id="h_email_<?= $quarto_item ?>_<?= $i ?>" placeholder="e-mail" value="<?= isset($hospedes_info[$i - 1]) ? $hospedes_info[$i - 1]['email'] : '' ?>">
                </div>
            </div>
            <!--Se for telas onde existam a exibição de campos adicionais como cpf, tipo de documento e telefone -->
            <?php
            if ($exibe_campos_adicionais) {
                $total_campos_adicionais = 0;
                ?>
                <!-- Verifica se o campo cpf vai ser exibido -->
                <?php if (strpos($pro_clicpfnum, "display: none") == false) { ?>
                    <div class="col-md-2 col-sm-6">
                        <?php if ($i == 1) { ?>
                            <label class="control-label col-md-12 col-sm-12"><b><?= $rot_clicpfnum ?></b></label>
                        <?php } ?>
                        <div class="col-md-12 col-sm-12">
                            <input class="form-control cpfcnpj h_cpf"  maxlength="18"   onchange="$('#h_has_changed_<?= $quarto_item ?>_<?= $i ?>').val('1')"  type="text" name="h_cpfnum_<?= $quarto_item ?>_<?= $i ?>" id="h_cpfnum_<?= $quarto_item ?>_<?= $i ?>"
                                   placeholder="<?= $for_clicpfnum ?>" <?= $pro_clicpfnum ?> value="<?= isset($hospedes_info[$i - 1]) ? $hospedes_info[$i - 1]['cpf'] ?? $hospedes_info[$i - 1]['cnpj'] : '' ?>" />
                        </div>
                    </div>
                    <?php
                    $total_campos_adicionais++;
                }
                ?>
                <!-- Verifica se o campo de telefone vai ser exibido -->
                <?php if (strpos($pro_clicelnum, "display: none") == false) { ?>
                    <div class="col-md-2 col-sm-6">
                        <?php if ($i == 1) { ?>
                            <label class="control-label col-md-12 col-sm-12"><b><?= $rot_gertelnum ?></b></label>
                        <?php } ?>
                        <div class="col-md-12 col-sm-12">
                            <input class="form-control celular" autocomplete="off" type="text"  onchange="$('#h_has_changed_<?= $quarto_item ?>_<?= $i ?>').val('1')" name="h_cel_<?= $quarto_item ?>_<?= $i ?>" id="h_cel_<?= $quarto_item ?>_<?= $i ?>" placeholder="<?= $for_clicelnum ?>" <?= $pro_clicelnum ?> <?= $val_clicelnum ?>
                                   value="<?= isset($hospedes_info[$i - 1]) ? $hospedes_info[$i - 1]['cel_numero'] : '' ?>" />
                        </div>
                    </div>
                    <?php
                    $total_campos_adicionais++;
                }
                ?>
                <!-- Verifica a quantidade de campos adicionais ja exibidos para determinar a largura final desses ultimos campos -->
                <div class="<?php
                if ($total_campos_adicionais == 1)
                    echo ' col-md-2 ';
                else
                    echo ' col-md-1 ';
                ?> col-sm-6">
                     <?php if ($i == 1 && $total_campos_adicionais == 1) { ?>
                        <label class="control-label col-md-12 col-sm-12"><b><?= $rot_clidoctip ?></b></label>
                    <?php } elseif ($i == 1) { ?>
                        <label class="control-label col-md-12 col-sm-12"><b><?= $rot_clidoctir ?></b></label>
                    <?php } ?>
                    <div class="col-md-12 col-sm-12">
                        <select class="form-control h_doctip"  onchange="$('#h_has_changed_<?= $quarto_item ?>_<?= $i ?>').val('1')"  name="h_doctip_<?= $quarto_item ?>_<?= $i ?>" id="h_doctip_<?= $quarto_item ?>_<?= $i ?>"  data-validation-depends-on="h_docnum_<?= $quarto_item ?>_<?= $i ?>" data-validation="required">
                            <option value=""></option>
                            <?php
                            foreach ($documento_tipo_lista as $item) {
                                $selected = "";
                                if (isset($hospedes_info[$i - 1]) && $hospedes_info[$i - 1]['cliente_documento_tipo'] == $item["valor"]) {
                                    $selected = "selected";
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>>
                                    <?= $item["rotulo"] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="<?php
                if ($total_campos_adicionais == 1)
                    echo ' col-md-2 ';
                else
                    echo ' col-md-1 ';
                ?> col-sm-6">
                     <?php if ($i == 1 && $total_campos_adicionais == 1) { ?>
                        <label class="control-label col-md-12 col-sm-12"><b><?= $rot_gernumtit ?></b></label>
                    <?php } elseif ($i == 1) { ?>
                        <label class="control-label col-md-12 col-sm-12"><b><?= $rot_gernumtir ?></b></label>
                    <?php } ?>
                    <div class="col-md-12 col-sm-12">
                        <input class="form-control h_docnum"   onchange="$('#h_has_changed_<?= $quarto_item ?>_<?= $i ?>').val('1')"  data-validation-depends-on="h_doctip_<?= $quarto_item ?>_<?= $i ?>" data-validation="required"  type="text" name="h_docnum_<?= $quarto_item ?>_<?= $i ?>" id="h_docnum_<?= $quarto_item ?>_<?= $i ?>"
                               placeholder="<?= $for_clidocnum ?>"  <?= $pro_clidocnum ?> <?= $val_clidocnum ?> value="<?= isset($hospedes_info[$i - 1]) ? $hospedes_info[$i - 1]['cliente_documento_numero'] : '' ?>" />
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

        </div>
    </div>
</div>
