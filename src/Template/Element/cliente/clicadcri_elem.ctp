
<?php
use App\Utility\Util;
?>
<div class="formulario">
    <form method="POST" name="clicadcri" id="clicadcri" action="<?= $action_form ?>" class="form-horizontal">
<!-- linha 1 -->
        <div class="form-group bkb">
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_cliprinom ?>
<?= Util::verificaAsterisco($pro_cliprinom) ?>
                </label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="cliprinom" id="cri_cliprinom" value="<?= $cliprinom ?? '' ?>" 
                           placeholder="<?= $for_cliprinom ?>" <?= $pro_cliprinom ?> <?= $val_cliprinom ?> />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clisobnom" <?= $pro_clisobnom ?>><?= $rot_clisobnom ?><?= Util::verificaAsterisco($pro_clisobnom) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="clisobnom" id="cri_clisobnom" value="<?= $clisobnom ?? '' ?>" placeholder="<?= $for_clisobnom ?>" <?= $pro_clisobnom ?> <?= $val_clisobnom ?> />
                </div>
            </div>
			
			 <div class="col-md-6 col-sm-12">
                <label class="control-label col-md-12 col-sm-12"></label>
                <div class="col-md-12 col-sm-12 he1">
				 
                </div>
            </div>	
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadema" <?= $pro_clicadema ?>><?= $rot_clicadema ?><?= Util::verificaAsterisco($pro_clicadema) ?></label> 
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="clicadema" id="cri_clicadema"  value="<?= $clicadema ?? '' ?>" placeholder="<?= $for_clicadema ?>"  <?= $pro_clicadema ?> <?= $val_clicadema ?> />
                </div>
            </div>
			
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12"  <?= $pro_gercelnum ?>><?= $rot_gercelnum ?><?= Util::verificaAsterisco($pro_clicelnum) ?></label>
                <div class="col-lg-1 col-md-4 col-sm-4 size1">
                    <select class="form-control" <?= $pro_clicelddi ?> name="clicelddi" id="cri_clicelddi"> <option value=""></option> <?php
                        foreach ($dominio_ddi_lista as $item) {
                            ?>
                            <option value="<?= $item["valor"] ?>" <?php if ($item['valor'] == $ddi_padrao) echo "selected='selected'" ?>><?= $item["valor"] ?> </option> <?php
                        }
                        ?> 
                    </select>
                </div>
               

                <div class="col-md-6 col-sm-6 size3">
                    <input class="form-control celular telefone_criar" autocomplete="off" type="text" name="clicelnum" id="cri_clicelnum" value="<?= $clicelnum ?? '' ?>" placeholder="<?= $for_clicelnum ?>" <?= $pro_clicelnum ?> <?= $val_clicelnum ?> />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12"  <?= $pro_gertelnum ?>><?= $rot_gertelnum ?><?= Util::verificaAsterisco($pro_clitelnum) ?></label>
                <div class="col-lg-1 col-md-4 col-sm-4 size1">
                    <select class="form-control" <?= $pro_clitelddi ?> name="clitelddi" id="cri_clitelddi"> <option value=""></option> <?php
                        foreach ($dominio_ddi_lista as $item) {
                            ?> <option value="<?= $item["valor"] ?>" <?php if ($item['valor'] == $ddi_padrao) echo "selected='selected'" ?>><?= $item["valor"] ?> </option> <?php
                        }
                        ?> 
                    </select>
                </div>
              
                <div class="col-md-6 col-sm-6 size3">
                    <input class="form-control celular_criar telefone" autocomplete="off" type="text" name="clitelnum" id="cri_clitelnum" value="<?= $clitelnum ?? '' ?>" placeholder="<?= $for_clitelnum ?>"   <?= $pro_clitelnum ?> <?= $val_clitelnum ?> />
                </div>
            </div>
           
        </div>
