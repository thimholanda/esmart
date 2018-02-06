<?php

use Cake\Routing\Router;
?>
<h1 class="titulo_pag">
    <?php
    echo $rot_serbcrtit;
    ?>
</h1>

<div class="content_inner">
    <div class="formulario">
        <form method="POST" name="resblocri" id="resblocri" action="<?= Router::url('/', true) ?>reservas/resblocri" class="form-horizontal">
            <?php if (isset($url_redirect_after)) { ?>
                <input type="hidden" id="url_redirect_after" name="url_redirect_after" value="<?= $url_redirect_after ?>" />
            <?php } ?>
            <div class="form-group">
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="resquacod"><?= $rot_resquacod ?>*</label>
                    <div class="col-md-12 col-sm-12">
                        <select class="no-select-all-with-search" name="serquacod" id="resquacod" <?= $pro_resquacod ?>  <?= $val_resquacod ?> data-validation="required"
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
                    <label class="control-label col-md-12 col-sm-12" for="serinidat"><?= $rot_gerdattit ?>*</label>
                    <div class='col-md-12 col-sm-11'> 
                        <input class='form-control data datepicker_future data_place data_incrementa_maior' aria-id-campo-filho='serfindat'  maxlength="10" type="text" name="serinidat" id="serinidat" value="<?= $serinidat ?? "" ?>" placeholder="<?= $for_serinidat ?>"  <?= $pro_serinidat ?>   <?= $val_serinidat ?>/>
                    </div>
                    <div class="col-md-1 col-sm-1"><span style="padding: 0 4px;"> _ </span></div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12">&nbsp;<?php //Inserir o nome           ?></label>
                    <div class='col-md-12 col-sm-12' id="div_serfindat"> 
                        <input class='form-control data datepicker_future data_place'  aria-id-campo-dependente="serinidat"  maxlength="10" type="text" name="serfindat" id="serfindat" value="<?= $serfindat ?? "" ?>" placeholder="<?= $for_serfindat ?>"  <?= $val_serfindat ?> />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-sm-12">
                    <input type="hidden" value="bc" name="serdoctip" id="serdoctip" />
                    <label class="control-label col-md-12 col-sm-12" for="resdocsta" ><?= $rot_resdocsta ?>*</label>
                    <div class="col-md-12 col-sm-12">
                        <select class="form-control" name="serdocsta" id="resdocsta" <?= $pro_resdocsta ?> aria-campo-padrao-valor ="<?= $campo_padrao_valor_resdocsta ?>"  aria-padrao-valor="<?= $padrao_valor_resdocsta ?? '' ?>"> 
                            <option  selected disabled hidden style='display: none' value='' ></option>
                            <?php
                            foreach ($gerdomsta_list as $item) {
                                $selected = "";

                                if (isset($serdocsta)) {
                                    if ($serdocsta == $item['valor'])
                                        $selected = 'selected = \"selected\"';
                                }else if (isset($padrao_valor_resdocsta)) {
                                    if ($padrao_valor_resdocsta == $item['valor']) {
                                        $selected = 'selected = \"selected\"';
                                    }
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>"  <?= $selected ?> ><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label id="serdocmot_lbl" class="control-label col-md-12 col-sm-12" ><?= $rot_germottit ?>*</label>
                    <div id="serdocmot_cam" class="col-md-12 col-sm-12">
                        <select class="form-control" name="serdocmot" id="serdocmot" <?= $pro_germottit ?>  <?= $val_germottit ?> > 
                            <option  selected disabled hidden style='display: none' value='' ></option>
                            <?php foreach ($resblomot_list as $item) {
                                ?>
                                <option value="<?= $item["valor"] ?>"  <?= $selected ?> ><?= $item["rotulo"] ?> </option> 
                            <?php }
                            ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="serdoctxt"><?= $rot_gerobstit ?></label>
                    <div class='col-md-12 col-sm-12'> 
                        <input class='form-control' type="text" name="serdoctxt" id="serdoctxt" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12">

                    <?php if (isset($modo_exibicao) && $modo_exibicao == 'dialog') { ?>
                        <div class="col-md-8 col-sm-8"></div>
                        <div class='pull-left col-md-2 col-sm-4'>
                            <input class="form-control btn-default close_dialog" aria-form-id="resblocri" type="button" onclick="<?= $onclick_action ?>" value="<?= $rot_gercanbot ?>">
                        </div>
                    <?php } else { ?>
                        <div class="col-md-10 col-sm-10"></div>
                    <?php } ?>
                    <?php
                    if (isset($modo_exibicao) && $modo_exibicao == 'dialog') {
//Até o momento consideramos que a pagina a ser salva no historico e apenas a respaiatu 
                        ?>
                        <div class='pull-left col-md-2 col-sm-4'>
                            <input class="form-control btn-primary submit-button" aria-form-id="resblocri" type="submit"  onclick="gerpagsal('respaiatu', 'reservas/respaiatu', 1);"  value="<?= $rot_gersalbot ?>">
                        </div>
                    <?php } else { ?>
                        <div class='pull-left col-md-2 col-sm-4'>
                            <input class="form-control btn-primary submit-button" aria-form-id="resblocri" type="submit" value="<?= $rot_gersalbot ?>">
                        </div>
                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="usar_padrao_horario"  id="usar_padrao_horario" value="1" />
            <input type="hidden" id="inicial_padrao_horario" name="inicial_padrao_horario" value="<?= $inicial_padrao_horario ?>" />
            <input type="hidden" id="final_padrao_horario" name="final_padrao_horario" value="<?= $final_padrao_horario ?>" />
        </form>
    </div>
</div>