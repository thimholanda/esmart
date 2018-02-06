<?php

use App\Model\Entity\Geral;
use App\Utility\Util;

$geral = new Geral();
?>
<div id="dados_contra" class="dados_item2">
    <div class="col-md-12 col-sm-12 info_quarto escd_info"  onclick="escd_info_quartos('#dados_contra');">
        <div class="col-md-6 col-sm-12">
            <a></a>
            <strong>Dados gerais</strong>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 D_contratante">
        <div class="col-md-12 col-sm-12 list_rescli_inner">
            <div class="col-md-4 col-sm-12">    
                <label class="control-label col-md-12 col-sm-12" for="resresdat" <?= $pro_resresdat ?>><?= $rot_resresdat ?>
                    <?= Util::verificaAsterisco($pro_resresdat) ?>
                </label>
                <div class="col-md-12 col-sm-11">
                    <input size="10" maxlength="10" class="form-control datepicker data"
                           data-validation="passadodata" data-validation-format="dd/mm/yyyy" data-validation-optional="false" 
                           type="text" name="resresdat" id="resresdat" value="<?= Util::convertDataDMY($geral->geragodet(1)) ?>" placeholder="<?= $for_resresdat ?>"  <?= $pro_resresdat ?> <?= $val_resresdat ?>  autocomplete="off"/>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="resviaage" <?= $pro_resviaage ?> ><?= $rot_resviaage ?>
                    * </label>
                <div class="col-md-12 col-sm-12">
                    <select class="form-control" name="resviaage" data-validation="required" id="resviaage"> 
                        <option value=""></option>
                        <?php foreach ($dominio_agencia_viagem as $item) { ?>
                            <option value="<?= $item["valor"] ?>">
                                <?= $item["rotulo"] ?>
                            </option>
                        <?php } ?>
                    </select> 
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="docnumage" <?= $pro_docnumage ?> ><?= $rot_docnumage ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" type="text" name="docnumage" id="docnumage" placeholder="<?= $for_docnumage ?>"  <?= $pro_docnumage ?> <?= $val_docnumage ?> />
                </div>
            </div>

        </div>
        <div class="col-md-12 col-sm-12 list_rescli_inner">
            <div class="col-md-4 col-sm-12">
                <input class="form-control" type="hidden" name="c_codigo" id="c_codigo" value="" />
                <label class="control-label col-md-12 col-sm-12" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_cliprinom ?> do contratante
                    <?= Util::verificaAsterisco($pro_cliprinom) ?>
                </label> 
                <div class="col-md-12 col-sm-12">
                    <input type='hidden' id='c_has_changed' name='c_has_changed' value='0' />
                    <input class="form-control input_autocomplete dados_contratante_replicacao" type="text" name="cliprinom" id="c_nome_autocomplete"  placeholder="<?= $for_cliprinom ?>"  <?= $pro_cliprinom ?> <?= $val_cliprinom ?> onchange="$('#c_has_changed').val('1')" /> 
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clisobnom" <?= $pro_clisobnom ?>><?= $rot_clisobnom ?>
                    <?= Util::verificaAsterisco($pro_clisobnom) ?>
                </label> 
                <div class="col-md-12 col-sm-12">
                    <input class="form-control dados_contratante_replicacao" type="text" name="clisobnom" id="clisobnom"  placeholder="<?= $for_clisobnom ?>"  <?= $pro_clisobnom ?> <?= $val_clisobnom ?> onchange="$('#c_has_changed').val('1')" />  
                </div>
            </div>
           
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicpfcnp" <?= $pro_clicpfcnp ?> ><?= $rot_clicpfcnp ?>
                    <?= Util::verificaAsterisco($pro_clicpfcnp) ?></label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control cpfcnpj" maxlength="18"  type="text" name="clicpfcnp" id="clicpfcnp" data-univoco-campo-1="cpf" onblur="cliunival1('clicpfcnp', null, 'dialog')" placeholder="<?= $for_clicpfcnp ?>" <?= $pro_clicpfcnp ?> <?= $val_clicpfcnp ?> onchange="$('#c_has_changed').val('1');" />
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 list_rescli_inner">
             <div class="col-md-4 col-sm-12">

                <label class="control-label col-md-12 col-sm-12" for="clicadema" <?= $pro_clicadema ?>><?= $rot_clicadema ?>
                    <?= Util::verificaAsterisco($pro_clicadema) ?>
                </label> 
                <div class="col-md-12 col-sm-12">
                    <input class="form-control dados_contratante_replicacao" type="text" name="clicadema" id="clicadema" placeholder="<?= $for_clicadema ?>" 
                           <?= $pro_clicadema ?> <?= $val_clicadema ?> onchange="$('#c_has_changed').val('1');" />
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12"  <?= $pro_gercelnum ?>><?= $rot_gercelnum ?> </label>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <select class="form-control " <?= $pro_clicelddi ?> name="clicelddi" id="clicelddi" onchange="$('#c_has_changed').val('1')" > 
                        <option value=""></option> <?php
                        foreach ($dominio_ddi_lista as $item) {
                            $selected = '';
                            if ($item['valor'] == $ddi_padrao)
                                $selected = 'selected';
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["valor"] ?> </option> 
                            <?php
                        }
                        ?> 
                    </select>
                </div>
                <div class="col-md-8 col-sm-6">
                    <input class="form-control celular" type="text" maxlength="15" name="clicelnum" id="clicelnum" placeholder="<?= $for_clicelnum ?>" <?= $pro_clicelnum ?> <?= $val_clicelnum ?> onchange="$('#c_has_changed').val('1')"  />
                </div>
            </div>                
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12"  <?= $pro_gertelnum ?>><?= $rot_gertelnum ?> </label>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <select class="form-control" <?= $pro_clitelddi ?> name="clitelddi" id="clitelddi" onchange="$('#c_has_changed').val('1')" >
                        <option value=""></option> <?php
                        foreach ($dominio_ddi_lista as $item) {
                            $selected = '';
                            if ($item['valor'] == $ddi_padrao)
                                $selected = 'selected';
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["valor"] ?> </option> 
                            <?php
                        }
                        ?> 
                    </select>
                </div>
                <div class="col-md-8 col-sm-6">
                    <input class="form-control telefone" type="text" name="clitelnum" id="clitelnum" maxlength="15" placeholder="<?= $for_clitelnum ?>"  <?= $pro_clitelnum ?> <?= $val_clitelnum ?> />
                </div>
            </div>

            
