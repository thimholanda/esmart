	
<?php if ($pagina == 'resdocpes') { ?>
    <input type="hidden" id="pesquisar_reservas" name="pesquisar_reservas" value="no">
<?php } else if ($pagina == 'estfnrpes') { ?>
    <input type="hidden" id="pesquisar_fnrhs" name="pesquisar_fnrhs" value="no">
<?php } ?>
<div class="form-group">
    <div class="col-md-2 col-sm-12">
        <label class="control-label col-md-12 col-sm-12" ><?= $rot_restittit ?></label>
        <div class="col-md-11 col-sm-12">
            <input class="form-control" type="text" id="resdocnum" value="<?= $resdocnum ?? '' ?>" name="resdocnum" />
        </div>
    </div>
    <?php if ($pagina == 'resdocpes') { ?>
        <div class="col-md-2 col-sm-12">
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
    <?php } ?>
    <?php if ($pagina == 'resdocpes') { ?>
        <div class="col-md-2 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="resquacod" ><?= $rot_resquacod ?></label>
            <div class="col-md-11 col-sm-12">
                <select class="no-select-all-with-search" multiple name="resquacod[]" id="resquacod" aria-campo-padrao-multiselect="1" 
                        aria-campo-padrao-valor ="<?= $campo_padrao_valor_resquacod ?>"  aria-padrao-valor="<?= $padrao_valor_resquacod ?? '' ?>">
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
            <label class="control-label col-md-12 col-sm-12" for="resquatip" ><?= $rot_resquatip ?></label>
            <div class="col-md-11 col-sm-12">
                <select id="resquatip" name="resquatip[]" class="select-all-no-search" multiple aria-campo-padrao-multiselect="1" 
                        aria-campo-padrao-valor ="<?= $campo_padrao_valor_resquatip ?>"  aria-padrao-valor="<?= $padrao_valor_resquatip ?? '' ?>">
                            <?php
                            foreach ($resquatip_list as $item) {
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
    <?php } ?>
    <?php if ($pagina == 'resdocpes') { ?>
        <div id='lbl_dias_max' class="col-md-2 col-sm-12" <?php if ($exibir_max_exp != '1') echo "style='display:none'"; ?> <?= $pro_resexpdia ?>>
            <label for="dias_expiracao_max" class="control-label col-md-12 col-sm-12"><?= $rot_resexpdia ?> </label>
            <div class="col-md-11 col-sm-12">
                <input  class='form-control' <?= $pro_resexpdia ?> <?= $val_resexpdia ?> id='dias_expiracao_max' name='dias_expiracao_max' type='text' <?php if ($exibir_max_exp != '1') echo "style='display:none'"; ?> class='numeric' value="<?= $dias_expiracao_max ?? ''; ?>" />
            </div>
        </div>
        <div id='lbl_horas_max' class="col-md-2 col-sm-12" <?php if ($exibir_max_exp != '1') echo "style='display:none'"; ?> <?= $pro_resexphor ?>>
            <label for="horas_expiracao_max" class="control-label col-md-12 col-sm-12"><?= $rot_resexphor ?>   </label>
            <div class="col-md-11 col-sm-12">
                <input  class='form-control' min="0" max="23"  type="number" <?= $pro_resexphor ?> <?= $val_resexphor ?> id='horas_expiracao_max' name='horas_expiracao_max' type='text' <?php if ($exibir_max_exp != '1') echo "style='display:none'"; ?> clsass='numeric' value="<?= $horas_expiracao_max ?? ''; ?>" />
            </div>
        </div>
    <?php } ?>

    <div class="col-md-2 col-sm-12">
        <input id='c_codigo' name='c_codigo' type="hidden" value="<?= $c_codigo ?? '' ?>" />
        <label class="control-label col-md-12 col-sm-12" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_gerclitit ?> </label>
        <div class="col-md-10 col-sm-11">    
            <input  class="form-control input_autocomplete" id='c_nome_autocomplete' type="text" name="cliprinom" value="<?= $cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>"  <?= $pro_cliprinom ?> <?= $val_cliprinom ?> /> 
        </div>  
        <div class="col-md-1 col-sm-1">
            <button class="<?= $ace_clicadpes ?> clicadpes btn-pequisar" type="button"  aria-cliente-codigo-id='c_codigo' aria-cliente-nome-id='c_nome_autocomplete' aria-cliente-cpf-cnpj-id=''>
            </button>
        </div>  
    </div>
    <?php if ($pagina == 'estfnrpes') { ?>
        <div class="col-md-2 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="estfnrsta" ><?= $rot_estfnrsta . ":" ?></label>
            <div class="col-md-11 col-sm-12">
                <select class="form-control" name="estfnrsta" id="estfnrsta"
                        aria-campo-padrao-valor ="<?= $campo_padrao_valor_estfnrsta ?>"  aria-padrao-valor="<?= $padrao_valor_estfnrsta ?? '' ?>">
                    <option value="" ></option>
                    <?php
                    $array_values = array(1 => 'Recebida', 2 => 'Rejeitada', 3 => 'Não enviada');

                    foreach ($array_values as $key => $item) {
                        $selected = "";

                        if (isset($estfnrsta)) {
                            if ($estfnrsta == $key)
                                $selected = 'selected = \"selected\"';
                        }else if (isset($padrao_valor_estfnrsta)) {
                            if ($padrao_valor_estfnrsta == $key) {
                                $selected = 'selected = \"selected\"';
                            }
                        }
                        ?>
                        <option value="<?= $key ?>" <?= $selected ?>><?= $item ?> </option> 
                    <?php } ?> 
                </select>
            </div>
        </div>
    <?php } ?> 

	
	
		        <?php if ($pagina == 'estfnrpes') { ?>
             
            <div class="col-md-2 col-sm-6">
                <label class="control-label col-md-12 col-sm-12" for="estfnrnum" <?= $pro_estfnrnum ?>><?= $rot_estfnrnum ?>: </label>
                <div class="col-md-12 col-sm-12">    
                    <input  class="form-control" id='estfnrnum' type="text" name="estfnrnum" value="<?= $estfnrnum ?? '' ?>" placeholder="<?= $for_estfnrnum ?>"  <?= $pro_estfnrnum ?> <?= $val_estfnrnum ?> /> 
                </div> 
            </div> 
        <?php } ?> 
	<!-- ---------------------------------------- -->	
    <div class="col-md-12 form-group">
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
            <div class="col-md-1 col-sm-1"><span style="padding: 0 4px;"> _ </span></div>
        </div>

	
		
		
	<!-- ---------------------------------------- -->	
	
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
        <?php if ($pagina == 'estfnrpes') { ?>
            <div class="col-md-2 col-sm-6">
                <label class="control-label col-md-12 col-sm-12" for="gerdatenv" <?= $pro_gerdatenv ?>><?= $rot_gerdatenv ?>: </label>
                <div class="col-md-11 col-sm-11">    
                    <input  class="form-control data" id='gerdatenv' type="text" name="gerdatenv" value="<?= $gerdatenv ?? '' ?>" placeholder="<?= $for_gerdatenv ?>"   <?= $pro_gerdatenv ?> <?= $val_gerdatenv ?> /> 
                </div> 
            </div> 
            
        <?php } ?> 
    </div>

    <!--<div class="form-group">
        <div class="col-md-2 col-sm-6">
            <label class="control-label col-md-12 col-sm-12" for="resentdai" <?= $pro_resentdai ?>><?= $rot_resentdat ?></label>
            <div class='col-md-11 col-sm-11'> 
                <input maxlength="10" class='form-control datepicker data data_place data_incrementa_igual' aria-id-campo-filho='resentdaf'  type="text" name="resentdat_inicio" id="resentdai"
                       aria-campo-padrao-valor ="<?= $campo_padrao_valor_resentdai ?>"  aria-padrao-valor="<?= $padrao_valor_resentdai ?? '' ?>"
                       value="<?= $resentdat_inicio ?? $padrao_valor_resentdai ?? '' ?>" placeholder="<?= $for_resentdai ?>" 
    <?= $pro_resentdai ?> <?= $val_resentdai ?> />
            </div>
            <div class="col-md-1 col-sm-1"><span style="padding: 0 4px;"> _ </span></div>
        </div>
        <div class="col-md-2 col-sm-6">
            <label class="control-label col-md-12 col-sm-12">&nbsp;<?php //Inseir                     ?></label>
            <div class='col-md-11 col-sm-11'> 
                <input maxlength="10" class='form-control datepicker data data_place'   aria-id-campo-dependente="resentdai"  type="text" name="resentdat_final" id="resentdaf"
                       aria-campo-padrao-valor ="<?= $campo_padrao_valor_resentdaf ?>"  aria-padrao-valor="<?= $padrao_valor_resentdaf ?? '' ?>"
                       value="<?= $resentdat_final ?? $padrao_valor_resentdaf ?? '' ?>" placeholder="<?= $for_resentdaf ?>" 
    <?= $pro_resentdaf ?> <?= $val_resentdaf ?> />
            </div>
        </div>
        <div class='col-md-1 col-sm-12' >&nbsp;</div>
        <div class="col-md-2 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="ressaidai" <?= $pro_ressaidai ?>><?= $rot_ressaidat ?></label>
            <div class='col-md-11 col-sm-11'> 
                <input maxlength="10" class='form-control datepicker data data_place data_incrementa_igual' aria-id-campo-filho='ressaidaf'  type="text" name="ressaidat_inicio" id="ressaidai"
                       aria-campo-padrao-valor ="<?= $campo_padrao_valor_ressaidai ?>"  aria-padrao-valor="<?= $padrao_valor_ressaidai ?? '' ?>"
                       value="<?= $ressaidat_inicio ?? $padrao_valor_ressaidai ?? '' ?>" placeholder="<?= $for_ressaidai ?>"
    <?= $pro_ressaidai ?> <?= $val_ressaidai ?> />
            </div>
            <div class="col-md-1 col-sm-1"><span style="padding: 0 4px;"> _ </span></div>
        </div>
        <div class="col-md-2 col-sm-6">
            <label class="control-label col-md-12 col-sm-12">&nbsp;<?php //Inserir o nome                      ?></label>
            <div class='col-md-11 col-sm-11'> 
                <input maxlength="10" class='form-control datepicker data data_place'   aria-id-campo-dependente="ressaidai"  type="text" name="ressaidat_final" id="ressaidaf" 
                       value="<?= $ressaidat_final ?? $padrao_valor_ressaidaf ?? '' ?>"
                       aria-campo-padrao-valor ="<?= $campo_padrao_valor_ressaidaf ?>"  aria-padrao-valor="<?= $padrao_valor_ressaidaf ?? '' ?>"
                       placeholder="<?= $for_ressaidaf ?>"
    <?= $pro_ressaidaf ?> <?= $val_ressaidaf ?> />
            </div>
        </div>
        <div class='col-md-1 col-sm-12' >&nbsp;</div>
        <div class="col-md-2 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="resesttit" <?= $pro_resesttit ?>><?= $rot_resesttit ?></label>
            <div class="col-md-12 col-sm-12">
                <input maxlength="10" class='form-control datepicker data data_place' type="text" name="resesttit" id="resesttit" class="width-80" value="<?= $resesttit ?? '' ?>" placeholder="<?= $for_resesttit ?>"   <?= $pro_resesttit ?> <?= $val_resesttit ?> />
            </div>
        </div>
    </div>-->
    <?php if ($pagina == 'resdocpes') { ?>
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class="col-md-10 col-sm-8"></div>
                <div class="pull-left col-md-2 col-sm-4">
                    <input class="form-control btn-primary submit-button" aria-form-id="resdocpes"  type="submit" name="btn_exi" id="btn_exi" value="<?= $rot_gerpesbot ?>">&nbsp;
                </div>
            </div>
        </div>
    <?php } else if ($pagina == 'estfnrpes') { ?>
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class="col-md-10 col-sm-8"></div>
                <div class="pull-left col-md-2 col-sm-4">
                    <input class="form-control btn-primary submit-button" aria-form-id="estfnrpes"  type="submit" name="btn_exi" id="btn_exi" value="<?= $rot_gerpesbot ?>">&nbsp;
                </div>
            </div>
        </div>
        <?php
    }
