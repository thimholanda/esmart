<?php

use Cake\Routing\Router;
use App\Model\Entity\Geral;
use App\Utility\Util;

$geral = new Geral();
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>

<div class="content_inner">
    <div class="formulario">
        <form method="POST" name="conpfppes" id="conpfppes" action="<?= Router::url('/', true) ?>documentocontas/conpfppes" class="form-horizontal">

            <input type="hidden" id="pagina" value="1" name="pagina" />
            <input type="hidden" id="form_atual" value="conpfppes" />
            <input type="hidden" id="form_force_submit" value="0" />
            <input type="hidden" id="aria-form-id-pdf" value="conpfppes" >
            <input type="hidden" id="gerdiacon_executada" value="0" />
            <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
            <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
            <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />
            <input type="hidden" id="export_csv" value="0" name="export_csv" />
            <input type="hidden" id="aria-form-id-csv" value="conpfppes" >
            <input type="hidden" id="export_pdf" value="0" name="export_pdf" />

            <div class='form-group'>
                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12" for="gerdattip"><?= $rot_gerdattit ?></label>
                    <div class='col-md-11 col-sm-11'>
                        <select name="gerdattip" id="gerdattip"  class="form-control" aria-campo-padrao-valor ="<?= $campo_padrao_valor_gerdattip ?>"  aria-padrao-valor="<?= $padrao_valor_gerdattip ?? '' ?>" >
                            <option value=""></option>
                            <option value="entrada" <?php if (($gerdattip ?? '') == 'entrada') echo 'selected'; ?>><?= $rot_gerentdat ?></option>
                            <option value="estadia" <?php if (($gerdattip ?? '') == 'estadia') echo 'selected'; ?>><?= $rot_esttittit ?></option>  
                            <option value="saida" <?php if (($gerdattip ?? '') == 'saida') echo 'selected'; ?>><?= $rot_gersaidat ?></option>                           
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12" for="gerdatini" <?= $pro_gerdatini ?>><?= $rot_gerdatini ?></label>
                    <div class='col-md-11 col-sm-11'> 
                        <input maxlength="10" class='form-control datepicker data data_place data_incrementa_igual' aria-id-campo-filho='gerdatfin'  type="text" name="gerdatini" id="gerdatini"
                               value="<?= $gerdatini ?? '' ?>" placeholder="<?= $for_gerdatini ?>" />
                    </div>
                    <div class="col-md-1 col-sm-1"><span style="padding: 0 4px;"> _ </span></div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12"><?= $rot_gerdatfin ?></label>
                    <div class='col-md-11 col-sm-11'> 
                        <input maxlength="10" class='form-control datepicker data data_place' aria-id-campo-dependente="gerdatini"  type="text" name="gerdatfin" id="gerdatfin"
                               value="<?= $gerdatfin ?? '' ?>" placeholder="<?= $for_gerdatfin ?>"  data-validation="futuradata3" data-validation-optional="true" />
                    </div>
                </div>

                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="resdocsta"><?= $rot_resdocsta ?></label>
                    <div class="col-md-11 col-sm-12">
                        <select id="resdocsta" name="resdocsta[]" class="select-all-no-search" multiple aria-campo-padrao-multiselect="1" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_resdocsta ?>"  aria-padrao-valor="<?= $padrao_valor_resdocsta ?? '' ?>">
                                    <?php
                                    foreach ($gerdomsta_list as $item) {
                                        $selected = "";
                                        if (isset($resdocsta)) {
                                            foreach ($resdocsta as $status_preenchido) {
                                                if ($item['valor'] == $status_preenchido)
                                                    $selected = "selected='selected'";
                                            }
                                        }
                                        ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>> <?= $item["rotulo"] ?></option>
                            <?php } ?>
                        </select>
                    </div>  
                </div>
            </div>

            <div class='form-group'>
                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="resquatip" ><?= $rot_resquatip ?></label>
                    <div class="col-md-11 col-sm-12">
                        <select id="resquatip" name="resquatip[]" class="select-all-no-search" multiple aria-campo-padrao-multiselect="1" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_resquatip ?>"  aria-padrao-valor="<?= $padrao_valor_resquatip ?? '' ?>">
                                    <?php
                                    foreach ($resquatip_list as $item) {
                                        $selected = "";
                                        if (isset($resquatip)) {
                                            foreach ($resquatip as $quarto_tipo_preenchido) {
                                                if ($item['valor'] == $quarto_tipo_preenchido)
                                                    $selected = "selected='selected'";
                                            }
                                        }
                                        ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <input id='c_codigo' name='c_codigo' type="hidden" value="<?= $c_codigo ?? '' ?>" />
                    <label class="control-label col-md-12 col-sm-12" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_gerclitit ?>: </label>
                    <div class="col-md-11 col-sm-11">    
                        <input  class="form-control input_autocomplete" id='c_nome_autocomplete' type="text" name="cliprinom" value="<?= $cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>"  <?= $pro_cliprinom ?> <?= $val_cliprinom ?> /> 
                    </div>  
                    <div class="col-md-1">
                        <button class=" clicadpes" type="button"  aria-cliente-codigo-id='c_codigo' aria-cliente-nome-id='c_nome_autocomplete' aria-cliente-cpf-cnpj-id=''>
                            <span class='ui-icon ui-icon-search'></span>
                        </button>
                    </div>
                </div>    
                <div class="col-md-3" style="margin-top: 38px; margin-left: 20px;">

                    <input type="checkbox" style="margin-right:3px" name="consaldze" id="consaldze" <?php if (isset($consaldze) && $consaldze == 'on') echo 'checked' ?> /> 
                    <label for="consaldze">Saldo diferente de zero</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <div class="col-md-10 col-sm-8"></div>
                    <div class="pull-left col-md-2 col-sm-4">
                        <input class="form-control btn-primary submit-button" aria-form-id="conpfppes"  type="submit" value="<?= $rot_gerexebot ?>">&nbsp;
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<?php if (isset($pesquisa_contas) && sizeof($pesquisa_contas) > 0) { ?>
    <div class="form-group">
        <div class="col-md-12 col-sm-12">
            <div class="col-md-12 col-sm-12" style="margin-bottom:12px">
                <table class="table_cliclipes">
                    <thead>
                        <tr class="tabres_cabecalho">
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'quarto_codigo', 'aria_form_id' => 'conpfppes', 'label' => $rot_resquacod]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'inicial_data', 'aria_form_id' => 'conpfppes', 'label' => $rot_gerchitit]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'final_data', 'aria_form_id' => 'conpfppes', 'label' => $rot_gerchotit]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_numero', 'aria_form_id' => 'conpfppes', 'label' => $rot_resdocnum]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'nome', 'aria_form_id' => 'conpfppes', 'label' => $rot_resdochos]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'saldo_a_pagar', 'aria_form_id' => 'conpfppes', 'label' => $rot_consalpag]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_despesas', 'aria_form_id' => 'conpfppes', 'label' => $rot_contotdes]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_cartao', 'aria_form_id' => 'conpfppes', 'label' => 'Cartão crédito']); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_debito', 'aria_form_id' => 'conpfppes', 'label' => 'Cartão débito']); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_dinheiro', 'aria_form_id' => 'conpfppes', 'label' => $rot_condintit]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_transferencia_deposito', 'aria_form_id' => 'conpfppes', 'label' => 'Transf. bancária']); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_cheque', 'aria_form_id' => 'conpfppes', 'label' => $rot_conchetit]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_faturado', 'aria_form_id' => 'conpfppes', 'label' => $rot_confattit]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_transferencia_entre_contas', 'aria_form_id' => 'conpfppes', 'label' => 'Transf. quarto']); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_transferencia_entre_contas', 'aria_form_id' => 'conpfppes', 'label' => 'Crédito']); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($pesquisa_contas as $value) {
                            ?>
                            <tr>
                                <td><?= $value['quarto_codigo'] ?></td>
                                <td><?= Util::convertDataDMY($value['inicial_data']) ?></td>
                                <td><?= Util::convertDataDMY($value['final_data']) ?></td>
                                <td><?= $value['documento_numero'] . '-' . $value['quarto_item'] ?></td>
                                <td><?= $value['nome'] . ' ' . $value['sobrenome'] ?></td>
                                <td><?= $geral->germoeatr() . ' ' . $geral->gersepatr($value['saldo_a_pagar']) ?></td>
                                <td><?= $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_despesas']) ?></td>
                                <td><?= $value['total_cartao'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_cartao']) : '' ?></td>
                                <td><?= $value['total_debito'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_debito']) : '' ?></td>
                                <td><?= $value['total_dinheiro'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_dinheiro']) : '' ?></td>
                                <td><?= $value['total_transferencia_deposito'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_transferencia_deposito']) : '' ?></td>
                                <td><?= $value['total_cheque'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_cheque']) : '' ?></td>
                                <td><?= $value['total_faturado'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_faturado']) : '' ?></td>
                                <td><?= $value['total_transferencia_entre_contas'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_transferencia_entre_contas']) : '' ?></td>
                                <td><?= $value['total_credito'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_credito']) : '' ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: #dedddd;">
                            <td colspan="5"><strong><?= $rot_gertottit ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_saldo_a_pagar']) ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_despesas']) ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_cartao']) ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_debito']) ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_dinheiro']) ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_transferencia']) ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_cheque']) ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_faturado']) ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_transferencia_entre_contas']) ?></strong></td>
                            <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_credito']) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php echo $paginacao; ?>
    <!--<div class="col-md-12">
        <strong><?= $rot_gertottit ?></strong><br/> 
        <strong><?= $rot_consalpag ?>: <?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_saldo_a_pagar']) ?></strong><br/> 
        <strong><?= $rot_contotdes ?>: <?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_despesas']) ?></strong><br/> 
        <strong><?= $rot_concartit ?>: <?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_cartao']) ?></strong><br/> 
        <strong><?= $rot_condintit ?>: <?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_dinheiro']) ?></strong><br/> 
        <strong><?= $rot_contratit ?>: <?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_transferencia']) ?></strong><br/> 
        <strong><?= $rot_conchetit ?>: <?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_cheque']) ?></strong><br/> 
        <strong><?= $rot_confattit ?>: <?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_faturado']) ?></strong><br/> 
        <strong><?= $rot_contrctit ?>: <?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_transferencia_entre_contas']) ?></strong><br/>
    </div>-->
    <?php
}
?>
