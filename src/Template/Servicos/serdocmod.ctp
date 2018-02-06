<?php

use Cake\Routing\Router;
use App\Utility\Util;
use App\Model\Entity\Geral;

$geral = new Geral();
?>
<h1 class="titulo_pag">
    <?php
    if ($serdoctip == 'bc')
        echo $rot_serblomod;
    else
        echo $rot_serdmotit;
    ?>
</h1>

<div class="content_inner">
    <div class="formulario">
        <form method="POST" name="serdocmod" id="serdocmod" action="<?= Router::url('/', true) ?>servicos/serdocmod" class="form-horizontal">
            <input type="hidden" name="pagina_referencia" id="pagina_referencia" value="<?= $pagina_referencia ?>" />
            <input type="hidden" name="serdocnum" value="<?= $serdocnum ?>" />
            <input type="hidden" name="form_atual" value="serdocmod" />
            <input type="hidden" id="url_redirect_after" value="<?= $pagina_referencia ?>" />
            <div class="form-group">
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-11 col-sm-12" ><?= $rot_gerdoctit ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input disabled="disabled" class='form-control' type="text" name="sernumord" id="sernumord" value="<?= $serdocnum ?>" />
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-11 col-sm-12"  <?= $pro_resquacod ?>><?= $rot_resquacod ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input type="hidden" name="serquacod" value="<?= $serquacod ?>" />
                        <select disabled="disabled" class="form-control" id="serquacod"  <?= $pro_resquacod ?>>
                            <option value=""></option>
                            <?php
                            foreach ($gerquacod_list as $item) {
                                if ($serquacod == $item["valor"]) {
                                    $selected = 'selected = \"selected\"';
                                } else {
                                    $selected = "";
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["valor"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-11 col-sm-12" ><?= $rot_resquatip ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input  class="form-control" type="text" value="<?= $serqtinom ?? "" ?>" disabled="disabled" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-11 col-sm-12" ><?= $rot_gertiptit ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input type="hidden" name="serdoctip" value="<?= $serdoctip ?>" />
                        <select disabled="disabled" class="form-control" id="serdoctip" > 
                            <?php
                            foreach ($gerdoctip_list as $item) {
                                if ($serdoctip == $item["valor"]) {
                                    $selected = 'selected = \"selected\"';
                                } else {
                                    $selected = "";
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-11 col-sm-12" ><?= $rot_resdocsta ?>*</label>
                    <div class="col-md-11 col-sm-12">
                        <input type="hidden" name="anterior_documento_status_codigo" value="<?= $serdocsta ?>" />
                        <select class="form-control" name="serdocsta" id="serdocsta" <?= $pro_resdocsta ?>> 
                            <?php
                            foreach ($gerdomsta_list as $item) {
                                if ($serdocsta == $item["valor"]) {
                                    $selected = 'selected = \"selected\"';
                                } else {
                                    $selected = "";
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <?php if ((isset($serdoctip) && ($serdoctip == 'mb' || $serdoctip == 'ms' || $serdoctip == 'bc'))) { ?>
                    <div class="col-md-3 col-sm-12">
                        <label id="serdocmot_lbl"  class="control-label col-md-11 col-sm-12" ><?= $rot_germottit ?>*</label>
                        <div id="serdocmot_cam" class="col-md-11 col-sm-12">
                            <input type="hidden" name="anterior_motivo_codigo" value="<?= $serdocmot ?>" />
                            <select class="form-control" name="serdocmot" id="serdocmot"  <?= $pro_germottit ?>  <?= $val_germottit ?>  > 
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
                                <?php } ?> 
                            </select>
                        </div>
                    </div>
                <?php } ?> 
            </div>

            <div class="form-group">
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="serinidat"><?= $rot_gerdattit ?>*</label>
                    <div class='col-md-11 col-sm-11'> 
                        <input type="hidden" name="anterior_inicial_data" value="<?= $serinidat ?>" />
                        <input required="required" class='form-control datepicker data data_incrementa_maior' 
                               <?php if (Util::comparaDatas($serinidat, $geral->geragodet(1)) == -1) echo 'disabled' ?> maxlength="10" type="text" name="serinidat" id="serinidat" value="<?= $serinidat ?>" placeholder="<?= $for_gerdattit ?>"   <?= $val_serinidat ?> />
                    </div>
                    <?php if ($serdoctip == 'bc' || $serdoctip == 'mb') { ?>
                        <div class="col-md-1 col-sm-1"><span style="padding: 0 4px;"> _ </span></div>
                    <?php } ?>
                </div>

                <div class='col-md-3 col-sm-12'> 
                    <label class="control-label col-md-11 col-sm-12" for="serinidat">&nbsp;<?php //Inserir o nome                            ?></label>
                    <div class='col-md-11 col-sm-12'> 
                        <input type="hidden" name="anterior_final_data" value="<?= $serfindat ?>" />
                        <?php if ($serdoctip == 'bc' || $serdoctip == 'mb') { ?>
                            <input class='form-control datepicker data' <?php if (Util::comparaDatas($serfindat, $geral->geragodet(1)) == -1) echo 'disabled' ?>   aria-id-campo-dependente="serinidat"  maxlength="10" type="text" name="serfindat" value="<?= $serfindat ?>" id="serfindat" placeholder="<?= $for_gerdattit ?>" <?= $val_serfindat ?> />
                        <?php } // else { ?>
<!--<input class='form-control datepicker data' <?php if (Util::comparaDatas($serfindat, $geral->geragodet(1)) == -1) echo 'disabled' ?>   aria-id-campo-dependente="serinidat"  maxlength="10" type="text" name="serfindat" value="<?= $serfindat ?>" id="serfindat" placeholder="<?= $for_gerdattit ?>" data-validation="futuradata3" data-validation-format="dd/mm/yyyy" data-validation-optional="false" />-->
                        <?php // } ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-sm-12">
                    <label class="control-label col-md-11 col-sm-12" for="serdoctxt"><?= $rot_gerobstit ?></label>
                    <input type="hidden" name="anterior_texto" value="<?= $serdoctxt ?? '' ?>" />
                    <div class='col-md-11 col-sm-12'> 
                        <input class='form-control' type="text" name="serdoctxt" id="serdoctxt" value="<?= $serdoctxt ?? '' ?>" />
                    </div>
                </div>
            </div>
            <?php
            if (($serdoctip == 'ca' || $serdoctip == 'cc' || $serdoctip == 'cf') && sizeof($dados_serdocref) > 0) {

                $datas = $geral->gerdatdet($dados_serdocref[0]['inicial_data'], $dados_serdocref[0]['final_data']);
                ?> 

                <div id='info_adicionais' class='branco'>

                    <div class='dados_serdocref form-group'>
                        <div class="col-md-12 col-sm-12" style="margin-bottom: 12px; margin-top: 5px">
                            <b>Documento referenciado</b>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="col-md-4 col-sm-12">
                                <label class="col-md-12 col-sm-12">Reserva referenciada: 
                                    <b><a href="#a" class="resdocmod link_ativo"  aria-documento-numero="<?= $dados_serdocref[0]['documento_numero'] ?>" 
                                          aria-quarto-item='<?= $dados_serdocref[0]['quarto_item'] ?>' > <?= $dados_serdocref[0]['documento_numero'] ?> </a></label>  
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label class="col-md-12 col-sm-12"><?= $rot_gerdattit ?>: 
                                    <b>
                                        <?= Util::convertDataDMY($dados_serdocref[0]['inicial_data'], 'd/m/Y H:i') ?> - <?= Util::convertDataDMY($dados_serdocref[0]['final_data'], 'd/m/Y H:i') ?>
                                    </b> (<?= sizeof($datas['datas']) ?> <?php
                                    if (sizeof($datas['datas']) > 1)
                                        echo 'diárias';
                                    else
                                        echo 'diária';
                                    ?>)</label> 
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label class="col-md-12 col-sm-12">PAX: <b>
                                        <?= $dados_serdocref[0]['adulto_qtd_ajustada'] ?>/<?= $dados_serdocref[0]['crianca_qtd_ajustada'] ?> </b></label>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="col-md-12 col-sm-12">
                                <label class="col-md-12 col-sm-12"><?= $rot_resadisel ?>: <b>
                                        <?php
                                        if (isset($dados_serdocref[0]['adicional_texto']) && $dados_serdocref[0]['adicional_texto'] != "") {
                                            $texto_adicionais = explode(" | ", $dados_serdocref[0]['adicional_texto']);
                                            for ($k = 0; $k < sizeof($texto_adicionais); $k++) {
                                                if ($texto_adicionais[$k] == " " || $texto_adicionais[$k] == "") {
                                                    unset($texto_adicionais[$k]); // remove item at index 0
                                                    $texto_adicionais = array_values($texto_adicionais);
                                                }
                                            }
                                            $texto_adicionais = implode("; ", $texto_adicionais);
                                        } else
                                            $texto_adicionais = "";

                                        echo str_replace(',', '', $texto_adicionais);
                                        ?> </b></label>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="col-md-12 col-sm-12">
                                <label class="col-md-12 col-sm-12"><?= $rot_resmsgcam ?>: <b>
                                        <?= $dados_serdocref[0]['camareira_texto'] ?> </b></label>
                            </div>
                        </div>
                        
                    </div>
                </div>
            <?php } ?>
            <div class="form-group" style="margin-top: 15px">
                <div class='pull-right col-md-2 col-sm-4'>
                    <input class="form-control btn-primary submit-button" aria-form-id="serdocmod"  type="submit" name="sermodbtn" value="<?= $rot_gersalbot ?>">
                </div>
            </div>

            <input type="hidden" name="usar_padrao_horario"  id="usar_padrao_horario" value="<?php
            if ($serdoctip == 'mb' || $serdoctip == 'bc') {
                echo '1';
            } else {
                echo '0';
            }
            ?>" />
            <input type="hidden" id="inicial_padrao_horario" name="inicial_padrao_horario" value="<?= $inicial_padrao_horario ?>" />
            <input type="hidden" id="final_padrao_horario" name="final_padrao_horario" value="<?= $final_padrao_horario ?>" />
        </form>
    </div>
</div>