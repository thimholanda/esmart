<?php

use Cake\Routing\Router;
use App\Model\Entity\Geral;
use App\Utility\Util;
use App\Model\Entity\DocumentoConta;

$geral = new Geral();
$documento_conta = new DocumentoConta();
?>
<?php if ($modo_exibicao == 'tela') { ?>
    <h1 class="titulo_pag">


    </h1>
<?php } ?>
<div class="content_inner">
    <div>

        <div class="row" style="margin-bottom: 25px">
            <strong><?= $rot_conedicon ?></strong>
        </div>

        <form method="POST" name="conitemod" id="conitemod" action="<?= Router::url('/', true) ?>documentocontas/conitemod" class="form-horizontal">
            <input type="hidden" name="gerempcod" id="gerempcod" value="<?= $gerempcod ?>" />
            <input type="hidden" name="quarto_item" id="quarto_item" value="<?= $quarto_item ?>" />
            <input type="hidden" name="resdocnum_modificar" id="resdocnum_modificar" value="<?= $resdocnum_modificar ?>" />
            <input type="hidden" name="geracever_conitemod" id="geracever_conitemod" value="<?= $geracever_conitemod ?>" />
            <input type="hidden" name="geracever_coniteexc" id="geracever_coniteexc" value="<?= $geracever_coniteexc ?>" />
            <input type="hidden" name="url_redirect_after"  id="url_redirect_after" value="<?= $pagina_referencia ?>">
            <input type="hidden" name="pagina_referencia"  id="pagina_referencia" value="<?= $pagina_referencia ?>">

            <div class="form-group">
                <label class='control-label col-md-1 col-sm-3' for="geritetit" <?= $pro_geritetit ?>><?= $rot_geritetit ?>: </label> 
                <div class='col-md-3 col-sm-3 row'> 
                    <div class="col-md-5">
                        <input readonly class='form-control' type="text" name="geritetit" id="geritetit" value="<?= $geritetit ?? '' ?>" placeholder="<?= $for_geritetit ?>"  <?= $pro_geritetit ?> <?= $val_geritetit ?> />
                    </div>
                </div>
                <?php if ($coniteref != 0) { ?>
                    <label class='control-label col-md-2 col-sm-3' style="text-align:left" for="coniteref" <?= $pro_coniteref ?>><?= $rot_coniteref ?>: </label> 
                    <div class='col-md-1 col-sm-3' style="margin-left: -84px;"> 
                        <input <?= $disabled ?>  readonly class='form-control' type="text" name="coniteref" id="coniteref" value="<?= $coniteref ?? '' ?>" placeholder="<?= $for_coniteref ?>" <?= $pro_coniteref ?> <?= $val_coniteref ?> />
                    </div>
                <?php } ?>
            </div>
            <div class='form-group'>
                <label class='control-label col-md-1 col-sm-3' for="convenpon" <?= $pro_convenpon ?>><?= $rot_convenpon ?>: </label> <div class='col-md-3 col-sm-3'> 
                    <select readonly class='form-control recalcula_preco' <?= $pro_convenpon ?> name="convenpon" id="convenpon" <?= $disabled ?>> <option value=""></option>
                        <?php
                        foreach ($convenpon_list as $item) {
                            if ($convenpon == $item["valor"]) {
                                $selected = 'selected = \"selected\"';
                            } else {
                                $selected = "";
                            }
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                        <?php } ?> 
                    </select>
                </div>
                <label class='control-label col-md-1 col-sm-3 ' for="gerdattit" <?= $pro_gerdattit ?>><?= $rot_gerdatcon ?>: </label>
                <div class='col-md-2 col-sm-3'> 
                    <input readonly  class="form-control " type="text" name="gerdattit" id="gerdattit" value="<?= $gerdattit ?? '' ?>" placeholder="<?= $for_gerdattit ?>"  <?= $pro_gerdattit ?> <?= $val_gerdattit ?> /></div>

            </div>
            <div class='form-group'>


                <label class='control-label col-md-1 col-sm-3 ' for="conprocod" <?= $pro_conprocod ?>><?= $rot_conprocod ?>: </label> 

                <div class='col-md-3 col-sm-3'> 
                    <input type="hidden" id="conprocod" name="conprocod" required='required' value="<?= $conprocod ?>|<?= $concontip ?>" />
                    <input type="hidden" id="concontip" name="concontip" value="<?= $concontip ?? '' ?>"   />
                    <input type="hidden" id="servico_taxa_incide" name="servico_taxa_incide" value="<?= $servico_taxa_incide ?>"   />
                    <input type="hidden" id="has_select" value="0" />
                    <input disabled='disabled' id="conpronom" autocomplete="off" type="text"  data-produto-codigo="conprocod" class='produto_autocomplete form-control input_autocomplete' required='required' value=" <?= $produto_nome ?>" <?= $pro_conprocod ?> /> 
                </div>
                <label class='control-label col-md-1 col-sm-3 ' for="concontip" <?= $pro_concontip ?>><?= $rot_concontip ?>: </label> 
                <div class='col-md-3 col-sm-3' id="contabil_tipo_exibicao"> 
                    <?= $concontip ?? '' ?>
                </div>
            </div>

            <div class='form-group'>
                <label class='control-label col-md-1 col-sm-3' for="conproqtd" <?= $pro_conproqtd ?>><?= $rot_conproqtd ?>: </label>

                <div class='col-md-2 col-sm-3 row'>
                    <div class='col-md-6'>
                        <input readonly  onchange="$('#conpretot').val(gervalexi(gervalper(this.value) * gervalper($('#conpreuni').val())))" <?= $disabled ?>  class='form-control' type="text" name="conproqtd" id="conproqtd" value="<?= $geral->gersepatr($conproqtd ?? '') ?>" placeholder="<?= $for_conproqtd ?>" <?= $pro_conproqtd ?> <?= $val_conproqtd ?> />
                    </div>
                    <div class='col-md-6' style='padding-top: 7px; padding-left: 0px;'>
                        <span class='control-label' id="exibe-variavel-fator-nome"><?= $selected_variavel_fator_nome ?? "" ?></span>
                    </div>
                </div>
                <label class='control-label col-md-2 col-sm-3' for="conpreuni" <?= $pro_conpreuni ?>><?= $rot_conpreuni ?> <?= $geral->germoeatr() ?>: </label> <div class='col-md-1 col-sm-3'> <input readonly="readonly" class='form-control' type="text" name="conpreuni" id="conpreuni" value="<?= $geral->gersepatr($conpreuni ?? '') ?>" placeholder="<?= $for_conpreuni ?>"  <?= $pro_conpreuni ?> <?= $val_conpreuni ?> /></div>
                <label class='control-label col-md-2 col-sm-3' for="conpretot" <?= $pro_conpretot ?>><?= $rot_conpretot ?> <?= $geral->germoeatr() ?>: </label> 
                <div class='col-md-1 col-sm-3'> 
                    <input  readonly="readonly" class='form-control' type="text" name="conpretot" id="conpretot" value="<?= $geral->gersepatr($contotpre ?? '') ?>" placeholder="<?= $for_conpretot ?>" <?= $pro_conpretot ?> <?= $val_conpretot ?> />
                </div>
                <?php if ($documento_conta->condeshab($quarto_status_codigo, $produto_tipo_codigo, $conta_editavel_preco)) { ?> 
                    <div class='col-md-1 col-sm-3' style="padding-left: 0; "> 
                        <button class="<?= $ace_condesapl ?> condesapl" title="Modificar valores"  <?= $disabled ?> style="padding:4px;<?php if ($condesval > 0) echo 'background-color:#9bbef7' ?>" id="conbtndes" type="button"  
                                ><span class='ui-icon ui-icon-pencil'></span></button>
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-4">
                    <?php if ($modo_exibicao == 'tela') { ?>
                        <input style="float:left; margin-right:10px" type="button" class="form-control btn-default"  value="<?= $rot_gerdesbot ?>" onclick="$('.voltar').click()" >
                    <?php } else { ?>
                        <input style="float:left; margin-right:10px" type="button" class="form-control close_dialog btn-default" value="<?= $rot_gerdesbot ?>" >
                    <?php } ?>
                </div>
                <?php if ($documento_conta->conesthab($quarto_status_codigo, $estornavel)) { ?> 
                    <div class="col-md-2 col-sm-4">
                        <button class="form-control btn-secundary excluir_conta" style="padding:2px; margin-right: 8px; float:left" <?= $disabled_exc ?> type="button"><?= $rot_gerestbot ?></button>
                    </div>
                <?php } ?>
                <div class="col-md-2 col-sm-4">
                    <input style="float:left" class="form-control btn-primary submit-button" aria-form-id='conitemod' type="submit" name="resmodbtn" <?= $disabled ?> value="<?= $rot_gersalbot ?>" >
                </div>
            </div>
            <!--Campos para desconto, se houver -->
            <input type="hidden" name="desc_cortesia_tmp" id="desc_cortesia_tmp" value="<?= $desc_cortesia_tmp ?>" />
            <input type="hidden" name="gerdesfat_tmp" id="gerdesfat_tmp" value="<?= $gerdesfat_tmp ?>" />
            <input type="hidden" name="gerdestip_tmp" id="gerdestip_tmp" value="<?= $gerdestip_tmp ?>" />
            <input type="hidden" name="gerdesval_tmp" id="gerdesval_tmp" value="<?= $gerdesval_tmp ?>" />
            <input type="hidden" name="gertipmot_tmp" id="gertipmot_tmp" value="<?= $gertipmot_tmp ?>" />
            <input type="hidden" name="gerobstit_tmp" id="gerobstit_tmp" value="<?= $gerobstit_tmp ?>" />
            <input type="hidden" name="gerusucod_tmp" id="gerusucod_tmp" value="<?= $gerusucod_tmp ?>" />
        </form>
    </div>
    <div class="form-group"  style="margin-top: 25px">
        <?php
        if ($produto_tipo_codigo == 'PAG') {
            echo $this->element('conta/conpagexi_elem', array());
        }
        ?>
    </div>
    <div class="row" style="margin-top: 25px">
        <label class="control-label col-md-12 col-sm-12">Criado por <b><?= $usuario_criacao_nome ?> </b>em <b><?= Util::convertDataDMY($criacao_data, 'd/m/Y H:i:s') ?> </b>
        </label>
    </div>
</div>