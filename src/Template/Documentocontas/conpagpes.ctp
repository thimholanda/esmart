<?php

use Cake\Routing\Router;
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<?php

use App\Model\Entity\Geral;

$geral = new Geral();
?>
<div class="content_inner">
    <div class="formulario">
        <div style="margin-bottom: 15px">
            <form method="POST" name="conpagpes" id="conpagpes" action="<?= Router::url('/', true) ?>documentocontas/conpagpes" class="form-horizontal" >
                <input type="hidden" id="pesquisar_pagamentos" name="pesquisar_pagamentos" value="no">
                <input type="hidden" id="form_atual" value="conpagpes" />
                <input type="hidden" id="form_force_submit" value="0" />
                <input type="hidden" id="pagina" value="1" name="pagina" />
                <input type="hidden" id="gerdiacon_executada" value="0" />
                <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
                <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
                <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />
                <input type="hidden" id="export_csv" value="0" name="export_csv" />
                <input type="hidden" id="aria-form-id-csv" value="conpagpes" >
                <input type="hidden" id="title_dialog_validator" value="" >
                <input type="hidden" id="form_validator_function" name="form_validator_function" value="
                       if ($('#resdocnum').val() == '' && ($('#c_codigo').val() == '' || $('#c_codigo').val() == '0')) {
                       if (gerdiacon($('#conpagdai').val(), $('#conpagdaf').val(),<?= $pagamento_pesquisa_max ?>, 2, 1) == 1)
                       return true;
                       else {
                       dialog = $('#exibe-germencri').dialog({
                       title: $('#title_dialog_validator').val(),
                       dialogClass: 'no_close_dialog',
                       autoOpen: false,
                       height: 200,
                       width: 530,
                       modal: true,
                       buttons: {
                       '<?= $rot_gerconrev ?>': function () {
                       dialog.dialog('close');
                       $('.click_disabled').removeClass('click_disabled');
                       $('#form_force_submit').val('1');
                       $('#conpagpes .submit-button').click();
                       },
                       '<?= $rot_gerrevdat ?>': function () {
                       dialog.dialog('close');
                       $('#gerdiacon_executada').val('0');
                       }
                       }
                       });
                       dialog.dialog('open');
                       return false;
                       }
                       }else
                       return true;
                       ">
                <div class="form-group">
                    <div class="col-md-2">
                        <span><b><?= $rot_gergertit ?></b></span>
                    </div>
                </div>
                <div class='form-group'>
                    <label class='control-label col-md-1 col-sm-3' for="resdocnum" <?= $pro_resdocnum ?>><?= $rot_resdocnum ?>: </label>
                    <div class='col-md-1 col-sm-3'> <input class='form-control' type="text" name="resdocnum" id="resdocnum" value="<?= $resdocnum ?? '' ?>" placeholder="<?= $for_resdocnum ?>" <?= $pro_resdocnum ?> <?= $val_resdocnum ?> /></div>
                </div>

                <div class="form-group">
                    <div class="col-md-2">
                        <span><b><?= $rot_conpagtit ?></b></span>
                    </div>
                </div>

                <div class='form-group'>
                    <label class='control-label col-md-1 col-sm-3' for="conpagdai" <?= $pro_conpagdai ?>><?= $rot_conpagdat ?>: </label> 
                    <div class='col-md-2 col-sm-3'  style="padding-left:0; padding-right: 0;"> 
                        <input class='form-control datepicker data data_incrementa_igual' aria-id-campo-filho='conpagdaf'  type="text" name="gerdattit_inicio" id="conpagdai"
                               value="<?= $gerdattit_inicio ?? $padrao_valor_conpagdai ?? '' ?>"
                               placeholder="<?= $for_conpagdai ?>"
                               aria-campo-padrao-valor ="<?= $campo_padrao_valor_conpagdai ?>"  aria-padrao-valor="<?= $padrao_valor_conpagdai ?? '' ?>"
                               <?= $pro_conpagdai ?> 
                               <?= $val_conpagdai ?> />

                    </div>
                    <div class='col-md-1 col-sm-3' style="padding:0; margin-left: -70px; margin-top: 4px;margin-right: -40px;"> 
                        <span>-</span>
                    </div>
                    <div class='col-md-2 col-sm-3' style="padding-left:0; margin-left: -58px;"> 
                        <input class='form-control datepicker data' type="text" name="gerdattit_final" id="conpagdaf"  aria-id-campo-dependente="conpagdai" 
                               value="<?= $gerdattit_final ?? $padrao_valor_conpagdaf ?? '' ?>"
                               placeholder="<?= $for_conpagdaf ?>"
                               aria-campo-padrao-valor ="<?= $campo_padrao_valor_conpagdaf ?>"  aria-padrao-valor="<?= $padrao_valor_conpagdaf ?? '' ?>"
                               <?= $pro_conpagdaf ?>
                               <?= $val_conpagdaf ?> />
                    </div>
                    <label class='control-label col-md-1 col-sm-3' ><?= $rot_respagval ?></label>
                    <div class='col-md-1 col-sm-3'>
                        <input class='form-control moeda' type="text" placeholder="<?= $for_respagval ?>" name="forma_valor" id="forma_valor" value="<?= $forma_valor ?? '' ?>">
                    </div>

                    <label class='control-label col-md-2 col-sm-3' for="respagfor" <?= $pro_respagfor ?>><?= $rot_respagfor ?>: </label>
                    <div class='col-md-3 col-sm-3'> 
                        <select class = 'form-control' name = 'respagfor' id = 'respagfor'>
                            <option value = "" selected = "selected"></option>

                            <?php foreach ($var_respagfor as $item_respafor) { ?>

                                <option value="<?= $item_respafor['pagamento_forma_codigo'] ?>"
                                <?php
                                if ($item_respafor['pagamento_forma_codigo'] == ($respagfor ?? '')) {
                                    echo ' selected ';
                                }
                                ?>>
                                            <?= $item_respafor["pagamento_forma_nome"] ?>
                                </option> 

                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class='control-label col-md-1 col-sm-3' ><?= $rot_rescarnum ?></label>
                    <div class='col-md-2 col-sm-3'>
                        <input class='form-control' type="text" size="12" name="forma_cartao_numero" id="forma_cartao_numero" value='<?= $forma_cartao_numero ?? '' ?>'>
                    </div>
                </div>
                <div class="form-group">
                    <label class='control-label col-md-1 col-sm-3' ><?= $rot_respagbnc ?></label>
                    <div class='col-md-1 col-sm-1'>
                        <input class='form-control' type="text" size="3" name="forma_banco" id="forma_banco" value='<?= $forma_banco ?? '' ?>'>
                    </div>
                    <label class='control-label col-md-1 col-sm-3' ><?= $rot_respagagc ?></label>
                    <div class='col-md-1 col-sm-1'>
                        <input class='form-control' type="text" size="5" name="forma_agencia" id="forma_agencia" value='<?= $forma_agencia ?? '' ?>' >
                    </div>

                    <label class='control-label col-md-2 col-sm-3' ><?= $rot_respagcco ?></label> 
                    <div class='col-md-2 col-sm-1'>
                        <input class='form-control' type="text" size="12" name="forma_conta_corrente" id="forma_conta_corrente"   value='<?= $forma_conta_corrente ?? '' ?>'>
                    </div>
                </div>
                <div class='form-group'>
                    <label class='control-label col-md-3 col-sm-3' for="conresdep" <?= $pro_conresdep ?>><?= $rot_conresdep ?>: </label> <div class='col-md-2 col-sm-3'> <input class='form-control' type="text" name="respagref" id="respagref" value="<?= $respagref ?? '' ?>" placeholder="<?= $for_respagref ?>"  <?= $pro_respagref ?> <?= $val_respagref ?> /></div>
                </div>


                <div class="form-group" id="linha-1">
                    <div class='col-md-12 col-sm-12'>
                        <b><?= $rot_gerclitit ?> <i style='color:red'>Avaliar limitar a pagante</i></b>
                    </div>
                    <div class="row form-group">
                        <input id='c_codigo' name='c_codigo' type="hidden" value="<?= $c_codigo ?? '' ?>" />
                        <label class="control-label col-md-1 col-sm-3" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_cliprinom ?>: </label>
                        <div class="col-md-2 col-sm-9">    
                            <input  class="form-control input_autocomplete" id='c_nome_autocomplete' type="text" name="cliprinom" value="<?= $cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>" <?= $pro_cliprinom ?> <?= $val_cliprinom ?> /> 
                        </div>  
                        <div class="col-md-1">
                            <button class="<?= $ace_clicadpes ?> clicadpes" type="button"  aria-cliente-codigo-id='c_codigo' aria-cliente-nome-id='c_nome_autocomplete' aria-cliente-cpf-cnpj-id=''>
                                <span class='ui-icon ui-icon-search'></span>
                            </button>
                        </div>
                    </div>


                    <div class="pull-left">
                        <input class="form-control btn-primary  submit-button" aria-form-id="conpagpes" type="submit" name="btn_exi_pag" id="btn_exi_pag" value="<?= $rot_gerexebot ?>" >
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (isset($pesquisa_pagamentos) && sizeof($pesquisa_pagamentos) > 0 && $pesquisar_pagamentos == 'yes') {
        echo $this->element('conta/conpagele_elem', ['pesquisa_pagamentos' => $pesquisa_pagamentos, 'id_form' => 'conpagpes',
            'back_page' => "documentoconta/conpagpes/", 'has_form' => '1']);
    }
    ?>
</div>