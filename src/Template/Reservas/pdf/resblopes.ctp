<div class="content_inner">
    <?php include('./pdf_header.php'); ?>

    <div style="margin-bottom: 60px">
        <form method="POST" name="resblopes" id="resblopes" action="<?= $path ?>reservas/resblopes" class="form-horizontal" >
            <input type="hidden" id="pesquisar_servicos" name="pesquisar_servicos" value="no">
            <input type="hidden" id="pagina" value="1" name="pagina" />
            <input type="hidden" id="form_atual" value="resblopes" />
            <input type="hidden" id="form_force_submit" value="0" />
            <input type="hidden" id="gerdiacon_executada" value="0" />
            <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
            <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
            <input type="hidden" id="export_csv" value="0" name="export_csv" />
            <input type="hidden" id="aria-form-id-csv" value="resblopes" >
            <input type="hidden" id="export_pdf" value="0" name="export_pdf" />
             <input type="hidden" id="title_dialog_validator" value="" >
            <input type="hidden" id="form_validator_function" name="form_validator_function" value="
                   if ($('#serdocnum').val() == ''  && $('#serserdat').val() ==  '') {
                   if (gerdiacon($('#serdatini').val(), $('#serdatinf').val(),<?= $servico_pesquisa_max ?>, 2, 1) == 1){
                   return true;
                   }
                   else if (gerdiacon($('#serdatfii').val(), $('#serdatfif').val(),<?= $servico_pesquisa_max ?>, 2, 1) == 1){
                   return true;
                   }
                   else {
                   dialog = $('#exibe-germencri').dialog({
                   title: $('#title_dialog_validator').val(),
                   dialogClass: 'no_close_dialog',
                   autoOpen: false,
                   height: 200,
                   width: 530,
                   modal: true,
                   buttons: {
                   Cancelar: function () {
                   dialog.dialog('close');
                   $('#form_force_submit').val('1');
                   $('#resblopes .submit-button').click();
                   },
                   Ok: function () {
                   dialog.dialog('close');
                   $('#gerdiacon_executada').val('0');
                   }
                   }
                   });
                   dialog.dialog('open');
                   return false;
                   }
                   }else
                   return true;
                   ">

            <div class="form-group">
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_gerdoctit ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input class="form-control" type="text" id="serdocnum" value="<?= $serdocnum??'' ?>" name="serdocnum" />
                </div>
            </div>
                <div class="col-md-1 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="resquacod" ><?= $rot_resquacod ?></label>
                    <div class="col-md-11 col-sm-12">
                        <select class="form-control" name="resquacod" id="resquacod" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_resquacod ?>"  aria-padrao-valor="<?= $padrao_valor_resquacod ?? '' ?>">
                            <option value=""></option>
                            <?php
                            foreach ($gerquacod_list as $item) {
                                $selected = "";

                                if (isset($resquacod)) {
                                    if ($resquacod == $item['valor'])
                                        $selected = 'selected = \"selected\"';
                                }else if (isset($padrao_valor_resquacod)) {
                                    if ($padrao_valor_resquacod == $item['valor']) {
                                        $selected = 'selected = \"selected\"';
                                    }
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["valor"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="resquatip" style="text-align: left; width: 117px; padding-left: 0;"><?= $rot_resquatip ?></label>
                    <div class="col-md-11 col-sm-12">
                    <select class="form-control" name="resquatip" id="resquatip" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_resquatip ?>"  aria-padrao-valor="<?= $padrao_valor_resquatip??'' ?>">
                        <option value=""></option>
                        <?php
                        foreach ($serquatip_list as $item) {
                            $selected = "";

                            if (isset($resquatip)) {
                                if ($resquatip == $item['valor'])
                                    $selected = 'selected = \"selected\"';
                            }else if (isset($padrao_valor_resquatip)) {
                                if ($padrao_valor_resquatip == $item['valor']) {
                                    $selected = 'selected = \"selected\"';
                                }
                            }
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                        <?php } ?> 
                    </select>        
                </div>
            </div>
                <div class="col-md-3 col-sm-12">
                <input type="hidden" value="bc" name="serdoctip" id="serdoctip" />

                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_resdocsta ?></label>
                    <div class="col-md-11 col-sm-12">

                    <dl class="dropdown"> 
                        <dt>
                            <a href="#">
                                <?php
                                $texto = "";
                                if (isset($gerdocsta) && sizeof($gerdocsta) == 1)
                                    $texto = "1 selecionado";
                                elseif (isset($gerdocsta) && sizeof($gerdocsta) > 1)
                                    $texto = sizeof($gerdocsta) . " selecionados ";
                                ?>
                                <input class="form-control" type="text" class="multiSel" value="<?= $texto ?>" id="show-check-number" />  
                            </a>
                        </dt>
                        <dd>
                            <div class="mutliSelect">
                                <ul id="serdocsta">
                                    <?php
                                    foreach ($gerdomsta_list as $item) {
                                        $checked = "";
                                        if (isset($gerdocsta)) {
                                            foreach ($gerdocsta as $status_preenchido) {
                                                if ($item['valor'] == $status_preenchido)
                                                    $checked = "checked='checked'";
                                            }
                                        }
                                        ?>
                                        <li><input name="gerdocsta[]" type="checkbox" <?= $checked ?> value="<?= $item["valor"] ?>" /> <?= $item["rotulo"] ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </dd>
                    </dl>
                </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_germottit ?></label>
                    <div class="col-md-11 col-sm-12">
                    <select class="form-control" name="serdocmot" id="serdocmot"> 
                        <option value=""></option>
                        <?php
                        foreach ($gerdommot_list as $item) {
                            if ($serdocmot == $item["valor"]) {
                                $selected = 'selected = \"selected\"';
                            } else {
                                $selected = "";
                            }
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php
                        }
                        ?> 
                    </select>
                </div>
            </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-md-1 col-sm-3" for="serdatini"><?= $rot_gerdatini ?>:</label>
                <div class="col-md-2 col-sm-3" style="padding-left:0; padding-right: 0;"> 
                    <input maxlength="10" class="form-control datepicker data" type="text"
                           aria-campo-padrao-valor ="<?= $campo_padrao_valor_serdatini ?>"  aria-padrao-valor="<?= $padrao_valor_serdatini??'' ?>"
                           name="serinidat_inicial" id="serdatini" value="<?= $serinidat_inicial??$padrao_valor_serdatini??'' ?>" placeholder="00/00/0000" 
                           data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-optional="true">
                </div>
                <div class="col-md-1 col-sm-3" style="padding:0; margin-left: -70px; margin-top: 4px;margin-right: -40px;"> 
                    <span>-</span>
                </div>
                <div class="col-md-2 col-sm-3" style="padding-left:0; margin-left: -58px;"> 
                    <input maxlength="10" class="form-control datepicker data" type="text" name="serinidat_final" id="serdatinf" 
                           aria-campo-padrao-valor ="<?= $campo_padrao_valor_serdatinf ?>"  aria-padrao-valor="<?= $padrao_valor_serdatinf??'' ?>"
                           value="<?= $serinidat_final??$padrao_valor_serdatinf??'' ?>" placeholder="00/00/0000"  
                           data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-optional="true">
                </div>

                <label class="control-label col-md-1 col-sm-3" for="serdatfii"><?= $rot_gerdatfin ?>:</label>
                <div class="col-md-2 col-sm-3" style="padding-left:0; padding-right: 0;"> 
                    <input maxlength="10" class="form-control datepicker data" 
                           aria-campo-padrao-valor ="<?= $campo_padrao_valor_serdatfii ?>"  aria-padrao-valor="<?= $padrao_valor_serdatfii??'' ?>"
                           type="text" name="serfindat_inicial" id="serdatfii" value="<?= $serfindat_inicial??$padrao_valor_serdatfii??'' ?>" placeholder="00/00/0000"
                           data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-optional="true">
                </div>
                <div class="col-md-1 col-sm-3" style="padding:0; margin-left: -70px; margin-top: 4px;margin-right: -40px;"> 
                    <span>-</span>
                </div>
                <div class="col-md-2 col-sm-3" style="padding-left:0; margin-left: -58px;"> 
                    <input maxlength="10" class="form-control datepicker data" type="text" name="serfindat_final" id="serdatfif"
                           aria-campo-padrao-valor ="<?= $campo_padrao_valor_serdatfif ?>"  aria-padrao-valor="<?= $padrao_valor_serdatfif??'' ?>"
                           value="<?= $serfindat_final??$padrao_valor_serdatfif??'' ?>" placeholder="00/00/0000" data-validation="date"
                           data-validation-format="dd/mm/yyyy" data-validation-optional="true">
                </div>
                <label class="control-label col-md-1 col-sm-3" for="serserdat"><?= $rot_gerdattit ?>:</label>
                <div class="col-md-2 col-sm-3 row">
                    <input maxlength="10" class="form-control datepicker data" type="text" name="serserdat" id="serserdat" value="<?= $serserdat??'' ?>" placeholder="00/00/0000"    data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-optional="true">
                </div>
                <div class="col-md-3 col-sm-3"></div>
            </div>
            <div class="col-md-7">
                <div class='col-md-2 col-sm-3'>
                    <input class="form-control btn-primary submit-button" aria-form-id="resblopes" type="submit" name="serpesbtn" value="<?= $rot_gerexebot ?>">
                </div>
            </div>
        </form>
    </div>

    <?php
    if (isset($pesquisa_servicos) && sizeof($pesquisa_servicos) > 0 && $pesquisar_servicos == 'yes') {
        if (count($pesquisa_servicos) > 0) {
            ?>
            <form id="serdoccmo" name="serdoccmo" action="<?= $path ?>/servicos/serdocmod" method="post">
                <input type="hidden" name="gerempcod" value="<?= $empresa_codigo ?>" />
                <input type="hidden" name="modifica_multiplos" value="1" />
                <input type="hidden" name="tipo_acao" id="tipo_acao" value="0" />
                <input type="hidden" id="url_redirect_after" value="reservas/resblopes" />
                <div>
                    <table class="table_cliclipes">
                        <thead>
                            <tr><td colspan='8'><?= $rot_sersertit ?></td></tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th><?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'inicial_data', 'aria_form_id' => 'resblopes', 'label' => $rot_gerdatini]); ?></th>
                                <th><?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'final_data', 'aria_form_id' => 'resblopes', 'label' => $rot_gerdatfin]); ?></th>
                                <th><?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'quarto_codigo', 'aria_form_id' => 'resblopes', 'label' => $rot_resquacod]); ?></th>
                                <th><?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'quarto_tipo_nome', 'aria_form_id' => 'resblopes', 'label' => $rot_resquatip]); ?></th>
                                <th><?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_numero', 'aria_form_id' => 'resblopes', 'label' => $rot_gerdoctit]); ?></th>
                                <th><?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_tipo_nome', 'aria_form_id' => 'resblopes', 'label' => $rot_gertiptit]); ?></th>
                                <th><?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_status_nome', 'aria_form_id' => 'resblopes', 'label' => $rot_resdocsta]); ?></th>
                            </tr>
                        </thead>
                        <?php
                        $indice = 0;
                        foreach ($pesquisa_servicos as $value) {
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="check_doc" name="indices_selecionados[]" value="<?= $indice ?>" />
                                    <input type="hidden" name="documento_tipos[]" value="<?= $value['documento_tipo_codigo'] ?>" />
                                    <input type="hidden" name="documento_numeros[]" value="<?= $value['documento_numero'] ?>" />
                                    <input type="hidden" name="quarto_codigos[]" value="<?= $value['quarto_codigo'] ?>" />
                                    <input type="hidden" name="documento_status[]" value="<?= $value['documento_status_codigo'] ?>" />
                                    <input type="hidden" name="inicial_datas[]" value="<?= $value['inicial_data'] ?>" />
                                    <input type="hidden" name="final_datas[]" value="<?= $value['final_data'] ?>" />
                                </td>
                                <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= date('d/m/Y', strtotime($value['inicial_data'])) ?></td>                     
                                <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= date('d/m/Y', strtotime($value['final_data'])) ?></td>                     
                                <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= $value['quarto_codigo'] ?></td>                     
                                <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= $value['quarto_tipo_nome'] ?></td>                     
                                <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= $value['documento_numero'] ?></td>                     
                                <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= $value['documento_tipo_nome'] ?></td>
                                <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= $value['documento_status_nome'] ?></td>
                            </tr>
                            <?php
                            $indice++;
                        }
                    }
                    ?>

                </table>
            </div>
            <div class="row" style="margin-top:15px">
                <div class='col-md-2 col-sm-3'>
                    <input class="form-control btn-primary submit-button" aria-form-id="serdoccmo"  type="submit" name="serpesbtn" value="<?= $rot_gercncsel ?>" 
                           onclick="gerpagsal('resblopes', 'reservas/resblopes', '1'); $('#tipo_acao').val('2');">
                </div>
            </div>
        </form>
        <?php
        echo $paginacao;
    }
    ?>

</div>