

<?php

use Cake\Routing\Router;
?>
<?php
if (isset($permite_busca) && $permite_busca == 1) {
    ?>
    <h1 class="titulo_pag">
        <?php
        echo $tela_nome;
        ?>
    </h1>
<?php } else  ?>
<?php

use App\Model\Entity\Geral;

$geral = new Geral();
$modo_exibicao = 'dialog';
?>
<div class="content_inner">
    <div class="formulario">
        <form method="POST" name="conresexi" id="conresexi" action="<?= Router::url('/', true) ?>documentocontas/conresexi" class="form-horizontal">
            <input type="hidden" name="opened_acordions" id="opened_acordions" value="<?= $opened_acordions ?? '' ?>" />

            <?php if (isset($permite_busca) && $permite_busca == 1) { ?>
                <div style="margin-bottom: 15px">

                    <input type="hidden" id="documento_numero_selecionado" name="documento_numero_selecionado"  value="<?= $documento_numero_selecionado ?? 0 ?>">
                    <input type="hidden" name="cliente_codigo_selecionado" id="cliente_codigo_selecionado" value="<?= $cliente_codigo_selecionado ?? 0 ?>" />
                    <input type="hidden" name="cliente_nome_selecionado" id="cliente_codigo_selecionado" value="<?= $cliente_nome_selecionado ?? 0 ?>" />
                    <input type="hidden" name="cliente_sobrenome_selecionado" id="cliente_codigo_selecionado" value="<?= $cliente_sobrenome_selecionado ?? 0 ?>" />
                    <input type="hidden" name="geracever_conitecri"  value="<?= $geracever_conitecri ?? '' ?>">
                    <input type="hidden" id="form_atual" value="conresexi" />
                    <input type="hidden" name="geracever_conitemod" value="<?= $geracever_conitemod ?? '' ?>" />
                    <input type="hidden" name="geracever_coniteexc" value="<?= $geracever_coniteexc ?? '' ?>" />
                    <input type="hidden" id="atual_data" value="<?= $geral->geragodet(1) ?>" />
                    <input type="hidden" id="export_csv" value="0" name="export_csv" />

                    <input type="hidden" id="aria-form-id-csv" value="conresexi" > 
                    <input type="hidden" name="permite_busca" id="permite_busca" value="<?= $permite_busca ?>" />

                    <div class="form-group" id="linha-1">
                        <div class='col-md-1 col-sm-1' style="width: 5%;">
                            <label  class='control-label col-md-1 col-sm-3'>
                                <input type="radio" name="radio_1" <?php if (isset($radio_checked) && $radio_checked == '1') echo 'checked'; ?> class="radio-item" id="radio_1" onclick="condescam(1)" />
                            </label>
                        </div>
                        <label class='control-label col-md-1 col-sm-3' style="text-align: left" for="resdocnum" <?= $pro_resdocnum ?>><?= $rot_resdocnum ?>: </label>
                        <div class='col-md-1 col-sm-3'> <input <?php if (isset($radio_checked) && $radio_checked != '1') echo 'disabled=\"true\"'; ?> required="true" id='resdocnum' type="text" class="form-control" name="resdocnum"  value="<?= $resdocnum ?? '' ?>" placeholder="<?= $for_resdocnum ?>"  <?= $pro_resdocnum ?> <?= $val_resdocnum ?> /></div>
                    </div>
                    <div class="form-group" id="linha-2">
                        <div class='col-md-1 col-sm-1' style="width: 5%;">
                            <label  class='control-label col-md-1 col-sm-3'>
                                <input type="radio" name="radio_2" id="radio_2" <?php if (isset($radio_checked) && $radio_checked == '2') echo 'checked'; ?> class="radio-item" onclick="condescam(2)" />
                            </label>
                        </div>
                        <label class='control-label col-md-1 col-sm-3' style="text-align: left" for="cliclicon" <?= $pro_cliclicon ?>><?= $rot_gerclitit ?>: </label> <div class='col-md-2 col-sm-3'> <input <?php if (isset($radio_checked) && $radio_checked != '2') echo 'disabled=\"true\"'; ?> id='c_nome_autocomplete_contas' type="text" class="form-control input_autocomplete" name="cliclicon"  value="<?= $cliclicon ?? '' ?>" placeholder="<?= $for_cliclicon ?>"  <?= $pro_cliclicon ?> <?= $val_cliclicon ?> /></div>
                    </div>
                    <div class="form-group" id="linha-3">
                        <div class='col-md-1 col-sm-1' style="width: 5%;">
                            <label  class='control-label col-md-1 col-sm-3'>
                                <input type="radio" name="radio_3" id="radio_3" <?php if (isset($radio_checked) && $radio_checked == '3') echo 'checked'; ?> class="radio-item" onclick="condescam(3)"  />
                            </label>
                        </div>
                        <label class='control-label col-md-1 col-sm-3' style="text-align: left" for="resquacod" <?= $pro_resquacod ?>><?= $rot_resquacod ?>: </label> <div class='col-md-1 col-sm-3'> 
                            <select <?php if (isset($radio_checked) && $radio_checked != '3') echo 'disabled=\"true\"'; ?> class="form-control" <?= $pro_resquacod ?> name="resquacod" id="resquacod" > 
                                <option value="" selected="selected"></option>
                                <?php
                                foreach ($resquacod_list as $item) {
                                    $selected = '';
                                    if (isset($resquacod) && $resquacod == $item["valor"]) {
                                        $selected = 'selected = \"selected\"';
                                    }
                                    ?>
                                    <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["valor"] ?><?php
                                        if ($item["rotulo"] != "") {
                                            echo " - " . $item["rotulo"];
                                        } else {
                                            echo "";
                                        }
                                        ?> </option> 
                                <?php } ?> 
                            </select>
                        </div>
                        <label class='control-label col-md-1 col-sm-3' style="text-align: left" for="resesttit" <?= $pro_resesttit ?>><?= $rot_resesttit ?>: </label> <div class='col-md-2 col-sm-3'> 
                            <input required="true" <?php if (isset($radio_checked) && $radio_checked != '3') echo 'disabled=\"true\"'; ?> type="text" class="form-control datepicker" name="resesttit" id="resesttit" value="<?= $resesttit ?? '' ?>" placeholder="<?= $for_resesttit ?>" <?= $pro_resesttit ?> <?= $val_resesttit ?> /></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-2">
                            <input class="form-control btn-primary submit-button" aria-form-id="conresexi" type="submit" name="btn_exi" id="btn_exi" value="<?= $rot_gerpesbot ?>" >
                        </div>
                    </div>

                </div>
            <?php } ?>
        </form>
    </div>
    <?php
    if (isset($pesquisa_contas)) {
        echo $this->element('conta/conresexi_elem', ['pesquisa_contas' => $pesquisa_contas, 'geracever_conitecri' => $geracever_conitecri ?? '', 'redirect_page' => '/documentocontas/conresexi',
            'opcao_add_conta' => true, 'back_page' => "documentocontas/conresexi", 'has_form' => '1', 'form_id' => 'conresexi', 'has_link' => 1, 'evento' => null,
            'quarto_itens' => $quarto_itens, 'modo_exibicao' => $modo_exibicao, 'tela' => 'conresexi']);
    }
    ?>


</div>