<!--
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clidoctip" <?= $pro_clidoctip ?> ><?= $rot_clidoctip ?></label>
                <div class="col-md-11 col-sm-12">
                    <select class="form-control" name="clidoctip" id="clidoctip" onchange="$('#c_has_changed').val('1')"  data-univoco-campo-1="cliente_documento_tipo" data-univoco-campo-2="cliente_documento_numero"  data-validation-depends-on="clidocnum" data-validation="required"> <option value=""></option>
                        <?php foreach ($documento_tipo_lista as $item) { ?>
                            <option value="<?= $item["valor"] ?>">
                                <?= $item["rotulo"] ?>
                            </option>
                        <?php } ?>
                    </select> 
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clidocnum" <?= $pro_clidocnum ?> ><?= $rot_clidocnum ?></label>
                <div class="col-md-11 col-sm-12">
                    <input class="form-control" type="text" name="clidocnum" id="clidocnum"  data-univoco-campo-1="cliente_documento_tipo" data-univoco-campo-2="cliente_documento_numero"  data-validation-depends-on="clidoctip" data-validation="required"  placeholder="<?= $for_clidocnum ?>"  <?= $pro_clidocnum ?> <?= $val_clidocnum ?> onchange="$('#c_has_changed').val('1')" />
                </div>
            </div>-->

        </div>


    </div>
</div>