<?php

use App\Model\Entity\Geral;
use Cake\Routing\Router;

$geral = new Geral();
?>
<div class="row" style="margin-bottom: 25px">
</div>
<form method="POST" name="conitecri" id="conitecri" action="<?= Router::url('/', true) ?>documentocontas/conitecri" class="form-horizontal">
    <input type="hidden" name="documento_numero"  value="<?= $documento_numero ?>">
    <input type="hidden" name="quarto_item" value="<?= $quarto_item ?>">
    <input type="hidden" name="quarto_status_codigo_atual" id="quarto_status_codigo_atual"  value="<?= $quarto_status_codigo_atual ?>">
    <input type="hidden" name="url_redirect_after"  id="url_redirect_after" value="<?= $redirect_page ?>">
    <input type="hidden" name="pagina_referencia"  id="pagina_referencia" value="<?= $redirect_page ?>">
    <input type="hidden" name="inicial_data"  id="inicial_data" value="<?= $inicial_data ?>">
    <input type="hidden" name="final_data"  id="final_data" value="<?= $final_data ?>">
    <?php
    //Não possui pdv cadastrado
    if (sizeof($convenpon_list) != 0) {
        ?>

        <div class="row">
            <div class="col-md-6">
                    <label class='control-label col-md-12 col-sm-12' for="gerdattit" <?= $pro_gerdattit ?>><?= $rot_gerdatcon ?> * </label>
                    <div class='col-md-12 col-sm-12'>
                        <input required="required"  class='form-control datepicker data' type="text" name="gerdattit" id="gerdattit" value="<?= date('d/m/Y') ?>" placeholder="<?= $for_gerdattit ?>"  <?= $pro_gerdattit ?> <?= $val_gerdattit ?> />
                    </div>
            </div>

            <div class="col-md-6">
                    <label class='control-label col-md-12 col-sm-12' for="convenpon" <?= $pro_convenpon ?>><?= $rot_convenpon ?> </label>
                    <?php
                    // se tiver apenas 1 pdv
                    if (sizeof($convenpon_list) == 1) {
                        ?>
                        <div class='col-md-12 col-sm-12'>
                            <input type="hidden" value="<?= $convenpon_list[0]["valor"] ?>" class='form-control' name="convenpon" id="convenpon" />
                            <input type="text" readonly="readonly" value="<?= $convenpon_list[0]["rotulo"] ?>" class='form-control' name="convenpon_txt" id="convenpon_txt" />
                        </div>

                        <?php
                    }// se tiver vários pdvs
                    elseif (sizeof($convenpon_list) > 1) {
                        ?>
                        <div class='col-md-12 col-sm-12'>
                            <select class='form-control recalcula_preco' <?= $pro_convenpon ?> name="convenpon" id="convenpon"> <option value=""></option>
                                <?php foreach ($convenpon_list as $item) { ?>
                                    <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option>
                                <?php } ?>
                            </select>
                        </div>

                    <?php } ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

                <input type="hidden" name="gerempcod" id="gerempcod" value="<?= $gerempcod ?>" />
                <label class='control-label col-md-12 col-sm-12' for="conprocod" <?= $pro_conprocod ?>><?= $rot_conprocod ?> * </label>
                <div class='col-md-12 col-sm-12'>
                    <input type="hidden" id="conprocod" name="conprocod" value=""  required='required'  />
                    <input type="hidden" id="concontip" name="concontip" value=""   />
                    <input type="hidden" id="produto_tipo_codigo" name="produto_tipo_codigo" value=""   />
                    <input type="hidden" id="conta_editavel_preco" name="conta_editavel_preco" value=""   />
                    <input type="hidden" id="servico_taxa_incide" name="servico_taxa_incide" value="0" />
                    <input type="hidden" id="has_select" value="0"   />
                    <input type="hidden" id="vendavel" value="1"   />
                    <input type="hidden" id="horario_modificacao_tipo" name="horario_modificacao_tipo" value=""   />
                    <input type="hidden" id="horario_modificacao_valor" name="horario_modificacao_valor" value=""   />
                    <input id="conpronom" autocomplete="off" type="text"  data-produto-codigo="conprocod" class='produto_autocomplete form-control input_autocomplete' required='required' <?= $pro_conprocod ?> />
                </div>

            </div>

            <div class="col-md-6">
                <div class='form-group'>
                    <label class='control-label col-md-12 col-sm-12 ' for="conproqtd" <?= $pro_conproqtd ?>><?= $rot_conproqtd ?>* </label>
                    <div class='col-md-12'>
                        <input onkeyup="$('#conpretot').val(gervalexi(gervalper(this.value) * gervalper($('#conpreuni').val())))"  required="required" class='form-control' type="text" name="conproqtd" id="conproqtd" value="<?= $conproqtd ?? '' ?>" placeholder="<?= $for_conproqtd ?>"  <?= $pro_conproqtd ?> <?= $val_conproqtd ?> />
                    </div>
                    <div class='col-md-12'>
                        <span class='control-label' id="variavel_fator_nome" style="display: none;"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class='form-group'>
                    <label class='control-label col-md-12 col-sm-12' for="conpreuni" <?= $pro_conpreuni ?>><?= $rot_conpreuni ?> <?= $geral->germoeatr() ?> </label>
                    <div class='col-md-12 col-sm-12'>
                        <input readonly="readonly" onkeyup="$('#conpretot').val(gervalexi(gervalper(this.value) * gervalper($('#conproqtd').val())))" class='form-control moeda' type="text" name="conpreuni" id="conpreuni" value="<?= $geral->gersepatr($conpreuni ?? '') ?>" placeholder="<?= $for_conpreuni ?>"   <?= $pro_conpreuni ?> <?= $val_conpreuni ?> />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class='form-group'>

                    <label class='control-label col-md-12 col-sm-12' for="conpretot" <?= $pro_conpretot ?>><?= $rot_conpretot ?> <?= $geral->germoeatr() ?> </label>
                    <div class='col-md-12 col-sm-12'>
                        <input disabled="disabled" class='form-control moeda' type="text" name="conpretot" id="conpretot" value="" placeholder="<?= $for_conpretot ?>"  <?= $pro_conpretot ?> <?= $val_conpretot ?> />
                        <button class="es-form-button <?= $ace_condesapl ?> condesapl" title="Modificar valores" id="conbtndes" style="padding: 4px;background-color:none; right: 5px;" type="button"><span class='ui-icon ui-icon-pencil'></span></button>
                    </div>
                </div>
            </div>

        </div>


        <div id='item_desconto_geral' class="row" style="display: none;">

            <div class="col-md-6">
                <div class='col-md-12 col-sm-12'>
                    <label style="padding-left: 0;" class='col-md-1 col-sm-12'><?= $rot_gertipmot ?> </label>

                    <select class="form-control" <?= $pro_gertipmot ?> name="gertipmot" id="gertipmot_geral_desc">
                        <?php
                        foreach ($gertipmot_list_desc as $item) { ?>
                            <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option>
                        <?php } ?>
                    </select>

                    <select class="form-control" <?= $pro_gertipmot ?> name="gertipmot" id="gertipmot_geral_acre">
                        <?php
                        foreach ($gertipmot_list_acre as $item) {
                            ?>
                            <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class='col-md-12 col-sm-12'>
                    <label style="padding-left: 0; padding-top: 0;" class='control-label col-md-1 col-sm-12' for="gerobstit"><?= $rot_gerobstit ?> </label>
                    <textarea maxlength="50" style="height: 50px !important; border-color: #ccc;" class='form-control' type="text" name="gerobstit" id="gerobstit" placeholder="<?= $for_gerobstit ?>"  <?= $pro_gerobstit ?> <?= $val_gerobstit ?>></textarea>
                </div>
            </div>

        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <?php if ($modo_exibicao == 'tela') { ?>
                    <input style="float:left; margin-right:10px; margin-left: 5px;" type="button"  value="<?= $rot_gerdesbot ?>" class="es-default-button" onclick="gerpagexi($('#url_redirect_after').val(), 1, {})">
                <?php } else { ?>
                    <button style="float:left; margin-right:10px; margin-left: 5px; width: 170px;" type="button" class="close_dialog es-default-button"><i class="fa fa-times-circle"></i> <?= $rot_gerdesbot ?></button>
                <?php } ?>
                <button style="float:right; margin-right: 5px;" class="es-default-button btn-primary btn submit-button" type="submit" aria-form-id="conitecri" name="resmodbtn" ><i class="fa fa-check-circle"></i> <?= $rot_gersalbot ?></button>
            </div>
        </div>

    <?php } else { ?>

        <div class="row alert alert-danger" >
            <?= $geral->germencri($gerempcod, 109, 3)['mensagem']; ?>
        </div>
        <div class="row"></div>
        <div class="row">
            <input type="button" class="close_dialog" value="<?= $rot_gerdesbot ?>" >
        </div>
    <?php } ?>

    <!--Campos para desconto, se houver -->
    <input type="hidden" name="desc_cortesia_tmp" id="desc_cortesia_tmp" value="" />
    <input type="hidden" name="gerdesfat_tmp" id="gerdesfat_tmp" value="" />
    <input type="hidden" name="gerdestip_tmp" id="gerdestip_tmp" value="" />
    <input type="hidden" name="gerdesval_tmp" id="gerdesval_tmp" value="" />
    <input type="hidden" name="gertipmot_tmp" id="gertipmot_tmp" value="" />
    <input type="hidden" name="gerobstit_tmp" id="gerobstit_tmp" value="" />
    <input type="hidden" name="gerusucod_tmp" id="gerusucod_tmp" value="" />
</form>
