
<?php

use App\Model\Entity\Geral;
use Cake\Routing\Router;
use Cake\Network\Session;
use App\Utility\Util;

$router = Router::url('/', true);
$action = $router . 'documentocontas/conpagcri';
$geral = new Geral();
$session = new Session();
?>
<form method="POST" name="conpagcri" id="conpagcri" action="<?= $action ?>" class="form-horizontal" novalidate>
    <input type="hidden" name="documento_numero" value="<?= $documento_numero ?>">       
    <input type="hidden" id="clicadcod" name="clicadcod"  value="<?= $pagante_codigo ?>">        
    <input type="hidden" id="cliprinom" value="">
    <input type="hidden" id="clisobnom" value="">
    <input type="hidden" id="empresa_grupo_codigo"  value="<?= $session->read("empresa_grupo_codigo_ativo") ?>">
    <input type="hidden" name="quarto_item_atual" id="quarto_item_atual"  value="<?= $quarto_item ?>">
    <input type="hidden" name="url_redirect_after"  id="url_redirect_after"  value="<?= $redirect_page ?>">
    <input type="hidden" id="total_pagamento_formas" name="total_pagamento_formas"  value="1">
    <input type="hidden" name="total_quartos_itens" value="<?= $total_quartos_itens ?>" />
    <input type="hidden" id="pagamento_total" name="pagamento_total"  value="<?= $pagamento_total ?>">
    <?php
    $total_a_pagar = 0;
    $total_pago = 0;
    $total_pre_autorizado = 0;

    //Exibe especifico para cada quarto item
    if (!$pagamento_total) {
        foreach ($partidas_por_quarto_item as $quarto_item => $partida_dados) {
            $a_pagar = 0;
            $pago = $partida_dados['pago'];
            $pre_autorizado = $partida_dados['pre_autorizado'];

            foreach ($partida_dados['partidas'] as $partida)
                $a_pagar += $partida['partida_valor'];

            //Soma no total a pagar a partida virtual
            $a_pagar += $partida_dados['final_partida']['partida_valor'];
            $total_a_pagar += $a_pagar;
            $total_pago += $pago;
            $total_pre_autorizado += $pre_autorizado;

            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <h6><b><?= $rot_gerrsutit ?></b></h6>
                    </div>
                </div>
            </div>
            <div class="row partida_dados">

                <div class="col-md-3">

                    <div class="col-md-12">

                        <a class="dialogo"><?= $rot_convalpag ?>: <b><?= $geral->germoeatr() ?> <?= $geral->gersepatr($a_pagar); ?></b>
                            <div class="dialogo_inner_conpagcri"><h6><?= $rot_conparpag ?></h6>
                                <div class="table-tooltip">
                                    <table class="table_cliclipes" >
                                        <thead>
                                        <tr>
                                            <th><?= $rot_conparres ?></th>
                                            <th><?= $rot_gerdattit ?></th>
                                            <th><?= $rot_respagval ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($partida_dados['partidas'] as $partida) {
                                            if ($partida['a_pagar'] != 0) {
                                                ?>
                                                <tr>
                                                    <td><?= $partida['partida_item'] ?></td>
                                                    <td><?= Util::convertDataDMY($partida['partida_liquidacao_data']) ?></td>
                                                    <td><?= $geral->gersepatr($partida['partida_valor']) ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        //insere também a partida virtual se for maior que 0
                                        if ($partida_dados['final_partida']['partida_valor'] != 0) {
                                            ?>
                                            <tr>
                                                <td><?= $partida_dados['final_partida']['partida_item'] ?></td>
                                                <td><?= Util::convertDataDMY($partida_dados['final_partida']['partida_liquidacao_data']) ?></td>
                                                <td><?= $geral->gersepatr($partida_dados['final_partida']['partida_valor']) ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </a>

                    </div>

                    <div class="col-md-12">

                        <a class="dialogo">  <?= $rot_conpreaut ?>: <b><?= $geral->germoeatr() ?> <?= $geral->gersepatr($pre_autorizado); ?></b>
                            <div class="dialogo_inner_conpagcri"><h6><?= $rot_conpreaut ?></h6>
                                <div class="table-tooltip">
                                    <table class="table_cliclipes" >
                                        <thead>
                                        <tr>
                                            <th><?= $rot_conparres ?></th>
                                            <th><?= $rot_gerdattit ?></th>
                                            <th><?= $rot_respagval ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($partida_dados['partidas'] as $partida) {
                                            if ($partida['pre_autorizado'] != 0) {
                                                ?>
                                                <tr>
                                                    <td><?= $partida['partida_item'] ?></td>
                                                    <td><?= Util::convertDataDMY($partida['partida_liquidacao_data']) ?></td>
                                                    <td><?= $geral->gersepatr($partida['partida_valor']) ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </a>

                    </div>

                    <div class="col-md-12">
                        <?= $rot_consalpag ?>: <b><?= $geral->germoeatr() ?> <?= $geral->gersepatr($a_pagar - $pago); ?></b>
                        <span style="display:none" id="somatoria_partida_valor"><?= $geral->gersepatr($a_pagar - $pago); ?></span>
                    </div>
                    <input type="hidden" id="a_pagar" name="a_pagar" value="<?= $a_pagar - $pago ?>" />

                </div>

                <div class="col-md-6" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <?= $rot_contotpag ?>: <b><?= $geral->germoeatr() ?> <?= $geral->gersepatr($pago); ?></b>
                    </div>

                    <div class="col-md-12">
                        <div class="respagval_dados">
                            <p><?= $rot_conapgate ?> <span style="color:red; font-style: italic"><?= Util::convertDataDMY($geral->geragodet(1)) ?></span> : <b><?= $geral->germoeatr() ?> <?= $geral->gersepatr($somatoria_partida_valor) ?></b></p>
                        </div>
                    </div>

                </div>


                </div>

            </div>


            <?php
        }
    }
    //Exibe o pagamento para todos os quartos
    else {
        $total_a_pagar = 0;
        $total_pago = 0;
        $total_pre_autorizado = 0;
        $total_quartos_itens = 0;
        foreach ($partidas_por_quarto_item as $quarto_item => $partida_dados) {
            $a_pagar = 0;
            $pago = $partida_dados['pago'];
            $pre_autorizado = $partida_dados['pre_autorizado'];

            foreach ($partida_dados['partidas'] as $partida)
                $a_pagar += $partida['partida_valor'];

            //Soma no total a pagar a partida virtual
            $a_pagar += $partida_dados['final_partida']['partida_valor'];
            $total_a_pagar += $a_pagar;
            $total_pago += $pago;
            $total_pre_autorizado += $pre_autorizado;
            $total_quartos_itens++;
            ?>
            <input type="hidden" name="a_pagar_<?= $quarto_item ?>" value="<?= $a_pagar-$pago ?>" />
            <?php
        }
        ?>
        <div class="row">
            <h6><b><?= $rot_gerrsutit ?></b></h6>
        </div>
        <div class="row partida_dados">
            <div class="col-md-6">
                <div class="col-md-12">
                    <a class="dialogo"><?= $rot_convalpag ?>: <?= $geral->germoeatr() ?> <?= $geral->gersepatr($total_a_pagar); ?></a>
                </div>
            </div>
        </div>

    <?php } ?>

    <?php #TODO: Fernando Vale - modificar dados dos radios de acordo com as regras de negócio. ?>

    <div class="row" style="margin-bottom: 30px; margin-top: 20px;">
        <div class="col-md-12">
            <div class="col-md-2 col-sm-2">
                <label class="radio-inline"><input type="radio" name="desc_cortesia" value="d">Pagamento</label>
                <label class="radio-inline"><input type="radio" name="desc_cortesia" value="c">Reembolso</label>
            </div>
        </div>
    </div>



   <!-- <button type="button" class="accordion-pgto active pagamento"><?= $rot_conpagtit ?> <span id="rotulo_pagamento_1"></span>
        <div class="row col-md-1 pull-right">
            <input class="form-control btn-primary fechar_acordion" aria-linha-pagamento="1" id="fechar_acordion_1" type="button" value="X" style="width:30px; float:right; display:none">
        </div>
    </button>

    <button type="button" class="accordion-pgto active reembolso"><?= $rot_gerreetit ?> <span id="rotulo_reembolso_1"></span>
        <div class="row col-md-1 pull-right">
            <input class="form-control btn-primary fechar_acordion" aria-linha-reembolso="1" id="fechar_acordion_1" type="button" value="X" style="width:30px; float:right; display:none">
        </div>
    </button>-->
    <?php #TODO: Fernando Vale - remover select. ?>
<!--    <div class="form-group col-md-12">-->
<!--        <div class="form-group col-md-4">-->
<!--            <select class="form-control pagamento_reembolso">-->
<!--                <option value="pagamento">--><?//= $rot_conpagtit ?><!--</option>-->
<!--                <option value="reembolso">--><?//= $rot_gerreetit ?><!--</option>-->
<!--            </select>   -->
<!--        </div>-->
<!--    </div>-->

    <div class="row">
        <div class="col-md-3">
            <label class="col-md-12"><?= $rot_respagnom ?></label>
            <div class='col-md-12 col-sm-12'>
                <input  type="hidden" name="pag_codigo_1" id="pag_codigo_1" value='<?= $pagante_codigo ?>' />
                <input  class='form-control pagante_nome' type="text" name="pagante_nome_1" id='pagante_nome_1' value='<?= $pagante_nome ?>'  data-validation='required' />

                <button style="right: 30px; z-index: 1;" id="clibtnpes" class='es-form-button clicadmod' aria-cliente-codigo-id='pag_codigo_1' aria-cliente-codigo="<?= $pagante_codigo ?>" aria-cliente-nome-id='pagante_nome_1' aria-cliente-cpf-cnpj-id='pagante_cpf_cnpj_1' type="button">
                    <span class='ui-icon ui-icon-pencil'></span>
                </button>

                <button style="right: 5px;" id="clibtnpes" class='es-form-button clicadpes' aria-cliente-codigo-id='pag_codigo_1' aria-cliente-nome-id='pagante_nome_1' aria-cliente-cpf-cnpj-id='pagante_cpf_cnpj_1' type="button">
                    <span class='ui-icon ui-icon-search'></span>
                </button>
            </div>
        </div>

        <div class="col-md-3">
            <label class="col-md-12"><?= $rot_clicpfnum ?> / <?= $rot_clicadcnp ?></label>
            <div class='col-md-12 col-sm-12'>
                <input  class='form-control cpfcnpj' type="text" name="pagante_cpf_cnpj_1" id='pagante_cpf_cnpj_1' maxlength="18" value='<?= $pagante_cpf_cnpj ?>'  data-validation="cpfcnpj" data-validation-optional="false"  />
            </div>
        </div>
    </div>

    <div class="row" class="pagamento_forma_dados" id="pagamento_forma_dados_1" style="margin-top: 0px; padding-top: 30px;">
        <div class="col-md-12"><div class="col-md-12"><hr style="margin: 0; margin-bottom: 20px; padding: 0 5px;"></div></div>
        <div class="col-md-3 show">
            <label class='col-md-12'><?= $rot_respagfor ?></label>
            <div class='col-md-12'>
                <select style="margin-bottom: 0;" class='form-control respagreg' name='respagfor_1' id='respagfor_1' aria-linha-pagamento="1" data-validation='required'>
                    <option value="" selected="selected"></option>
                    <?php foreach ($var_respagfor as $item_respafor) { ?>
                        <option value="<?= $item_respafor['pagamento_forma_codigo'] ?>|<?= $item_respafor['contabil_tipo'] ?>">
                            <?= $item_respafor["pagamento_forma_nome"] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">

            <input type="hidden" name="linha_pgto_atual" id="linha_pgto_atual" value="" />
            <div id="div_saldo_credito_1" style="display: none">
                <label  class="col-md-2"><b><?= $rot_concresal ?> <?= $geral->germoeatr() ?></b> </label>
                <div class='col-md-2 col-sm-1'>
                    <div class="col-md-9">
                        <input class='form-control' type="text" readonly name="saldo_credito_1" id="saldo_credito_1">
                    </div>
                    <div class="col-md-2">
                        <button  style="padding: 4px;background-color:none" type="button"
                                 onclick="concreexi_dialog(1);" >
                            <span class='ui-icon ui-icon-search'></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id='div_respagreg_1'>
        </div>
    </div>

    <div class="row" style="margin-bottom:10px">
        <div class="col-md-12">
            <div class="col-md-12">
                <a style="float: right; margin-top: 5px;" href="#" class="conitecri_pagamento_adicional"><i class="fa fa-plus-circle"></i> <?= $rot_conpagadi ?></a>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 40px;">
        <button type="button" class="close_dialog es-default-button" style="width: 170px; float: left;"><i class="fa fa-times-circle"></i> <?= $rot_gerdesbot ?></button>
        <button style="float:right; display:none" class="es-default-button btn-primary btn submit-button" type="submit" aria-form-id="conpagcri" name="gersalbot"  id="conpagcri_button" >Enviar</button>
        <button style="float:right;" class="btn-primary btn es-default-button" type="button" name="gersalbot" onclick="conpagval()"><i class="fa fa-check-circle"></i> <?= $rot_gersalbot ?></button>
    </div>
</form>