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

    <div>

        <form method="POST" name="conitemod" id="conitemod" action="<?= Router::url('/', true) ?>documentocontas/conitemod" class="form-horizontal">
            <input type="hidden" name="gerempcod" id="gerempcod" value="<?= $gerempcod ?>" />
            <input type="hidden" name="quarto_item" id="quarto_item" value="<?= $quarto_item ?>" />
            <input type="hidden" name="resdocnum_modificar" id="resdocnum_modificar" value="<?= $resdocnum_modificar ?>" />
            <input type="hidden" name="geracever_conitemod" id="geracever_conitemod" value="<?= $geracever_conitemod ?>" />
            <input type="hidden" name="geracever_coniteexc" id="geracever_coniteexc" value="<?= $geracever_coniteexc ?>" />
            <input type="hidden" name="url_redirect_after"  id="url_redirect_after" value="<?= $pagina_referencia ?>">
            <input type="hidden" name="pagina_referencia"  id="pagina_referencia" value="<?= $pagina_referencia ?>">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-md-12 col-sm-12" for="geritetit" <?= $pro_geritetit ?>><?= $rot_geritetit ?> </label>
                        <div class="col-md-12 col-sm-12">
                            <input readonly class='form-control' type="text" name="geritetit" id="geritetit" value="<?= $geritetit ?? '' ?>" placeholder="<?= $for_geritetit ?>"  <?= $pro_geritetit ?> <?= $val_geritetit ?> />
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class='control-label col-md-12 col-sm-12 ' for="concontip" <?= $pro_concontip ?>><?= $rot_concontip ?>: </label>
                        <div class='col-md-12 col-sm-12' id="contabil_tipo_exibicao" style="margin-top: 0;">
                            <input <?= $disabled ?>  readonly class='form-control' type="text" name="coniteref" id="coniteref" value="<?= $concontip ?? '' ?>" />
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label class='control-label col-md-12 col-sm-12 ' for="gerdattit" <?= $pro_gerdattit ?>><?= $rot_gerdatcon ?>: </label>
                        <div class='col-md-12 col-sm-12'>
                            <input readonly  class="form-control " type="text" name="gerdattit" id="gerdattit" value="<?= $gerdattit ?? '' ?>" placeholder="<?= $for_gerdattit ?>"  <?= $pro_gerdattit ?> <?= $val_gerdattit ?> />
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">

                        <label class='control-label col-md-12 col-sm-12' for="convenpon" <?= $pro_convenpon ?>><?= $rot_convenpon ?>: </label>
                        <div class='col-md-12 col-sm-12'>
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

                    </div>
                </div>

            </div>

                <?php if ($coniteref != 0) { ?>
                    <label class='control-label col-md-2 col-sm-3' style="text-align:left" for="coniteref" <?= $pro_coniteref ?>><?= $rot_coniteref ?>: </label>
                    <div class='col-md-1 col-sm-3' style="margin-left: -84px;"> 
                        <input <?= $disabled ?>  readonly class='form-control' type="text" name="coniteref" id="coniteref" value="<?= $coniteref ?? '' ?>" placeholder="<?= $for_coniteref ?>" <?= $pro_coniteref ?> <?= $val_coniteref ?> />
                    </div>
                <?php } ?>

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-md-12 col-sm-12" for="conprocod" <?= $pro_conprocod ?>><?= $rot_conprocod ?> </label>
                        <div class="col-md-12 col-sm-12">
                            <input type="hidden" id="conprocod" name="conprocod" required='required' value="<?= $conprocod ?>|<?= $concontip ?>" />
                            <input type="hidden" id="concontip" name="concontip" value="<?= $concontip ?? '' ?>"   />
                            <input type="hidden" id="servico_taxa_incide" name="servico_taxa_incide" value="<?= $servico_taxa_incide ?>"   />
                            <input type="hidden" id="has_select" value="0" />
                            <input disabled='disabled' id="conpronom" autocomplete="off" type="text"  data-produto-codigo="conprocod" class='produto_autocomplete form-control input_autocomplete' required='required' value=" <?= $produto_nome ?>" <?= $pro_conprocod ?> />
                        </div>
                    </div>

                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class='control-label col-md-12 col-sm-12' for="conproqtd" <?= $pro_conproqtd ?>><?= $rot_conproqtd ?>: </label>
                        <div class='col-md-12 col-sm-12'>
                            <input readonly  onchange="$('#conpretot').val(gervalexi(gervalper(this.value) * gervalper($('#conpreuni').val())))" <?= $disabled ?>  class='form-control' type="text" name="conproqtd" id="conproqtd" value="<?= $geral->gersepatr($conproqtd ?? '') ?>" placeholder="<?= $for_conproqtd ?>" <?= $pro_conproqtd ?> <?= $val_conproqtd ?> />
                            <div class='col-md-12' style='padding-top: 7px;'>
                                <span style="display: none" class='control-label' id="exibe-variavel-fator-nome"><?= $selected_variavel_fator_nome ?? "" ?></span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label class='control-label col-md-12 col-sm-12' for="conpreuni" <?= $pro_conpreuni ?>><?= $rot_conpreuni ?> <?= $geral->germoeatr() ?>: </label>
                        <div class='col-md-12 col-sm-12'> <input readonly="readonly" class='form-control' type="text" name="conpreuni" id="conpreuni" value="<?= $geral->gersepatr($conpreuni ?? '') ?>" placeholder="<?= $for_conpreuni ?>"  <?= $pro_conpreuni ?> <?= $val_conpreuni ?> /></div>
                    </div>
                </div>

                <div class="col-md-3">

                    <label class='control-label col-md-12 col-sm-12' for="conpretot" <?= $pro_conpretot ?>><?= $rot_conpretot ?> <?= $geral->germoeatr() ?>: </label>
                    <div class='col-md-12 col-sm-12'>
                        <input  readonly="readonly" class='form-control' type="text" name="conpretot" id="conpretot" value="<?= $geral->gersepatr($contotpre ?? '') ?>" placeholder="<?= $for_conpretot ?>" <?= $pro_conpretot ?> <?= $val_conpretot ?> />

                        <?php if ($documento_conta->condeshab($quarto_status_codigo, $produto_tipo_codigo, $conta_editavel_preco)) { ?>
                            <button class="es-form-button <?= $ace_condesapl ?> condesapl" title="Modificar valores"  <?= $disabled ?> style="padding:4px;<?php if ($condesval > 0) echo 'background-color:#9bbef7' ?>" id="conbtndes" type="button"
                            ><span class='ui-icon ui-icon-pencil'></span></button>
                        <?php } ?>

                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-12">

                            <?php if ($modo_exibicao == 'tela') { ?>
                                <button style="margin: 0;" type="button" class="form-control btn-default es-default-button"onclick="$('.voltar').click()" ><?= $rot_gerdesbot ?></button>
                            <?php } else { ?>
                                <button style="margin: 0;" type="button" class="form-control close_dialog btn-default es-default-button" ><i class="fa fa-times-circle"></i> <?= $rot_gerdesbot ?></button>
                            <?php } ?>

                            <button style="margin: 0; float: right;" class="form-control btn-primary submit-button es-default-button" aria-form-id='conitemod' type="submit" name="resmodbtn" <?= $disabled ?> ><i class="fa fa-check-circle"></i> <?= $rot_gersalbot ?></button>

                            <?php if ($documento_conta->conesthab($quarto_status_codigo, $estornavel)) { ?>
                                <button style="margin: 0; margin-right: 10px; float: right;" class="form-control btn-secundary excluir_conta es-default-button" <?= $disabled_exc ?> type="button"><i class="fa fa-minus-circle"></i> <?= $rot_gerestbot ?></button>
                            <?php } ?>

                        </div>
                    </div>
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

    <div class="row" style="margin-top: 25px">
        <div class="col-md-12">
            <div class="form-group">
                <div class="col-md-12">
                    <label class="control-label col-md-12 col-sm-12">Criado por <b><?= $usuario_criacao_nome ?> </b>em <b><?= Util::convertDataDMY($criacao_data, 'd/m/Y H:i:s') ?> </b>
                    </label>
                </div>
            </div>
        </div>
    </div>


    <?php if ($produto_tipo_codigo == 'PAG'): ?>
        <div class="form-group es-tela-pagamento-added"  style="margin-top: 55px">
        <?php echo $this->element('conta/conpagexi_elem', array()); ?>
        </div>
    <?php endif; ?>