<!--linha 2 -->
 
		
		<!--linha 3 -->
		   <div class="form-group bkb">
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicpfcnp" <?= $pro_clicpfcnp ?>><?= $rot_clicpfcnp ?><?= Util::verificaAsterisco($pro_clicpfcnp) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control  cpfcnpj"  maxlength="18"  autocomplete="off" type="text" name="clicpfcnp" id="cri_clicpfcnp" data-univoco-campo-1="cpf" onblur="cliunival1('cri_clicpfcnp', null)" value="<?= $clicpfcnp ?? '' ?>" placeholder="<?= $for_clicpfcnp ?>" <?= $pro_clicpfcnp ?> <?= $val_clicpfcnp ?> />
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <label class="control-label label-no-top col-md-12 col-sm-12" for="clidoctip" <?= $pro_clidoctip ?>><?= $rot_clidoctip ?><?= Util::verificaAsterisco($pro_clidoctip) ?></label>
                <div class="col-md-12 col-sm-12">
                    <select class="form-control" name="clidoctip" id="cri_clidoctip" <?= $pro_clidoctip ?> data-univoco-campo-1="cliente_documento_tipo" data-univoco-campo-2="cliente_documento_numero"  onblur="cliunival1('cri_clidoctip', 'cri_clidocnum')"  data-validation-depends-on="clidocnum" data-validation="required" > <option value=""></option>
                        <?php foreach ($documento_tipo_lista as $item) { ?>
                            <option value="<?= $item["valor"] ?>">
                                <?= $item["rotulo"] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
			
            <div class="col-md-3 col-sm-12">
                <label class="control-label label-no-top col-md-12 col-sm-12" for="clidocnum" <?= $pro_clidocnum ?>><?= $rot_clidocnum ?><?= Util::verificaAsterisco($pro_clidocnum) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="clidocnum" data-univoco-campo-1="cliente_documento_tipo" data-univoco-campo-2="cliente_documento_numero"  onblur="cliunival1('cri_clidoctip', 'cri_clidocnum')" data-validation-depends-on="clidoctip" data-validation="required"  id="cri_clidocnum" value="<?= $clidocnum ?? '' ?>" placeholder="<?= $for_clidocnum ?>"   <?= $pro_clidocnum ?> <?= $val_clidocnum ?> />
					
                </div>
            </div>
			
			  <div class="col-md-3 col-sm-12">
                <label class="control-label label-no-top col-md-12 col-sm-12" for="clidocorg" <?= $pro_clidocorg ?>><?= $rot_clidocorg ?><?= Util::verificaAsterisco($pro_clidocorg) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="clidocorg" id="cri_clidocorg" value="<?= $clidocorg ?? '' ?>" placeholder="<?= $for_clidocorg ?>"  <?= $pro_clidocorg ?> <?= $val_clidocorg ?> />       
                </div>
            </div>
 
 
  <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadpai" <?= $pro_clicadpai ?>><?= $rot_clicadpai ?><?= Util::verificaAsterisco($pro_clicadpai) ?></label>
                <div class="col-md-12 col-sm-12">
                    <select class="selectpicker" <?= $pro_clicadpai ?> name="clicadpai" id="cri_clicadpai" onchange="gerestdet('cri_clicadest', this.value)"> 
                        <option value=""></option> 
                        <option value="Brasil" selected="selected">Brasil </option>
                        <option data-divider="true"></option> 
                        <?php
                        foreach ($dominio_paises_lista as $item) {
                            if ($item["rotulo"] != 'Brasil') {
                                ?>
                                <option value="<?= $item["rotulo"] ?>" <?php if ($item['rotulo'] == $pais_nome_padrao) echo "selected='selected'" ?>><?= $item["rotulo"] ?> </option> <?php
                            }
                        }
                        ?> 
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadcep" <?= $pro_clicadcep ?>><?= $rot_clicadcep ?><?= Util::verificaAsterisco($pro_clicadcep) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control cep" autocomplete="off" type="text" maxlength="9" name="clicadcep" id="cri_clicadcep" value="<?= $clicadcep ?? '' ?>" placeholder="<?= $for_clicadcep ?>"   <?= $pro_clicadcep ?> <?= $val_clicadcep ?> onblur="gerenddet(this.value, 'cri_clicadend', 'cri_clicadbai', 'cri_clicadcid', 'cri_clicadest', 'cri_clicadpai', 'cri_cliresnum')" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadest" <?= $pro_clicadest ?>><?= $rot_clicadest ?><?= Util::verificaAsterisco($pro_clicadest) ?></label>
                <div class="col-md-12 col-sm-12">
                    <select class="form-control" <?= $pro_clicadest ?> name="clicadest" id="cri_clicadest"> <option value=""></option> <?php
                        foreach ($dominio_estados_lista as $item) {
                            ?> <option value="<?= $item["valor"] ?>" <?php if ($item['valor'] == $estado_codigo_padrao) echo "selected='selected'" ?>><?= $item["rotulo"] ?> </option> <?php
                        }
                        ?> 
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadcid" <?= $pro_clicadcid ?>><?= $rot_clicadcid ?><?= Util::verificaAsterisco($pro_clicadcid) ?></label>
                <input type='hidden' id='has_select' value='0' />
                <div class="col-md-12 col-sm-12">
                    <input autocomplete="off" type="text" aria-estado_referencia="cri_clicadest" aria-pais_referencia="cri_clicadpai" class='cidade_autocomplete form-control' <?= $pro_clicadcid ?> name="clicadcid" id="cri_clicadcid" onblur="console.log($('#has_select').val());
                            if ($('#has_select').val() == '0')
                                this.value = '';
                           " /> 
                </div>
            </div>
			
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadbai" <?= $pro_clicadbai ?>><?= $rot_clicadbai ?><?= Util::verificaAsterisco($pro_clicadbai) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="clicadbai" id="cri_clicadbai" value="<?= $clicadbai ?? '' ?>" placeholder="<?= $for_clicadbai ?>"   <?= $pro_clicadbai ?> <?= $val_clicadbai ?> />   
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadend" <?= $pro_clicadend ?>><?= $rot_clicadend ?><?= Util::verificaAsterisco($pro_clicadend) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="clicadend" id="cri_clicadend" value="<?= $clicadend ?? '' ?>" placeholder="<?= $for_clicadend ?>"  <?= $pro_clicadend ?> <?= $val_clicadend ?> />       
                </div>
            </div>
            <div class="col-md-1 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="cliresnum" <?= $pro_cliresnum ?>><?= $rot_cliresnum ?><?= Util::verificaAsterisco($pro_cliresnum) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="cliresnum" id="cri_cliresnum" value="<?= $cliresnum ?? '' ?>" placeholder="<?= $for_cliresnum ?>"  <?= $pro_cliresnum ?> <?= $val_cliresnum ?> />       
                </div>
            </div>  
            <div class="col-md-5 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clirescom" <?= $pro_clirescom ?>><?= $rot_clirescom ?><?= Util::verificaAsterisco($pro_clirescom) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="clirescom" id="cri_clirescom" value="<?= $clirescom ?? '' ?>" placeholder="<?= $for_clirescom ?>" <?= $pro_clirescom ?> <?= $val_clirescom ?> />   
                </div>
            </div>
        </div>

		<!--linha 4 -->
	
		
	
		

    

     

        <div class="form-group bkb">      
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadnac" <?= $pro_clicadnac ?>><?= $rot_clicadnac ?><?= Util::verificaAsterisco($pro_clicadnac) ?></label>
                <div class="col-md-12 col-sm-12">
                    <select class="selectpicker" <?= $pro_clicadnac ?> name="clicadnac" id="cri_clicadnac"> 
                        <option value=""></option>
                        <option value="Brasil" selected="selected">Brasil </option>
                        <option data-divider="true"></option>  
                        <?php
                        foreach ($dominio_nacionalidades_lista as $item) {
                            if ($item["rotulo"] != 'Brasil') {
                                ?>
                                <option value="<?= $item["rotulo"] ?>" <?php if ($item['rotulo'] == $pais_nome_padrao) echo "selected='selected'" ?>><?= $item["rotulo"] ?> </option> <?php
                            }
                        }
                        ?> 
                    </select>
                </div>
            </div>
           
		    <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadocu" <?= $pro_clicadocu ?>><?= $rot_clicadocu ?><?= Util::verificaAsterisco($pro_clicadocu) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="clicadocu" id="cri_clicadocu" value="<?= $clicadocu ?? '' ?>" placeholder="<?= $for_clicadocu ?>"  <?= $pro_clicadocu ?> <?= $val_clicadocu ?> />
                </div>
            </div>
            
            
            <div class="col-md-3 col-sm-12">
                <label class="control-label label-no-top col-md-12 col-sm-12 data" for="clinacdat" <?= $pro_clinacdat ?>><?= $rot_clinacdat ?><?= Util::verificaAsterisco($pro_clinacdat) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control data" autocomplete="off" type="text" name="clinacdat" id="cri_clinacdat" value="<?= $clinacdat ?? '' ?>" placeholder="<?= $for_clinacdat ?>"  <?= $pro_clinacdat ?> <?= $val_clinacdat ?> />   
                </div>
            </div>
        </div>

        

        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class="col-md-8"></div>
                <div class="cancel-right col-md-2 col-sm-4">
                    <input class="form-control btn-default" style="float:right" type="" name="" value="Voltar" onclick="">
                </div>
              
                <div class="pull-left col-md-2 col-sm-4">
                    <?php if ($tipo_salvar == 'ajax') { ?>
                        <input class="form-control btn-primary" style="float:right" type="<?= $type_button_salvar ?>" name="clicadbtn" value="<?= $rot_gersalbot ?>" onclick="clicadcri_sal()">
                    <?php } else { ?>
                        <input class="form-control btn-primary submit-button" aria-form-id="clicadcri" style="float:right" type="<?= $type_button_salvar ?>" name="clicadbtn" value="<?= $rot_gersalbot ?>">
<?php } ?>
                </div>
            </div>
        </div>

        <input type="hidden" id="empresa_grupo_codigo_js" value="<?= $empresa_grupo_codigo ?>" />
    </form>
</div>



