<?php

use App\Model\Entity\Geral;

$geral = new Geral();
?>

<form class="form-horizontal" autocomplete="off">
    <input style="display:none">
    <input type="password" style="display:none">
    <input type="hidden" id="conpretot_desc" value="<?= $preco_total ?>" />
    <input type="hidden" id="total_desconto" value="<?= $total_desconto ?>" />
    <div class="form-group">
        <div class="col-md-12 col-sm-12">
            <div class="col-md-2 col-sm-3">
                <label class="radio-inline"><input class="desconto_cortesia" <?php if ($desc_cortesia == 'd') echo "checked='checked'"; ?> type="radio" name="desc_cortesia" value="d"><?= $rot_gerdescon ?></label>
            </div>
            <div class="col-md-2 col-sm-3">
                <label class="radio-inline"><input class="desconto_cortesia" <?php if ($desc_cortesia == 'c') echo "checked='checked'"; ?> type="radio" name="desc_cortesia" value="c"><?= $rot_gerdescor ?></label>
            </div>
            <div class="col-md-2 col-sm-3">
                <label class="radio-inline"><input class="desconto_cortesia" <?php if ($desc_cortesia == 'a') echo "checked='checked'"; ?> type="radio" name="desc_cortesia" value="a"><?= $rot_gerconacr ?></label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-4 col-sm-12">
            <label  class='control-label col-md-12 col-sm-12'><?= $rot_geranttit ?> (<?= $geral->germoeatr(); ?>)</label>
            <div class='col-md-12 col-sm-12'>
                <input disabled="disabled" class='form-control' type="text" id="preco_anterior" value="<?= $geral->gersepatr($preco_anterior) ?>" />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <label class='col-md-12 col-sm-12 control-label'><span id="desconto_rotulo"><?= $rot_gerdescon ?>/<?= $rot_gerconacr ?></span> </label>
            <div class="col-md-12 col-sm-12">    
                <input <?php if ($desc_cortesia == 'c') echo "disabled='disabled'"; ?> class='form-control <?php
                if ($desconto_tipo == 'p')
                    echo 'moeda_sem_decimais';
                else
                    echo 'moeda'
                    ?> atualiza_valor_desconto desconto_fator' type="text" name="gerdesfat" id="gerdesfat" value="<?php
                                                                                       if ($desconto_tipo == 'p')
                                                                                           echo intval($desconto_fator);
                                                                                       else
                                                                                           echo $geral->gersepatr($desconto_fator)
                                                                                           ?>" placeholder="<?= $for_gerdesfat ?>"   <?= $pro_gerdesfat ?> />
            </div>
        </div>
        <div class="col-md-2 col-sm-4">
            <label class='control-label col-md-12 col-sm-12 <?php if ($desc_cortesia == 'c') echo 'display_none'; ?>' id="gerdestip_label">Unidade</label>
            <div class="col-md-12 col-sm-12">   
                <select class="form-control  <?php if ($desc_cortesia == 'c') echo 'display_none'; ?>" <?= $pro_gerdestip ?> name="gerdestip" id="gerdestip" style="padding:0 !important">                     
                    <option value="p" <?php if ($desconto_tipo == 'p') echo 'selected' ?>>%</option> 
                    <option value="v"  <?php if ($desconto_tipo == 'v') echo 'selected' ?>><?= $geral->germoeatr(); ?></option> 
                </select> 
            </div>
        </div>        
        <div class="col-md-3 col-sm-12">
            <label id="label-moeda" class='control-label col-md-12 col-sm-12  <?php if ($desc_cortesia == 'c') echo 'display_none'; ?>'><?= $rot_gerdescon ?>/<?= $rot_gerconacr ?> (<?= $geral->germoeatr(); ?>) </label>
            <div class='col-md-12 col-sm-12'>
                <input readonly="readonly" class='form-control <?php if ($desc_cortesia == 'c') echo 'display_none'; ?>' type="text" name="gerdesval" id="gerdesval" value="<?= $geral->gersepatr($desconto_valor) ?>" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-4 col-sm-12">
            <label class='control-label col-md-12 col-sm-12'><?= $rot_gerposTit ?> (<?= $geral->germoeatr(); ?>) </label>
            <div class='col-md-12 col-sm-12'>
                <input disabled="disabled" class='form-control' type="text" id="preco_posterior" value="<?= $geral->gersepatr($preco_posterior) ?>" />
            </div>
        </div>
        <div class="col-md-8 col-sm-12" id="motivos-desconto" <?php if ($desc_cortesia != 'd') echo "style='display:none'"; ?> >
            <label  class='control-label col-md-12 col-sm-12'><?= $rot_gertipmot ?> </label>
            <div class='col-md-12 col-sm-12'> 
                <select class="form-control" <?= $pro_gertipmot ?> name="gertipmot" id="gertipmot_desc"> 
                    <?php
                    foreach ($gertipmot_list_desc as $item) {
                        $selected = '';
                        if ($item['valor'] == $motivo_desconto)
                            $selected = "selected = 'selected' ";
                        ?>
                        <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                    <?php } ?> 
                </select>                        
            </div>
        </div>

        <div class="col-md-8 col-sm-12"  id="motivos-cortesia" <?php if ($desc_cortesia != 'c') echo "style='display:none'"; ?>>
            <label  class='control-label col-md-12 col-sm-12'><?= $rot_gertipmot ?> </label>
            <div class='col-md-12 col-sm-12'> 
                <select class="form-control" <?= $pro_gertipmot ?> name="gertipmot" id="gertipmot_cort"> 
                    <?php
                    foreach ($gertipmot_list_cort as $item) {
                        $selected = '';
                        if ($item['valor'] == $motivo_cortesia)
                            $selected = "selected = 'selected' ";
                        ?>
                        <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                    <?php } ?> 
                </select>                        
            </div>
        </div>

        <div class="col-md-8 col-sm-12"  id="motivos-acrescimo" <?php if ($desc_cortesia != 'a') echo "style='display:none'"; ?>>
            <label  class='control-label col-md-12 col-sm-12'><?= $rot_gertipmot ?> </label>
            <div class='col-md-12 col-sm-12'> 
                <select class="form-control" <?= $pro_gertipmot ?> name="gertipmot" id="gertipmot_acre"> 
                    <?php
                    foreach ($gertipmot_list_acre as $item) {
                        $selected = '';
                        if ($item['valor'] == $motivo_acrescimo)
                            $selected = "selected = 'selected' ";
                        ?>
                        <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                    <?php } ?> 
                </select>                        
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class='col-md-12 col-sm-12'> 
            <label class='control-label col-md-12 col-sm-12' for="gerobstit"><?= $rot_gerobstit ?> </label> 
            <div class='col-md-12 col-sm-12'> 
                <textarea maxlength="50" style="height: 50px !important;" class='form-control' type="text" name="gerobstit" id="gerobstit_condesapl" placeholder="<?= $for_gerobstit ?>"  <?= $pro_gerobstit ?> <?= $val_gerobstit ?>><?= $observacao ?></textarea>
            </div>
        </div>
    </div>

    <div id="Exib_quarto_1">
        <div id="exibir_quarto_inner" class="dados_item2">
            <div class="col-md-12 col-sm-12 info_quarto" style="margin-bottom:0px; margin-top:5px">
                <div class="col-md-9 col-sm-10 escd_info" onclick="exibi_info_quartos('#Exib_quarto_1');" >
                    <a ></a>
                    <strong style="font-size:14px;"><?= $rot_gerdesaut ?></strong>
                </div>
            </div>   

        </div>  
        <div class="panel col-md-12 col-sm-12">
            <div class='branco'>
                <div class="col-md-4 col-sm-12">
                    <label class='control-label col-md-12 col-sm-12' for="gerusucod" <?= $pro_gerusucod ?>><?= $rot_gerusucod ?> </label> 
                    <div class='col-md-11 col-sm-12'> 
                        <input class='form-control' type="text" name="gerusucod" id="gerusucod" value="<?= $usuario_codigo ?>" placeholder="<?= $for_gerusucod ?>"   <?= $pro_gerusucod ?> <?= $val_gerusucod ?> />
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <label class='control-label col-md-12 col-sm-12' for="gerlogsen" <?= $pro_gerlogsen ?>><?= $rot_gerlogsen ?> </label> 
                    <div class='col-md-11 col-sm-12'> 
                        <input type='password' class='form-control' type="text" name="gerlogsen" id="gerlogsen" value="<?= $usuario_senha ?>" placeholder="<?= $for_gerlogsen ?>"  <?= $pro_gerlogsen ?> <?= $val_gerlogsen ?> />
                    </div>
                </div>
                <div class="col-md-4 col-sm-12"></div>
            </div>
        </div>
    </div>

    <div class="row col-md-12 col-sm-12 quat_botoes2">
        <div class="col-md-6 col-sm-4"></div>
        <div class="cancel-right col-md-3 col-sm-4 ui-dialog-btn-close">
            <input type="button" class="form-control btn-default close_dialog" value="<?= $rot_gerdesbot ?>" >
        </div>
        <div class="pull-left col-md-3 col-sm-4">
            <input  style="float:left" class="form-control btn-primary verifica_acesso_desconto_<?= $tipo_conta ?>" type="button" name="resmodbtn"  value="<?= $rot_gersalbot ?>">
        </div>
    </div>
</form>
