<?php

use Cake\Routing\Router;

$path = Router::url('/', true);
?>
<h1 class="titulo_pag">
    <?php
    echo $rot_serbpetit;
    ?>
</h1>

<script>
    $('.export-pdf-link').click(function () {
        $("#resblopes").attr("target", "_blank");
        var action = $("#resblopes").attr("action");
        $("#resblopes").attr("action", "<?= Router::url('/', true) ?>reservas/resblopes/bloqueio.pdf");
        $("#resblopes").submit();
        $("#resblopes").attr("action", action);
    });
</script>

<div class="content_inner " style=''>

    <div class="formulario">
        <form method="POST" name="resblopes" id="resblopes" action="<?= $path ?>reservas/resblopes" class="form-horizontal" >
            <input type="hidden" id="pesquisar_servicos" name="pesquisar_servicos" value="no">
            <input type="hidden" id="pagina" value="1" name="pagina" />
            <input type="hidden" id="form_atual" value="resblopes" />
            <input type="hidden" id="form_force_submit" value="0" />
            <input type="hidden" id="gerdiacon_executada" value="0" />
            <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
            <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
            <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />
            <input type="hidden" id="export_csv" value="0" name="export_csv" />
            <input type="hidden" id="aria-form-id-csv" value="resblopes" >
            <input type="hidden" id="export_pdf" value="1" name="export_pdf" />
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
                   '<?= $rot_gerconrev ?>': function () {
                   dialog.dialog('close');
                   $('#form_force_submit').val('1');
                   $('#resblopes .submit-button').click();
                   },
                   '<?= $rot_gerrevdat ?>': function () {
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
                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_gerdoctit ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input class="form-control" type="text" id="serdocnum" value="<?= $serdocnum ?? '' ?>" name="serdocnum" />
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="resquacod" ><?= $rot_resquacod ?></label>
                    <div class="col-md-11 col-sm-12">
                        <select class="no-select-all-with-search"  multiple name="resquacod[]" id="resquacod">
                            <?php
                            foreach ($quarto_por_tipo as $quarto => $quarto_tipo_curto_nome) {
                                $selected = "";

                                if (isset($resquacod)) {
                                    foreach ($resquacod as $quarto_preenchido) {
                                        if ($quarto == $quarto_preenchido)
                                            $selected = "selected='selected'";
                                    }
                                }
                                ?>
                                <option data-subtext="<?= $quarto_tipo_curto_nome ?>" value="<?= $quarto ?>" <?= $selected ?>><?= $quarto ?></option>
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="resquatip"><?= $rot_resquatip ?></label>
                    <div class="col-md-11 col-sm-12">
                        <select id="resquatip" name="resquatip[]" class="select-all-no-search" multiple aria-campo-padrao-multiselect="1" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_resquatip ?>"  aria-padrao-valor="<?= $padrao_valor_resquatip ?? '' ?>">
                                    <?php
                                    foreach ($serquatip_list as $item) {
                                        $selected = "";

                                        if (isset($resquatip)) {
                                            foreach ($resquatip as $quarto_tipo_preenchido) {
                                                if ($item['valor'] == $quarto_tipo_preenchido)
                                                    $selected = "selected='selected'";
                                            }
                                        }
                                        ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <input type="hidden" value="bc" name="serdoctip" id="serdoctip" />

                    <label class="control-label col-md-12 col-sm-12" for="gerdocsta"><?= $rot_gerdocsta ?></label>
                    <div class="col-md-11 col-sm-12">
                        <select id="gerdocsta" name="gerdocsta[]" class="select-all-no-search" multiple aria-campo-padrao-multiselect="1" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_gerdocsta ?>"  aria-padrao-valor="<?= $padrao_valor_gerdocsta ?? '' ?>">
                                    <?php
                                    foreach ($gerdomsta_list as $item) {
                                        $selected = "";
                                        if (isset($gerdocsta)) {
                                            foreach ($gerdocsta as $status_preenchido) {
                                                if ($item['valor'] == $status_preenchido)
                                                    $selected = "selected='selected'";
                                            }
                                        }
                                        ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>> <?= $item["rotulo"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="germottit" ><?= $rot_germottit ?></label>
                    <div class="col-md-12 col-sm-12">
                        <select id="germottit" name="germottit[]" class="no-select-all-with-search" aria-campo-padrao-multiselect="1" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_germottit ?>"  aria-padrao-valor="<?= $padrao_valor_germottit ?? '' ?>">
                            <option></option>
                            <?php
                            foreach ($gerdommot_list as $item) {
                                $selected = "";
                                if (isset($germottit) && is_array($germottit)) {
                                    foreach ($germottit as $motivo_preenchido) {
                                        if ($motivo_preenchido != "")
                                            if ($item['valor'] == explode("_", $motivo_preenchido)[1])
                                                $selected = "selected='selected'";
                                    }
                                }
                                ?>
                                <option value="bc_<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12" for="gerdattip"><?= $rot_gerdattip ?></label>
                    <div class='col-md-11 col-sm-11'>
                        <select name="gerdattip" id="gerdattip"  class="form-control" aria-campo-padrao-valor ="<?= $campo_padrao_valor_gerdattip ?>"  aria-padrao-valor="<?= $padrao_valor_gerdattip ?? '' ?>" >
                            <option value=""></option>
                            <option value="criacao" <?php if (($gerdattip ?? '') == 'criacao') echo 'selected'; ?>><?= $rot_gercritit ?></option>
                            <option value="entrada" <?php if (($gerdattip ?? '') == 'entrada') echo 'selected'; ?>><?= $rot_gerentdat ?></option>
                            <option value="estadia" <?php if (($gerdattip ?? '') == 'estadia') echo 'selected'; ?>><?= $rot_esttittit ?></option>  
                            <option value="saida" <?php if (($gerdattip ?? '') == 'saida') echo 'selected'; ?>><?= $rot_gersaidat ?></option>                           
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12" for="gerdatini" <?= $pro_gerdatini ?>><?= $rot_gerdatini ?></label>
                    <div class='col-md-11 col-sm-11'> 
                        <input maxlength="10" class='form-control datepicker data data_place data_incrementa_igual' aria-id-campo-filho='gerdatfin'  type="text" name="gerdatini" id="gerdatini"
                               value="<?= $gerdatini ?? '' ?>" placeholder="<?= $for_gerdatini ?>" />
                    </div>
                    <div class="col-md-1 col-sm-1"><span style="padding: 0 4px;"> _ </span></div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12"><?= $rot_gerdatfin ?></label>
                    <div class='col-md-11 col-sm-11'> 
                        <input maxlength="10" class='form-control datepicker data data_place' aria-id-campo-dependente="gerdatini"  type="text" name="gerdatfin" id="gerdatfin"
                               value="<?= $gerdatfin ?? '' ?>" placeholder="<?= $for_gerdatfin ?>"  data-validation="futuradata3" data-validation-optional="true" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <div class="col-md-10 col-sm-8"></div>
                    <div class='pull-left col-md-2 col-sm-4'>
                        <input class="form-control btn-primary submit-button" aria-form-id="resblopes" type="submit" name="serpesbtn" value="<?= $rot_gerexebot ?>">
                    </div>
                </div>
            </div>
        </form>
        <?php
        if (isset($pesquisa_servicos) && sizeof($pesquisa_servicos) > 0 && $pesquisar_servicos == 'yes') {
            if (count($pesquisa_servicos) > 0) {
                ?>
                <form id="serdoccmo" name="serdoccmo" action="<?= $path ?>/servicos/serdocmod" method="post">
                    <input type="hidden" name="gerempcod" value="<?= $empresa_codigo ?>" />
                    <input type="hidden" name="modifica_multiplos" value="1" />
                    <input type="hidden" name="tipo_acao" id="tipo_acao" value="0" />
                    <input type="hidden" id="url_redirect_after" value="reservas/resblopes" />
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <div class="col-md-12 col-sm-12">
                                <table class="table_cliclipes">
                                    <thead>
                                        <tr class="tabres_cabecalho2">
                                            <th width='1%'>&nbsp;</th>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'inicial_data', 'aria_form_id' => 'resblopes', 'label' => $rot_gerdatini]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'final_data', 'aria_form_id' => 'resblopes', 'label' => $rot_gerdatfin]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'quarto_codigo', 'aria_form_id' => 'resblopes', 'label' => $rot_resquacod]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_status_nome', 'aria_form_id' => 'resblopes', 'label' => $rot_resdocsta]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_numero', 'aria_form_id' => 'resblopes', 'label' => $rot_gerdoctit]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'motivo_nome', 'aria_form_id' => 'resblopes', 'label' => $rot_germottit]); ?>
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
                                            <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= $value['quarto_codigo'] . ' - ' . $value['quarto_tipo_curto_nome'] ?></td>                     
                                            <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= $value['documento_status_nome'] ?></td>
                                            <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= $value['documento_numero'] ?></td>                     
                                            <td class="serdocmod" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-documento-tipo-codigo='<?= $value["documento_tipo_codigo"] ?>'><?= $value['motivo_nome'] ?></td>
                                        </tr>
                                        <?php
                                        $indice++;
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group" style="margin-top:15px">
                    <div class="col-md-12 col-sm-12 top1">
                     
                        <div class='col-md-1 tamanho_btnPes col-sm-2'>
                            <input class="form-control btn-secundary submit-button pad_btnPes" aria-form-id="serdoccmo"  type="submit" name="serpesbtn" value="Cancelar<?php //$rot_gercncsel           ?>" 
                                   onclick="gerpagsal('resblopes', 'reservas/resblopes', '1');
                                               $('#tipo_acao').val('2');">
                        </div>
						
						   <div class='col-md-1 tamanho_btnPes col-sm-2'>
                            <button type="button" class="form-control btn-primary" onclick="gerpagexi('/reservas/resblocri', 1, {})" >Criar<?php //$rot_sercribot           ?></button>
                        </div>
						
						
                        <div class="col-md-9 col-sm-8 font_12" style="float: right; padding: 0px;" >
                            <div class="col-md-10 col-sm-12"  style="float: right;" >
                                <?php echo $paginacao; ?>
                            </div>
                        </div>
                    </div>    
                </div>
            </form>
        <?php } ?>   

    </div>

</div>

