<?php

use Cake\Routing\Router;
use App\Model\Entity\Geral;

$geral = new Geral();
?>
<div class="content_inner">
    <div class="formulario">
        <form id='cusfolmod' action="<?= Router::url('/', true) ?>custos/cusfolmod" method="POST">
            <input type="hidden" id="documento_numero" name="documento_numero" value="<?= $documento_numero ?>" />
            <input type="hidden" id="quarto_item" name="quarto_item" value="<?= $quarto_item ?>" />
            <input type="hidden" id="conta_item" name="conta_item" value="<?= $conta_item ?>" />
            <input type="hidden" id="pai_produto_qtd" name="pai_produto_qtd" value="<?= $pai_produto_qtd ?>" />
            <input type="hidden" id="pai_variavel_fator_codigo" name="pai_variavel_fator_codigo" value="<?= $pai_variavel_fator_codigo ?>" />
            <input type="hidden" id="form_atual" value="cusfolmod" />
            <div class='form-group row'>
                <div class='col-md-3 col-sm-3'> 
                    <input type="hidden" id="conprocod" name="conprocod" value="<?= $pai_produto_codigo ?? '' ?>" />
                    <input type="hidden" id="convarnom" name="convarnom" value="<?= $convarnom ?? '' ?>" />
                    <?= $rot_conprocod ?>: <span><b><?= $pai_produto_nome ?? '' ?></b></span>
                </div>
                <div class='col-md-2 col-sm-3'>
                    <?= $rot_conproqtd ?>: <b><?= $pai_produto_qtd ?></b>
                </div>

                <div class='col-md-3 col-sm-3'>
                    <?= $rot_prounimed ?>: <span id="produto_pai_variavel_fator_nome"><b><?= $pai_unidade_medida ?? '' ?></b></span>
                </div>

            </div>
            <div  class='form-group' id='linha_custo_folha_itens' style="margin-top:40px">
                <?php
                $indice_custo_folha_item = 1;
                $total_itens_custo_folha = 0;
                foreach ($custo_folha_itens as $custo_folha_item) {
                    ?>
                    <!--Mantem os excluido para preservar o controle do adicionar mais-->
                    <?php if ($custo_folha_item['excluido'] != '0') { ?>
                       <!-- <div class='itens_excluidos'>
                            <input type="hidden" class="excluido" name="prolisexc[]"  id="prolisexc_<?= $indice_custo_folha_item ?>" value="1" />
                            <input type="hidden" class="excluido item_custo_folha" name="proprofil[]" id="conprocod_<?= $indice_custo_folha_item ?>" value="<?= $custo_folha_item['produto_codigo'] ?>" />
                            <input type="hidden" class="excluido" name="qtd[]"  id="qtd_<?= $indice_custo_folha_item ?>" value="<?= $custo_folha_item['qtd'] ?>"  />
                            <input type="hidden" class="excluido" name="prounimed[]"  id="prounimed_<?= $indice_custo_folha_item ?>" value="<?= $custo_folha_item['fator_codigo'] ?>"  />
                            <input type="hidden" class="excluido" name="unitario_custo[]"  id="unitario_custo_<?= $indice_custo_folha_item ?>" value="<?= $geral->gersepatr($custo_folha_item['unitario_custo']) ?>"  />
                            <input type="hidden" class="excluido" name="total_custo[]"  id="total_custo_<?= $indice_custo_folha_item ?>" value="<?= $geral->gersepatr($custo_folha_item['total_custo']) ?>"  />
                        </div> -->
                    <?php } else { ?>
                        <div class='form-group linha_custo_folha_item row'>
                            <div class="col-md-3">
                                <?php if ($indice_custo_folha_item == 1) { ?>
                                    <label class="control-label col-md-12 col-sm-12" style="margin-left: 30px;" for="proprofil_<?= $indice_custo_folha_item ?>">Item</label>
                                <?php } ?>
                                <div class="col-md-1">
                                    <input type="hidden" name="prolisexc[]"  id="prolisexc_<?= $indice_custo_folha_item ?>" value="0" />
                                    <input type="checkbox" name="prolischk[]"  data-indice-custo-item="<?= $indice_custo_folha_item ?>"  id="prolischk_<?= $indice_custo_folha_item ?>" />
                                </div>
                                <div class='col-md-11 col-sm-12'>
                                    <input type="hidden"  data-validation='required' id="conprocod_<?= $indice_custo_folha_item ?>" name="proprofil[]" value="<?= $custo_folha_item['produto_codigo'] ?>"  />
                                    <input autocomplete="off" type="text"  data-validation='required' class='produto_autocomplete form-control input_autocomplete item_custo_folha' maxlength="7"
                                           id="proprofil_<?= $indice_custo_folha_item ?>" value="<?= $custo_folha_item['produto_nome'] ?>" data-produto-codigo="conprocod_<?= $indice_custo_folha_item ?>"
                                           <?php if ($origem_clique == 'lupa') echo 'readonly' ?> />
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <?php if ($indice_custo_folha_item == 1) { ?>
                                    <label class="control-label col-md-12 col-sm-12" for="prounimed_<?= $indice_custo_folha_item ?>"><?= $rot_prounimed ?></label>
                                <?php } ?>
                                <div class='col-md-11 col-sm-12'>
                                    <select readonly="readonly" class="form-control unidade_medida_folha_custo_item" data-indice-custo-item="<?= $indice_custo_folha_item ?>"  id="prounimed_<?= $indice_custo_folha_item ?>" name="prounimed[]"  <?php if ($origem_clique == 'lupa') echo 'readonly' ?> >
                                        <option></option>
                                        <?php
                                        foreach ($unidades_medida as $item) {
                                            $selected = "";
                                            if ($item['valor'] == $custo_folha_item['fator_codigo'])
                                                $selected = "selected='selected'";
                                            ?>
                                            <option value="<?= $item["valor"] ?>" <?= $selected ?>> <?= $item["rotulo"] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12">
                                <?php if ($indice_custo_folha_item == 1) { ?>
                                    <label class="control-label col-md-12 col-sm-12" for="qtd_<?= $indice_custo_folha_item ?>"><?= $rot_conproqtd ?></label>
                                <?php } ?>
                                <div class='col-md-11 col-sm-12'> 
                                    <input class='form-control quantidade_folha_custo_item'  data-validation='required' data-indice-custo-item="<?= $indice_custo_folha_item ?>"  type="text" name="qtd[]"  id="qtd_<?= $indice_custo_folha_item ?>" value="<?= intval($custo_folha_item['qtd']) ?>"  <?php if ($origem_clique == 'lupa') echo 'readonly' ?>  />
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <?php if ($indice_custo_folha_item == 1) { ?>
                                    <label class="control-label col-md-12 col-sm-12" for="unitario_custo_<?= $indice_custo_folha_item ?>">Custo unitário (<?= $geral->germoeatr() ?>) </label>
                                <?php } ?>
                                <div class='col-md-11 col-sm-12'> 
                                    <input class='form-control unitario_folha_custo_item moeda' data-indice-custo-item="<?= $indice_custo_folha_item ?>" type="text" name="unitario_custo[]"  id="unitario_custo_<?= $indice_custo_folha_item ?>" value="<?= $geral->gersepatr($custo_folha_item['unitario_custo']) ?>"  <?php if ($origem_clique == 'lupa') echo 'readonly' ?>  />
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <?php if ($indice_custo_folha_item == 1) { ?>
                                    <label class="control-label col-md-12 col-sm-12" for="total_custo_<?= $indice_custo_folha_item ?>">Custo total (<?= $geral->germoeatr() ?>) </label>
                                <?php } ?>
                                <div class='col-md-11 col-sm-12'> 
                                    <input readonly="readonly" class='form-control' type="text" name="total_custo[]"  id="total_custo_<?= $indice_custo_folha_item ?>" value="<?= $geral->gersepatr($custo_folha_item['total_custo']) ?>" 
                                           <?php if ($origem_clique == 'lupa') echo 'readonly' ?>  />
                                </div>
                            </div>

                        </div>
                        <?php
                        $total_itens_custo_folha += $custo_folha_item['total_custo'];
                    }
                    ?>
                    <?php
                    $indice_custo_folha_item++;
                }
                ?>
            </div> <div class="form-group"  style="margin-top: -13px; margin-left: 21px;">
                <div class="col-md-12 col-sm-12">
                    <div class='pull-left col-md-2 col-sm-4'>
                        <b><a href="#" class="novo_item_custo_folha"><?= $rot_resaddpfi ?></a></b>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top: 15px; margin-bottom:20px; margin-left: 23px">
                <div class="col-md-3" style="margin-top: 10px;">
                    Total (<?= $geral->germoeatr() ?>)
                </div>
                <div class="col-md-2 col-sm-12">
                    &nbsp;
                </div>
                <div class="col-md-1 col-sm-12">
                    &nbsp;
                </div>

                <div class="col-md-2 col-sm-12">
                    &nbsp;
                </div>
                <div class="col-md-2 col-sm-12">
                    <div class='col-md-11 col-sm-12'>
                        <input id="total_itens_custo_folha" style="margin-left: -11px;" readonly="readonly" class='form-control' type="text" value="<?= $geral->gersepatr($total_itens_custo_folha) ?>" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12"  style="margin-top: 50px;">
                    <div class='pull-right col-md-2 col-sm-4'> 
                        <input class="form-control btn-primary submit-button" aria-form-id="cusfolmod" type="submit" style="margin-top: 15px;" id="cusfolmod_salvar" value="<?= $rot_gersalbot ?>" >
                    </div>
                    <div class='pull-left col-md-2 col-sm-4'>
                        <input class="form-control btn-default close_dialog" type="button" value="<?= $rot_gerdesbot ?>">
                    </div>
                    <div class='pull-left col-md-2 col-sm-4'>
                        <input class="form-control btn-default reinicializar_folha_custo" type="button" value="Reincializar folha">
                    </div>
                    <div class='pull-left col-md-2 col-sm-4'>
                        <input class="form-control btn-danger seleciona_custo_folha_excluidos" type="button" value="<?= $rot_gerqtpexc ?> item">
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>