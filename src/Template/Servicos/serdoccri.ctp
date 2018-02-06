<?php

use Cake\Routing\Router;
?>
<h1 class="titulo_pag">
    <?php
    echo $rot_serdcrtit;
    ?>
</h1>
<div class="content_inner">
    <div class="formulario">
        <form method="POST" name="serdoccri" id="serdoccri" action="<?= Router::url('/', true) ?>servicos/serdoccri" class="form-horizontal">
            <?php if (isset($url_redirect_after)) { ?>
                <input type="hidden" id="url_redirect_after" name="url_redirect_after" value="<?= $url_redirect_after ?>" />
            <?php } ?>
            <div class="form-group">
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="gertiptit" ><?= $rot_gertiptit ?>*</label>
                    <div class="col-md-11 col-sm-12">
                        <select required="required" class="form-control" name="serdoctip" id="gertiptit" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_gertiptit ?>"  aria-padrao-valor="<?= $padrao_valor_gertiptit ?? '' ?>"
                                onchange="gerdomsta(this.value, '1', 'select');
                                        if (this.value == 'mb') {
                                            $('#usar_padrao_horario').val(1);
                                            $('#serfindat').prop('disabled', false);
                                            $('#delimitador_data, #div_serfindat, #serdocmot_lbl, #serdocmot_cam').css('display', 'block');
                                            gerdommot(this.value);
                                        } else if (this.value == 'ms') {
                                            $('#usar_padrao_horario').val(0);
                                            $('#serdocmot_lbl, #serdocmot_cam').css('display', 'block');
                                            $('#serfindat').prop('disabled', 'disabled');
                                            $('#delimitador_data, #div_serfindat').css('display', 'none');
                                            gerdommot(this.value);
                                        } else {
                                            $('#usar_padrao_horario').val(0);
                                            $('#serfindat').prop('disabled', 'disabled');
                                            $('#delimitador_data, #div_serfindat, #serdocmot_lbl, #serdocmot_cam').css('display', 'none');

                                        }

                                "> 
                            <option  selected disabled hidden style='display: none' value='' ></option>
                            <?php
                            foreach ($gerdoctip_list as $item) {
                                if ($item['valor'] != 'bc') {
                                    $selected = "";
                                    if (isset($padrao_valor_resquacod)) {
                                        if ($padrao_valor_resquacod == $item['valor']) {
                                            $selected = 'selected = \"selected\"';
                                        }
                                    }
                                    ?>
                                    <option value="<?= $item["valor"] ?>" <?= $selected ?> ><?= $item["rotulo"] ?> </option> 
                                    <?php
                                }
                            }
                            ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_resdocsta ?>*</label>
                    <div 
                    <?php
                    if ((isset($padrao_valor_gertiptit) && ($padrao_valor_gertiptit == 'mb')) || (isset($serdoctip) && ($serdoctip == 'mb')))
                        echo "class='col-md-11 col-sm-12'";
                    else
                        echo "class='col-md-11 col-sm-12'"
                        ?>>
                        <select class="form-control" name="serdocsta" id="serdocsta"> 
                            <?php
                            foreach ($gerdomsta_list as $item) {
                                if ($serdocsta == $item["valor"]) {
                                    $selected = 'selected = \"selected\"';
                                } else {
                                    $selected = "";
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php }
                            ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label id="serdocmot_lbl" class="control-label col-md-12 col-sm-12" 
                    <?php
                    if ((isset($padrao_valor_gertiptit) && ($padrao_valor_gertiptit == 'mb' || $padrao_valor_gertiptit == 'ms')) ||
                            (isset($serdoctip) && ($serdoctip == 'mb' || $serdoctip == 'ms')))
                        echo "style='display: block'";
                    else
                        echo "style='display: none'"
                        ?>>
                        <?= $rot_germottit ?>*
                    </label>
                    <div id="serdocmot_cam" class="col-md-12 col-sm-12" 
                    <?php
                    if ((isset($padrao_valor_gertiptit) && ($padrao_valor_gertiptit == 'mb' || $padrao_valor_gertiptit == 'ms')) ||
                            (isset($serdoctip) && ($serdoctip == 'mb' || $serdoctip == 'ms')))
                        echo "style='display: block'";
                    else
                        echo "style='display: none'"
                        ?>>
                        <select class="form-control" name="serdocmot" id="serdocmot"  <?= $pro_germottit ?>  <?= $val_germottit ?> > 
                            <option value=""></option>
                            <?php
                            foreach ($gerdommot_list as $item) {
                                $selected = "";
                                if (isset($padrao_valor_serdocmot)) {
                                    if ($padrao_valor_serdocmot == $item["valor"]) {
                                        $selected = 'selected = \"selected\"';
                                    }
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php }
                            ?> 
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="resquacod" <?= $pro_resquacod ?>><?= $rot_resquacod ?>*</label>
                    <div class="col-md-11 col-sm-12">
                        <select class="no-select-all-with-search" name="serquacod" id="resquacod" <?= $pro_resquacod ?>
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_resquacod ?>"  aria-padrao-valor="<?= $padrao_valor_resquacod ?? '' ?>"> 
                            <option></option>
                            <?php
                            foreach ($quarto_por_tipo as $quarto => $quarto_tipo_curto_nome) {
                                $selected = "";

                                if (isset($serquacod)) {
                                    if ($serquacod == $quarto)
                                        $selected = 'selected = \"selected\"';
                                }else if (isset($padrao_valor_resquacod)) {
                                    if ($padrao_valor_resquacod == $quarto) {
                                        $selected = 'selected = \"selected\"';
                                    }
                                }
                                ?>
                                <option data-subtext="<?= $quarto_tipo_curto_nome ?>" value="<?= $quarto ?>" <?= $selected ?>><?= $quarto ?></option>
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12" for="serinidat"  <?= $pro_serinidat ?>><?= $rot_serinidat ?>*</label>
                    <div class='col-md-11 col-sm-11'> 
                        <input class='form-control datepicker_future data data_place data_incrementa_maior' aria-id-campo-filho='serfindat' maxlength="10" type="text" name="serinidat" id="serinidat" value="<?= $serinidat ?? "" ?>" placeholder="<?= $for_serinidat ?>"  <?= $val_serinidat ?> />
                    </div>
                    <div class="col-md-1 col-sm-1"  id="delimitador_data" style="<?php
                    if (isset($padrao_valor_gertiptit) && ($padrao_valor_gertiptit == 'mb'))
                        echo "display: block";
                    else
                        echo "display: none"
                        ?>"><span style="padding: 0 4px;"> _ </span>
                    </div>
                </div>
                <div class='col-md-3 col-sm-6' id="div_serfindat" style="<?php
                if ((isset($padrao_valor_gertiptit) && ($padrao_valor_gertiptit == 'mb')) || (isset($serdoctip) && ($serdoctip == 'mb')))
                    echo "display: block";
                else
                    echo "display: none"
                    ?>"> 
                    <label class="control-label col-md-12 col-sm-12">&nbsp;<?php //Inserir o nome                             ?></label>
                    <div class='col-md-12 col-sm-12'> 
                        <input class='form-control datepicker_future data data_place' aria-id-campo-dependente="serinidat" maxlength="10" type="text" name="serfindat" id="serfindat" value="<?= $serfindat ?? "" ?>" placeholder="<?= $for_serfindat ?>" <?= $val_serfindat ?> />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-9 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="serdoctxt"><?= $rot_gerobstit ?></label>
                    <div class='col-md-12 col-sm-12'> 
                        <input class='form-control' type="text" name="serdoctxt" id="serdoctxt" />
                    </div>       
                </div>
            </div>

            <div id='info_adicionais' style='display:none'>
                <div class='dados_serdocref form-group'>
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="serdocref"><?= $rot_serdocref ?></label>
                        <div class='col-md-11 col-sm-12'> 
                            <input class='form-control' type="text" name="serdocref" id="serdocref" disabled="disabled" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="resentdat"><?= $rot_resentdat ?></label>
                        <div class='col-md-11 col-sm-12'> 
                            <input class='form-control' type="text" name="resentdat" id="resentdat" disabled="disabled" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="resaduqtd"><?= $rot_resaduqtd ?></label>
                        <div class='col-md-11 col-sm-12'> 
                            <input class='form-control' type="text" name="resaduqtd" id="resaduqtd" disabled="disabled" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="rescriqtd"><?= $rot_rescriqtd ?></label>
                        <div class='col-md-12 col-sm-12'>  
                            <input class='form-control' type="text" name="rescriqtd" id="rescriqtd" disabled="disabled" />
                        </div>
                    </div>
                </div>

                <div class='dados_serdocref form-group' id="dados_serdocref_adicional_0">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="resaditit"><?= $rot_resaditit ?></label>
                        <div class='col-md-11 col-sm-12'> 
                            <input class='form-control' type="text" name="resaditit" id="resaditit-0" disabled="disabled" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="resadiqtd"><?= $rot_conproqtd ?></label>
                        <div class='col-md-11 col-sm-12'>
                            <input class='form-control' type="text" name="resadiqtd" id="resadiqtd-0" disabled="disabled" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="resprefat"><?= $rot_resprefat ?></label>
                        <div class='col-md-11 col-sm-12'>
                            <input class='form-control' type="text" name="resprefat" id="resprefat-0" disabled="disabled" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <?php if (isset($modo_exibicao) && $modo_exibicao == 'dialog') { ?>
                        <div class="col-md-8 col-sm-8"></div>
                        <div class='pull-left col-md-2 col-sm-4'>
                            <input class="form-control btn-default close_dialog" aria-form-id="resblocri" type="button" onclick="<?= $onclick_action ?>" value="<?= $rot_gerdesbot ?>">
                        </div>
                    <?php } else { ?>
                        <div class="col-md-10 col-sm-10"></div>
                    <?php } ?>
                    <?php
                    if (isset($modo_exibicao) && $modo_exibicao == 'dialog') {
                        //Até o momento consideramos que a pagina a ser salva no historico e apenas a respaiatu 
                        ?>
                        <div class='pull-left col-md-2 col-sm-4'>
                            <input class="form-control btn-primary submit-button" aria-form-id="serdoccri" type="submit" name="sercadbtn" onclick="gerpagsal('respaiatu', 'reservas/respaiatu', 1);" value="<?= $rot_gersalbot ?>">
                        </div>
                    <?php } else { ?>
                        <div class='pull-left col-md-2 col-sm-4'>
                            <input class="form-control btn-primary submit-button" aria-form-id="serdoccri" type="submit" name="sercadbtn" value="<?= $rot_gersalbot ?>">
                        </div>
                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="usar_padrao_horario"  id="usar_padrao_horario" value="<?php
            if (isset($padrao_valor_gertiptit) && ($padrao_valor_gertiptit == 'mb' || $padrao_valor_gertiptit == 'bc'))
                echo '1';
            else
                echo '0'
                ?>" />
            <input type="hidden" id="inicial_padrao_horario" name="inicial_padrao_horario" value="<?= $inicial_padrao_horario ?>" />
            <input type="hidden" id="final_padrao_horario" name="final_padrao_horario" value="<?= $final_padrao_horario ?>" />
        </form>
    </div>
</div>