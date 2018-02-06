<?php

use App\Utility\Util;
?>
<div class="form-group row" style="margin-top: 15px">
    <input type="hidden" autofocus="true" />
    <div class="col-md-12 col-sm-12">   
        <div class="col-md-6 col-sm-12">    
            <label class="control-label col-md-12 col-sm-12" for="resentdat" <?= $pro_resentdat ?>><?= $rot_resentdat ?></label>
            <input size="10" maxlength="10" class="form-control datepicker_future data data_incrementa_maior data_place"  aria-id-campo-filho='ressaidat'  type="text" name="resentdat" id="resentdat"
                   value="<?= Util::convertDataDMY($inicial_data) ?>" placeholder="<?= $for_resentdat ?>"  <?= $pro_resentdat ?> <?= $val_resentdat ?>
                   autocomplete="off"/>
        </div>
        <div class="col-md-6 col-sm-12">    
            <label class="control-label col-md-12 col-sm-12" for="ressaidat" <?= $pro_ressaidat ?>><?= $rot_ressaidat ?></label>
            <input size="10" maxlength="10" class="form-control data datepicker_future data_place"  aria-id-campo-dependente="resentdat"  
                   type="text" name="ressaidat" id="ressaidat"
                   value="<?= Util::convertDataDMY($final_data) ?>"
                   placeholder="<?= $for_ressaidat ?>" <?= $pro_ressaidat ?> <?= $val_ressaidat ?> autocomplete="off" />
        </div>
    </div>

    <div class="row col-md-12 col-sm-12 quat_botoes">
        <div class="pull-left col-md-3 col-sm-4">
            <input class="form-control btn-default close_dialog" type="button" value="<?= $rot_gerdesbot ?>" />
        </div>
        <div class="pull-right col-md-3 col-sm-4" style="margin: 15px 0px 10px">
            <input style="margin-right:10px" class='form-control btn-primary reserva_criar' data-quarto-tipo-codigo="<?= $quarto_tipo_codigo ?>"
                   data-quarto-codigo = "<?= $quarto_codigo ?>" type="button" value='<?= $rot_geravabot ?>' /> 
        </div>
    </div>
    <div class="row col-md-12 col-sm-12" style="margin-top: 15px">
        <p class="col-xs-12 msg_error" style='display:none' id="mensagem_indisponibilidade"></p>
    </div>
</div>