<?php

use Cake\Routing\Router;
?>
<div class="content_inner">
    <div class="formulario">
        <form id='resveimod' action="<?= Router::url('/', true) ?>reservas/resveimod" method="POST">
            <input type="hidden" name="documento_numero" value="<?= $documento_numero ?>" />
            <input type="hidden" name="quarto_item" value="<?= $quarto_item ?>" />
            <input type="hidden"  name="url_redirect_after" id="url_redirect_after" value="<?= $url_redirect_after ?>" />
            <div id='linha_veiculos'>
                <?php
                $indice_veiculo = 1;
                foreach ($veiculos as $veiculo) {
                    ?>

                    <!--Mantem os excluido para preservar o controle do adicionar mais-->
                    <?php if ($veiculo['excluido'] != '0') { ?>
                        <input type="hidden" class="resveiite" id="resveiite_<?= $indice_veiculo ?>" name="resveiite[]" value="<?= $veiculo['veiculo_item'] ?>" />
                        <input type="hidden" name="resveiexc[]"  id="resveiexc_<?= $indice_veiculo ?>" value="1" />
                        <input type="hidden" name="resveipla[]"  id="resveipla_<?= $indice_veiculo ?>" value="<?= $veiculo['placa'] ?>" />
                        <input type="hidden" name="resveimar[]"  id="resveimar_<?= $indice_veiculo ?>" value="<?= $veiculo['marca_modelo'] ?>"  />
                        <input type="hidden" name="resveicor[]"  id="resveicor_<?= $indice_veiculo ?>" value="<?= $veiculo['cor'] ?>"  />
                    <?php } else { ?>

                        <div class='form-group linha_veiculo row'>
                            <input type="hidden" class="resveiite" id="resveiite_<?= $indice_veiculo ?>" name="resveiite[]" value="<?= $veiculo['veiculo_item'] ?>" />

                            <div class="col-md-3">
                                <label class="control-label col-md-12 col-sm-12" style="margin-left: 23px;" for="resveipla_<?= $indice_veiculo ?>"><?= $rot_resveipla ?></label>
                                <div class="col-md-1">
                                    <input type="hidden" name="resveiexc[]"  id="resveiexc_<?= $indice_veiculo ?>" value="0" />
                                    <input type="checkbox" name="resveichk[]"  id="resveichk_<?= $indice_veiculo ?>" onclick="if ($(this).is(':checked')) {
                                                        $('#resveiexc_<?= $indice_veiculo ?>').val(1);
                                                    } else {
                                                        $('#resveiexc_<?= $indice_veiculo ?>').val(0);
                                                    }" />
                                </div>
                                <div class='col-md-11 col-sm-12'> 
                                    <input class='form-control' type="text" name="resveipla[]" maxlength="7" id="resveipla_<?= $indice_veiculo ?>" value="<?= $veiculo['placa'] ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label class="control-label col-md-12 col-sm-12" for="resveimar_<?= $indice_veiculo ?>"><?= $rot_resveimar ?></label>
                                <div class='col-md-11 col-sm-12'> 
                                    <input class='form-control' type="text" name="resveimar[]"  id="resveimar_<?= $indice_veiculo ?>" value="<?= $veiculo['marca_modelo'] ?>"  />
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <label class="control-label col-md-12 col-sm-12" for="resveicor_<?= $indice_veiculo ?>"><?= $rot_resveicor ?></label>
                                <div class='col-md-11 col-sm-12'>
                                    <select class="form-control" id="resveicor_<?= $indice_veiculo ?>" name="resveicor[]">
                                        <option></option>
                                        <?php
                                        foreach ($cores_lista as $item) {
                                            $selected = "";
                                            if ($item['valor'] == $veiculo['cor'])
                                                $selected = "selected='selected'";
                                            ?>
                                            <option value="<?= $item["valor"] ?>" <?= $selected ?>> <?= $item["rotulo"] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    <?php } ?>
                    <?php
                    $indice_veiculo++;
                }
                ?>
            </div>

            <div class="form-group" style="margin-top: 15px">
                <div class="col-md-12 col-sm-12">
                    <div class='pull-left col-md-2 col-sm-4'>
                        <b><a href="#" class="novo_veiculo"><?= $rot_resaddvei ?></a></b>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <div class='pull-right col-md-2 col-sm-4'>
                        <input class="form-control btn-primary submit-button" aria-form-id="resveimod" type="submit" value="<?= $rot_gersalbot ?>" >
                    </div>
                    <div class='pull-left col-md-2 col-sm-4'>
                        <input class="form-control btn-default close_dialog" type="button" value="<?= $rot_gerdesbot ?>">
                    </div>
                    <div class='pull-left col-md-2 col-sm-4'>
                        <input class="form-control btn-danger submit-button"  aria-form-id="resveimod" type="submit" value="<?= $rot_gerqtpexc ?>">
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>