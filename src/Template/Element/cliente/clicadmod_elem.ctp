
<?php

use App\Utility\Util;
?>
<form method="POST" name="clicadmod" id="clicadmod" action="<?= $action_form ?>" class="form-horizontal">
    <input type="hidden" name="clicadcod" id="clicadcod_mod"  value="<?= $clicadcod ?? 0 ?>" />
    <input type="hidden" id="click_ver_reservas" name="click_ver_reservas" value="<?= $click_ver_reservas ?? 0 ?>" />
    <input type="hidden" id="clickbotaopesquisar" name="clickbotaopesquisar" value="<?= $clickbotaopesquisar ?? "" ?>" />
    <input type="hidden" name="pagina_referencia" id="pagina_referencia" value="<?= $pagina_referencia ?>" />
    <input type="hidden" name="url_redirect_after" id="url_redirect_after" value="<?= $pagina_referencia ?>" />
    <div class="form-group">
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_cliprinom ?><?= Util::verificaAsterisco($pro_cliprinom) ?></label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control" type="text" name="cliprinom" id="mod_cliprinom" value="<?= $cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>"  <?= $pro_cliprinom ?> <?= $val_cliprinom ?> />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clisobnom" <?= $pro_clisobnom ?>><?= $rot_clisobnom ?><?= Util::verificaAsterisco($pro_clisobnom) ?></label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control"class="form-control" type="text" name="clisobnom" id="mod_clisobnom" value="<?= $clisobnom ?? '' ?>" placeholder="<?= $for_clisobnom ?>"   <?= $pro_clisobnom ?> <?= $val_clisobnom ?> />
            </div>
        </div>


    </div>
    <div class="form-group">

        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clicadema" <?= $pro_clicadema ?>><?= $rot_clicadema ?><?= Util::verificaAsterisco($pro_clicadema) ?></label> 
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control"type="text" name="clicadema" id="mod_clicadema" value="<?= $clicadema ?? '' ?>" placeholder="<?= $for_clicadema ?>"   <?= $pro_clicadema ?> <?= $val_clicadema ?> />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12"  <?= $pro_gercelnum ?>><?= $rot_gercelnum ?></label>
            <div class="col-lg-1 col-md-4 col-sm-4 size1">
                <select <?= $dis_clicadmod ?>  class="form-control" <?= $pro_clicelddi ?> name="clicelddi" id="mod_clicelddi"> <option value=""></option> <?php
                    foreach ($dominio_ddi_lista as $item) {
                        if (isset($clicelddi) && trim($item["valor"]) == trim($clicelddi)) {
                            ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> 
                        <?php } else {
                            ?> <option value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php
                        }
                    }
                    ?> 
                </select>
            </div>

            <div class="col-md-6 col-sm-6 size3">
                <input <?= $dis_clicadmod ?>  class="form-control telefone_criar celular" type="text" name="clicelnum" id="mod_clicelnum" value="<?= $clicelnum ?? '' ?>" placeholder="<?= $for_clicelnum ?>"  <?= $pro_clicelnum ?> <?= $val_clicelnum ?> />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12"  <?= $pro_gertelnum ?>><?= $rot_gertelnum ?> </label>
            <div class="col-lg-1 col-md-4 col-sm-4 size1">
                <select <?= $dis_clicadmod ?>  class="form-control" <?= $pro_clitelddi ?> name="clitelddi" id="mod_clitelddi"> <option value=""></option> <?php
                    foreach ($dominio_ddi_lista as $item) {
                        if (isset($clitelddi) && $item["valor"] == $clitelddi) {
                            ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php } else {
                            ?> <option value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php
                        }
                    }
                    ?> 
                </select>
            </div>

            <div class="col-md-6 col-sm-6 size3">
                <input <?= $dis_clicadmod ?> class="form-control celular_criar telefone"type="text" name="clitelnum" id="mod_clitelnum" value="<?= $clitelnum ?? '' ?>" placeholder="<?= $for_clitelnum ?>"  <?= $pro_clitelnum ?> <?= $val_clitelnum ?> />
            </div>
        </div>


    </div>

    <div class="form-group">
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clicpfcnp" <?= $pro_clicpfcnp ?>><?= $rot_clicpfcnp ?></label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control cpfcnpj" type="text" maxlength="18" name="clicpfcnp" id="mod_clicpfcnp" value="<?= $clicpfcnp ?? '' ?>" placeholder="<?= $for_clicpfcnp ?>" <?= $pro_clicpfcnp ?> <?= $val_clicpfcnp ?> />
            </div>
        </div>


        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clidoctip" <?= $pro_clidoctip ?>><?= $rot_clidoctip ?></label>
            <div class="col-md-12 col-sm-12">
                <select <?= $dis_clicadmod ?> class="form-control" name="clidoctip"  data-validation-depends-on="clidocnum"  data-validation="required" id="mod_clidoctip" <?= $pro_clidoctip ?>> <option value=""></option>
                    <?php
                    foreach ($documento_tipo_lista as $item) {
                        if (isset($clidoctip) && $item["valor"] == $clidoctip) {
                            ?>
                            <option selected='selected' value="<?= $item["valor"] ?>">
                                <?= $item["rotulo"] ?>
                            </option>
                        <?php } else { ?>
                            <option value="<?= $item["valor"] ?>">
                                <?= $item["rotulo"] ?>
                            </option>
                            <?php
                        }
                    }
                    ?> 
                </select>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clidocnum" <?= $pro_clidocnum ?>><?= $rot_clidocnum ?></label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control"type="text" name="clidocnum"  data-validation-depends-on="clidoctip" data-validation="required"  id="mod_clidocnum" value="<?= $clidocnum ?? '' ?>" placeholder="<?= $for_clidocnum ?>"   <?= $pro_clidocnum ?> <?= $val_clidocnum ?>" />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clidocorg" <?= $pro_clidocorg ?>><?= $rot_clidocorg ?> </label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control"type="text" name="clidocorg" id="mod_clidocorg" value="<?= $clidocorg ?? '' ?>" placeholder="<?= $for_clidocorg ?>"  <?= $pro_clidocorg ?> <?= $val_clidocorg ?> />       
            </div>
        </div>

    </div>



    <div class="form-group">

        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clicadpai" <?= $pro_clicadpai ?>><?= $rot_clicadpai ?> </label>
            <div class="col-md-12 col-sm-12">
                <select <?= $dis_clicadmod ?> class="selectpicker" <?= $pro_clicadpai ?> name="clicadpai" id="mod_clicadpai" onchange="gerestdet('mod_clicadest', this.value)"> 
                    <option value=""></option>
                    <option value="Brasil" <?php
                    if (isset($clicadpai) && $clicadpai == "Brasil") {
                        echo "selected='selected'";
                    }
                    ?>>Brasil </option>
                    <option data-divider="true"></option> 
                    <?php
                    foreach ($dominio_paises_lista as $item) {
                        if ($item["rotulo"] != 'Brasil') {
                            if (isset($clicadpai) && $item["rotulo"] == $clicadpai) {
                                ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else {
                                ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                            }
                        }
                    }
                    ?> 
                </select> 
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-21" for="clicadcep" <?= $pro_clicadcep ?>><?= $rot_clicadcep ?> </label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control cep" maxlength="9" type="text" name="clicadcep" id="mod_clicadcep" value="<?= $clicadcep ?? '' ?>" placeholder="<?= $for_clicadcep ?>"  <?= $pro_clicadcep ?> <?= $val_clicadcep ?> onblur="gerenddet(this.value, 'mod_clicadend', 'mod_clicadbai', 'mod_clicadcid', 'mod_clicadest', 'mod_clicadpai', 'mod_cliresnum')" />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clicadest" <?= $pro_clicadest ?>><?= $rot_clicadest ?> </label>
            <div class="col-md-12 col-sm-12">
                <select <?= $dis_clicadmod ?> class="form-control" <?= $pro_clicadest ?> name="clicadest" id="mod_clicadest"> <option value=""></option> <?php
                    foreach ($dominio_estados_lista as $item) {
                        if (isset($clicadest) && $item["valor"] == $clicadest) {
                            ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option>
                        <?php } else { ?> <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> <?php
                        }
                    }
                    ?> 
                </select>    
            </div>     
        </div>
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clicadcid" <?= $pro_clicadcid ?>><?= $rot_clicadcid ?> </label>
            <div class="col-md-12 col-sm-12">
                <input type='hidden' id='has_select' value='0' />
                <input <?= $dis_clicadmod ?> type='text' aria-estado_referencia="mod_clicadest" aria-pais_referencia="mod_clicadpai" class='cidade_autocomplete form-control' <?= $pro_clicadcid ?> name="clicadcid" id="cri_clicadcid" onblur="console.log($('#has_select').val());
                        if ($('#has_select').val() == '0')
                            this.value = '';
                                             " value="<?= $clicadcid ?? "" ?>" /> 
            </div>
        </div>
    </div>

    <div class="form-group">

        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clicadend" <?= $pro_clicadend ?>><?= $rot_clicadend ?> </label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control"type="text" name="clicadend" id="mod_clicadend" value="<?= $clicadend ?? '' ?>" placeholder="<?= $for_clicadend ?>" <?= $pro_clicadend ?> <?= $val_clicadend ?> />       
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clicadbai" <?= $pro_clicadbai ?>><?= $rot_clicadbai ?> </label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control" type="text" name="clicadbai" id="mod_clicadbai" value="<?= $clicadbai ?? '' ?>" placeholder="<?= $for_clicadbai ?>" <?= $pro_clicadbai ?> <?= $val_clicadbai ?> />   
            </div>
        </div>        
        <div class="col-md-1 col-sm-4">
            <label class="control-label col-md-12 col-sm-12" for="cliresnum" <?= $pro_cliresnum ?>><?= $rot_cliresnum ?></label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control" type="text" name="cliresnum" id="mod_cliresnum" value="<?= $cliresnum ?? '' ?>" placeholder="<?= $for_cliresnum ?>"  <?= $pro_cliresnum ?> <?= $val_cliresnum ?> />       
            </div>
        </div>
        <div class="col-md-5 col-sm-8">
            <label class="control-label col-md-12 col-sm-12" for="clirescom" <?= $pro_clirescom ?>><?= $rot_clirescom ?></label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control" type="text" name="clirescom" id="mod_clirescom" value="<?= $clirescom ?? '' ?>" placeholder="<?= $for_clirescom ?>" <?= $pro_clirescom ?> <?= $val_clirescom ?> />   
            </div>
        </div>
    </div>

    <div class="form-group">

        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clicadnac" <?= $pro_clicadnac ?>><?= $rot_clicadnac ?> </label>
            <div class="col-md-12 col-sm-12">
                <select <?= $dis_clicadmod ?> class="selectpicker" <?= $pro_clicadnac ?> name="clicadnac" id="mod_clicadnac" > 
                    <option value=""></option>
                    <option value="Brasil" <?php
                    if (isset($clicadnac) && $clicadnac == "Brasil") {
                        echo "selected='selected'";
                    }
                    ?>>Brasil </option>
                    <option data-divider="true"></option>  
                    <?php
                    foreach ($dominio_nacionalidades_lista as $item) {
                        if ($item["rotulo"] != 'Brasil') {
                            if (isset($clicadnac) && $item["rotulo"] == $clicadnac) {
                                ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else {
                                ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                            }
                        }
                    }
                    ?> 
                </select>
            </div>
        </div>


        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clicadocu" <?= $pro_clicadocu ?>><?= $rot_clicadocu ?></label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control"type="text" name="clicadocu" id="mod_clicadocu" value="<?= $clicadocu ?? '' ?>" placeholder="<?= $for_clicadocu ?>"  <?= $pro_clicadocu ?> <?= $val_clicadocu ?> />
            </div>
        </div> 


        <div class="col-md-3 col-sm-12">
            <label class="control-label col-md-12 col-sm-12" for="clinacdat" <?= $pro_clinacdat ?>><?= $rot_clinacdat ?> </label>
            <div class="col-md-12 col-sm-12">
                <input <?= $dis_clicadmod ?> class="form-control data"  atype="text" name="clinacdat" id="mod_clinacdat" value="<?= $clinacdat ?? '' ?>" placeholder="<?= $for_clinacdat ?>"  <?= $pro_clinacdat ?> <?= $val_clinacdat ?> />   
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row col-md-12 col-sm-12 quat_botoes">
            <div class="col-md-12 col-sm-12">


                <?php if ($tipo_salvar == 'ajax') { ?>
                    <div class="cancel-right col-md-2 col-sm-4">
                        <input type="button"   value="<?= $rot_gerdesbot ?>" onclick="fechaDialogById('clicadmod_dialog')">
                        <input <?= $dis_clicadmod ?>  class="form-control btn-primary" type="<?= $type_button_salvar ?>" name="clicadbtn"  value="<?= $rot_gersalbot ?>" onclick="clicadmod_sal();" >
                    </div>
                <?php } else { ?>
                    <div class="form-group">
                        <div class="col-md-2 col-sm-4">
                            <input class="form-control btn-default" style="float:left; margin-right:10px" type="button" value="<?= $rot_gerdesbot ?>" onclick="$('.voltar').click()" >
                        </div>
                        <div class="pull-right col-md-2 col-sm-4">
                            <input <?= $dis_clicadmod ?> class="form-control btn-primary submit-button" aria-form-id="clicadmod" style="float:right" type="<?= $type_button_salvar ?>" name="clicadbtn" value="<?= $rot_gersalbot ?>">
                        </div>

                        <div class=' col-md-2 col-sm-4  <?= $ace_reserva_exi ?>'>

                            <input class='form-control btn-default credito' type="button" value="<?= $rot_gercretit ?>" 
                                   onclick="
                                           callAjax('ajax/ajaxgerpagsal', {form: $('#clicadmod').serialize(), back_page: 'clientes/clicadmod/<?= $clicadcod ?>'}, function (html) {
                                               callAjax('/documentocontas/concreexi', {c_codigo:<?= $clicadcod ?? 0 ?>, cliprinom: '<?= $cliprinom ?> <?= $clisobnom ?>'}, function (html) {

                                                               $('#content').html(html);
                                                           });
                                                       });
                                   ">

                        </div>


                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if ($ver_reservas) { ?>

            <?php
            if (isset($pesquisa_reservas) && sizeof($pesquisa_reservas) > 0) {
                echo $this->element('reserva/resexiele_elem', ['pesquisa_reservas' => $pesquisa_reservas, 'id_form' => 'clicadmod',
                    'back_page' => "clientes/clicadmod/" . $clicadcod, 'has_form' => '1', 'multiple_select' => false, 'limite_confirmacao' => true, 'limited_actions' => true]);
            }
        }
        ?>
    </div>
</form>

