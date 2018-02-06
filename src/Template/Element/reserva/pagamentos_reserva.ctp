<?php

use App\Model\Entity\Geral;
use App\Utility\Util;

$geral = new Geral();
?>
<div id="Pag_normal" class="col-md-12 col-sm-12 dados_item2" style="display: none">
    <div class="col-md-12 col-sm-12 info_quarto">
        <div class="col-md-3 col-sm-12">
            <a class="exibi_info" onclick="exibi_info_quartos('#Pag_normal');"></a>
            <a class="escd_info" onclick="escd_info_quartos('#Pag_normal');"></a>
            <strong><?= $rot_conpagtit ?></strong>
        </div>
        <div class="pull-right col-md-2 col-sm-2">
            <?= $geral->germoeatr() ?>: <span id="total_a_pagar"></span>
        </div>

    </div>

    <div id="pagamentos" class="col-md-12 col-sm-12" style="display:none">
        <input type="hidden" id="total_pagamento_formas"  name="total_pagamento_formas" value="0" />
        <div class="pagamento_forma_dados" id="pagamento_forma_dados_1" style="display:none">
            <input type="hidden" id="quarto_item_atual" value="1" />

            <span style="display:none" id="somatoria_partida_valor">0</span>
            <div class="col-md-12 col-sm-12 form_pagamentos">
                <div class="col-md-11 col-sm-11">
                    <div class="col-md-3 col-sm-12 title_topic subtitulo_pagamento" style="display:none" ><strong><?= $rot_conpagtit ?> <span id="rotulo_pagamento_1">1</span></strong></div>
                    <button type="button" class="btn-fechar col-md-2 col-sm-2 title_topic pull-right">
                        <a class="btn-fechar-inner fechar_acordion" aria-linha-pagamento="1" id="fechar_acordion_1" style="display:none; float:right" >x</a>
                    </button>

                </div>

                <div class="panel col-md-12 col-sm-12 show">
                    <div class="col-md-12 col-sm-12 list_rescli_inner">
                        <div class='col-md-4 col-sm-12'>
                            <label class='control-label col-md-12 col-sm-12'><?= $rot_respagfor ?></label>
                            <div class='col-md-11 col-sm-12'>
                                <select class='form-control respagreg' name='respagfor_1' id='respagfor_1' aria-linha-pagamento="1">
                                    <option value="" selected="selected"></option>
                                    <?php
                                    foreach ($var_respagfor as $item_respafor) {
                                        if ($item_respafor['pagamento_forma_codigo'] != '7') {
                                            ?>
                                            <option value="<?= $item_respafor['pagamento_forma_codigo'] ?>|<?= $item_respafor['contabil_tipo'] ?>">
                                                <?= $item_respafor["pagamento_forma_nome"] ?>
                                            </option> 
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12">
                            <label class="control-label col-md-12 col-sm-12"><?= $rot_respagnom ?></label>
                            <div class='col-md-10 col-sm-10'> 
                                <input  type="hidden" name="pag_codigo_1" id="pag_codigo_1" />
                                <input  type="hidden" name="pagante_igual_contratante_1" id="pagante_igual_contratante_1" value="1" />
                                <input  class='form-control pagante_nome' type="text" name="pagante_nome_1" id='pagante_nome_1' />
                            </div>
                            <div class="col-md-2 col-sm-2">
                                <button id="clibtnpes" class='btn-pequisar clicadpes' aria-cliente-codigo-id='pag_codigo_1' aria-cliente-nome-id='pagante_nome_1' aria-cliente-cpf-cnpj-id='pagante_cpf_cnpj_1' 
                                        type="button">
                                    <a class="btn-pequisar-inner"></a>
                                </button>
                            </div>
                        </div>    
                        <div class="col-md-3 col-sm-12">
                            <label class="control-label col-md-12 col-sm-12"><?= $rot_clicpfnum ?> / <?= $rot_clicadcnp ?></label>
                            <div class='col-md-10 col-sm-10'> 
                                <input  class='form-control cpfcnpj' type="text" name="pagante_cpf_cnpj_1" id='pagante_cpf_cnpj_1'  data-validation="cpfcnpj" data-validation-optional="false"  maxlength="18" />
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 list_rescli_inner">
                        <div class="col-md-12 col-sm-12">
                            <input type="hidden" name="linha_pgto_atual" id="linha_pgto_atual" value="" />
                            <div id="div_saldo_credito col-md-4 col-sm-12" style="display: none">
                                <label  class="control-label col-md-12 col-sm-12"><?= $rot_concresal ?> <?= $geral->germoeatr() ?> </label>
                                <div class='col-md-10 col-sm-10'>
                                    <input class='form-control' type="text" readonly name="saldo_credito" id="saldo_credito">
                                    <div class="col-md-2 col-sm-2">
                                        <button  style="padding: 4px;background-color:none" type="button" onclick="concreexi_dialog(1);" >
                                            <span class='ui-icon ui-icon-search'></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id='div_respagreg_1' class="col-md-12 col-sm-12"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6" style="margin-bottom:10px">
            <a href="#" class="conitecri_pagamento_adicional"><?= $rot_conpagadi ?></a>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom:10px"></div>

<div id="pagamentos_pre_autorizados" class="col-md-12 col-sm-12 pagamento_forma_dados dados_item2" style="display:none">
    <div class="col-md-12 col-sm-12 pre_auto_inner">
        <div class="col-md-12 col-sm-12 info_quarto">
            <div class="col-md-4 col-sm-12">
                <a class="exibi_info" onclick="exibi_info_quartos('#pagamentos_pre_autorizados');"></a>
                <a class="escd_info" onclick="escd_info_quartos('#pagamentos_pre_autorizados');"></a>
                <strong><?= $rot_conpreaut ?></strong>
            </div>                            
            <div class="pull-right col-md-2">
                <?= $geral->germoeatr() ?>: <span id="total_a_pagar_pre_autorizado"></span>
            </div>
        </div>

        <div class="panel col-md-12 col-sm-12 pre_auto">
            <div class="col-md-12 col-sm-12 list_rescli_inner">
                <div class='col-md-4 col-sm-12'>
                    <label class='control-label col-md-12 col-sm-12'><?= $rot_respagfor ?></label>
                    <div class='col-md-11 col-sm-12'>
                        <input class='form-control pagante_nome' type="text" value="Pré-autorizado" disabled="disabled"/>
                    </div>
                </div>
                <div class='col-md-5 col-sm-12'>
                    <label class="control-label col-md-12 col-sm-12"><?= $rot_respagnom ?></label>
                    <div class='col-md-10 col-sm-10'> 
                        <input type="hidden" name="pre_pag_codigo" id="pre_pag_codigo" />
                        <input  type="hidden" name="pre_pagante_igual_contratante" id="pre_pagante_igual_contratante" value="1" />
                        <input class='form-control pre_pagante_nome' type="text" name="pre_pagante_nome" id='pre_pagante_nome'  />
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <button id="clibtnpes" class='btn-pequisar clicadpes' aria-cliente-codigo-id='pre_pag_codigo' aria-cliente-nome-id='pre_pagante_nome' aria-cliente-cpf-cnpj-id='pre_pagante_cpf_cnpj' 
                                type="button">
                            <a class="btn-pequisar-inner"><!--span class='ui-icon ui-icon-search'></span--></a>
                        </button>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12"><?= $rot_clicpfnum ?> / <?= $rot_clicadcnp ?></label>
                    <div class='col-md-10 col-sm-10'> 
                        <input class='form-control cpfcnpj' type="text" name="pre_pagante_cpf_cnpj" id='pre_pagante_cpf_cnpj'  data-validation="cpfcnpj" data-validation-optional="false"  maxlength="18" />
                    </div>

                </div>
            </div>
            <div class="col-md-12 col-sm-12 list_rescli_inner">
                <div class='col-md-2 col-sm-12'>
                    <label class='control-label col-md-12 col-sm-12' ><?= $rot_conpagdat ?></label>
                    <div class='col-md-11 col-sm-12'>
                        <input class='form-control data datepicker data_place' maxlength='10' type='text' placeholder='<?= $for_conpagdat ?>' <?= $pro_conpagdat ?> <?= $val_conpagdat ?> name='pre_forma_data' id='pre_forma_data' value='<?= Util::convertDataDMY($geral->geragodet(1)) ?>'>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class='control-label col-md-12 col-sm-12' ><?= $rot_resnomcom ?></label>
                    <div class='col-md-11 col-sm-12'>
                        <input class='form-control alphaonly' type='text' size='30' placeholder='<?= $for_resnomcom ?>' <?= $pro_resnomcom ?> <?= $val_resnomcom ?> maxlength='30' name='pre_forma_pagante_nome' >
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label class='control-label col-md-12 col-sm-12' ><?= $rot_rescarnum ?></label>
                    <div class='col-md-11 col-sm-12'>
                        <input class='form-control credit_card'  type='text' size='19' placeholder='<?= $for_rescarnum ?>' <?= $pro_rescarnum ?> <?= $val_rescarnum ?> maxlength='19' name='pre_forma_cartao_numero'>
                    </div>
                </div>
                <div class="col-md-1 col-sm-12">
                    <label class='control-label col-md-12 col-sm-12' ><?= $rot_rescarval ?></label>
                    <div class='col-md-11 col-sm-12'>
                        <input class='form-control validade_cartao' type='text' size='5' maxlength='5' placeholder="00/00" <?= $pro_rescarval ?> <?= $val_rescarval ?> name='pre_forma_cartao_validade' >
                    </div>
                </div>
                <div class="col-md-1 col-sm-12">
                    <label class='control-label col-md-12 col-sm-12' ><?= $rot_rescarvez ?></label>
                    <div class='col-md-11 col-sm-12'>
                        <input class='form-control numeric-positive' type='text' size='5' maxlength='5' <?= $pro_rescarvez ?> <?= $val_rescarvez ?> name='pre_forma_cartao_parcela' >
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <label class='control-label col-md-12 col-sm-12' ><?= $rot_respagval . " (" . $geral->germoeatr() . ")" ?></label>
                    <div class='col-md-11 col-sm-12'>
                        <input  class='form-control moeda' type='text' size='10' maxlength='15' placeholder='<?= $for_rescarvlo ?>' <?= $pro_rescarvlo ?> <?= $val_rescarvlo ?> name='pre_forma_valor'  id='pre_forma_valor'>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
